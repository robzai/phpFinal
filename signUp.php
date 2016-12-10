<?php
ob_start();
require_once('db.php');

$userName = "0";
$password = "0";

$usersTable = "users";

if(isset($_POST["u"]) && !empty($_POST["u"])){
	$userName = $_POST["u"];
}
if(isset($_POST["p"]) && !empty($_POST["p"])){
	$password = $_POST["p"];
}
if(isset($_POST["emails"]) && !empty($_POST["emails"])){
	$emails = $_POST["emails"];
	foreach ($emails as $email) {
		
		 echo $email . "<br>";
	}
}
	

//if username and password are valid, insert them in database
if( !invalidUsername($userName) && !invalidPasswprd($password)){
	//insert into database
	//$query = " INSERT INTO $usersTable values(null, $userName, $password)";
	//mysqli_query($db, $query) or die(mysqli_error($db));
	//startSession($userName);
	
	
	echo "successfully signed up";
}


function checkLength ($password){
	return strlen($password) != 3;
}

function checkUserNameExist ($userName){
	global $usersTable;
	global $db;
	$selectQuery="
					select userName from $usersTable
				 ";
	$resultSet = mysqli_query($db, $selectQuery) or die(mysqli_error($db));
	while($row = mysqli_fetch_assoc($resultSet)){
		foreach($row as $key=>$col){
			if($userName == $col){
				return true;
			}
		}
	}
	return false;
}


function invalidUsername($userName){
	if(checkUserNameExist($userName)){
		echo "username exist";
		echo "<br>";
		return true;
	}
	return false;
}

function invalidPasswprd($password){
	if(checkLength($password)){
		echo "password must be 3 digits or longer";
		echo "<br>";
		return true;
	}
	return false;
}


function startSession($userName){
	session_start();
	$_SESSION['authenticated'] = 1;
	$_SESSION['userName'] = $userName;
}

ob_end_flush();
?>