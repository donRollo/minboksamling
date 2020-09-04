<?php
	
	// Inkludera databasconnection m.m
	include 'incs/startSession.php';
    include 'incs/dbConn.php';
    include 'incs/Mobile_Detect.php';

	// Kolla device
	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

    // Hämta alla serier för att populera dropdown
    $sqlLoop = "SELECT ";
    $sqlLoop = $sqlLoop . "serie_id, ";
    $sqlLoop = $sqlLoop . "serie_name ";
    $sqlLoop = $sqlLoop . "FROM tbl_serie ";
    $sqlLoop = $sqlLoop . "ORDER BY serie_name ASC ";
    $rsLoop = mysqli_query($conn, $sqlLoop);
    $rows = array();
    while($row = $rsLoop->fetch_array()){
        $rows[] = $row;
    }

    // Ta emot valt serieID, annars sätt till default
    if (isset($_GET["serieID"]) && !empty($_GET["serieID"])) {
        $intSerieID = intval($_GET["serieID"]);    
    } else {  
        $intSerieID = 215;    
    }
    
?>
<html>
	<head>
	
		<meta charset="utf-8" />
		<meta http-equiv="x-ua-compatible" content="ie=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		
		<title>Boksamling</title>
	 	
	 	<!-- css -->
	 	<link rel='stylesheet' href='css/tailwind.min.css' type='text/css' media='all' />
		<link rel='stylesheet' id='' href='css/select2.min.css' type='text/css' media='all' />
		<link rel='stylesheet' id='' href='css/select2.design.css' type='text/css' media='all' />
		<link rel='stylesheet' href='css/application.1.1.0.css' type='text/css' media='all' />

	 	<!-- js -->
		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="js/select2.min.js"></script>

	 	<!-- funktioner och jquery -->
		<script>
		    
		    $(document).ready(function(){
		        
		        $('#serieID').on('change', function() {
		            document.forms['myBooks'].submit();
		        })
		        
		        $('#serieIDMobile').on('change', function() {
		            document.forms['myBooksMobile'].submit();
		        })
		        
		        $('#mySubmit').on('click', function() {
		            if ($.trim($('#userName').val()) === '') {
		            	document.myForm.userName.focus();
		                return false;
		            }
		            if ($.trim($('#userPassword').val()) === '') {
		            	document.myForm.userPassword.focus();
		                return false;
		            }
		        });

   		        $('#showForm').on('click', function() {
		        	setTimeout(
		            function() {
					  	var element = document.getElementById("myBlurID");
		  			  	element.classList.add("myBlur");
					  	var element = document.getElementById("myBlurFooterID");
		  			  	element.classList.add("myBlur");
					  	var element = document.getElementById("myBlurHeaderID");
		  			  	element.classList.add("myBlur");
		                $("#myForm").fadeIn(1500);
		                $("#myForm").css("display", "block");
		            },
		            100);
		        })

		        $('#closeForm').on('click', function() {
		        	setTimeout(
		            function() {
					  	document.getElementById("myForm").style.display = "none";
					  	var element = document.getElementById("myBlurID");
		  			  	element.classList.remove("myBlur");
					  	var element = document.getElementById("myBlurFooterID");
		  			  	element.classList.remove("myBlur");
					  	var element = document.getElementById("myBlurHeaderID");
		  			  	element.classList.remove("myBlur");
		                $("#myForm").fadeIn(1500);
		                $("#myForm").css("display", "none");
		            },
		            100);
		        })

		    });

	   	</script>

	</head>

<body class="">

	<!-- LOGIN FORM -->
	<div class="form-popup" id="myForm">
	  <form action="doLogin.php" class="form-container">
	    <label for="email"><b><font color="white">Användarnamn</font></b></label>
	    <input type="text" placeholder="Skriv in användarnamn" name="userName" required>
	    <label for="psw"><b><font color="white">Lösenord</font></b></label>
	    <input type="password" placeholder="Skriv in lösenord" name="userPassword" required>
	    <button type="submit" id="mySubmit" class="btn">Logga in</button>
	    <button type="submit" class="btn cancel" id="closeForm">Avbryt</button>
	  </form>
	</div>

	<!-- FIXED HEADER -->
	<div class="container mx-auto pt-8" style="max-width: 1120px;">	
		<div id="myBlurHeaderID" style="max-width:100%;position:fixed;background-color: #000000;">
			<div style="max-width: 1120px;" class="pl-8 text-center">
				<div class="flex mb-4">
			  		<div class="w-3/4">
						<span class="center strong text-white pl2 pr2">
							<a href="index.php" class="top-link">Startsida</a>
						</span>
						<span class="center strong text-white pl2 pr2">
							<a href="pocket.php" class="top-link pl-4 pr-4">Pocket</a>
						</span>
						<span class="center strong text-white pl2 pr2">
							<a href="whalstrom.php" class="top-link">Whalströms</a>
						</span>
						<span class="center strong text-white pl2 pr2">
							<a href="filmer.php" class="top-link pl-4 pr-4">Filmer</a>
						</span>
						<span class="center strong text-white pl2 pr2">
							<a href="serier.php" class="top-link">Serier</a>
						</span>
						<span class="center strong text-white pl2 pr2">
							<a href="album.php" class="top-link pl-4 pr-4">Album</a>
						</span>
						<?php if (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == 1) { ?>
						<span class="center strong text-white pl2 pr2">
								<a href="doLogout.php" class="top-link">Logga ut</a>
							</span>
						<?php } else { ?>
							<span class="center">
								<a href="#ex2" id="showForm" class="top-link">Logga in</a>
							</span>
						<?php } ?>
					</div>
			  		<div class="w-1/4">
						<form method="get" name="myBooks" id="myBooks" action="pocket.php">
		        		<input type="hidden" name="doSearch" value="1">
		        		<select class="mySelect2" name="serieID" id="serieID" style="width:280px;">
		                <?php
		                    $strSelect = "";
		                    foreach($rows as $row){
		                    $intSerieID_loop    = intval($row["serie_id"]);
		                    $strSerieName_loop  = utf8_encode(trim($row["serie_name"]));
		                    if ($intSerieID == $intSerieID_loop) {
		                        $strSelect = "selected";
		                    }
		                ?>
		                    <option value="<?=$intSerieID_loop?>" <?=$strSelect?>><?=$strSerieName_loop?></option>
		                <?php 
		                    $strSelect = "";
		                    } 
		                ?>
		                </select>
		            	</form>
		        	</div>
				</div>
			</div>
		</div>
	</div>

	<!-- INGENTING -->
	<div id="myBlurID" class="container mx-auto pt-8" style="max-width:1120px;padding-top: 70px;">
		<div style="background-color:#333333;max-width:1120px; border-top: 2px solid grey; border-bottom: 2px solid grey;">
			<div class="flex mb-4 pt-3 pb-3">
		  		<div class="w-4/4">
		  			<h3 class="pt-3 pl-8 text-white">
						Sidan för Whalströms gröna finns inte ännu
					</h3>
				</div>
			</div>
		</div>
	</div>
	
	<!-- FOOTER -->
	<?php include 'incs/footer.php' ?>

	<!-- SELECT2 -->
	<script>
	    $('.mySelect2').select2({theme: "flat" });
	</script>

</body>
</html>