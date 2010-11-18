<?php

// Initializer code

session_start(); // always

// requires
require_once('lib/EpiCurl.php');
require_once('lib/EpiOAuth.php');
require_once('lib/EpiFoursquare.php');
require_once('config.php');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("cache-control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("cache-control: post-check=0, pre-check=0", false);
header("cache-control: max-age=0");
header("Pragma: no-cache");	
set_time_limit(120);

// login?
$logged_in = false;

// set that up
if ($_SESSION['currentUserID']) {
	$logged_in = true;
	$user_info = $_SESSION['4sq_user'];
	$foursquareObj = $_SESSION['4sq_obj'];
}

// make sure we can connect to the db

if(!mysql_connect($db_host, $db_user, $db_pass)){die ("Could not connect to the DB");}
if(!mysql_selectdb($db_name)){die("Could not select DB.");}

// useful functions
function tmpl($string) {
	require("template/" . $string . ".php");
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

?>