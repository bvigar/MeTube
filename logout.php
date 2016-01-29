<?php
	require_once('/var/www/spring13/u2/include/defs.php.inc');
	include(SERVER_ROOT . 'views/header.php');

	/* unset all the cookies */
	/* this sets the cookie expiration date to one hour ago, PHP's official way of deleting it */
	setcookie('first_name', "", time() - 3600);
	setcookie('last_name', "", time() - 3600);
	setcookie('email_address', "", time() - 3600);
	setcookie('username', "", time() - 3600);
	setcookie('birthdate', "", time() - 3600);
	setcookie('first_login', "", time() - 3600);
?>
			<!-- Begin Menu bar -->
			<div class="menu_bar">

				<a href="index.php">
					<img src="images/metube.png" alt="MeTube">
				</a>
				
					<form id='search_form' action='index.php' >
							<input type='text' name="search" placeholder="Enter keywords..." >
							<input type='submit' value="Search" > 
					</form>
					
					<form id='signin_form' action='signin.php'>
							<input type='submit' id='signin_button' name='Signin' value='Signin' />
					</form>

			</div>
			<!-- End Menu Bar -->

			<div class="body_container">
				
				<div class="sort_header">
				</div>

				<div class="nav_bar">
					<ul>
						<li><a href="index.php">All</a></li>
						<li><a href="?activism=true">Activism</a></li>
						<li><a href="?comedy=true">Comedy</a></li>
						<li><a href="?entertainment=true">Entertainment</a></li>
						<li><a href="?music=true">Music</a></li>
						<li><a href="?news=true">News</a></li>
						<li><a href="?religous=true">Religious</a></li>
						<li><a href="?science=true">Science</a></li>
						<li><a href="?sports=true">Sports</a></li>
						<li><a href="?tutorials=true">Tutorials</a></li>
					</ul>
				</div>

				<div class="media_list">
					<center><h2>
						<b>You have successfully signed out.</b><br /><br />
						<a href="index.php">Back to Home</a>
					</h2></center>
				</div>

			</div>
<?php
	include(SERVER_ROOT . '/views/footer.php');
?>
