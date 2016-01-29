<?php
	require_once(SERVER_ROOT . 'include/defs.php.inc');
	require_once(SERVER_ROOT . '/include/db.php');
	include(SERVER_ROOT . '/views/header.php');

	/* global errors array that the include file will set appropriately */
	$errors = Array();

	if ( isset($_POST["Create"]) ) {
		include(SERVER_ROOT . '/include/create.php.inc');

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
			/* no errors, move back to the signin page and display the message */
			debug_print("Account created for : " . $_POST["username"]);
			$msg = "Account successfully created.";

			/* clear out the post before we switch */
			$_POST = Array();

			/* let the next page know we're coming from create account */
			$_POST["created"] = 1;

			/* send the user to the signin page now that they're registered */
			Header("Location: " . SITE_ROOT . "/signin.php");
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
						<input type='text' name="search" placeholder="Enter keywords..." >
						<input type='submit' value="Search" > 
				</form>
				
				<form id='signin_form' action='signin.php'>
						<input type='submit' id='signin_button' value='Sign in' />
				</form>

		</div>
		<!-- End Menu Bar -->

			<!-- Form for creating an account -->
			<form id='create' action='create.php' method='post'>
				<fieldset>
					<legend>Information</legend>

					<label for='firstname'>First Name</label>
					<input type='text' name='firstname' maxlength='20'>
					<br />

					<label for='lastname'>Last Name</label>
					<input type='text' name='lastname' maxlength='20'>
					<br />

					<label for='usremail'>Email</label>
					<input type='email' name='usremail'>
					<br />

					<label for='username'>Choose a username</label>
					<input type='text' name='username' maxlength='20'>
					<br />

					<label for='password'>Create a password</label>
					<input type='password' name='password' maxlength='20'>
					<br />

					<label for='confirm_password'>Confirm your password</label>
					<input type='password' name='confirm_password' maxlength='20'>
					<br />

					<label for='birthday'>Birthday</label>
					<select name='month'>
						<option value='january'>January</option>
						<option value='february'>February</option>
						<option value='march'>March</option>
						<option value='april'>April</option>
						<option value='may'>May</option>
						<option value='june'>June</option>
						<option value='july'>July</option>
						<option value='august'>August</option>
						<option value='september'>September</option>
						<option value='october'>October</option>
						<option value='november'>November</option>
						<option value='december'>December</option>
					</select>&nbsp;
					<select name='day'>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
						<option value='11'>11</option>
						<option value='12'>12</option>
						<option value='13'>13</option>
						<option value='14'>14</option>
						<option value='15'>15</option>
						<option value='16'>16</option>
						<option value='17'>17</option>
						<option value='18'>18</option>
						<option value='19'>19</option>
						<option value='20'>20</option>
						<option value='21'>21</option>
						<option value='22'>22</option>
						<option value='23'>23</option>
						<option value='24'>24</option>
						<option value='25'>25</option>
						<option value='26'>26</option>
						<option value='27'>27</option>
						<option value='28'>28</option>
						<option value='29'>29</option>
						<option value='30'>30</option>
						<option value='31'>31</option>
					</select>&nbsp;
					<!-- the youngest person that can use metube is 4 -->
					<!-- the oldest person that can use metube is 63 -->
					<!-- umad. -->
					<select name='year'>
						<option value='2009'>2009</option>
						<option value='2008'>2008</option>
						<option value='2007'>2007</option>
						<option value='2006'>2006</option>
						<option value='2005'>2005</option>
						<option value='2004'>2004</option>
						<option value='2003'>2003</option>
						<option value='2002'>2002</option>
						<option value='2001'>2001</option>
						<option value='2000'>2000</option>
						<option value='1999'>1999</option>
						<option value='1998'>1998</option>
						<option value='1997'>1997</option>
						<option value='1996'>1996</option>
						<option value='1995'>1995</option>
						<option value='1994'>1994</option>
						<option value='1993'>1993</option>
						<option value='1992'>1992</option>
						<option value='1991'>1991</option>
						<option value='1990'>1990</option>
						<option value='1989'>1989</option>
						<option value='1988'>1988</option>
						<option value='1987'>1987</option>
						<option value='1986'>1986</option>
						<option value='1985'>1985</option>
						<option value='1984'>1984</option>
						<option value='1983'>1983</option>
						<option value='1982'>1982</option>
						<option value='1981'>1981</option>
						<option value='1980'>1980</option>
						<option value='1979'>1979</option>
						<option value='1978'>1978</option>
						<option value='1977'>1977</option>
						<option value='1976'>1976</option>
						<option value='1975'>1975</option>
						<option value='1974'>1974</option>
						<option value='1973'>1973</option>
						<option value='1972'>1972</option>
						<option value='1971'>1971</option>
						<option value='1970'>1970</option>
						<option value='1969'>1969</option>
						<option value='1968'>1968</option>
						<option value='1967'>1967</option>
						<option value='1966'>1966</option>
						<option value='1965'>1965</option>
						<option value='1964'>1964</option>
						<option value='1963'>1963</option>
						<option value='1962'>1962</option>
						<option value='1961'>1961</option>
						<option value='1960'>1960</option>
						<option value='1959'>1959</option>
						<option value='1958'>1958</option>
						<option value='1957'>1957</option>
						<option value='1956'>1956</option>
						<option value='1955'>1955</option>
						<option value='1954'>1954</option>
						<option value='1953'>1953</option>
						<option value='1952'>1952</option>
						<option value='1951'>1951</option>
						<option value='1950'>1950</option>
					</select>
					<br />

					<input type='radio' name='sex' value='male'>Male&nbsp;
					<input type='radio' name='sex' value='female'>Female
					<br />

					<label for='country'>Country</label>
					<select name='country'>
						<option value="Afghanistan">Afghanistan</option>
						<option value="Aland Islands">Aland Islands</option>
						<option value="Albania">Albania</option>
						<option value="Algeria">Algeria</option>
						<option value="American Samoa">American Samoa</option>
						<option value="Andorra">Andorra</option>
						<option value="Angola">Angola</option>
						<option value="Anguilla">Anguilla</option>
						<option value="Antarctica">Antarctica</option>
						<option value="Antigua and Barbuda">Antigua and Barbuda</option>
						<option value="Argentina">Argentina</option>
						<option value="Armenia">Armenia</option>
						<option value="Aruba">Aruba</option>
						<option value="Australia">Australia</option>
						<option value="Austria">Austria</option>
						<option value="Azerbaijan">Azerbaijan</option>
						<option value="Bahamas">Bahamas</option>
						<option value="Bahrain">Bahrain</option>
						<option value="Bangladesh">Bangladesh</option>
						<option value="Barbados">Barbados</option>
						<option value="Belarus">Belarus</option>
						<option value="Belgium">Belgium</option>
						<option value="Belize">Belize</option>
						<option value="Benin">Benin</option>
						<option value="Bermuda">Bermuda</option>
						<option value="Bhutan">Bhutan</option>
						<option value="Bolivia">Bolivia</option>
						<option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
						<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
						<option value="Botswana">Botswana</option>
						<option value="Bouvet Island">Bouvet Island</option>
						<option value="Brazil">Brazil</option>
						<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
						<option value="Brunei">Brunei</option>
						<option value="Bulgaria">Bulgaria</option>
						<option value="Burkina Faso">Burkina Faso</option>
						<option value="Burundi">Burundi</option>
						<option value="Cambodia">Cambodia</option>
						<option value="Cameroon">Cameroon</option>
						<option value="Canada">Canada</option>
						<option value="Cape Verde">Cape Verde</option>
						<option value="Cayman Islands">Cayman Islands</option>
						<option value="Central African Republic">Central African Republic</option>
						<option value="Chad">Chad</option>
						<option value="Chile">Chile</option>
						<option value="China">China</option>
						<option value="Christmas Island">Christmas Island</option>
						<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
						<option value="Colombia">Colombia</option>
						<option value="Comoros">Comoros</option>
						<option value="Congo">Congo</option>
						<option value="Cook Islands">Cook Islands</option>
						<option value="Costa Rica">Costa Rica</option>
						<option value="Cote d'ivoire (Ivory Coast)">Cote d'ivoire (Ivory Coast)</option>
						<option value="Croatia">Croatia</option>
						<option value="Cuba">Cuba</option>
						<option value="Curacao">Curacao</option>
						<option value="Cyprus">Cyprus</option>
						<option value="Czech Republic">Czech Republic</option>
						<option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option>
						<option value="Denmark">Denmark</option>
						<option value="Djibouti">Djibouti</option>
						<option value="Dominica">Dominica</option>
						<option value="Dominican Republic">Dominican Republic</option>
						<option value="Ecuador">Ecuador</option>
						<option value="Egypt">Egypt</option>
						<option value="El Salvador">El Salvador</option>
						<option value="Equatorial Guinea">Equatorial Guinea</option>
						<option value="Eritrea">Eritrea</option>
						<option value="Estonia">Estonia</option>
						<option value="Ethiopia">Ethiopia</option>
						<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
						<option value="Faroe Islands">Faroe Islands</option>
						<option value="Fiji">Fiji</option>
						<option value="Finland">Finland</option>
						<option value="France">France</option>
						<option value="French Guiana">French Guiana</option>
						<option value="French Polynesia">French Polynesia</option>
						<option value="French Southern Territories">French Southern Territories</option>
						<option value="Gabon">Gabon</option>
						<option value="Gambia">Gambia</option>
						<option value="Georgia">Georgia</option>
						<option value="Germany">Germany</option>
						<option value="Ghana">Ghana</option>
						<option value="Gibraltar">Gibraltar</option>
						<option value="Greece">Greece</option>
						<option value="Greenland">Greenland</option>
						<option value="Grenada">Grenada</option>
						<option value="Guadaloupe">Guadaloupe</option>
						<option value="Guam">Guam</option>
						<option value="Guatemala">Guatemala</option>
						<option value="Guernsey">Guernsey</option>
						<option value="Guinea">Guinea</option>
						<option value="Guinea-Bissau">Guinea-Bissau</option>
						<option value="Guyana">Guyana</option>
						<option value="Haiti">Haiti</option>
						<option value="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
						<option value="Honduras">Honduras</option>
						<option value="Hong Kong">Hong Kong</option>
						<option value="Hungary">Hungary</option>
						<option value="Iceland">Iceland</option>
						<option value="India">India</option>
						<option value="Indonesia">Indonesia</option>
						<option value="Iran">Iran</option>
						<option value="Iraq">Iraq</option>
						<option value="Ireland">Ireland</option>
						<option value="Isle of Man">Isle of Man</option>
						<option value="Israel">Israel</option>
						<option value="Italy">Italy</option>
						<option value="Jamaica">Jamaica</option>
						<option value="Japan">Japan</option>
						<option value="Jersey">Jersey</option>
						<option value="Jordan">Jordan</option>
						<option value="Kazakhstan">Kazakhstan</option>
						<option value="Kenya">Kenya</option>
						<option value="Kiribati">Kiribati</option>
						<option value="Kosovo">Kosovo</option>
						<option value="Kuwait">Kuwait</option>
						<option value="Kyrgyzstan">Kyrgyzstan</option>
						<option value="Laos">Laos</option>
						<option value="Latvia">Latvia</option>
						<option value="Lebanon">Lebanon</option>
						<option value="Lesotho">Lesotho</option>
						<option value="Liberia">Liberia</option>
						<option value="Libya">Libya</option>
						<option value="Liechtenstein">Liechtenstein</option>
						<option value="Lithuania">Lithuania</option>
						<option value="Luxembourg">Luxembourg</option>
						<option value="Macao">Macao</option>
						<option value="Macedonia">Macedonia</option>
						<option value="Madagascar">Madagascar</option>
						<option value="Malawi">Malawi</option>
						<option value="Malaysia">Malaysia</option>
						<option value="Maldives">Maldives</option>
						<option value="Mali">Mali</option>
						<option value="Malta">Malta</option>
						<option value="Marshall Islands">Marshall Islands</option>
						<option value="Martinique">Martinique</option>
						<option value="Mauritania">Mauritania</option>
						<option value="Mauritius">Mauritius</option>
						<option value="Mayotte">Mayotte</option>
						<option value="Mexico">Mexico</option>
						<option value="Micronesia">Micronesia</option>
						<option value="Moldava">Moldava</option>
						<option value="Monaco">Monaco</option>
						<option value="Mongolia">Mongolia</option>
						<option value="Montenegro">Montenegro</option>
						<option value="Montserrat">Montserrat</option>
						<option value="Morocco">Morocco</option>
						<option value="Mozambique">Mozambique</option>
						<option value="Myanmar (Burma)">Myanmar (Burma)</option>
						<option value="Namibia">Namibia</option>
						<option value="Nauru">Nauru</option>
						<option value="Nepal">Nepal</option>
						<option value="Netherlands">Netherlands</option>
						<option value="New Caledonia">New Caledonia</option>
						<option value="New Zealand">New Zealand</option>
						<option value="Nicaragua">Nicaragua</option>
						<option value="Niger">Niger</option>
						<option value="Nigeria">Nigeria</option>
						<option value="Niue">Niue</option>
						<option value="Norfolk Island">Norfolk Island</option>
						<option value="North Korea">North Korea</option>
						<option value="Northern Mariana Islands">Northern Mariana Islands</option>
						<option value="Norway">Norway</option>
						<option value="Oman">Oman</option>
						<option value="Pakistan">Pakistan</option>
						<option value="Palau">Palau</option>
						<option value="Palestine">Palestine</option>
						<option value="Panama">Panama</option>
						<option value="Papua New Guinea">Papua New Guinea</option>
						<option value="Paraguay">Paraguay</option>
						<option value="Peru">Peru</option>
						<option value="Phillipines">Phillipines</option>
						<option value="Pitcairn">Pitcairn</option>
						<option value="Poland">Poland</option>
						<option value="Portugal">Portugal</option>
						<option value="Puerto Rico">Puerto Rico</option>
						<option value="Qatar">Qatar</option>
						<option value="Reunion">Reunion</option>
						<option value="Romania">Romania</option>
						<option value="Russia">Russia</option>
						<option value="Rwanda">Rwanda</option>
						<option value="Saint Barthelemy">Saint Barthelemy</option>
						<option value="Saint Helena">Saint Helena</option>
						<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
						<option value="Saint Lucia">Saint Lucia</option>
						<option value="Saint Martin">Saint Martin</option>
						<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
						<option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
						<option value="Samoa">Samoa</option>
						<option value="San Marino">San Marino</option>
						<option value="Sao Tome and Principe">Sao Tome and Principe</option>
						<option value="Saudi Arabia">Saudi Arabia</option>
						<option value="Senegal">Senegal</option>
						<option value="Serbia">Serbia</option>
						<option value="Seychelles">Seychelles</option>
						<option value="Sierra Leone">Sierra Leone</option>
						<option value="Singapore">Singapore</option>
						<option value="Sint Maarten">Sint Maarten</option>
						<option value="Slovakia">Slovakia</option>
						<option value="Slovenia">Slovenia</option>
						<option value="Solomon Islands">Solomon Islands</option>
						<option value="Somalia">Somalia</option>
						<option value="South Africa">South Africa</option>
						<option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
						<option value="South Korea">South Korea</option>
						<option value="South Sudan">South Sudan</option>
						<option value="Spain">Spain</option>
						<option value="Sri Lanka">Sri Lanka</option>
						<option value="Sudan">Sudan</option>
						<option value="Suriname">Suriname</option>
						<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
						<option value="Swaziland">Swaziland</option>
						<option value="Sweden">Sweden</option>
						<option value="Switzerland">Switzerland</option>
						<option value="Syria">Syria</option>
						<option value="Taiwan">Taiwan</option>
						<option value="Tajikistan">Tajikistan</option>
						<option value="Tanzania">Tanzania</option>
						<option value="Thailand">Thailand</option>
						<option value="Timor-Leste (East Timor)">Timor-Leste (East Timor)</option>
						<option value="Togo">Togo</option>
						<option value="Tokelau">Tokelau</option>
						<option value="Tonga">Tonga</option>
						<option value="Trinidad and Tobago">Trinidad and Tobago</option>
						<option value="Tunisia">Tunisia</option>
						<option value="Turkey">Turkey</option>
						<option value="Turkmenistan">Turkmenistan</option>
						<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
						<option value="Tuvalu">Tuvalu</option>
						<option value="Uganda">Uganda</option>
						<option value="Ukraine">Ukraine</option>
						<option value="United Arab Emirates">United Arab Emirates</option>
						<option value="United Kingdom">United Kingdom</option>
						<option value="United States">United States</option>
						<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
						<option value="Uruguay">Uruguay</option>
						<option value="Uzbekistan">Uzbekistan</option>
						<option value="Vanuatu">Vanuatu</option>
						<option value="Vatican City">Vatican City</option>
						<option value="Venezuela">Venezuela</option>
						<option value="Vietnam">Vietnam</option>
						<option value="Virgin Islands, British">Virgin Islands, British</option>
						<option value="Virgin Islands, US">Virgin Islands, US</option>
						<option value="Wallis and Futuna">Wallis and Futuna</option>
						<option value="Western Sahara">Western Sahara</option>
						<option value="Yemen">Yemen</option>
						<option value="Zambia">Zambia</option>
						<option value="Zimbabwe">Zimbabwe</option>
					</select>

					<input type='submit' name='Create' value='Create' />
				</fieldset>
			</form>
<?php
	include(SERVER_ROOT . '/views/footer.php');
?>
