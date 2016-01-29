<?php
	require_once('/var/www/spring13/u2/include/defs.php.inc');
	require_once(SERVER_ROOT . 'include/db.php');
	include(SERVER_ROOT . 'views/header.php');

	$errors = Array();

	if ( $_COOKIE['first_login'] == 1 ) {
		setcookie('first_login', 0, 0);

		/* a user is coming from signin, we will now set the session to hold this data */
		if ( isset($_COOKIE['username']) ) {
			$msg = "Welcome " . $_COOKIE['first_name'] . " " . $_COOKIE['last_name'] . "!";
		}
	}

		if ( isset($_POST['subscribe']) ) {
			debug_print("pressed subscribe button");
			if ( $_POST['subscribe'] == "Subscribe" ) {
				$msg = "You have subscribed to " . $_GET['user'] . "'s channel.";

				/* now, add the subscription to the database */
				$sql = "INSERT INTO Subscriptions (uID, subID) VALUES(" . $_COOKIE['userid'] . ", (SELECT id FROM Users WHERE username='" . $_GET['user'] . "'))";
				$db->doQuery($sql);
			}
			else {
				$msg = "You have unsubscribed from " . $_GET['user'] . "'s channel.";

				/* now, remove the subscription from the database */
				$sql = "DELETE FROM Subscriptions WHERE uID=" . $_COOKIE['userid'] . " and subID=(SELECT id FROM Users WHERE username='" . $_GET['user'] . "')";
				$db->doQuery($sql);
			}
		}

	if ( isset($msg) ) {
?>
		<div class="message">
			<?= $msg ?>
		</div>
<?php
	}
?>

		<!-- Begin Menu bar -->
		<div class="menu_bar">

			<a href="index.php">
				<img src="images/metube.png" alt="MeTube">
			</a>

				<form id='search_form' action='index.php' >
						<input type='text' name="search" placeholder="Enter keywords..." required>
						<select name='searchby'>
							<option value='keyword'>Keyword</option>
							<option value='user'>User</option>
						</select>
						<input type='submit' value="Search" > 
				</form>

<?php
	if ( isset($_COOKIE['username']) ) {
?>
		<h2 style='float:right; margin-right:1em; margin-top:1em; margin-bottom:1em;'>
			<?php echo "signed in as : <b>" . $_COOKIE['username'] . "</b>"; ?> &nbsp; | &nbsp;
			<a href="upload.php">Upload</a> &nbsp; | &nbsp; 
			<a href="account.php">Manage</a> &nbsp; | &nbsp;
			<a href="logout.php">Logout</a>
		</h2>
<?php
	}
	else {
?>
				<form id='signin_form' action='signin.php'>
						<input type='submit' id='signin_button' name='Signin' value='Signin' />
				</form>
<?php
	}
?>

		</div>
		<!-- End Menu Bar -->

		<!-- Begin Body Container -->
		<div class="body_container">

			<!-- Navigation bar -->
			<div class="nav_bar">
				<ul>
					<li><a href="?category=all">All</a></li>
					<li><a href="?category=comedy">Comedy</a></li>
					<li><a href="?category=entertainment">Entertainment</a></li>
					<li><a href="?category=gaming">Gaming</a></li>
					<li><a href="?category=music">Music</a></li>
					<li><a href="?category=news">News</a></li>
					<li><a href="?category=other">Other</a></li>
					<li><a href="?category=religious">Religious</a></li>
					<li><a href="?category=science/technology">Science/Technology</a></li>
					<li><a href="?category=sports">Sports</a></li>
					<li><a href="?category=tutorials">Tutorials</a></li>
<?php
				if ( isset($_COOKIE['username']) ) {
?>
					<li><a href="?subscriptions=true">Subscriptions</a></li>
<?php
				}
?>
				</ul>
			</div>
			<!-- End Navigation Bar -->

		<!-- Title for the media listing -->
