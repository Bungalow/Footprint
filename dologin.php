<?php
	session_start();
	
	require_once('header.php');
	
	$consumer_key = "HF0QSB4B5TVXKYUPWEGR4HQMWV23OZABRZUQR0VJZSISYTVC";
	$consumer_secret = "3EEYW01AEP0ANE40IHGYOFVRUZPLTLDCMBWRYSCDZZKSKOWT";
	$loginurl = "";
	//foursquare-async library files
	require_once('EpiCurl.php');
	require_once('EpiOAuth.php');
	require_once('EpiFoursquare.php');
	
	//validate footprint login

	if (mysql_connect("hostname", "dbusername", "dbpass")) {
		//echo("connected to db");
		//connect to database
		if (mysql_selectdb("footprint")) {
			//try to validate user info
			$loginExistsQuery = mysql_query("select * from users where username='mydogisarobot@gmail.com' and footprintpass=password('testerpass')");
			if (mysql_num_rows($loginExistsQuery) == 0) {
				//no username
				echo("user not found<br />");
				//redirect back to login page with error code
			}
			else {
				echo("user found<br />");
				//set session variable for logged in status
				$_SESSION['loggedIn'] = 1;
				$resultRow = mysql_fetch_object($loginExistsQuery);
				$_SESSION['currentUsername'] = $resultRow->username;
				$_SESSION['currentUserID'] = $resultRow->userid;
				//echo($_SESSION['currentUsername']);
				//if valid footprint login, proceed to Foursquare login
				
				//if oauth_token exists in database, skip login attempt and go to application page? 
/*
				try {
				  $foursquareObj = new EpiFoursquare($consumer_key, $consumer_secret);
				  $results = $foursquareObj->getAuthorizeUrl();
				  $loginurl = $results['url'] . "?oauth_token=" . $results['oauth_token'];
				  $_SESSION['secret'] = $results['oauth_token_secret'];
				  
				  
				  
				  ->automatically go to loginurl
				  
				  
				  
				}
				
				catch (Exception $e) {
					
					
				}
*/
			}
		}
		else {
			//db unavailable
			echo("could not connect to footprintdb");
		}
		
	}
	else {
		//db unavailable
		$errorMsg = "Unable to connect to the database. If this problem persists, please contact the Administrator.<br />";
		echo($errorMsg);
	}
	
	
	
	
	
	
	
	

	require_once('footer.php');
?>