<?php
	session_start();
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header("cache-control: no-store, no-cache, must-revalidate"); // HTTP/1.1
	header("cache-control: post-check=0, pre-check=0", false);
	header("cache-control: max-age=0");
	header("Pragma: no-cache");
	
	set_time_limit(120);
	require_once('template/header.php');
	
	require_once('config.php'); // instantiates key and secret (hopefully)
	require_once('lib/EpiCurl.php');
	require_once('lib/EpiOAuth.php');
	require_once('lib/EpiFoursquare.php');
	
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
	
	//get user info
	if (mysql_connect($db_host, $db_user, $db_pass)) {
		if (mysql_selectdb($db_name)) {
			$result = mysql_query("select sum(mileage) as mileagesum from destinations where userid='".$_SESSION['currentUserID']."' and transportmode='self'");
			$selfPowMileage = round(mysql_fetch_object($result)->mileagesum,2);
			$result = mysql_query("select sum(mileage) as mileagesum from destinations where userid='".$_SESSION['currentUserID']."' and transportmode='mass'");
			$massTransMileage = round(mysql_fetch_object($result)->mileagesum,2);
			$result = mysql_query("select sum(mileage) as mileagesum from destinations where userid='".$_SESSION['currentUserID']."' and transportmode='car'");
			$carMileage = round(mysql_fetch_object($result)->mileagesum,2);
			$totalMileage = $selfPowMileage + $massTransMileage + $carMileage;
		}
	}
	
	
?>
<div class="userinfo">
	<img src="img/me.jpg"/>
	<div class="inner">
		<?php
		//print_r($foursquareUser->response['user']);
		
