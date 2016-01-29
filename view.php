<?php
	require_once(SERVER_ROOT . 'include/defs.php.inc');
	require_once(SERVER_ROOT . 'include/db.php');
	include(SERVER_ROOT . 'views/header.php');
	
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
	/* update the view count */
	$sql = "UPDATE Media SET views=views+1 WHERE id=" . $_GET['id'];
	$db->doQuery($sql);	

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

	/* need to add the comment the user made */
	if ( isset($_POST['comment']) ) {
		$sql = "INSERT INTO Comments (mediaID, userID, timestamp, content) VALUES(" . $_GET['id'] . "," . $_COOKIE['userid'] . ",NOW(),'" . $_POST['comment_val'] . "')";
		$db->doQuery($sql);
		debug_print("Inserting into comments : " . $sql);
	}

?>

		</div>
		<!-- End Menu Bar -->

		<!-- Navigation bar -->
			<div class="nav_bar">
				<ul>
					<li><a href="index.php">All</a></li>
					<li><a href="index.php?category=comedy">Comedy</a></li>
					<li><a href="index.php?category=entertainment">Entertainment</a></li>
					<li><a href="index.php?category=gaming">Gaming</a></li>
					<li><a href="index.php?category=music">Music</a></li>
					<li><a href="index.php?category=news">News</a></li>
					<li><a href="index.php?category=other">Other</a></li>
					<li><a href="index.php?category=religious">Religious</a></li>
					<li><a href="index.php?category=science/technology">Science/Technology</a></li>
					<li><a href="index.php?category=sports">Sports</a></li>
					<li><a href="index.php?category=tutorials">Tutorials</a></li>
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
<?php
	/* first, we're going to get the id for the media to play from the URL GET string, and use it to get the server file path for the media sources */
	$sql = "SELECT * FROM Media LEFT JOIN Users ON Media.owner_id=Users.id WHERE Media.id=" . $_GET['id'];
	if ( $db->doQuery($sql) === false ) {
		$error = "There was an internal SQL error : " + $db->getError();
		debug_print($error);
		return;
	}

	$dataRow = $db->getNextRow();
	$fileName = end( explode("/", $dataRow[7]) );
	$filePath = SITE_ROOT . "users/" . $dataRow[16] . "/" . $fileName;
?>

	<div class='view_box'>
		<table>
			<tr>
<?php
	switch ( $_GET['type'] ) {
		case "video" :
?>
				<td>
					<object style="display:inline;" data="<?php echo $filePath; ?>" type="application/x-mplayer2" width="640" height="480">
						<param name="<?php echo $fileName; ?>" value="<?php echo $filePath; ?>">
					</object>
				</td>

				<td>
					<!-- file metadata to display -->
					<h2 style="float:left; padding-left:50px;"><b>Uploaded on : </b><?php echo $dataRow[11]; ?></h2><br>
					<h2 style="float:left; padding-left:50px;"><b>Uploaded by : </b><?php echo $dataRow[16]; ?></h2><br>
					<h2 style="float:left; padding-left:50px;"><b>Title : </b><?php echo $dataRow[2]; ?></h2><br>
					<h2 style="float:left; padding-left:50px;"><b>Description : </b><?php echo $dataRow[3]; ?></h2><br>
					<h2 style="float:left; padding-left:50px;"><b>Views : </b><?php echo $dataRow[9]; ?></b></h2><br><br>

<?php
	if ( isset($_COOKIE['username']) ) {
?>
					<!-- playlist form -->
					<form id='playlist_form' action='view.php'>
						<select id='playlist_form_select' name="playlistadd" onchange="playlistFormChanged(this)">
							<option value="">Add to playlist...</option>
							<script>
								for (i in playlistList)
									document.write("<option value='"+i+"'>"+i+"</option>");
							</script>
							<option value="new_pl">Create new playlist</option>
						</select>
						<input type='text' style='display:none;' name='id' value='<?php echo $_GET['id']; ?>'></input>
						<input type='text' style='display:none;' name='type' value='<?php echo $_GET['type']; ?>'></input>
					</form>
<?php
	}
?>

					<a href="<?php echo $filePath; ?>" style="float:left; font-size:32px; padding-left:50px">Download File</a>
				</td>
<?php
			break;
		case "audio" :
?>
			<td>
				<object data="<?php echo $filePath; ?>" type="application/x-mplayer2" width="640" height="480">
					<param name="<?php echo $fileName; ?>" value="<?php echo $filePath; ?>">
				</object>
			</td>

			<td>
				<!-- file metadata to display -->
				<h2 style="float:left; padding-left:50px;"><b>Uploaded on : </b><?php echo $dataRow[11]; ?></h2><br>
				<h2 style="float:left; padding-left:50px;"><b>Uploaded by : </b><?php echo $dataRow[16]; ?></h2><br>
				<h2 style="float:left; padding-left:50px;"><b>Title : </b><?php echo $dataRow[2]; ?></h2><br>
				<h2 style="float:left; padding-left:50px;"><b>Description : </b><?php echo $dataRow[3]; ?></h2><br>
				<h2 style="float:left; padding-left:50px;"><b>Views : </b><?php echo $dataRow[9]; ?></b></h2><br><br>
<?php
	if ( isset($_COOKIE['username']) ) {
?>
					<!-- playlist form -->
					<form id='playlist_form' action='view.php'>
						<select id='playlist_form_select' name="playlistadd" onchange="playlistFormChanged(this)">
							<option value="">Add to playlist...</option>
							<script>
								for (i in playlistList)
									document.write("<option value='"+i+"'>"+i+"</option>");
							</script>
							<option value="new_pl">Create new playlist</option>
						</select>
						<input type='text' style='display:none;' name='id' value='<?php echo $_GET['id']; ?>'></input>
						<input type='text' style='display:none;' name='type' value='<?php echo $_GET['type']; ?>'></input>
					</form>
<?php
	}
?>
				<a href="<?php echo $filePath; ?>" style="float:left; font-size:32px; padding-left:50px">Download File</a>
			</td>
<?php
			break;
		case "image" :
?>
			<td>
				<img src="<?php echo $filePath; ?>" width="640" height="480">
			</td>

			<td>
				<!-- file metadata to display -->
				<h2 style="float:left; padding-left:50px;"><b>Uploaded on : </b><?php echo $dataRow[11]; ?></h2><br>
				<h2 style="float:left; padding-left:50px;"><b>Uploaded by : </b><?php echo $dataRow[16]; ?></h2><br>
				<h2 style="float:left; padding-left:50px;"><b>Title : </b><?php echo $dataRow[2]; ?></h2><br>
				<h2 style="float:left; padding-left:50px;"><b>Description : </b><?php echo $dataRow[3]; ?></h2><br>
				<h2 style="float:left; padding-left:50px;"><b>Views : </b><?php echo $dataRow[9]; ?></b></h2><br><br>
<?php
	if ( isset($_COOKIE['username']) ) {
?>
					<!-- playlist form -->
					<form id='playlist_form' action='view.php'>
						<select id='playlist_form_select' name="playlistadd" onchange="playlistFormChanged(this)">
							<option value="">Add to playlist...</option>
							<script>
								for (i in playlistList)
									document.write("<option value='"+i+"'>"+i+"</option>");
							</script>
							<option value="new_pl">Create new playlist</option>
						</select>
						<input type='text' style='display:none;' name='id' value='<?php echo $_GET['id']; ?>'></input>
						<input type='text' style='display:none;' name='type' value='<?php echo $_GET['type']; ?>'></input>
					</form>
<?php
	}
?>
				<a href="<?php echo $filePath; ?>" style="float:left; font-size:32px; padding-left:50px">Download File</a>
			</td>
<?php
			break;
	} // end switch
