<?php
ob_start();
require_once('db.php');
session_start();
//var_dump($_SESSION);
//echo "<br>";

if(isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == "1"){
	$userName = $_SESSION['userName'];
	echo "login as " . $userName;
	//echo "<br>";
	getComments($userName);
	//showrecord($_SESSION['userName'], '2016-12-10', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
	echo "<a href='logout.php'>logout</a>";
}else{
	//print_r($_SESSION);
	header("Location: signInUI.html");
}

		
function showrecord($userName, $date, $contents){
	echo "<div id='divActivites' name='divActivites' style='border:1px solid black'>
		<div>
			<span>$userName</span>
			<span>$date</span>
		</div>
		<span>
			<textarea id='inActivities' name='inActivities' style='border:1px solid black'>$contents</textarea>
			<span>file</span>
		</span>
	</div>"; 
}

function getComments($userName){
	
	global $db;	
	global $usersTable;
	global $commentsTable;
	global $filesTable;
	
	$userId;	
	$selectQuery="
					select userId from $usersTable where userName = $userName
				 ";
	$resultSet = mysqli_query($db, $selectQuery) or die(mysqli_error($db));
	$row = mysqli_fetch_assoc($resultSet);
	foreach($row as $key=>$col){
		$userId = $col;
		//echo $userId;
	}
	
	$selectQuery="
					select comment,date from $commentsTable where userId = $userId order by commentId desc
				 ";
	$resultSet = mysqli_query($db, $selectQuery) or die(mysqli_error($db));
	while($row = mysqli_fetch_assoc($resultSet)){		
		$contents = $row["comment"];
		$date = $row["date"];		
		showrecord($userName, $date, $contents);
	}
	
}
		
ob_end_flush();	
?>