<?php
	session_start();
	session_unset(); 
	session_destroy(); 
	//unset($_SESSION["username"]);
	//unset($_SESSION["pid"]);
	//unset($_SESSION["role"]);
  	//session_write_close();
	header("location: index.php");
?>
