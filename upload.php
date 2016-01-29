<?php
	require_once('/var/www/spring13/u2/include/defs.php.inc');
	require_once(SERVER_ROOT . 'include/db.php');
	include(SERVER_ROOT . 'views/header.php');

	debug_print("HERE");
	$error = NULL;
	$success = false;

	/* here we check for submitted files from the form */
if ( isset($_FILES["file"]) ) {
		/* set default file upload permissions to 755 */
		umask(022);

		$return = validateFileUpload( $_FILES["file"] );
		switch ($return) {
			case FILE_VALID :
				break;
			case FILE_ALREADY_EXISTS :
				$error = "ERROR : file already exists on server.";
				goto error;
				break;
			case FILE_SIZE_TOO_LARGE :
				$error = "ERROR : file size too large. Please make sure file is 20MB or smaller.";
				goto error;
				break;
			case FILE_INVALID_TYPE :
				debug_print("invalid file type");
				$error = "ERROR : file type is invalid. Supported file types : \"gif\", \"jpeg\", \"jpg\", \"png\", \"mp3\", \"mp4\", \"mov\", \"wmv\", \"flv\", \"avi\""; 
				goto error;
				break;
			default :
				$error = "UNKNOWN ERROR : " . $_FILES["file"]["error"];
				goto error;
				break;
		}

		/* now we will get all the information to put into the database for the media file */
		$sql = "SELECT id FROM Users WHERE username=";
		$sql .= "\"" . $_COOKIE["username"] . "\"";
		if ( $db->doQuery($sql) === false ) {
			$error = "There was an internal SQL error : " . $db->getError();
				goto error;
			return;
		}

		if ( $db->getRowCount() != 1 ) {
			$error = "Error retrieving currently logged in user details.";
				goto error;
			return;
		}
		/* 1) */
		$tmp = $db->getNextRow();
		$userID = $tmp[0];

		/* 2) */
		$fileTitle = $_POST["title"];

		/* 3) */
		$fileDescription = $_POST["description"];

		/* 4) */
		$fileExtension = end( explode(".", $_FILES["file"]["name"]) );

		/* 5) */
		$fileSize = $_FILES["file"]["size"];

		/* 6) */
		$filePath = ROOT_USER_DIR . $_COOKIE["username"] . "/" . $_FILES["file"]["name"];

		/* 7) */
		$sql = "SELECT id FROM Category WHERE category=";
		$sql .= "\"" . $_POST["file_category"] . "\"";
		if ( $db->doQuery($sql) === false ) {
			$error = "There was an internal SQL error : " . $db->getError();
				goto error;
			return;
		}

		if ( $db->getRowCount() != 1 ) {
			$error = "Error retrieving the file category from the database.";
				goto error;
			return;
		}
		$tmp2 = $db->getNextRow();
		$fileCategoryID = $tmp2[0];

		/* 8) */
		$defaultViews = 1;

		/* 9) */
		$defaultRating = 0;

		$sql = "SELECT type FROM media_types WHERE ext=";
		$sql .= "\"" . $fileExtension . "\"";

		$result = $db->doQuery($sql);
		if ( !$result ) {
			$error = "There was an unknown SQL error when retrieving type from media_types table : " . $db->getError();
				goto error;
			return;
		}

		$tmp3 = $db->getNextRow();
		$fileType = $tmp3[0];

		$sql = NULL;
		$sql = "INSERT INTO Media (owner_id, title, description, file_extension, type, file_size, path, category_id, views, rating, date_uploaded) VALUES";
		$sql .= "(";
		$sql .= "$userID" . ",";
		$sql .= "\"" . $fileTitle . "\",";
		$sql .= "\"" . $fileDescription . "\",";
		$sql .= "\"" . $fileExtension . "\",";
		$sql .= "\"" . $fileType . "\",";
		$sql .= "$fileSize" . ",";
		$sql .= "\"" . $filePath . "\",";
		$sql .= "$fileCategoryID" . ",";
		$sql .= "$defaultViews" . ",";
		$sql .= "$defaultRating" . ",";
		$sql .= "CURDATE()";
		$sql .= ")";

		$result = $db->doQuery($sql);
		if ( !$result ) {
			$error = "There was an unknown SQL error when inserting into Media table : " . $db->getError();
				goto error;
			return;
		}

		/* now that the file's uploaded and while we're www-data user, chmod to 775  */
		chmod(ROOT_USER_DIR . $_COOKIE['username'] . "/" . $_FILES["file"]["name"], 0775);

		/* grab the rowID from the previous query */
		$mediaID = $db->getRowID();
		$sql = NULL;

		/* now, add the tags for the media  */
		$tags = explode(",", $_POST['tags']);
		foreach($tags as $tag) {
			$sql = "INSERT INTO Tags (mediaID, tag) VALUES(";
			$sql .= "$mediaID" . ",";
			$sql .= "'" . $tag . "')";

			$result = $db->doQuery($sql);

			if ( !$result ) {
				$error = "There was an unknown SQL error when inserting into Tags table : " . $db->getError();
				goto error;
				return;
			}

			$sql = NULL;
		}

error:
	debug_print($error);
	if ( isset($error) ) {
?>
		<div class="message">
			<?php echo $error; ?>
		</div>
<?php
	}
	/* successful file upload */
	else {
		$success = true;
	}
}
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

		</div>
		<!-- End Menu Bar -->

		<!-- Begin Body Container -->
		<div class="body_container">

<?php
		if ( $success ) {
			echo "<center><h2>Successfully uploaded file : \"" . $_FILES["file"]["name"] . "\" to MeTube.</h2></center><br><br><center><h3><a href=\"index.php\">Back to Home</a></h3></center>";
		}
?>
			<form method="post" action="upload.php" enctype="multipart/form-data">
				<fieldset>
					<legend>Upload file information</legend>
						File to upload: 	<input type="file" name="file" size="255" /><br>
						File type: 			<select name="file_type">
													<option value="video">Video</option>
													<option value="audio">Audio</option>
													<option value="image">Image</option>
												</select><br>
						File title:			<input type="text" name="title" size="40" placeholder="e.g. Gangnam Style Music Video" maxsize="40"><br>
						File category:		<select name="file_category">
													<option value="comedy">Comedy</option>
													<option value="entertainment">Entertainment</option>
													<option value="gaming">Gaming</option>
													<option value="music">Music</option>
													<option value="news">News</option>
													<option value="other">Other</option>
													<option value="religious">Religious</option>
													<option value="science/technology">Science/Technology</option>
													<option value="sports">Sports</option>
													<option value="tutorials">Tutorials</option>
												</select><br>
						File tags (comma separated) <input type='text' name="tags" size="48" placeholder="e.g. America, food, tasty" ><br> 
						File description: <br><textarea name="description" rows="8" cols="40"></textarea><br>

				</fieldset>
				<input type="submit" name="submit" value="Submit">
			</form>

		</div>
		<!-- End Body Container -->

<?php
	include(SERVER_ROOT . 'views/footer.php');
?>
