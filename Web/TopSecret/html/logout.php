<?php 

session_start();
session_unset();
session_regenerate_id();
header('Location: /index.php');
die();

?>