?>
			</tr>
		</table>
	</div>
	<!-- End View_box container -->

	<!-- start comments section -->
	<h2 style="position:absolute; display:block; float:left; padding-left:300px; padding-top:600px; font-size:32px;"><u>Comments :</u></h2>
<?php
	$sql = "SELECT * FROM Comments WHERE mediaID=" . $_GET['id'];
	$db->doQuery($sql);

	/* no comments yet */
	if ( $db->getRowCount() == 0 ) {
?>
		<h2 style="position:absolute; display:block; float:left; padding-left:300px; padding-top:680px; font-size:20px;">No comments yet.</h2>
<?php
		if ( isset($_COOKIE['username']) ) {
			$type = $_GET['type'];
			$medID = $_GET['id'];
			$URLstring = "view.php?type=" . $_GET['type'] . "&id=" . $_GET['id'];
?>
			<form method="post" action="<?php debug_print($URLstring); echo $URLstring; ?>">
				<textarea style="display:block; position:absolute; top:800px; left:320px;" name="comment_val" rows="2" cols="40"></textarea>	
				<input style="position:absolute; left:320px; top:880px; width:200px; height:30px;" type='submit' name='comment' value="Comment">
			</form>
<?php
		}
	}
	else if ( $db->getRowCount() > 0 ) {
		$sql = "SELECT * FROM Comments LEFT JOIN Users ON Comments.userID=Users.id WHERE mediaID=" . $_GET['id'];
		$db->doQuery($sql);
		debug_print("FOUND : " . $db->getRowCount());
?>
		<table style="display:block; position:absolute; padding-bottom:100px; top:720px; left:300px;">
<?php
		for ($index=0; $index < $db->getRowCount(); $index++) {
			$dbRow = $db->getNextRow();
?>
			<tr style="height:100px;">
				<td style="width:400px; padding-left:10px; border:2px; border-style:solid; border-color:#000000;">
					<h3><?php echo $dbRow[4]; ?></h3>
				</td>
				<td style="width:200px; padding-left:10px; border:2px; border-style:solid; border-color:#000000;">
					<h3><?php echo $dbRow[9]; ?></h3>
				</td>
				<td style="width:250px; padding-left:10px; border:2px; border-style:solid; border-color:#000000;">
					<h3><?php echo $dbRow[3]; ?></h3>
				</td>
			</tr><br>
<?php
		}
?>
		</table>	

			<form method="post" action="<?php debug_print($URLstring); echo $URLstring; ?>">
				<textarea style="display:block; position:absolute; top:620px; left:600px;" name="comment_val" rows="2" cols="40" placeholder="Post comment here..."></textarea>	
				<input style="position:absolute; left:960px; top:630px; width:200px; height:40px;" type='submit' name='comment' value="Comment">
			</form>
<?php
	}
?>


<?php
	include(SERVER_ROOT . '/views/footer.php');
?>
