<?php
ob_start();
require_once('db.php');
require_once('startSession.php');


//read username and password from input
$userName;
$password;
if(isset($_POST["u"]) && !empty($_POST["u"])){
	$userName = $_POST["u"];
}
if(isset($_POST["p"]) && !empty($_POST["p"])){
	$password = $_POST["p"];
}	

if(checkUser($userName, $password)){
	startSession($userName);
	//var_dump($_SESSION);		
	header("Location: membersonly2.php");
} 


//check if user is valid
function checkUser($userName, $password){
	
	global $usersTable;
	global $db;
	$selectQuery="
					select password from $usersTable where userName = $userName
				 ";
	$resultSet = mysqli_query($db, $selectQuery) or die(mysqli_error($db));
	$row = mysqli_fetch_assoc($resultSet);
	
	if($password == $row["password"];){
		return true;
	}
			
	return false;
}

ob_end_flush();	
?>