<?php
	
	include 'incs/startSession.php';
	include 'incs/dbConn.php';
	include 'incs/includeFunctions.php';

	// ******************
	// Request variables 
	// ******************
	
	$strUserAlias 		= trim($_REQUEST['userName']);
	$strUserPassword 	= trim($_REQUEST['userPassword']);
	
	// *******************************
	// Create dates to calculate with 
	// *******************************
	
	$dtmDateDate 		= getDateDate();
	$dtmDateNow 		= getDateNow();
	
	$sql = "SELECT COUNT(*) AS finnsDenna ";
	$sql = $sql . "FROM tbl_user ";
	$sql = $sql . "WHERE ";
	$sql = $sql . "user_alias = '$strUserAlias' ";
	$sql = $sql . "AND ";
	$sql = $sql . "user_password = '$strUserPassword'";
	$result = mysqli_query($conn, $sql);
	$data = mysqli_fetch_assoc($result);
	$intFinnsDenna = $data['finnsDenna'];
	
	if ($intFinnsDenna == 1) {
	    
		// ********************************
		// Hämta data om aktuell användare 
		// ********************************
		
		$sql = "SELECT ";
		$sql = $sql . "user_id, ";
		$sql = $sql . "user_alias, ";
		$sql = $sql . "user_status ";
		$sql = $sql . "FROM tbl_user ";
		$sql = $sql . "WHERE ";
		$sql = $sql . "user_alias = '$strUserAlias' ";
		$sql = $sql . "AND ";
		$sql = $sql . "user_password = '$strUserPassword'";
		$result = mysqli_query($conn, $sql);
		$data = mysqli_fetch_assoc($result);
		
		$getUserID							= ceil($data["user_id"]);
		$getUserAlias						= trim($data["user_alias"]);
		$getUserStatus						= ceil($data["user_status"]);
		
		// ************************************************
		// Sätt sessions-variabler för att användas senare 
		// ************************************************
		
		$_SESSION["isLoggedIn"]				= 1;
		$_SESSION["userID"]					= $getUserID;
		$_SESSION["userAlias"]				= $getUserAlias;
		$_SESSION["userStatus"]				= $getUserStatus;
		
		// **********************
		// Uppdatera antal login 
		// **********************
		
		$sql = "SELECT count_login ";
		$sql = $sql . "FROM tbl_user ";
		$sql = $sql . "WHERE ";
		$sql = $sql . "user_id = $getUserID ";
		$result = mysqli_query($conn, $sql);
		$data = mysqli_fetch_assoc($result);
		
		$intCountLogin 		= $data['count_login'];
		$intCountLoginNew 	= ceil($intCountLogin+1);
		$dtmLatestLogin 	= $dtmDateNow;
		
		$sql = "UPDATE tbl_user SET ";
		$sql = $sql . "count_login = $intCountLoginNew, ";
		$sql = $sql . "latest_login = '$dtmLatestLogin' ";
		$sql = $sql . "WHERE ";
		$sql = $sql . "user_id = $getUserID ";
		if (mysqli_query($conn, $sql)) {
	    	//echo "Update was successfully";
		} else {
	    	//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}

		header("Location: index.php?status=1");
		
		$conn->close();
		
	} else {
	    
		header("Location: index.php?status=2");
		
	}
?>