<?php
		if ( isset($_GET['subscriptions']) ) {
?>
			<h1 class="list_title"><?php echo "Your Subscriptions"; ?></h1>
<?php
		}

		else if ( isset($_GET['searchby']) ) {
?>
			<h1 class="list_title"><?php echo "Results for search : " . $_GET['search']; ?></h1>
<?php
		}

		else if ( isset($_GET['category']) && $_GET['category'] != "all" ) {
?>
			<h1 class="list_title"><?php echo "Category : " . $_GET['category']; ?></h1>
<?php
		}
		else if ( isset($_GET['user']) ) {
?>
			<h1 class="list_title"><?php echo $_GET['user'] . "'s Channel"; ?></h1>
<?php
			if ( isset($_COOKIE['username']) ) {
				if ( $_GET['user'] != $_COOKIE['username'] ) {
					/* check if they're already subscribed */
					$sql = "SELECT * FROM Subscriptions WHERE uID=" . $_COOKIE['userid'] . " and subID=(SELECT id FROM Users WHERE username='" . $_GET['user'] . "')";
					if ( $db->doQuery($sql) === false ) {
						$error = "There was an internal SQL error : " . $db->getError();
						return;
					}

					/* found the subscription */
					if ( $db->getRowCount() > 0 ) {
						debug_print("Found the subscription");
?>
					<form method="post" action="index.php?user=<?php echo $_GET['user']; ?>" >
						<input style="position:relative; left:300px; bottom:20px; width:250px; height:40px;" type='submit' name="subscribe" value="Unsubscribe">	
					</form>
<?php
					}

					/* no subscription yet */
					else {
						debug_print("Found no subscription yet");
?>
						<form method="post" action="index.php?user=<?php echo $_GET['user']; ?>" >
							<input style="position:relative; left:300px; bottom:20px; width:250px; height:40px;" type='submit' name="subscribe" value="Subscribe">	
						</form>
<?php
					}
				}
			}
?>
<?php
		}
		/* category isn't selected, and user is logged in, display their channel */
		else if ( isset($_COOKIE['username']) && !isset($_GET['category']) ) {
?>
			<h1 class="list_title"><?php echo $_COOKIE['username'] . "'s Channel"; ?></h1>
<?php
		}
		/* display recently uploaded */
		else {
?>
			<h1 class="list_title"><?php echo "Recently Uploaded"; ?></h1>
<?php
		}
?>

				<table class="media_table" >
