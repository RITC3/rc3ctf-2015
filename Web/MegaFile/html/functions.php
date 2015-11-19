<?php

function connect_to_db($find = 0){
	if($find == 1)
		return new mysqli("localhost", "mf-dba-upload", "ThisIsMuhUPLOADpassword!", "MegaFile");
	else if($find == 2)
		return new mysqli("localhost", "mf-dba-del", "ThisIsMuhDELpassword!", "MegaFile");
	else
		return new mysqli("localhost", "mf-dba", "ThisIsMuhDBpassword!", "MegaFile");
}

function register_user($conn, $username, $password, $first, $last, $admin = 0){
	//setup prepared statement
	$statement = $conn->prepare("INSERT INTO users (username, password, first, last, admin) VALUES (?, ?, ?, ?, ?)");

	//generate hash and session ID
    $pass = hash("sha512", $username . $password);

	//bind params to prepared statement, execute query
	$statement->bind_param("ssssi", $username, $pass, $first, $last, $admin);
	$statement->execute();
    if($statement->error)
        $result = $statement->error."<br />".$sql;
    else
        $result = $statement->insert_id;

	$statement->close();
    return $result;
}

function check_user($conn, $username){
	$statement = $conn->prepare("SELECT * FROM users WHERE username = ?");
	$statement->bind_param("s", $username);
	$statement->execute();

    $result = $statement->get_result();
    echo $statement->error;

    if($result->num_rows > 0)
        return True; 
    else
        return False;
}

function login_user($conn, $username, $ptpass){
    $password = hash("sha512", $username . $ptpass);
	$statement = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
	$statement->bind_param("ss", $username, $password);
	$statement->execute();
	$query = $statement->get_result();
    $error = $statement->error;

	if($error){
        $result = $error;
        return $result;
    }
    if ($query->num_rows > 0) {
        $result = $query->fetch_array(MYSQLI_ASSOC);
        //$result = $query;
        $query->free();
    }
    else
        $result = False;

	return $result;
}

function get_info($conn, $id){
	if($id == "")
	{
		$result['error'] = "ID must not be blank.";
		return $result;
	}

	$statement = $conn->prepare("SELECT * FROM users where id = ?");
	$statement->bind_param("s", $id);
	$statement->execute();
	$query = $statement->get_result();
    $error = $statement->error;

	if($error)
	{
		$result['error'] = $error;
		return $result;
	}

    $result = $query->fetch_array(MYSQLI_ASSOC);
    $query->free();
    return $result;
}

function lookup_user($conn, $id){
    if(is_numeric($id))
	{
		$statement = $conn->prepare("SELECT id, username FROM users WHERE id = ?");
		$statement->bind_param("i", $id);
	}
    else
	{
		$statement = $conn->prepare("SELECT id, username FROM users WHERE "
									. "username LIKE ?");
		$id = "%" . $id . "%";
		$statement->bind_param("s", $id);
	}

	$statement->execute();
	$error = $statement->error;
	$result = $statement->get_result();
	$statement->close();

	return array($result, $error);
}

function getFiles($conn, $accountID)
{
	$statement = $conn->prepare("SELECT id,name,size FROM files WHERE userid = ?");
	$statement->bind_param("i", $accountID);
	$statement->execute();

	$error = $statement->error;
	$result = $statement->get_result();
	$statement->close();

	return array($result, $error);
}

function getFile($conn, $fileid)
{
	$statement =$conn->prepare("SELECT * FROM files WHERE id = ?");
	$statement->bind_param("i", $fileid);
	$statement->execute();

	$error = $statement->error;
	$result = $statement->get_result()->fetch_array();
	$statement->close();

	return array($result, $error);
}

function addShare($conn, $myID, $theirID)
{
	if(intval($myID) === intval($theirID))
		return "Cannot share files with yourself.";

	//make sure not already sharing
	$statement = $conn->prepare("SELECT * FROM shares WHERE ownerid = ? AND "
								. "shareid = ?");
	$statement->bind_param("ii", $myID, $theirID);
	$statement->execute();
	$rows = $statement->get_result()->num_rows;
	$statement->close();

	if($rows > 0)
		return "You are already sharing files with this user.";

	$statement = $conn->prepare("INSERT INTO shares (ownerid, shareid) VALUES(?, ?)");
	$statement->bind_param("ii", $myID, $theirID);
	$statement->execute();

	$error = $statement->error;
	$statement->close();

	return $error;
}

