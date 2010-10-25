<?php
	require_once('template/header.php');
?>
	<h1>Footprint</h1>
	How you get to where you go affects the environment
	
<?php
	//if error code in url, display error message
	
?>
	<form action="dologin.php" method="post">
		<h2>Log In</h2>
		<div class="inputRow">
			<label for="footprintUsername">Footprint Username</label>
			<input type="text" id="footprintUsername" />
		</div>
		<div class="inputRow">
			<label for="footprintPassword">Footprint Password</label>
			<input type="password" id="footprintPassword" /><br />
			<a href="forgotpass.php" class="noteText">Forgot your password?</a>
		</div>
		<input type="submit" value="Log In" />
	</form>

	<a href="createaccount.php">Don't have an account?</a>




<?php
	require_once('template/footer.php');
?>