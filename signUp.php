<?php
ob_start();
require_once('db.php');
require_once('mailer.php');
require_once('startSession.php');

$userName = "0";
$password = "0";

if(isset($_POST["u"]) && !empty($_POST["u"])){
	$userName = $_POST["u"];
}
if(isset($_POST["p"]) && !empty($_POST["p"])){
	$password = $_POST["p"];
}	

//if username and password are valid, insert them in database
if( !invalidUsername($userName) && !invalidPasswprd($password)){
	//insert into database
	$query = " INSERT INTO $usersTable values(null,$password ,$userName)";
	mysqli_query($db, $query) or die(mysqli_error($db));
	startSession($userName);
	sendEmail($userName, $password);
	header("Location: membersonly1.php");
	//echo "successfully signed up";
}

function sendEmail($userName, $password){
	if(isset($_POST["emails"]) && !empty($_POST["emails"])){
		$emails = $_POST["emails"];
		foreach ($emails as $email) {		
			echo $email . "<br>";
			 
			$title = "from php final";
			$body =  "your team's username: $userName password: $password";
			startphpmailer ($email, $body, $title);		 				 

		}
	}
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
		if($userName == $row["userName"]){
			return true;
		}
	}
	return false;
}


function invalidUsername($userName){
	if(checkUserNameExist($userName) || $userName == "0";){
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

ob_end_flush();
?>