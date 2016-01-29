<?php
	require_once('/var/www/spring13/u2/include/defs.php.inc');
	require_once(SERVER_ROOT . '/include/db.php');
	include(SERVER_ROOT . '/views/header.php');

	$errors = Array();

	/* coming from the create account page, display the message first */
	if ( isset($_POST["created"]) ) {
		debug_print("Message : " . $msg);
?>
	<div class="message">
		<?= $msg ?>
	</div>
<?php
	}

	/* only come in here if we're not coming from create account page */
	if ( isset($_POST["Signin"]) ) {
		include(SERVER_ROOT . '/include/signin.php.inc');

		if ( count($errors) > 0 ) {
			$msg = "";
			for ($i=0; $i<count($errors); $i++) {
				$msg .= $errors[$i];
				if ( $i+1 < count($errors) ) {
					$msg .= "<br />\n";
				}
				debug_print($errors[$i] . "\n");
			}
		}
		else {
			debug_print("Successfully signed in as user : " . $_POST["username"]);
		
			$_POST["signed_in"] = $_POST["username"];

			/* take us back home */
			Header("Location: " . SITE_ROOT);
		}

		if ( isset($msg) ) {
?>
			<div class="message">
				<?= $msg ?>
			</div>
<?php
		}
	}
?>
		<div id="signin_container">

			<!-- Begin Menu bar -->
			<div class="menu_bar">

				<a href="index.php">
					<img src="images/metube.png" alt="MeTube">
				</a>
				
					<form id='search_form' action='index.php' >
							<input type='text' name="search" placeholder="Enter keywords..." >
							<input type='submit' value="Search" > 
					</form>
					
					<form id='signin_form' action='create.php'>
							<input type='submit' id='signin_button' value='Create an Account' />
					</form>

			</div>
			<!-- End Menu Bar -->

			<form id='signin' action='signin.php' method='post'>
				<fieldset>
					<legend>Sign in</legend>

					<label for='username'>Username</label>
					<input type='text' name='username' id='username' maxlength='20' />

					<label for='password'>Password</label>
					<input type='password' name='password' id='password' maxlength='20' />

					<input type='submit' name='Signin' value='Sign in' />
				</fieldset>
			</form>
			
		</div>
	</body>
</html>
<?php
	include(SERVER_ROOT . '/views/footer.php');
?>
