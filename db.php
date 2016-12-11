<?php
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$database = "finalexam";
	
	$usersTable = "users";
	$commentsTable = "comments";
	$filesTable = "files";

	$db = mysqli_connect($servername, $username, $password, $database) or die(mysqli_connect_error());
	