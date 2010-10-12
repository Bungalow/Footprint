<?php
	session_start();

	//nab variables
	$foursquareCheckInID = $_POST['foursquareCheckInID'];
	$startPoint = $_POST['startPoint'];
	$mileage = $_POST['mileage'];
	$transportMode = $_POST['transportMode'];
	$lastStop = $_POST['lastStop'];
	$ignoreCheckIn = $_POST['ignoreCheckIn'];
	
	$updateQuery = "update destinations set startpoint='".$startPoint."', mileage='".$mileage."', transportmode='".$transportMode."', laststop='".$lastStop."', ignorecheckin='".$ignoreCheckIn."' where foursquarecheckinid=".$foursquareCheckInID." and userid=".$_SESSION['currentUserID']; 
	
	//open db connection
	
	if (mysql_connect("hostname", "dbusername", "dbpass")) {
		if (mysql_selectdb("footprint")) {
			$result = mysql_query($updateQuery);
			if ($result) {
				echo "success";
			}
			else {
				echo "db update failure";
			}
		}
		else {
			echo "unable to connect to table";
		}
	}
	else {
		echo "unable to connect to database";
	}
				
			
	
?>