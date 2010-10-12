<?php
	session_start();
	
	require_once('EpiCurl.php');
	require_once('EpiOAuth.php');
	require_once('EpiFoursquare.php');
	
	
	//check session variables
	
	session_destroy();
	session_start();


	//foursquare API key/secret
	//$consumer_key = "";
	//$consumer_secret = "";
	require_once('config.php');
	// $loginurl = "";
	
	try {
	  $foursquareObj = new EpiFoursquare($consumer_key, $consumer_secret);
	  $results = $foursquareObj->getAuthorizeUrl();
	  $loginurl = $results['url'] . "?oauth_token=" . $results['oauth_token'];
	  $_SESSION['secret'] = $results['oauth_token_secret'];
	}
	
	catch (Execption $e) {
		echo("here is the exception: ".print_r($e));
	  //If there is a problem throw an exception
	}
	echo "<a href='" . $loginurl . "'>Login Via Foursquare</a>";  //Display the Foursquare login link
	echo "<br />";
	//This is your OAuth token and secret generated above
	//The OAuth token is part of the Foursquare link above
	//They are dynamic and will change each time you refresh the page
	//If everything is working correctly both of these will show up when you open index.php
	var_dump($results['oauth_token']);
	echo "<br />";
	var_dump($_SESSION['secret']);
?>