<?php
	if ( isset($_GET['subscriptions']) ) {
		$sql = "SELECT * FROM Media LEFT JOIN Users ON Media.owner_id=Users.id WHERE Users.id IN (SELECT subID FROM Subscriptions WHERE uID=" . $_COOKIE['userid'] . ") ORDER BY date_uploaded DESC";
		$db->doQuery($sql);
			
			if ( $db->getRowCount() > 0 ) {
			for ($index=0; $index < $db->getRowCount(); $index++) {
				$dataRow = $db->getNextRow();
?>
					<tr>
						<td>
<?php
			/* switch on type of media file so we know what to display */
			switch ( $dataRow[5] ) {
				case "image" :
?>
							<a href="view.php?type=image&id=<?php echo $dataRow[0]; ?>">
								<img src="<?php echo SITE_ROOT . "users/" . $dataRow[16] . "/" . end( explode("/", $dataRow[7]) ); ?>" width="400" style="display:inline;">
							</a>
<?php
					break;
				case "video" :
?>
							<a href="view.php?type=video&id=<?php echo $dataRow[0]; ?>">
								<img src="images/generic_video.png" style="display:inline;">
							</a>
<?php
					break;
				case "audio" :
?>
							<a href="view.php?type=audio&id=<?php echo $dataRow[0]; ?>">
								<img src="images/generic_audio.png" style="display:inline;">
							</a>
<?php
					break;
				default :
					break;
			}
?>
						</td>
						<td>
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Uploaded on : </b><?php echo "$dataRow[11]"; ?></h3> 
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Uploaded by : </b><a href="?user=<?php echo $dataRow[16]; ?>"><?php echo $dataRow[16]; ?></a></h3> 
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Title : </b><?php echo "$dataRow[2]"; ?></h3> 
						</td>
					</tr>
<?php
		} // close for
		}// close if
	}
	else if ( isset($_GET['searchby']) ) {
			if ( $_GET['searchby'] == "keyword" ) {
				/* scary long search string to check */
				$sql = "SELECT * FROM Media LEFT JOIN Users ON Media.owner_id=Users.id WHERE Media.title LIKE \"%" . $_GET['search'] . "%\" OR Media.description LIKE \"%" . $_GET['search'] . "%\" OR Media.id IN (SELECT mediaID FROM Tags WHERE tag LIKE \"%" . $_GET['search'] . "%\") ORDER BY date_uploaded DESC";
			}
			/* user search */
			else {
				$sql = "SELECT * FROM Media LEFT JOIN Users ON Media.owner_id=Users.id WHERE Users.username LIKE \"%" . $_GET['search'] . "%\" ORDER BY date_uploaded DESC";
			}
			$db->doQuery($sql);
			
			if ( $db->getRowCount() > 0 ) {
			for ($index=0; $index < $db->getRowCount(); $index++) {
				$dataRow = $db->getNextRow();
?>
					<tr>
						<td>
<?php
			/* switch on type of media file so we know what to display */
			switch ( $dataRow[5] ) {
				case "image" :
?>
							<a href="view.php?type=image&id=<?php echo $dataRow[0]; ?>">
								<img src="<?php echo SITE_ROOT . "users/" . $dataRow[16] . "/" . end( explode("/", $dataRow[7]) ); ?>" width="400" style="display:inline;">
							</a>
<?php
					break;
				case "video" :
?>
							<a href="view.php?type=video&id=<?php echo $dataRow[0]; ?>">
								<img src="images/generic_video.png" style="display:inline;">
							</a>
<?php
					break;
				case "audio" :
?>
							<a href="view.php?type=audio&id=<?php echo $dataRow[0]; ?>">
								<img src="images/generic_audio.png" style="display:inline;">
							</a>
<?php
					break;
				default :
					break;
			}
?>
						</td>
						<td>
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Uploaded on : </b><?php echo "$dataRow[11]"; ?></h3> 
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Uploaded by : </b><a href="?user=<?php echo $dataRow[16]; ?>"><?php echo $dataRow[16]; ?></a></h3> 
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Title : </b><?php echo "$dataRow[2]"; ?></h3> 
						</td>
					</tr>
<?php
		} // close for
		}// close if
	}
	else if ( isset($_GET['category']) && $_GET['category'] != "all" ) {
		/* get the category id to query media on */
		$sql = "SELECT id FROM Category WHERE category='" . $_GET['category'] . "'";
		$db->doQuery($sql);
		$tmp = $db->getNextRow();
		$categoryID = $tmp[0];

		$sql = "SELECT * FROM Media LEFT JOIN Users ON Media.owner_id=Users.id WHERE category_id=" . $categoryID . " ORDER BY date_uploaded DESC";
		if ( $db->doQuery($sql) === false ) {
			$error = "There was an internal SQL error : " . $db->getError();
			debug_print($error);
			return;
		}
		
		if ( $db->getRowCount() > 0 ) {
		for ($index=0; $index < $db->getRowCount(); $index++) {
			$dataRow = $db->getNextRow();
?>
					<tr>
						<td>
<?php
			/* switch on type of media file so we know what to display */
			switch ( $dataRow[5] ) {
				case "image" :
?>
							<a href="view.php?type=image&id=<?php echo $dataRow[0]; ?>">
								<img src="<?php echo SITE_ROOT . "users/" . $dataRow[16] . "/" . end( explode("/", $dataRow[7]) ); ?>" width="400" style="display:inline;">
							</a>
<?php
					break;
				case "video" :
?>
							<a href="view.php?type=video&id=<?php echo $dataRow[0]; ?>">
								<img src="images/generic_video.png" style="display:inline;">
							</a>
<?php
					break;
				case "audio" :
?>
							<a href="view.php?type=audio&id=<?php echo $dataRow[0]; ?>">
								<img src="images/generic_audio.png" style="display:inline;">
							</a>
<?php
					break;
				default :
					break;
			}
?>
						</td>
						<td>
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Uploaded on : </b><?php echo "$dataRow[11]"; ?></h3> 
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Uploaded by : </b><a href="?user=<?php echo $dataRow[16]; ?>"><?php echo $dataRow[16]; ?></a></h3> 
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Title : </b><?php echo "$dataRow[2]"; ?></h3> 
						</td>
					</tr>
<?php
		} // close for
		}// close if
	} // close if

	/* user channels */
	else if ( isset($_GET['user']) ) {
		$sql = "SELECT * FROM Media LEFT JOIN Users ON Media.owner_id=Users.id WHERE username='" . $_GET['user'] . "' ORDER BY date_uploaded DESC";
		if ( $db->doQuery($sql) === false ) {
			$error = "There was an internal SQL error : " . $db->getError();
			return;
		}
		
		if ( $db->getRowCount() > 0 ) {
		for ($index=0; $index < $db->getRowCount(); $index++) {
			$dataRow = $db->getNextRow();
?>
					<tr>
						<td>
<?php
			/* switch on type of media file so we know what to display */
			switch ( $dataRow[5] ) {
				case "image" :
?>
							<a href="view.php?type=image&id=<?php echo $dataRow[0]; ?>">
								<img src="<?php echo SITE_ROOT . "users/" . $_GET['user'] . "/" . end( explode("/", $dataRow[7]) ); ?>" width="400" style="display:inline;">
							</a>
<?php
					break;
				case "video" :
?>
							<a href="view.php?type=video&id=<?php echo $dataRow[0]; ?>">
								<img src="images/generic_video.png" style="display:inline;">
							</a>
<?php
					break;
				case "audio" :
?>
							<a href="view.php?type=audio&id=<?php echo $dataRow[0]; ?>">
								<img src="images/generic_audio.png" style="display:inline;">
							</a>
<?php
					break;
				default :
					break;
			}
?>
						</td>
						<td>
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Uploaded on : </b><?php echo "$dataRow[11]"; ?></h3> 
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Uploaded by : </b><a href="?user=<?php echo $_COOKIE['username']; ?>"><?php echo $_GET['user']; ?></a></h3> 
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Title : </b><?php echo "$dataRow[2]"; ?></h3> 
						</td>
					</tr>
<?php
		} // close for
		} // close if
	} // close else if

	/* user is logged in and category isn't selected, will display their channel */
	else if ( isset($_COOKIE['username']) && !isset($_GET['category']) ) {
		/* get all the files uploaded by the user sorted by date/time uploaded descending */
		$sql = "SELECT * FROM Media WHERE owner_id=(SELECT id FROM Users WHERE username='" . $_COOKIE['username'] . "') ORDER BY date_uploaded DESC";
		if ( $db->doQuery($sql) === false ) {
			$error = "There was an internal SQL error : " . $db->getError();
			return;
		}

		if ( $db->getRowCount() > 0 ) {
		for ($index=0; $index < $db->getRowCount(); $index++) {
			$dataRow = $db->getNextRow();
?>
					<tr>
						<td>
<?php
			/* switch on type of media file so we know what to display */
			switch ( $dataRow[5] ) {
				case "image" :
?>
							<a href="view.php?type=image&id=<?php echo $dataRow[0]; ?>">
								<img src="<?php debug_print("DISPLAYING : " . SITE_ROOT . "users/" . $_COOKIE['username'] . "/" . end( explode("/", $dataRow[7]) )); echo SITE_ROOT . "users/" . $_COOKIE['username'] . "/" . end( explode("/", $dataRow[7]) ); ?>" width="400" style="display:inline;">
							</a>
<?php
					break;
				case "video" :
?>
							<a href="view.php?type=video&id=<?php echo $dataRow[0]; ?>">
								<img src="images/generic_video.png" style="display:inline;">
							</a>
<?php
					break;
				case "audio" :
?>
							<a href="view.php?type=audio&id=<?php echo $dataRow[0]; ?>">
								<img src="images/generic_audio.png" style="display:inline;">
							</a>
<?php
					break;
				default :
					break;
			}
?>
						</td>
						<td>
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Uploaded on : </b><?php echo "$dataRow[11]"; ?></h3> 
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Uploaded by : </b><a href="?user=<?php echo $_COOKIE['username']; ?>"><?php echo $_COOKIE['username']; ?></a></h3> 
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Title : </b><?php echo "$dataRow[2]"; ?></h3> 
						</td>
					</tr>
<?php
		} // close for
		} // close if
		/* there are no files for the user, display the sorry, no results message */
		else {
?>
			<h2 style="float:left; padding-left:100px;">Sorry, No Files have been uploaded yet. &nbsp;<a href="upload.php">Upload now?</a>
<?php
		}
	}
	/* this is the view for non-logged in accounts; just display most recently uploaded files */
	else {
		$sql = "SELECT * FROM Media LEFT JOIN Users ON Media.owner_id=Users.id ORDER BY date_uploaded DESC";
		if ( $db->doQuery($sql) === false ) {
			$error = "There was an internal SQL error : " . $db->getError();
			return;
		}

		if ( $db->getRowCount() > 0 ) {
		for ($index=0; $index < $db->getRowCount(); $index++) {
			$dataRow = $db->getNextRow();
?>
					<tr>
						<td>
<?php
			/* switch on type of media file so we know what to display */
			switch ( $dataRow[5] ) {
				case "image" :
?>
							<a href="view.php?type=image&id=<?php echo $dataRow[0]; ?>">
								<img src="<?php echo SITE_ROOT . "users/" . $dataRow[16] . "/" . end( explode("/", $dataRow[7]) ); ?>" width="400" style="display:inline;">
							</a>
<?php
					break;
				case "video" :
?>
							<a href="view.php?type=video&id=<?php echo $dataRow[0]; ?>">
								<img src="images/generic_video.png" style="display:inline;">
							</a>
<?php
					break;
				case "audio" :
?>
							<a href="view.php?type=audio&id=<?php echo $dataRow[0]; ?>">
								<img src="images/generic_audio.png" style="display:inline;">
							</a>
<?php
					break;
				default :
					break;
			}
?>
						</td>
						<td>
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Uploaded on : </b><?php echo "$dataRow[11]"; ?></h3> 
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Uploaded by : </b><a href="?user=<?php echo $dataRow[16]; ?>"><?php echo $dataRow[16]; ?></a></h3> 
							<h3 style="display: block; margin-top:5px; margin-bottom:5px;"><b>Title : </b><?php echo "$dataRow[2]"; ?></h3> 
						</td>
					</tr>
<?php
		} // close for
		}// close if
	}
?>
				</table>

		</div>
		<!-- End Body Container -->

<?php
	include(SERVER_ROOT . '/views/footer.php');
?>