// 		(
//     [id] => 3773696
//     [firstname] => Zac
//     [lastname] => Clark
//     [friendstatus] => self
//     [homecity] => Boulder, CO
//     [photo] => http://playfoursquare.s3.amazonaws.com/userpix_thumbs/PVIXGAYSBEVU44IT.jpg
//     [gender] => male
//     [email] => hi+foursquare@zacclark.com
//     [types] => Array
//         (
//             [0] => user
//         )
//  
//     [settings] => Array
//         (
//             [pings] => off
//             [sendtotwitter] => 
//             [sendtofacebook] => 
//         )
//  
//     [status] => Array
//         (
//             [friendrequests] => 0
//         )
//  
//     [checkin] => Array
//         (
//             [id] => 268977836
//             [created] => Tue, 16 Nov 10 21:41:14 +0000
//             [timezone] => America/Denver
//             [venue] => Array
//                 (
//                     [id] => 102512
//                     [name] => Engineering Center - UCB
//                     [primarycategory] => Array
//                         (
//                             [id] => 78997
//                             [fullpathname] => Arts & Entertainment:Strip Club
//                             [nodename] => Strip Club
//                             [iconurl] => http://foursquare.com/img/categories/arts_entertainment/stripclub.png
//                         )
//  
//                     [address] => Engineering Center
//                     [city] => Boulder
//                     [state] => CO
//                     [zip] => 80309
//                     [verified] => 
//                     [geolat] => 40.0070631
//                     [geolong] => -105.262544
//                     [hasTodo] => false
//                 )
//  
//             [display] => Zac C. @ Engineering Center - UCB
//         )
//  
//     [badges] => Array
//         (
//             [0] => Array
//                 (
//                     [id] => 1
//                     [name] => Newbie
//                     [icon] => http://foursquare.com/img/badge/newbie.png
//                     [description] => Congrats on your first check-in!
//                 )
//  
//             [1] => Array
//                 (
//                     [id] => 2
//                     [name] => Adventurer
//                     [icon] => http://foursquare.com/img/badge/adventurer.png
//                     [description] => You've checked into 10 different venues!
//                 )
//  
//             [2] => Array
//                 (
//                     [id] => 3
//                     [name] => Explorer
//                     [icon] => http://foursquare.com/img/badge/explorer.png
//                     [description] => You've checked into 25 different venues!
//                 )
//  
//             [3] => Array
//                 (
//                     [id] => 7
//                     [name] => Local
//                     [icon] => http://foursquare.com/img/badge/local.png
//                     [description] => You've been at the same place 3x in one week!
//                 )
//  
//             [4] => Array
//                 (
//                     [id] => 8
//                     [name] => Super User
//                     [icon] => http://foursquare.com/img/badge/superuser.png
//                     [description] => That's 30 check-ins in a month for you!
//                 )
//  
//         )
//  
//     [mayorcount] => 4
//     [mayor] => Array
//         (
//             [0] => Array
//                 (
//                     [id] => 10555155
//                     [name] => 2554 Paintbrush
//                     [address] => 
//                     [city] => 
//                     [state] => 
//                     [verified] => 
//                     [geolat] => 40.001458
//                     [geolong] => -105.129957
//                     [hasTodo] => false
//                 )
//  
//             [1] => Array
//                 (
//                     [id] => 10329439
//                     [name] => 500 Manhattan
//                     [primarycategory] => Array
//                         (
//                             [id] => 79132
//                             [fullpathname] => Home / Work / Other:Home
//                             [nodename] => Home
//                             [iconurl] => http://foursquare.com/img/categories/building/home.png
//                         )
//  
//                     [address] => 
//                     [city] => 
//                     [state] => 
//                     [verified] => 
//                     [geolat] => 39.996618
//                     [geolong] => -105.22879
//                     [hasTodo] => false
//                 )
//  
//             [2] => Array
//                 (
//                     [id] => 11250505
//                     [name] => Apex Movement Boulder
//                     [address] => 
//                     [city] => 
//                     [state] => 
//                     [verified] => 
//                     [geolat] => 40.015073
//                     [geolong] => -105.21908
//                     [hasTodo] => false
//                 )
//  
//             [3] => Array
//                 (
//                     [id] => 48092
//                     [name] => Whole Foods
//                     [primarycategory] => Array
//                         (
//                             [id] => 79235
//                             [fullpathname] => Shops:Food & Drink:Grocery / Supermarket
//                             [nodename] => Grocery / Supermarket
//                             [iconurl] => http://foursquare.com/img/categories/shops/food_grocery.png
//                         )
//  
//                     [address] => 2584 Baseline Rd
//                     [crossstreet] => Broadway
//                     [city] => Boulder
//                     [state] => CO
//                     [zip] => 80302
//                     [verified] => 
//                     [geolat] => 40.0002715
//                     [geolong] => -105.261102
//                     [hasTodo] => false
//                 )
//  
//         )
//  
// )

		?>
		<h1><?php echo $user_info['firstname']; ?> <?php echo $user_info['lastname']; ?></h1><br/>
		<div id="stat">
			<p><b>Level:</b> 70 <b>Rank:</b> Nature God</p>
		</div>
		<div class="clear"></div>
	</div>
</div>
<div class="achievments">
	<h1>Achievments</h1>
	<div class="clear"></div>
		<img src="img/greenmedal.png"/><img src="img/redmedal.png"/><img src="img/bluemedal.png"/>
		<img src="img/greenmedal.png"/><img src="img/redmedal.png"/><img src="img/bluemedal.png"/>
		<img src="img/greenmedal.png"/><img src="img/redmedal.png"/><img src="img/bluemedal.png"/>
		<img src="img/greenmedal.png"/><img src="img/redmedal.png"/><img src="img/bluemedal.png"/>
		<img src="img/greenmedal.png"/><img src="img/redmedal.png"/><img src="img/bluemedal.png"/>
		<img src="img/greenmedal.png"/><img src="img/redmedal.png"/><img src="img/bluemedal.png"/>
		<img src="img/greenmedal.png"/><img src="img/redmedal.png"/><img src="img/bluemedal.png"/>
		<img src="img/greenmedal.png"/><img src="img/redmedal.png"/><img src="img/bluemedal.png"/>
		<img src="img/greenmedal.png"/><img src="img/redmedal.png"/><img src="img/bluemedal.png"/>
		<img src="img/greenmedal.png"/><img src="img/redmedal.png"/><img src="img/bluemedal.png"/>
		<img src="img/greenmedal.png"/><img src="img/redmedal.png"/><img src="img/bluemedal.png"/>
		<img src="img/greenmedal.png"/><img src="img/redmedal.png"/><img src="img/bluemedal.png"/>
		<div class="clear"></div>
