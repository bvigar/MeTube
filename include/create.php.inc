<?php
	/* validate the fields before putting in the database */
	if ( !isset($_POST["firstname"]) ) {
		$errors[] = "Please enter a first name.";
		return;	
	} 	
	$first_name = $_POST["firstname"];

	if ( !isset($_POST["lastname"]) ) {
		$errors[] = "Please enter a last name.";
		return;
	}
	$last_name = $_POST["lastname"];

	if ( !isset($_POST["usremail"]) || !isValidEmail($_POST["usremail"]) ) {
		$errors[] = "Please enter a valid email address.";
		return;
	}
	$email_addr = $_POST["usremail"];

	if ( !isset($_POST["username"]) ) {
		$errors[] = "Please enter a username.";
		return;
	}
	$username = $_POST["username"];

	if ( !isset($_POST["password"]) || strlen($_POST["password"]) < 8 ) {
		$errors[] = "Please enter a password that is atleast 8 characters (max 20).";
		return;
	}
	/* make sure the password and confirm_passowrd fields match before proceeding */
	if ( strcmp($_POST["password"], $_POST["confirm_password"]) == 0 ) {
		$password = hash("sha256", $_POST["password"]);
	}
	else {
		$errors[] = "The selected password and confirmed password don't match.";
		return;
	}

	if ( !isset($_POST["month"]) || !isset($_POST["day"]) || !isset($_POST["year"]) ) {
		$errors[] = "Please enter your complete date of birth.";
		return;	
	}	
	$dateString = getDateString($_POST["month"], $_POST["day"], $_POST["year"]);

	if ( !isset($_POST["sex"]) ) {
		$errors[] = "Please select either male or female.";
		return;
	}
	$sex = $_POST["sex"];

	/* make sure the first and last name are not already in the database */
	$sql = "SELECT * FROM Users WHERE first_name=";
	$sql .= "\"" . $db->escapeString($first_name) . "\"";
	$sql .= " AND last_name=";
	$sql .= "\"" . $db->escapeString($last_name) . "\"";
	if ( $db->doQuery($sql) === false) {
		$errors[] = "There was an internal SQL error.";
		$errors[] = $db->getError();
		return;
	}

	if ( $db->getRowCount() > 0 ) {
		$errors[] = "The first and last name supplied already belong to an existing account.";
		return;
	}

	/* make sure the username is not already in the database */
	$sql = "SELECT * FROM Users WHERE username=";
	$sql .= "\"" . $db->escapeString($username) . "\"";
	if ( $db->doQuery($sql) === false ) {
		$errors[] = "There was an internal SQL error.";
		$errors[] = $db->getError();
		return;
	}

	if ( $db->getRowCount() > 0 ) {
		$errors[] = "The username you chose already belongs to an existing account.";
		return;
	}

	/* at this point, we've checked everything, insert it into the database */
	$sql = "INSERT INTO Users (first_name, last_name, email_addr, username, password, birthdate, date_joined, sex, country_id) VALUES";
	$sql .= "(";
	$sql .= "\"" . $db->escapeString($first_name) . "\",";
	$sql .= "\"" . $db->escapeString($last_name) . "\",";
	$sql .= "\"" . $db->escapeString($email_addr) . "\",";
	$sql .= "\"" . $db->escapeString($username) . "\",";
	$sql .= "\"" . $password . "\",";		/* don't need to escape string a hashed value */
	$sql .= "\"" . $dateString . "\",";
	$sql .= "NOW(),";
	$sql .= "\"" . $sex . "\",";

	/* get the country_id using the country name we sent over */
	$query = "SELECT idCountry FROM countries WHERE countryName=";
	$query .= "\"" . $_POST["country"] . "\"";
	if ( $db->doQuery($query) === false ) {
		$errors[] = "There was an internal SQL error.";
		return;
	}

	if ( $db->getRowCount() != 1 ) {
		$errors[] = "Unable to find country code for country.";
		return;	
	}

	$tmp = $db->getNextRow();
	$country_code = $tmp[0];
	$sql .= "$country_code";
	$sql .= ")";

	$result = $db->doQuery($sql);
	if ( !$result ) {
		$errors[] = "There was an unknown error adding the user to the Users table.";
		$errors[] = $db->getError();
		return;
	}

	/* now, create the directory for the user account to upload files to */
	$userDir = ROOT_USER_DIR . $username;
	if ( !mkdir($userDir, 0775) ) {
		die("Failed to create the user folder for . $username");
		return;
	}
?>
