<?php
require_once('init.php');
tmpl('header');
?>

<?php if (!$logged_in) { // no user ?>
	
	<div class="login">
		<img src="img/greenlogin.png"/>
		<p>
			<?php
	
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
				echo "<a href='" . $loginurl . "'><input type='button' name='groovybtn2' class='groovy' value='Login Via FourSquare' title=''></a>";  //Display the Foursquare login link
			?>
		</p>
		<p>Not a FourSquare User? It's ok join by clicking below:</p>
		<img src="img/greennew.png"/><br/>
		<a href="http://foursquare.com"><input type="button" name="groovybtn2" class="groovy" value="Join Foursquare" title=""></a>
	</div>
	
<?php }else{ // user is logged in ?>
	
	<?php
	//get mileage info
	$result = mysql_query("select sum(mileage) as mileagesum from destinations where userid='".$_SESSION['currentUserID']."' and transportmode='self'");
	$selfPowMileage = round(mysql_fetch_object($result)->mileagesum,2);
	$result = mysql_query("select sum(mileage) as mileagesum from destinations where userid='".$_SESSION['currentUserID']."' and transportmode='mass'");
	$massTransMileage = round(mysql_fetch_object($result)->mileagesum,2);
	$result = mysql_query("select sum(mileage) as mileagesum from destinations where userid='".$_SESSION['currentUserID']."' and transportmode='car'");
	$carMileage = round(mysql_fetch_object($result)->mileagesum,2);
	$totalMileage = $selfPowMileage + $massTransMileage + $carMileage;
	?>
	
	<div class="userinfo">
		<img src="img/me.jpg"/>
		<div class="inner">
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
		<?php
		foreach($user_info['badges'] as $badge){
		?>
		<img src="<?php echo $badge['icon']; ?>"/>
		<?php
		}
		?>	
		<div class="clear"></div>
	</div>
	
	<div class="footprint">
		<h2>Your Footprint:</h2>
		<em>How you get to where you go affects the environment.</em>
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
	<script type='text/javascript' language='JavaScript'>
		var homebaseGeoLat = <?php echo($_SESSION["homebase"]["geolat"]); ?>;
		var homebaseGeoLong = <?php echo($_SESSION["homebase"]["geolat"]); ?>; 
	</script>
	<?php		
		
		//this data will come from the application database
		//change numVenues to a default (20)
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
						//echo("<td></td>\n");
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
?>
	
<?php } ?>

<?php tmpl('footer'); ?>
