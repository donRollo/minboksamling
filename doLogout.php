<?php
	
	include 'incs/startSession.php';

	// Ta bort alla sessions
	session_unset(); 

	// F�rst�r alla sessions
	session_destroy(); 

	header("Location: index.php?status=3");

?>