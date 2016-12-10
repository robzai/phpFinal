<?php
ob_start();
session_start();
var_dump($_SESSION);
echo "<br>";
ob_end_flush();	
?>