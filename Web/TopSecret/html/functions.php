<?php

function connect_to_db($find = 0){
	if($find)
		return new mysqli("localhost", "ts-dba-find", "ThisIsMuhFINDpassword!", "TopSecret");
	else
		return new mysqli("localhost", "ts-dba", "ThisIsMuhDBpassword!", "TopSecret");
}

function register_user($conn, $username, $password, $admin = 0){
	//setup prepared statement
	$statement = $conn->prepare("INSERT INTO users (username, password, session, admin) VALUES (?, ?, ?, ?)");

	//generate hash and session ID
    $pass = hash("sha512", $username . $password);
	$tempSessID = hash("sha512", "gobbledygook" . $pass . $username . $admin . $password . microtime());

	//bind params to prepared statement, execute query
	$statement->bind_param("sssi", $username, $pass, $tempSessID, $admin);
	$statement->execute();
    if($statement->error)
        $result = $statement->error."<br>".$sql;
    else
        $result = $statement->insert_id;

	$statement->close();
    return array($result, $tempSessID);
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
    else{
        $result = False;
    }
    return $result;
}

function get_info($conn, $sessionid){
	if($sessionid == "")
	{
		$result['error'] = "Session ID must not be blank.";
		return $result;
	}
	$statement = $conn->prepare("SELECT id, username FROM users where session = ?");
	$statement->bind_param("s", $sessionid);
	$statement->execute();
	$query = $statement->get_result();
    $error = $statement->error;

	if($error){
        $result['error'] = $error;
        return $result;
    }
    $result = $query->fetch_array(MYSQLI_ASSOC);
    $query->free();
    return $result;
}

function lookup_user($conn, $id){
    if(is_numeric($id))
        $sql = "SELECT id, username FROM users WHERE `id` = $id";
    else
        $sql = "SELECT id, username FROM users WHERE username = '".$id."'";

	$query = $conn->query($sql);
	$rows = "";
    if($conn->error){
        $result = $sql."<br>".$conn->error;
    }
    else if($query->num_rows == 0){
        if(isset($result)) {
            $result = $result."<br>No user found";
        }
        else{
            $result = "No user found";
        }
    }
    else{
        $rows = $query->fetch_all();
		$result = "";
        $query->free();
    }
    return array($rows, $result);
}

function getNumSecrets($conn, $id)
{
	$sql = "select COUNT(id) as count from secrets where userid = ?";
	$statement = $conn->prepare($sql);
	$statement->bind_param("s", $id);
	$statement->execute();
	$result = $statement->get_result();
	$statement->close();
	$result = $result->fetch_array(MYSQLI_ASSOC);
	return $result['count'];
}

function getSecrets($conn, $sessionid)
{
	$sql = "SELECT secret, userid FROM secrets WHERE userid IN(SELECT id from users where session = ?)";
	$statement = $conn->prepare($sql);
	$statement->bind_param("s", $sessionid);
	$statement->execute();
	$result = $statement->get_result();
	$statement->close();
	return $result;
}

function addSecret($conn, $sec, $myID)
{
	$statement = $conn->prepare("INSERT INTO secrets (secret, userid) VALUES (?, ?)");
	$statement->bind_param("si", $sec, $myID);
	$statement->execute();
	$error = $statement->error;
	$statement->close();

	return $error;
}

?>
