
function editUserInfo(field, val) {
	if (field != "Password") {
		var newField = prompt("Editing : " + field, val);
	}
	else {
		var newField = prompt("Editing : " + field + " (WARNING : characters are not masked)", "Password");
	}

	if ( newField != null ) {
		switch(field) {
			case "First Name" :
				document.cookie = "first_name=" + newField;
				window.location = "http://localhost/webapps/account.php?updated=first_name";
				break;
			case "Last Name" :
				document.cookie = "last_name=" + newField;
				window.location = "http://localhost/webapps/account.php?updated=last_name";
				break;
			case "Email Address" : 
				document.cookie = "email_address=" + newField;
				window.location = "http://localhost/webapps/account.php?updated=email_addr";
				break;
			case "Username" :
				document.cookie = "username=" + newField;
				window.location = "http://localhost/webapps/account.php?updated=username";
				break;
			case "Password" :
				document.cookie = "password=" + newField;
				window.location = "http://localhost/webapps/account.php?updated=password";
				break;
			default :
				break;
		}	
	}
	else if ( newField == val || newField == null ) {
		window.location = "http://localhost/webapps/account.php";
	}
}
