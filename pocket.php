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

    // Om man ska söka
    if (isset($_GET["doSearch"]) && !empty($_GET["doSearch"])) {
        $intDoSearch = 1;    
    }else{  
        $intDoSearch = 1;    
    }

    // Ta emot valt serieID, annars sätt till default
    if (isset($_GET["serieID"]) && !empty($_GET["serieID"])) {
        $intSerieID = intval($_GET["serieID"]);    
    } else {  
        $intSerieID = 215;    
    }

    // Gör söken
    if ($intDoSearch == 1) {

        $sqlLoop1 = "SELECT ";
        $sqlLoop1 .= "M.media_id, ";
        $sqlLoop1 .= "M.media_no, ";
        $sqlLoop1 .= "M.media_name, ";
        $sqlLoop1 .= "M.media_year, ";
        $sqlLoop1 .= "M.serie_id, ";
        $sqlLoop1 .= "S.serie_name, ";
        $sqlLoop1 .= "W.writer_name ";
        $sqlLoop1 .= "FROM tbl_media M ";
        $sqlLoop1 .= "INNER JOIN tbl_serie S ON M.serie_id = S.serie_id ";
        $sqlLoop1 .= "INNER JOIN tbl_writer W ON M.writer_id = W.writer_id ";
        $sqlLoop1 .= "WHERE ";
        $sqlLoop1 .= "M.serie_id = ".$intSerieID." ";
        $sqlLoop1 .= "ORDER BY M.media_no ASC";
        $rsLoop1 = mysqli_query($conn, $sqlLoop1);

        $sql = "SELECT ";
        $sql = $sql . "serie_name, ";
        $sql = $sql . "number_of_items, ";
        $sql = $sql . "max_items ";
        $sql = $sql . "FROM tbl_serie ";
        $sql = $sql . "WHERE ";
        $sql = $sql . "serie_id = $intSerieID ";
        $result = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($result);
        $strSerieName = utf8_encode($data['serie_name']);
        $intNumberOfItems = $data['number_of_items'];
        $intMaxItems = $data['max_items'];
    }

