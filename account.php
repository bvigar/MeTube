<?php
	require_once(SERVER_ROOT . 'include/defs.php.inc');
	require_once(SERVER_ROOT . 'include/db.php');
	include(SERVER_ROOT . 'views/header.php');


if ( isset($_GET['updated']) ) {
		switch ( $_GET['updated'] ) {
			case "first_name" :
				$sql = "UPDATE Users SET first_name='" . $_COOKIE['first_name'] . "' WHERE username='" . $_COOKIE['username'] . "'";
				$db->doQuery($sql);
				break;
			case "last_name" :
				$sql = "UPDATE Users SET last_name='" . $_COOKIE['last_name'] . "' WHERE username='" . $_COOKIE['username'] . "'";
				$db->doQuery($sql);
				break;
			case "email_addr" :
				$sql = "UPDATE Users SET email_addr='" . $_COOKIE['email_address'] . "' WHERE username='" . $_COOKIE['username'] . "'";
				$db->doQuery($sql);
				break;
			case "username" :
				$sql = "UPDATE Users SET username='" . $_COOKIE['username'] . "' WHERE email_addr='" . $_COOKIE['email_address'] . "'";
				$db->doQuery($sql);
				break;
			case "password" :
				$hashedPass = hash("sha256", $_COOKIE['password']);
				$sql = "UPDATE Users SET password='" . $hashedPass . "' WHERE username='" . $_COOKIE['username'] . "'";
				$db->doQuery($sql);
				break;
			default :
				break;
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

			<h2 style='float:right; margin-right:1em; margin-top:1em; margin-bottom:1em;'>
				<?php echo "signed in as : <b>" . $_COOKIE['username'] . "</b>"; ?> &nbsp; | &nbsp; 
				<a href="account.php">Manage</a> &nbsp; | &nbsp;
				<a href="logout.php">Logout</a>
			</h2>

		</div>
		<!-- End Menu Bar -->

		<!-- Begin Body Container -->
		<div class="body_container">

			<!-- Sort header -->
			<div class="sort_header">

			</div>
			<!-- End Sort Header -->

			<div class="media_list">
				<table cellpadding="15">
					<tr>
						<td>
							First Name : 
						</td>
						<td>
							<?php echo "<b>" . $_COOKIE['first_name'] . "</b>"; ?> &nbsp; &nbsp;
							<button onclick="editUserInfo('First Name', <?php echo "'" . $_COOKIE['first_name'] . "'"; ?>)">Edit</button>
						</td>
					</tr>
					<tr>
						<td>
							Last Name :
						</td>
						<td>
							<?php echo "<b>" . $_COOKIE['last_name'] . "</b>"; ?>
							<button onclick="editUserInfo('Last Name', <?php echo "'" . $_COOKIE['last_name'] . "'"; ?>)">Edit</button>
						</td>
					</tr>
					<tr>
						<td>
							Email Address :
						</td>
						<td>
							<?php echo "<b>" . $_COOKIE['email_address'] . "</b>"; ?>
							<button onclick="editUserInfo('Email Address', <?php echo "'" . $_COOKIE['email_address'] . "'"; ?>)">Edit</button>
						</td>
					</tr>
					<tr>
						<td>
							Username :
						</td>
						<td>
							<?php echo "<b>" . $_COOKIE['username'] . "</b>"; ?>
							<button onclick="editUserInfo('Username', <?php echo "'" . $_COOKIE['username'] . "'"; ?>)">Edit</button>
						</td>
					</tr>
					<tr>
						<td>
							Password :
						</td>
						<td>
							<button onclick="editUserInfo('Password', '')">Edit</button>
						</td>
					</tr>
				</table>
			</div>

		</div>
		<!-- End Body Container -->

<?php
	include(SERVER_ROOT . '/views/footer.php');
?>
