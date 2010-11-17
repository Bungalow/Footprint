<?php
	require_once('init.php');
	
	$foursquareObj = new EpiFoursquare($consumer_key, $consumer_secret);
	
	if (!isset($_SESSION['oauth_token'])) {
		$foursquareObj->setToken($_REQUEST['oauth_token'],$_SESSION['secret']);
		$token = $foursquareObj->getAccessToken();
		$_SESSION['oauth_token'] = $token->oauth_token;
		$_SESSION['secret'] = $token->oauth_token_secret;
	}
	
	$foursquareObj->setToken($_SESSION['oauth_token'], $_SESSION['secret']);
	
	// User info
	$params = array("badges"=>1, "mayor"=>1);
	$foursquareUser = $foursquareObj->get_user($params);
	$user_info = $foursquareUser->response['user'];
	$_SESSION['currentUserID'] = $user_info['id'];
	$_SESSION['4sq_user'] = $user_info;
	$_SESSION['4sq_obj'] = $foursquareObj;

	try {
		 
		// alert if homebase isn't set -> should do this during registration -> after registration, but before page access -> shouldn't be able to get to this page without homebase being set
		
		// what if homebase isn't a foursquare location?
		/* -> can guide user through creation of venue (cumbersome)
		 * -> can set foursquare vid to 0 or -1 and set geolocation (geolong, geolat) via google maps
		 */
		
		//ideally, these items should be stored in the Footprint db to save on 4sq API calls
		//hardcoded homebase
		$homebase = $foursquareObj->get_venue(array("vid"=>$homebase_vid));
		// $homebaseVID = 4500522; this should now be instantiated in config.php
		$homebaseGeoLat = $homebase->venue->geolat;
		$homebaseGeoLong = $homebase->venue->geolong;
		$homebaseURL = $homebase->venue->short_url;
		$_SESSION["homebase"] = Array(
			"geolat" => $homebaseGeoLat,
			"geolong" => $homebaseGeoLong
		)
		//another reason to store these items in the db	is for homebases that aren't 4sq locationss
		
		//eventually, this will be done via async js/php calls - for now, we'll cheat
		//specifying the variables like this essentially makes them global variables
		//(i know, i know - like i said, cheating)
?>

<?php		
		//see if there are any records in the db
		//order by timestamp desc
		
		//if there are, get the most recent record's timestamp and set it as sinceid as option in history query
		
		if (mysql_connect($db_host, $db_user, $db_pass)) {
			if (mysql_selectdb($db_name)) {
				$destinationsQuery = mysql_query("select foursquarecheckinid from destinations where userid=".$_SESSION['currentUserID']." order by foursquarecheckinid desc");
				if (mysql_num_rows($destinationsQuery) == 0) {
					$sinceID = "";
				}
				else {
					$resultRow = mysql_fetch_object($destinationsQuery);
					$sinceID = $resultRow->foursquarecheckinid;
				}
			}
		}
		
		//get recent checkins
		//$venues = $foursquareObj->get_history();
		$venues = $foursquareObj->get_history(array("sinceid"=>$sinceID))->checkins;
		
		//a cleaner approach: filter out 'shout-only' checkins from $venues so they don't show up in the total number of venues
		
		$numVenues = sizeof($venues);
		//echo("Number of new stops: ".$numVenues."<br />");
		//if using a sinceid, ordering will be oldest to newest. if not, ordering will be newest to oldest
		if ($sinceID != "") {
			$venues = array_reverse($venues);
		}
		
		//insert data from foursquare into database
		for ($i=0; $i<$numVenues; $i++) {
			//need to check each venue to make sure it is a legal venue
			$currentVenue = ($venues[$i]);
			if (isset($currentVenue->venue->id)) {
				$currentVID = $currentVenue->venue->id;
				$currentVenueCheckinTime = $currentVenue->created;
				$currentVenueLongURL = "http://foursquare.com/venue/".$currentVID;
				$currentVenueName = htmlspecialchars($currentVenue->venue->name, ENT_QUOTES);
				$currentFoursquareCheckinID = $currentVenue->id;
				$currentVenueGeoLat = $currentVenue->venue->geolat;
				$currentVenueGeoLong = $currentVenue->venue->geolong;
				//insert data into database
				if (mysql_selectdb("footprint")) {
					$result = mysql_query("insert into destinations (userid, foursquarevid, checkintime, foursquareurl, geolat, geolong, foursquarecheckinid, venuename) values ('".$_SESSION['currentUserID']."', '".$currentVID."', '".$currentVenueCheckinTime."', '".$currentVenueLongURL."', '".$currentVenueGeoLat."', '".$currentVenueGeoLong."', '".$currentFoursquareCheckinID."', '".$currentVenueName."')");
					//echo("number of affected rows:".mysql_num_rows($result));
				}
			}
		}
	}

	catch (Exception $e) {
		echo("Well, golly - something went wrong.<br />");
		echo "Error: " . $e;
 	}

?>
<html>
	<head>
		<meta http-equiv="refresh" content="0;url=/">
	</head>
	<body>
		Redirecting
	</body>
</html>