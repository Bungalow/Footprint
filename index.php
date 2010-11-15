<html>
<title>FootPrint</title>
<head>
<LINK href="style2.css" rel="stylesheet" type="text/css">
</head>
<!--<STYLE type="text/css">
	body{
		font-family:"Helvetica";
		background-color:rgb(187,187,187);
		color:rgb(256,256,256);
	}
	#top{
		margin:auto;
		width:635px;
		
	}
	#top img{
		display:block;
		float:left;
		padding:10px;
		margin:10px;
	}
	#top .inner{
		float:left;
	}
	#login{
		margin:auto;
		width:635px;
	}
	#logincontent{
		-moz-border-radius:10px 10px 10px 10px;
		-moz-box-shadow:0 1px 10px #3F3F3F;
		background-color:rgba(0, 0, 0, 0.1);
		border:1px solid #4F4F4F;
		display:inline-block;
		padding:10px 0 20px;
		text-align:center;
		width:545px;
	}	
	.clear{
		clear:both;
	}
	h1{
		font-size:75px;
		font-family:"Helvetica";
	}
	h3{
		font-size:25px;
		font-family:"Helvetica";
	}
	.header{
		float:right;
	}
</STYLE>-->
<!--
<body>
<div class="header">
	<a href="help.html"><img src="img/help.png"/></a>
	<a href="http://localhost/"><img src="img/ghome.png"/></a>
</div>
<br><br>
<div id="top">
		<img src="img/logo3.gif"/>
		<div class="inner">
			<h1>Footprint</h1>
			<h3 align="center">How you travel affects the environment.</h3>
		</div>
		<div class="clear"></div>
</div>
<br>
<div id ="login">
<div id ="logincontent">
<img src="img/greenlogin.png"/>
<p align="center">-->
<body>
<div class="header">
<img src="img/greenfoot2.png"/>
<h1>Footprint</h1>
</div>
<div id="mypageheader">
	<a href="/">Username</a>
	<a href="/">Blah</a>
	<a href="/">Find Friends</a>
	<a href="/">Logout</a>
	<div class="clear"></div>
</div>
<div class="pxspace"></div>
<div class="navbar">
	<a href="/"><div class="button">Home</div></a>
	<a href="/feedback.html"><div class="button">FeedBack</div></a>
	<a href="/help.html"><div class="button">Help</div></a>
</div>
<div class="login">
<img src="img/greenlogin.png"/>
<p><?php
	session_start();
	
	require_once('lib/EpiCurl.php');
	require_once('lib/EpiOAuth.php');
	require_once('lib/EpiFoursquare.php');
	
	//check session variables
	
	session_destroy();
	session_start();

	require_once('config.php');
	
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
	echo "<br />";
	//This is your OAuth token and secret generated above
	//The OAuth token is part of the Foursquare link above
	//They are dynamic and will change each time you refresh the page
	//If everything is working correctly both of these will show up when you open index.php
	//var_dump($results['oauth_token']);
	echo "<br />";
	//var_dump($_SESSION['secret']);
?></p>
<p>Not a FourSquare User it's ok join by clicking below:</p>
			<img src="img/greennew.png"/><br/>
			<a href="http://foursquare.com"><input type="button" name="groovybtn2" class="groovy" value="Join Foursquare" title=""></a>
</div>
<!--
</p>
<!--<a href="register.html">Join FootPrint</a>
<a href="http://foursquare.com">Join FourSquare</a>-->
<!--<form name="groovy">
		<p>-->
			<!--<p>Not a FourSquare User it's ok join by clinking below:</p>-->
			<!--<img src="img/greennew.png"/><br/>
			<a href="http://foursquare.com"><input type="button" name="groovybtn2" class="groovy" value="Join Foursquare" title=""></a>
		<!--</p>
	</form>-->
<!--</div>
</div>
<h6>Copyright © info & etc.</h6>
</body>
</html>-->

</body>
</html>