<?php
	session_start();
	unset($_SESSION['LOGINDB'], $_SESSION['PASSWORDDB'],$_SESSION['USER_ID']);
	header('Location: Main.php');
?>