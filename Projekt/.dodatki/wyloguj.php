<?php
	session_start();
	unset($_SESSION['LOGINDB'], $_SESSION['PASSWORDDB']);
	header('Location: Main.html');
?>