function getShares($conn, $myID)
{
	$statement = $conn->prepare("SELECT ownerid FROM shares WHERE shareid = ?");
	$statement->bind_param("i", $myID);
	$statement->execute();

	$error = $statement->error;
	$result = $statement->get_result();
	$statement->close();

	return array($result, $error);
}

function getMyShares($conn, $myID)
{
	$statement = $conn->prepare("SELECT shareid FROM shares WHERE ownerid = ?");
	$statement->bind_param("i", $myID);
	$statement->execute();

	$error = $statement->error;
	$result = $statement->get_result();
	$statement->close();

	return array($result, $error);
}

function deleteFile($filename, $ownerid)
{
	$tempConn = connect_to_db(2);
	$statement = $tempConn->prepare("DELETE FROM files WHERE name = ? AND "
									. "userid = ?");
	$statement->bind_param("si", $filename, $ownerid);
	$statement->execute();
	$error = $statement->error;
	$statement->close();

	return $error;
}

function deleteShare($ownerid, $shareid)
{
	$tempConn = connect_to_db(2);
	$statement = $tempConn->prepare("DELETE FROM shares WHERE ownerid = ? AND "
									. "shareid = ?");
	$statement->bind_param("ii", $ownerid, $shareid);
	$statement->execute();
	$error = $statement->error;
	$statement->close();

	return $error;
}

function getSize($conn, $userid)
{
	$statement = $conn->prepare("SELECT sum(size) as sum FROM files WHERE "
								. "userid = ?");
	$statement->bind_param("i", $userid);
	$statement->execute();

	$error = $statement->error;
	$result = $statement->get_result()->fetch_array();
	$statement->close();

	if($error)
		return $error;
	else
		return intval($result['sum']);
}

function getKeys($conn, $code)
{
	if(checkpw($code))
	{
		$results = $conn->query("SELECT * FROM privkeys");
		return array($results, $conn->error);
	}
	else
		return false;
}

function updateUser($first, $last, $bio, $userid)
{
	$newConn = connect_to_db(1);
	$statement = $newConn->prepare("UPDATE users SET first = ?, last = ?, "
								   . "bio = ? WHERE id = ?");
	$statement->bind_param("sssi", $first, $last, $bio, $userid);
	$statement->execute();
	$newConn->close();

	$error = $statement->error;
	$statement->close();

	return $error;
}

function checkpw($code)
{
	$len = strlen($code) - 1;
	$last = intval($code[$len]);
	if(!!$code && $code & 1 && $last % 2 == 0 && $code = "DirtyHarry99!")
		return true;

	return false;
}

function addFile($conn, $filename, $filetype, $filesize, $contents, $myID)
{
	//check to see if file with the same name exists, if it does, then we update that
	$statement = $conn->prepare("SELECT * FROM files where name = ? AND userid = ?");
	$statement->bind_param("si", $filename, $myID);
	$statement->execute();
	$error = $statement->error;
	$result = $statement->get_result();
	$statement->close();

	if($error)
		return $error;

	//update existing file
	if($result->num_rows > 0)
	{
		$newConn = connect_to_db(1);
		$statement = $newConn->prepare("UPDATE files SET type = ?, size = ?, content = ? WHERE userid = ? AND name = ?");
		$statement->bind_param("sisis", $filetype, $filesize, $contents, $myID, $filename);
	}
	else //adding new file
	{
		$statement = $conn->prepare("INSERT INTO files (name, type, size, content, userid) VALUES (?, ?, ?, ?, ?)");
		$statement->bind_param("ssisi", $filename, $filetype, $filesize, $contents, $myID);
	}

	$statement->execute();
	$error = $statement->error;
	$statement->close();

	if(isset($newConn))
		$newConn->close();

	return $error;
}

function getClientIp() {
    $ipaddress = '';
    if (array_key_exists('HTTP_CLIENT_IP', $_SERVER) && $_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(array_key_exists('HTTP_X_FORWARDED', $_SERVER) && $_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(array_key_exists('HTTP_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(array_key_exists('HTTP_FORWARDED', $_SERVER) && $_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(array_key_exists('REMOTE_ADDR', $_SERVER) && $_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

?>
