<?php
	require_once('header.php');
	
	//check if info was posted
	
	$emailAddress = isset($_POST['emailAddress']) ? $_POST['emailAddress'] : null;
	$footprintPass = isset($_POST['$footprintPass']) ? $_POST['$footprintPass'] : null;
	
	if (($emailAddress != null) && ($footprintPass != null)) {
		//attempt to create account
		
		
	}
	
	else {
		//if neither are set, display form normally
		if ($emailAddress == "") {
			//display email address error
			
		}
		
		if ($footprintPass == "") {
			//display email address error message
			
		}
?>

	<h2>Create Footprint Account</h2>
	<form action="createaccount.php" method="post">
		<div class="inputRow">
			<label>Email Address</label>
			<input id="emailAddress" type="text" />
		</div>
		<div class="inputRow">
			<label>Footprint Password</label>
			<input id="footprintPass" type="password" /><br />
			<span class="noteText">You'll use this password to log in to Footprint</span>
		</div>
		<input type="submit" value="Create Account" />
	</form>
<?php
	}
?>



<?php
	require_once('footer.php');
?>