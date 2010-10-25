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
	
	require_once('../config.php');
	
	if (mysql_connect($db_host, $db_user, $db_pass)) {
		if (mysql_selectdb($db_name)) {
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