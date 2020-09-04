<?php

	$servername = "my165b.sqlserver.se";
	$username = "191516_sx70755";
	$password = "DBboksamling0";
	$dbname = "191516-boksamling";
	
	// Create connection
 	$conn = mysqli_connect($servername, $username, $password, $dbname);
 	// Check connection
 	if (!$conn) {
    	die("Connection failed: " . mysqli_connect_error());
	}

?>