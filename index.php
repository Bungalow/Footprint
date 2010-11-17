<?php
	session_start();
	
	require_once('lib/EpiCurl.php');
	require_once('lib/EpiOAuth.php');
	require_once('lib/EpiFoursquare.php');
	
	//check session variables
	
	session_destroy();
	session_start();

	require_once('config.php');
	require_once('template/header.php');
	
?>

<div class="login">
<img src="img/greenlogin.png"/>
<p><?php
	
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
<h6>Copyright � info & etc.</h6>
</body>
</html>-->

</body>
</html>