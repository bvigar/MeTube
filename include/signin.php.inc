<?php
	/* validate the fields before putting them in the database */
	if ( !isset($_POST["username"]) ) {
		$errors[] = "Please enter a username.";
		return;
	}
	$username = $_POST["username"];

	if ( !isset($_POST["password"]) ) {
		$errors[] = "Please enter a password.";
		return;
	}
	$password = hash("sha256", $_POST["password"]); 

	/* now, make sure the account exists by searching for the username */
	$sql = "SELECT * FROM Users WHERE username=";
	$sql .= "\"" . $db->escapeString($username) . "\"";
	if ( $db->doQuery($sql) === false) {
		$errors[] = "There was an internal SQL error.";
		$errors[] = $db->getError();
		debug_print("internal SQL error when retrieving account username");
		return;
	}

	$result1 = $db->getRowCount();
	debug_print("Found : " . $result1 . " accounts matching.");
	/* the account exists, proceed to checking for password match */
	if ( $result1 == 1 ) {
		debug_print("Found account : " . $username);
		$sql = "SELECT * FROM Users WHERE password=";
		$sql .= "\"" . $password . "\"";
		$sql .= " AND username=";
		$sql .= "\"" . $db->escapeString($username) ."\"";
		if ( $db->doQuery($sql) === false) {
			$errors[] = "There was an internal SQL error.";
			$errors[] = $db->getError();
			debug_print("internal SQL error when retrieving account password");
			return;
		}
	
		$result2 = $db->getRowCount();
		debug_print("Found : " . $result2 . " account(s).");
		/* the password supplied was correct and matched with the supplied username, proceed to logging in */
		if ( $result2 == 1 ) {
			/* set the logged in user */
			$user_data = $db->getNextRow();

			/* the time for the cookie is 0, meaning the cookie will expire when the browser restarts */
			setcookie('userid', $user_data[0], 0);
			setcookie('first_name', $user_data[1], 0);
			setcookie('last_name', $user_data[2], 0);
			setcookie('email_address', $user_data[3], 0);
			setcookie('username', $user_data[4], 0);
			setcookie('birthdate', $user_data[5], 0);
			setcookie('first_login', 1, 0);

			Header("Location: " . SITE_ROOT);
		}
		/* the username is correct, but wrong password */
		else if ( $result2 == 0 ) {
			$errors[] = "The account exists, but the password is incorrect, try again.";
			return;
		}
	}
	/* the account was not found */
	else if ( $result1 == 0 ) {
		$errors[] = "The username supplied does not belong to an existing account.";
		return;
	}
?>
