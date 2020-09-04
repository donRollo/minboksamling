<?php
	
	include 'incs/startSession.php';

	// Ta bort alla sessions
	session_unset(); 

	// Förstör alla sessions
	session_destroy(); 

	header("Location: index.php?status=3");

?>