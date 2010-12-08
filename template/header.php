
<html>
	<title>FootPrint</title>
	<head>
		<script src="/js/jquery.min.js" type="text/javascript"></script>
		<script src="/js/jquery.json-1.3.js" type="text/javascript"></script>
		<script src="/js/base.js" type="text/javascript"></script>
		<script src="/js/highcharts.js" type="text/javascript"></script>
		<link href="style2.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="header">
			<img src="img/greenfoot2.png"/>
			<h1>Footprint</h1>
		</div>
		
		<?php if ($logged_in) { // no user ?>
		<div id="mypageheader">
			<a href="/"><?php echo $user_info['firstname']; ?> <?php echo $user_info['lastname']; ?></a>
			<a href="http://foursquare.com">Foursquare</a>
			<a href="/">Find Friends</a>
			<a href="/logout.php">Logout</a>
			<div class="clear"></div>
		</div>
		<?php } ?>
		<div class="navbar">
			<a href="/"><div class="button">Home</div></a>
			<a href="/"><div class="button">Mypage</div></a>
			<a href="http://foursquare.com/mobile/checkin"><div class="button">Check-In</div></a>
			<a href="/"><div class="button">Stats</div></a>
			<a href="/feedback.php"><div class="button">FeedBack</div></a>
			<a href="/help.php"><div class="button">Help</div></a>
		</div>