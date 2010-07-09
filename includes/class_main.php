<?php/* For licensing terms, see /license.txt */
//Check if called by script
if(THT != 1){die();}class main {
	public $postvar = array(), $getvar = array(); # All post/get strings	
	public function cleaninteger($var){ # Transforms an Integer Value (1/0) to a Friendly version (Yes/No)
	     $patterns[0] = '/0/';
         $patterns[1] = '/1/';
         $replacements[0] = 'No';
         $replacements[1] = 'Yes';
         return preg_replace($patterns, $replacements, $var);
	}
	public function cleanwip($var){ # Cleans v* from the version Number so we can work
	     if(preg_match('/v/', $var)) {
	     	$wip[0] = '/v/';
	     	$wipr[0] = '';
	     	$cleaned = preg_replace($wip, $wipr, $var);
	     	return $cleaned;
	     } else {	     	return $var; #Untouched
	     }	}
	public function error($array) {
		echo "<strong>ERROR<br /></strong>";
		foreach($array as $key => $data) {
			echo "<strong>". $key . ":</strong> ". $data ."<br />";
		}
		echo "<br />";
	}
	
	public function redirect($url, $headers = 0, $long = 0) { # Redirects user, default headers
		if(!$headers) {
			header("Location: ". $url);	# Redirect with headers
		} else {
			echo '<meta http-equiv="REFRESH" content="'.$long.';url='.$url.'">'; # HTML Headers
		}
	}
		/**	 *  Shows error default, sets error if $error set	 */
	public function errors($error = 0) {		
		if(!$error) {
			if($_SESSION['errors']) {
				return $_SESSION['errors'];
			}
		} else {
			$_SESSION['errors'] = $error;
		}			}
	
	public function table($header, $content = 0, $width = 0, $height = 0) { # Returns the HTML for a THT table
		global $style;
		if($width) {
			$props = "width:".$width.";";		}		
		if($height) {
			$props .= "height:".height.";";
		}
		$array['PROPS'] = $props;
		$array['HEADER'] = $header;
		$array['CONTENT'] = $content;
		$array['ID'] =rand(0,999999);
		$link = LINK."../themes/". THEME ."/tpl/table.tpl";
		if(file_exists($link)) {
			$tbl = $style->replaceVar("../themes/". THEME ."/tpl/table.tpl", $array);
		} else {
			$tbl = $style->replaceVar("tpl/table.tpl", $array);
		}
		return $tbl;
	}
	public function sub($left, $right) { # Returns the HTML for a THT table
		global $style;
		$array['LEFT'] = $left;
		$array['RIGHT'] = $right;
		$link = LINK."../themes/". THEME ."/tpl/sub.tpl";
		if(file_exists($link)) {
			$tbl = $style->replaceVar("../themes/". THEME ."/tpl/sub.tpl", $array);
		} else {
			$tbl = $style->replaceVar("tpl/sub.tpl", $array);
		}
		return $tbl;
	}
	
	public function evalreturn($code) { # Evals code and then returns it without showing
		ob_start();
		eval("?> " . $code . "<?php ");
		$data = ob_get_contents();
		ob_clean();
		return $data;
	}
	
	public function done() { # Redirects the user to the right part
		global $main;
		foreach($main->getvar as $key => $value) {
			if($key != "do") {
				if($i) {
					$i = "&";
				} else {
					$i = "?";
				}
				$url .= $i . $key . "=" . $value;
			}		}
		$main->redirect($url);
	}
	
	public function check_email($email) {
		if($this->validEmail($email)) {
			return true;
		}
		else {
			return false;
		}
	}		/*	public function createInput($type, $label, $name, $value) {		switch($type) {			case THT_INPUT:			$html = $label.': <input name="'.$name.'" value="'.$value.'"> <br/>';			break;			case THT_CHECKBOX:								}				return $html;	}		*/
	/**	 * Creates an input	 * @param string	label	 * @param string	name	 * @param bool		true if the checkbox will be checked	 * @return string html	 * 	 */	public function createInput($label, $name, $value) {		$html = $label.' <input name="'.$name.'" value="'.$value.'"> <br/>';		return $html;	}		/**	 * Creates a checkbox	 * @param string	label	 * @param string	name	 * @param bool		true if the checkbox will be checked	 * @return string html	 * 	 */	public function createCheckbox($label, $name, $checked = false) {		if ($checked == true) {			$checked = 'checked="'.$checked.'"';		} else {			$checked = '';		}		if(empty($label)) {			$label = '';		} else {			$label = $label.':';		}		$html = $label.'<input type="checkbox" name="'.$name.'"  '.$checked.' > <br/>';		return $html;	}		
	public function dropDown($name, $values, $default = 0, $top = 1, $class = "", $parameter_list = array()) { # Returns HTML for a drop down menu with all values and selected		if($top) {			$extra = '';			foreach($parameter_list as $key=>$parameter) {				$extra .= $key.'="'.$parameter.'"';			}
			$html .= '<select name="'.$name.'" id="'.$name.'" class="'.$class.'" '.$extra.'>';
		}
		if($values) {
			foreach($values as $key => $value) {
				$html .= '<option value="'.$value[1].'"';
				if($default == $value[1]) {
					$html .= 'selected="selected"';				}
				$html .= '>'.$value[0].'</option>';
			}
		}
		if($top) {
			$html .= '</select>';
		}
		return $html;
	}		/**	 * New simpler version of the dropDown	 */	public function createSelect($name, $values, $default = 0, $top = 1, $class = "",$parameter_list = array(), $show_blank_item = true) { # Returns HTML for a drop down menu with all values and selected		if($top) {			$extra = '';			foreach($parameter_list as $key=>$parameter) {				$extra .= $key.'="'.$parameter.'"';			}			$html .= '<select name="'.$name.'" id="'.$name.'" class="'.$class.'" '.$extra.'>';		}		if ($show_blank_item) {			$html .= '<option value="0">-- Select --</option>';		}		if($values) {			foreach($values as $key => $value) {				$html .= '<option value="'.$key.'"';				if($default == $key) {					$html .= 'selected="selected"';				}				$html .= '>'.$value.'</option>';			}		}		if($top) {			$html .= '</select>';		}		return $html;	}			
	public function folderFiles($link) { # Returns the filenames of a content in a folder
		$folder = $link;
		if ($handle = opendir($folder)) { # Open the folder
			while (false !== ($file = readdir($handle))) { # Read the files
				if($file != "." && $file != ".." && $file != ".svn" && $file != "index.html") { # Check aren't these names
					$values[] = $file;
				}
			}
		}
		closedir($handle); #Close the folder
		return $values;
	}
	public function checkIP($ip) { # Returns boolean for ip. Checks if exists
		global $db;
		global $main;
		$query = $db->query("SELECT * FROM `<PRE>users` WHERE `ip` = '{$db->strip($ip)}'");
		if($db->num_rows($query) > 0) {
			return false;
		}else {
			return true;	
		}
	}
		/**	 * Checks the staff permissions for a nav item	 * @param 	int	user id	 */	 
	public function checkPerms($id, $user = 0) {
		global $main, $db;
		if(!$user) {
			$user = $_SESSION['user'];
		}			//Use now session to avoid useless query calls to the DB		if (isset($_SESSION['user_permissions'])) {			foreach($_SESSION['user_permissions'] as $value) {				if($value == $id) {					return false;					}			}			return true;		} else {
			$query = $db->query("SELECT * FROM `<PRE>staff` WHERE `id` = '{$user}'");
			if($db->num_rows($query) == 0) {
				$array['Error'] = "Staff member not found";
				$array['Staff ID'] = $id;
				$main->error($array);
			} else {
				$data = $db->fetch_array($query);			
				$perms = explode(",", $data['perms']);											$_SESSION['user_permissions'] = $perms;				
				foreach($perms as $value) {
					if($value == $id) {
						return false;	
					}
				}
				return true;
			}		}
	}
	public function clientLogin($user, $pass) { # Checks the credentails of the client and logs in, returns true or false
		global $db, $main;
		if($user && $pass) {
			$query = $db->query("SELECT * FROM `<PRE>users` WHERE `user` = '{$main->postvar['user']}' AND (`status` <= '2' OR `status` = '4')");
			if($db->num_rows($query) == 0) {
				return false;
			} else {
				$data = $db->fetch_array($query,'ASSOC');
				$ip = $_SERVER['REMOTE_ADDR'];
				if(md5(md5($main->postvar['pass']) . md5($data['salt'])) == $data['password']) {
					$_SESSION['clogged'] = 1;
					$_SESSION['cuser'] = $data['id'];										//Save all user in this session 					$data['password'] = null;					$data['salt'] = null;					$_SESSION['user_information'] = $data;					
					$date = time();
					$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
														'{$data['id']}',
														'{$main->postvar['user']}',
														'{$date}',
														'Login successful ($ip)')");
					return true;
				}	else {
					$date = time();
					$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
														'{$data['id']}',
														'{$main->postvar['user']}',
														'{$date}',
														'Login failed ($ip)')");					return false;
				}
			}
		} else {
			return false;
		}
	}
	
	public function staffLogin($user, $pass) { # Checks the credentials of a staff member and returns true or false
		global $db, $main;
		if($user && $pass) {
			$query = $db->query("SELECT * FROM `<PRE>staff` WHERE `user` = '{$main->postvar['user']}'");
			if($db->num_rows($query) == 0) {
				return false;
			}
			else {
				$data = $db->fetch_array($query);
				if(md5(md5($main->postvar['pass']) . md5($data['salt'])) == $data['password']) {
					$_SESSION['logged'] = 1;
					$_SESSION['user'] = $data['id'];										$data['password'] = null;					$data['salt'] = null;					$_SESSION['user_information'] = $data;					
					$date = time();
					$ip = $_SERVER['REMOTE_ADDR'];
					$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
														'{$data['id']}',
														'{$main->postvar['user']}',
														'{$date}',
														'STAFF LOGIN SUCCESSFUL ($ip)')");
					return true;
				}
				else {
					$date = time();
					$ip = $_SERVER['REMOTE_ADDR'];
					$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
														'{$data['id']}',
														'{$main->postvar['user']}',
														'{$date}',
														'STAFF LOGIN FAILED ($ip)')");
					return false;
				}
			}
		}
		else {
			return false;
		}
	}
	
	public function laterMonth($num) { # Makes the date with num of months after current
		$day = date('d');
		$month = date('m');
		$year = date('Y');
		
		$endMonth = $month + $num;
		
		switch($endMonth) {
		case 1:
		$year++;
		break;
		case 2:
		{
		if ($day > 28)
		{
		// check if the year is leap
		$day = 28; // or you can keep the day and increase the month
		}
		}
		break;
		default:
		// nothing to do 
		break;
		}
		
		return mktime(0,0,0,$endMonth,$day,$year);
	}
	
	/**
	* Validate an email address.
	* Provide email address (raw input)
	* Returns true if the email address has the email 
	* address format and the domain exists.
	* Thank you, Linux Journal!
	* http://www.linuxjournal.com/article/9585
	*/
	public function validEmail($email)
	{
	   $isValid = true;
	   $atIndex = strrpos($email, "@");
	   if (is_bool($atIndex) && !$atIndex)
	   {
		  $isValid = false;
	   }
	   else
	   {
		  $domain = substr($email, $atIndex+1);
		  $local = substr($email, 0, $atIndex);
		  $localLen = strlen($local);
		  $domainLen = strlen($domain);
		  if ($localLen < 1 || $localLen > 64)
		  {
			 // local part length exceeded
			 $isValid = false;
		  }
		  else if ($domainLen < 1 || $domainLen > 255)
		  {
			 // domain part length exceeded
			 $isValid = false;
		  }
		  else if ($local[0] == '.' || $local[$localLen-1] == '.')
		  {
			 // local part starts or ends with '.'
			 $isValid = false;
		  }
		  else if (preg_match('/\\.\\./', $local))
		  {
			 // local part has two consecutive dots
			 $isValid = false;
		  }
		  else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
		  {
			 // character not valid in domain part
			 $isValid = false;
		  }
		  else if (preg_match('/\\.\\./', $domain))
		  {
			 // domain part has two consecutive dots
			 $isValid = false;
		  }
		  else if
	(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
					 str_replace("\\\\","",$local)))
		  {
			 // character not valid in local part unless 
			 // local part is quoted
			 if (!preg_match('/^"(\\\\"|[^"])+"$/',
				 str_replace("\\\\","",$local)))
			 {
				$isValid = false;
			 }
		  }
		  if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
			 // domain not found in DNS
			 $isValid = false;
		  }
	   }
	   return $isValid;
	}
	
	/**
	 * A more or less centralized function for changing a client's
	 * password. This updates both the cPanel/WHM and THT password.
	 * Will return true ONLY on success. Any other returned value should
	 * be treated as a failure. If the return value happens to be a
	 * string, it is an error message.	 * @todo this function should be moved to the class_user.php file
	 */
	function changeClientPassword($clientid, $newpass) {
		global $db, $server;
		//Making sure the $clientid is a reference to a valid id.		var_dump($clientid, $newpass);
		$query = $db->query("SELECT * FROM `<PRE>users` WHERE `id` = {$db->strip($clientid)}");
		if($db->num_rows($query) == 0) {
			return "That client does not exist.";
		}
		
		/*
		 * We're going to set the password in cPanel/WHM first. That way
		 * if the password is rejected for some reason, THT will not 
		 * desync.
		 */
		$command = $server->changePwd($clientid, $newpass);
		if($command !== true) {
			return $command;
		}
		
		/*
		 * Let's change THT's copy of the password. Might as well make a
		 * new salt while we're at it.
		 */
		mt_srand((int)microtime(true));
		$salt = md5(mt_rand());
		$password = md5(md5($newpass) . md5($salt));
		$db->query("UPDATE `<PRE>users` SET `password` = '{$password}' WHERE `id` = '{$db->strip($clientid)}'");
		$db->query("UPDATE `<PRE>users` SET `salt` = '{$salt}' WHERE `id` = '{$db->strip($clientid)}'");
		
		//Let's wrap it all up.
		return true;
	}		// Order status	public function getOrderStatusList() {		return array(			ORDER_STATUS_ACTIVE						=> 'Active', 			ORDER_STATUS_WAITING_USER_VALIDATION 	=> 'Waiting user validation',						ORDER_STATUS_WAITING_ADMIN_VALIDATION	=> 'Waiting admin validation',			ORDER_STATUS_CANCELLED 					=> 'Canceled',  			//ORDER_STATUS_WAITING_PAYMENT	=> 'Waiting payment', 			ORDER_STATUS_DELETED			=> 'Deleted', 			);	}		public function getInvoiceStatusList() {		return array(			INVOICE_STATUS_PAID				=> 'Paid', 			INVOICE_STATUS_CANCELLED		=> 'Cancelled',						INVOICE_STATUS_WAITING_PAYMENT	=> 'Pending', 			INVOICE_STATUS_DELETED			=> 'Deleted'			);	}		public function getUserStatusList() {		return array(			USER_STATUS_ACTIVE						=> 'Active', 			USER_STATUS_SUSPENDED 					=> 'Suspend', 			USER_STATUS_WAITING_ADMIN_VALIDATION	=> 'Waiting admin validation',  			//USER_STATUS_WAITING_PAYMENT				=> 'Waiting payment',  //should be remove only added for backward comptability			USER_STATUS_DELETED						=> 'Deleted', 			);	}		/**	 * Gets current user info 	 */	public function getCurrentUserInfo() {		if (isset($_SESSION['user_information']) && is_array($_SESSION['user_information'])) {			return $_SESSION['user_information'];		} else {			return false;		}	}		/**	 * Gets the curren user id 	 */	public function getCurrentUserId() {		if (isset($_SESSION['user_information']) && is_array($_SESSION['user_information'])) {			return intval($_SESSION['user_information']['id']);		} else {			return false;		}	}}