</div>
<div class="footprint">
	<!--<h2>Footprint:UserName</h2>-->
	<h2>Your Footprint:</h2>
	<i>How you get to where you go affects the environment.</i>
	<div id="headerInfo">
		<div id="mileageDetails">
			<div class='headerRow'>Total Miles: <span id='totalMileage' class='mileageSum'><?php echo($totalMileage);?></span></div>
			<div class='headerRow'>Self-powered Miles: <span id='selfPowMileage' class='mileageSum'><?php echo($selfPowMileage);?></span></div>
			<div class='headerRow'>Mass Transit Miles: <span id='massTransitMileage' class='mileageSum'><?php echo($massTransMileage);?></span></div>
			<div class='headerRow'>Driving Miles: <span id='carMileage' class='mileageSum'><?php echo($carMileage);?></span></div>
		</div>
		<div id="accomplishmentsCont">
			<div class="accomplishment">
				<?php
					/* this messaging should be dynamic based on heuristics that we specify
					 * 
					 */
				?>
				In the past 14 days, you've racked up <span class='mileageSum'><?php echo($selfPowMileage);?></span> miles on foot, which is <span class='mileageSum'>##</span> more than your previous 14 day total.  Nice work!
			</div>
		</div>
		<br clear="all" />
	</div>
<?php	
	try {
		
		/* 
		 alert if homebase isn't set -> should do this during registration -> after registration, but before
		 page access -> shouldn't be able to get to this page without homebase being set
		 */
		
		/* what if homebase isn't a foursquare location? */
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
		//another reason to store these items in the db	is for homebases that aren't 4sq locationss
		
		//eventually, this will be done via async js/php calls - for now, we'll cheat
		//specifying the variables like this essentially makes them global variables
		//(i know, i know - like i said, cheating)
?>
		<script type='text/javascript' language='JavaScript'>
			var homebaseGeoLat = <?php echo($homebaseGeoLat); ?>;
			var homebaseGeoLong = <?php echo($homebaseGeoLong); ?>; 
		</script>
		
		

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
		echo("Number of new stops: ".$numVenues."<br />");
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
?>
	
<?php
		
		//this data will come from the application database
		//change numVenues to a default (20)
		if (mysql_selectdb($db_name)) {
			$result = mysql_query("select * from destinations where userid='".$_SESSION['currentUserID']."' order by foursquarecheckinid desc limit 25");
			$numRows = mysql_num_rows($result);
			if ($numRows > 0) {
			//if no results, don't display table headers
?>	
				<table id="destinationTable">
					<thead>
						<tr>
							<th>Destination</th>
							<th id="checkInDateCol">Check In Date</th>
							<th id="startPointCol">Start Point</th>
							<th id="mileageCol">Mileage</th>
							<th>Mode of Transportation</th>
							<th class="lastStopCell">Last Stop?</th>
							<th class="ignoreCheckInCell">Ignore Check In?</th>  <?php  //or would deleting the record be better? ?>
							<th id="updateCol">&nbsp;</th>
						</tr>
					</thead>
				<tbody>
<?php
				/* need to store mileage in db */
				
				for ($i=0; $i<$numRows; $i++) {
					//need to check each venue to make sure it is a legal venue
					$currentVenue = mysql_fetch_object($result);
					if (isset($currentVenue->foursquarecheckinid)) {
						//$currentVID = $currentVenue->foursquarevid;
						$currentVenueLongURL = "http://foursquare.com/venue/".$currentVenue->foursquarevid;
						
						//print_r($currentVenueDetails);
						//echo("venue name: ".$currentVenue->venue->name." (".$currentVenue->created.")<br />");
						echo("<tr>\n<td>");
						
						//if ($currentVenueLongURL != "") {
						echo("<a href='".$currentVenueLongURL."' target='_blank'>".$currentVenue->venuename."</a><input type='hidden' class='foursquareCheckInID' value='".$currentVenue->foursquarecheckinid."' />");
						//}
						//else {
						//	echo(htmlspecialchars($currentVenue->venue->name));
						//}
						
						echo("</td>\n");
						echo("<td>".parseFoursquareDate($currentVenue->checkintime)."</td>\n");
						//echo("<td>".$currentVenue->id."</td>\n");
						
						/* 
						 * need to  get vid of next venue in db (previous in timeline) ->? why?
						 * 
						 */
						
						//if ignoreChecked, select boxes and last stop should be disabled
						if ($currentVenue->ignorecheckin == 1) {
							$ignoreChecked = " checked='checked'";
							$startPointDisabled = $transportModeDisabled = $lastStopDisabled = " disabled='disabled'";
						}
						else {
							$ignoreChecked = $startPointDisabled = $lastStopDisabled = $transportModeDisabled = "";
						}
						
						//$ignoreChecked = ($currentVenue->ignorecheckin == 1 ? " checked='checked'" : "");
						
						
						echo("<td><input type='hidden' class='geoLatCoord' value='".$currentVenue->geolat."' /><input type='hidden' class='geoLongCoord' value='".$currentVenue->geolong."' />");
						echo("<select class='startPointSelector'".$startPointDisabled."><option value='-1'>select start point...</option>");
						echo("<option value='home'".selectValueChecker($currentVenue->startpoint, "home").">Homebase</option>");
						echo("<option value='prev'".selectValueChecker($currentVenue->startpoint, "prev").">Previous Check In</option></select></td>\n");

						echo("<td class='mileageCell'>".round($currentVenue->mileage,2)."</td>\n");
						
						echo("<td><select class='transportationModeSelector'".$transportModeDisabled."><option value='0'>select transportation...</option>");
						echo("<option value='self'".selectValueChecker($currentVenue->transportmode, "self").">Self-Powered</option>");
						echo("<option value='mass'".selectValueChecker($currentVenue->transportmode, "mass").">Mass Transportation</option>");
						echo("<option value='car'".selectValueChecker($currentVenue->transportmode, "car").">Car</option></select></td>\n");

						$lastStopChecked = ($currentVenue->laststop == 1 ? " checked='checked'" : ""); 
						echo("<td class='lastStopCell'><input type='checkbox' class='lastStopCheckbox'".$lastStopChecked.$lastStopDisabled." /></td>\n");
						
						echo("<td class='ignoreCheckInCell'><input type='checkbox' class='ignoreCheckinCheckbox'".$ignoreChecked." /></td>\n");
						echo("<td><input type='button' value='update' class='updateDestinationInfoButton' /><span class='progressIndicator'>&nbsp;</span></td>");
						//echo("<td class='rowStatus'>&nbsp;</td>");	//used to display update status messaging
						echo("</tr>\n");
					}
				}
?>

					</tbody>
				</table>
<?php
			}
		}
	}

	catch (Exception $e) {
		echo("Well, golly - something went wrong.<br />");
		echo "Error: " . $e;
 	}
	
	
	function calcSphDistance($startLat, $startLong, $endLat, $endLong) {
		$startLat = deg2rad($startLat);
		$startLong = deg2rad($startLong);
		$endLat = deg2rad($endLat);
		$endLong = deg2rad($endLong);
		$distance = acos(sin($startLat)*sin($endLat) + cos($startLat)*cos($endLat)*cos($endLong-$startLong))*6371;
		
		return $distance;	
	}
	
	function selectValueChecker($actualVal, $selectionVal) {
		if ($actualVal == $selectionVal) {
			return " selected='selected'";
		}
		else {
			return "";
		}
	}
	
	function parseFoursquareDate($foursquareDate) {
		$day = substr($foursquareDate, 5, 2);
		$month = substr($foursquareDate, 8, 3);
		$year = substr($foursquareDate, 12, 2);
		return $month." ".$day.", 20".$year;
	}


	//require_once('template/footer.php');
?>
</div>
</div>
</div>
</div>
</body>
</html>
