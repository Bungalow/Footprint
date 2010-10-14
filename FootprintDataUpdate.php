<?php
	session_start();

	//nab variables
	$queryType = $_POST['queryType'];
	
	require_once('config.php');
	
	if (mysql_connect($db_host, $db_user, $db_pass)) {
		if (mysql_selectdb($db_bame)) {
			$result = mysql_query("select sum(mileage) as mileagesum from destinations where userid='".$_SESSION['currentUserID']."' and transportmode='self'");
			$selfPowMileage = round(mysql_fetch_object($result)->mileagesum,2);
			$result = mysql_query("select sum(mileage) as mileagesum from destinations where userid='".$_SESSION['currentUserID']."' and transportmode='mass'");
			$massTransMileage = round(mysql_fetch_object($result)->mileagesum,2);
			$result = mysql_query("select sum(mileage) as mileagesum from destinations where userid='".$_SESSION['currentUserID']."' and transportmode='car'");
			$carMileage = round(mysql_fetch_object($result)->mileagesum,2);
			$totalMileage = $selfPowMileage + $massTransMileage + $carMileage;
			
			//json encoded result back
			$mileageArray = array('selfPowMileage'=>$selfPowMileage,'massTransMileage'=>$massTransMileage,'carMileage'=>$carMileage,'totalMileage'=>$totalMileage);
			echo json_encode($mileageArray);
			
			/*
			 * if ($result) {
			 * 
			 * }
			 * 
			 * else {
				echo "db update failure";
				}
			*/
			
		}
		
	else {
			echo "unable to connect to table";
		}
	}
	else {
		echo "unable to connect to database";
	}
	
	
				
			
	
?>