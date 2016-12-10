<?php
function startSession($userName){
	session_start();
	$_SESSION['authenticated'] = 1;
	$_SESSION['userName'] = $userName;
}
?>