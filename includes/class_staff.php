<?php
/* For licensing terms, see /license.txt */

//Check if called by script
if(THT != 1){
	die();
}

class staff extends model {
	
	public $columns 	= array('id', 'user','email', 'password','salt', 'name', 'perms');
	public $table_name 	= 'staff';
	
	/** 
	 * Creates an user staff
	 * 
	 * @param 	int		User id
	 * @param	float	amount
	 * @param	date	expiration date
	 */
	public function create($params, $clean_token = true) {
		global $db, $main;		 
		if (!empty($params['user']) &&  !empty($params['email'])) {
			if ($this->userNameExists($params['user']) == false) {			
				$params['salt']			= md5(rand(0,9999999));					
				$params['password'] 	= md5(md5($params['password']).md5($params['salt']));					
				$user_id = $this->save($params);	    
				$main->addLog("Staff created: $user_id");    	
	      		return $user_id;
			} else {
				//$array['Error'] = "That username already exist!";				
				$main->errors('That username already exist!');
			}
		} else {
			$main->errors('Please field the username and email');
		}		
		return false;
	}
	
	
	public function edit($id, $params) {
		global $order, $main;	
		$this->setId($id);	
		
		if (isset($params['password']) && !empty($params['password']) )  {			
			$params['salt']			= md5(rand(0,9999999));
			$params['password'] 	= md5(md5($params['password']).md5($params['salt']));
		}
		$main->addLog("Staff User updated: $id");
		$this->update($params);
	}
	
	/**
	 * Checks if the username is taken or not
	 * @param	string	username
	 * @return 	bool	true if success
	 */
	public function userNameExists($username) {
		global $db;
		$query = $db->query("SELECT id FROM ".$this->getTableName()." WHERE user = '{$username}'");
		if($db->num_rows($query) > 0) {
			return true;
		} else {	
			return false;	
		}
	}
	
	/**
	 * Deletes a user 
	 */
	public function delete($id) {
		global $main;
		//you cant delete yourself 
		if ($id != $main->getCurrentStaffId()) {
			$this->setId($id);
			parent::delete();
			$main->addLog("Staff User deleted: $id");	
			return true;
		} 
		return false;
	}
		
	/**
	 * Gets user information by id
	 * @param	int		user id
	 * @param	array	user information
	 */
	public function getStaffUserById($user_id) {
		global $db, $main;
		$query = $db->query("SELECT * FROM ".$this->getTableName()." WHERE id = '{$db->strip($user_id)}'");
		$data = array();
		if($db->num_rows($query) > 0) {
			$data = $db->fetch_array($query,'ASSOC');			
		}
		return $data;		
	}
	
	/**
	 * Gets user information by username
	 * @param	int		user id
	 * @param	array	user information
	 */
	public function getStaffUserByUserName($username) {
		global $db, $main;
		$query = $db->query("SELECT * FROM ".$this->getTableName()." WHERE user = '{$db->strip($username)}'");
		$data = array();
		if($db->num_rows($query) > 0) {
			$data = $db->fetch_array($query,'ASSOC');			
		}
		return $data;
	}
	
	
	/**
	 * Search a user from a keyword (username, email, name) 
	 */
	public function searchStaffUser($query) {
		global $db;
		$user_list = array();
		if (!empty($query)) {
			$sql = "SELECT * FROM ".$this->getTableName()." 
					  WHERE user 		LIKE '%{$db->strip($query)}%' OR 
							email 		LIKE '%{$db->strip($query)}%'  OR 								
							name 	LIKE '%{$db->strip($query)}%'";
			$result = $db->query($sql);
			
			if($db->num_rows($result) > 0) {
				while($data = $db->fetch_array($result,'ASSOC')) {
					$user_list[] = $data;
				};		
			}
		}
		return $user_list;		
	}	
	
	public function gettAllStaff() {
		global $db, $main;
		$result = $db->query("SELECT * FROM ".$this->getTableName());
		$user_list = array();
		if($db->num_rows($result) > 0) {
			while($data = $db->fetch_array($result,'ASSOC')) {
				$user_list[] = $data;
			}			
		}
		return $user_list;		
	}
	
	public function getStaffById($staff_id) {
		global $db, $main;
		$query = $db->query("SELECT * FROM ".$this->getTableName()." WHERE id = '{$db->strip($staff_id)}'");
		$data = array();
		if($db->num_rows($query) > 0) {
			$data = $db->fetch_array($query,'ASSOC');			
		}
		return $data;		
	}
	
	
	/**
	 * Only changes the system password not the Control Panel password
	 * 
	 * A more or less centralized function for changing a client's
	 * password. This updates both the cPanel/WHM and THT password.
	 * Will return true ONLY on success. Any other returned value should
	 * be treated as a failure. If the return value happens to be a
	 * string, it is an error message.
	 * @todo this function should be moved to the class_user.php file
	 * 
	 */
	public function changeStaffPassword($staff_id, $newpass) {
		global $db, $user;
		//Making sure the $clientid is a reference to a valid id.
		$user_info	=	$user->getStaffById($staff_id);
		
		if (is_array($user_info) && !empty($user_info)) {
			$this->edit($staff_id, array('password'=>$newpass));			
		} else {
			return "That client does not exist.";
		}		
		return true;
	}
	

	
	/*
	public function updateUserStatus($user_id, $status) {
		global $main;		
		$this->setId($user_id);
		$user_status_list = array_keys($main->getUserStatusList());		
		if (in_array($status, $user_status_list)) {		
			$params['status'] = $status;
			$main->addLog("updateUserStatus function called: $user_id");
			$this->update($params);
		}		
	}*/
	
}