?>
<html>
	<head>
	
		<meta charset="utf-8" />
		<meta http-equiv="x-ua-compatible" content="ie=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		
		<title>Boksamling - pocket</title>
	 	
	 	<!-- css -->
		<link rel='stylesheet' href='css/tailwind.min.css' type='text/css' media='all' />
		<link rel='stylesheet' id='' href='css/select2.min.css' type='text/css' media='all' />
		<link rel='stylesheet' id='' href='css/select2.design.css' type='text/css' media='all' />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
	 	<link rel='stylesheet' id='' href='css/lightbox.css' type='text/css' media='all' />
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
						<span class="center">
							<a href="index.php" class="top-link">Startsida</a>
						</span>
						<span class="center">
							<a href="pocket.php" class="top-link-active pl-4 pr-4">Pocket</a>
						</span>
						<span class="center">
							<a href="whalstrom.php" class="top-link">Whalströms</a>
						</span>
						<span class="center">
							<a href="filmer.php" class="top-link pl-4 pr-4">Filmer</a>
						</span>
						<span class="center">
							<a href="serier.php" class="top-link">Serier</a>
						</span>
						<span class="center">
							<a href="album.php" class="top-link pl-4 pr-4">Album</a>
						</span>
						<?php if (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == 1) { ?>
						<span class="center">
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

	<!-- LISTA -->
	<div id="myBlurID" class="container mx-auto pt-8" style="max-width:1120px;padding-top: 70px;">
		<div style="background-color:#333333;max-width:1120px; border-top: 2px solid grey; border-bottom: 2px solid grey;">
			<div class="flex mb-4 pt-3 pb-3">
		  		<div class="w-3/4">
		  			<h2 class="pt-3 pl-8" style="color:white;">
						&nbsp;<?=$strSerieName?> (<?=$intNumberOfItems?>/<?=$intMaxItems?>)
					</h2>
				</div>
		  		<div class="w-1/4">
		  			<div class="pt-3 pr-8 text-right">
						<a><span><i class="fa fa-list" style="color:white;font-size:30px;"></i></span></a>&nbsp;&nbsp;&nbsp;
						<a><span><i class="fa fa-image" style="color:white;font-size:30px;"></i></span></a>
					</div>
	        	</div>
			</div>
			<?php if (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == 1) { ?>
			<div>
				<div class="w-4/4">
					<div class="pl-8 pr-8 pb-8">
						<div id="myAddAccordian">
							<div class="myAddAccordian-toggle center bg-black test-white p-2" style="cursor:default;">
						    	<ul class="flex flex-wrap pt1 pb1">
						    		<li class="col-2 lg-col-2 background-black p1">
										<span style="color:#cccccc;"><strong>Lägg till ny bok</strong></span>
									</li>
								</ul>
						    </div>
						    <div class="myAddAccordian-content">
								<form method="post" action="saveBook.php">
									<table class="table-fixed">
									  <thead>
									    <tr class="text-white">
									      <th class="w-1/12 px-4 py-2 text-left">Nummer</th>
									      <th class="w-4/12 px-4 py-2 text-left">Titel</th>
									      <th class="w-4/12 px-4 py-2 text-left">Författare</th>
									      <th class="w-1/4 px-4 py-2 text-left">År</th>
									      <th class="w-1/4 px-4 py-2 text-left">&nbsp;</th>
									    </tr>
									  </thead>
									  <tbody>
									    <tr class="text-white">
									      <td class="px-4 py-2"><input type="text" name="media_no" value="" style="width: 100%; height: 25px;"></td>
									      <td class="px-4 py-2"><input type="text" name="media_name" value="" style="width: 100%; height: 25px;"></td>
									      <td class="px-4 py-2"><input type="text" name="media_writer" value="" style="width: 100%; height: 25px;"></td>
									      <td class="px-4 py-2"><input type="text" name="media_year" value="" style="width: 100%; height: 25px;"></td>
									      <td class="px-4 py-2"><input type="submit" class="myButton" value="SPARA"></td>
									    </tr>
									  </tbody>
									</table>
							    </form>
						    </div>
						</div>	
					</div>
				</div>
			</div>
			<?php } ?>
			<div>
				<div class="w-4/4">
					<div class="pl-8 pr-8 pb-8">
						<table class="table-fixed">
						  <thead>
						    <tr class="bg-black text-white">
						      <th class="w-1/12 px-4 py-2 text-left">Nummer</th>
						      <th class="w-4/12 px-4 py-2 text-left">Titel</th>
						      <th class="w-4/12 px-4 py-2 text-left">Författare</th>
						      <th class="w-1/4 px-4 py-2 text-left">År</th>
						      <th class="w-1/4 px-4 py-2 text-left">Bild</th>
						    </tr>
						  </thead>
						  <tbody>
						    <?php   
                            $countX = 0;
                            while($row = mysqli_fetch_assoc($rsLoop1)) {
                                $intMediaID_loop1 = intval($row["media_id"]);
                                $intMediaNo_loop1 = intval($row["media_no"]);
                                $strMediaName_loop1 = utf8_encode(trim($row["media_name"]));
                                $strSerieName_loop1 = utf8_encode(trim($row["serie_name"]));
                                $intSerieID_loop1 = intval($row["serie_id"]);
                                $intMediaYear_loop1 = intval($row["media_year"]);
                                $strWriterName_loop1 = utf8_encode(trim($row["writer_name"]));
                                if ($intMediaNo_loop1 > 10) {
	                                $strFileName_loop1 = $intSerieID_loop1 . "_00" . $intMediaNo_loop1 . "_F.JPG";
                                } else {
                               		$strFileName_loop1 = $intSerieID_loop1 . "_0" . $intMediaNo_loop1 . "_F.JPG";
                                }
                            ?>
						    <tr class="text-white">
						      <td class="px-4 py-2"><?=$intMediaNo_loop1?></td>
						      <td class="px-4 py-2"><?=$strMediaName_loop1?></td>
						      <td class="px-4 py-2"><?=$strWriterName_loop1?></td>
						      <td class="px-4 py-2"><?=$intMediaYear_loop1?></td>
						      <td class="px-4 py-2"><a href="img/sol-big.jpg" data-lightbox="image-1" data-title="My caption"><img src="img/sol-small.jpg"></a></td>
						    </tr>
						    <?php 
                            	$countX = $countX + 1;
                            } ?>
						  </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
		
	<!-- FOOTER -->
	<?php include 'incs/footer.php' ?>

	<!-- SELECT2 -->
	<script src="js/lightbox.js"></script>
	<script>
	    $('.mySelect2').select2({theme: "flat" });
	</script>
	
	<?php if (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == 1) { ?>
	<script type="text/javascript">
	    $(document).ready(function() {
	        $('#myAddAccordian').find('.myAddAccordian-toggle').click(function(){
	            $(this).next().slideToggle('slow');
	            $(".myAddAccordian-content").not($(this).next()).slideUp('slow');
	        });
	    });
	</script>
	<?php } ?>

</body>
</html>