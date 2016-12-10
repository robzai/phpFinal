<?php
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$database = "finalexam";

	$db = mysqli_connect($servername, $username, $password, $database) or die(mysqli_connect_error());
	