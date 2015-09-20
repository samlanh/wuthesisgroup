<?php

class RsvAcl_Model_DbTable_DbUser extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_acl_user';
	//function for getting record user by user_id
	public function getUser($user_id)
	{
		$select=$this->select();		
		$select->where('user_id=?',$user_id);
		$row=$this->fetchRow($select);
		if(!$row) return NULL;
		return $row->toArray();
	}
	//get user name
	public function getUserName($user_id)
	{
		$select=$this->select();
		$select->from($this,'username')
			->where("user_id=?",$user_id);
		$row=$this->fetchRow($select);
		if(!$row) return null; 
		return $row['username'];
	}
	//change password user wanted
	public function changePassword($user_id,$password)
	{
		$data=array('password'=>$password);
		$where=$this->getAdapter()->quoteInto('user_id=?',$user_id);
		$this->update($data,$where);
	}
	//is valid password
	public function isValidCurrentPassword($user_id,$current_password)
	{
		$select=$this->select();
		$select->from($this,'password')
			->where("user_id=?",$user_id);
		$row=$this->fetchRow($select);
		if($row){
			$current_password=md5($current_password);
			$password=$row['password'];			 
			if($password==$current_password) return true;
		}
		return false;
	}
	//get infomation of user
	public function getUserInfo($sql)
	{
		$db=$this->getAdapter();  		
  		$stm=$db->query($sql);
  		$row=$stm->fetchAll();
  		if(!$row) return NULL;
  		return $row;
	}
	//function get user id from database
	public function getUserID($username)
	{
		$select=$this->select();
			$select->from($this,'user_id')
			->where('username=?',$username);
		$row=$this->fetchRow($select);
		if(!$row) return NULL;
		return $row['user_id'];
	}
	//function retrieve record users by column 
	public function getUsers($column)
	{		
		$sql='user_id not in(select user_id from pdbs_acl) AND status=1 ';	
		$select=$this->select();
		$select->from($this,$column)
			   ->where($sql);
		$row=$this->fetchAll($select);
		if(!$row) return NULL;		
		return $row->toArray();
	}
	//function check user have exist
	public function isUserExist($username)
	{
		$select=$this->select();
		$select->from($this,'username')
			->where("username=?",$username);
		$row=$this->fetchRow($select);
		if(!$row) return false;
		return true;
	}
	//function check id number have exist
	public function isIdNubmerExist($id_number)
	{
		$select=$this->select();
		$select->from($this,'id_number')
			->where("id_number=?",$id_number);
		$row=$this->fetchRow($select);
		if(!$row) return false;
		return true;
	}
	//add user
	public function insertUser($arr)
	{
		$data=array(); 
		$arr['password']=md5($arr['password']);   	
     	foreach($arr as $key=>$value){
     		if($key=='title' || $key=='name' || $key=='cso_id'){
     			$user_info[$key] = $value;
     		}
     		elseif(!($key=='confirm_password' ||
     		     $key=='save'
     		  ) 	 
     		){     		
     			$data[$key]=$value;
     		}     	
     	}	
     	$data['created_date']=date("Y-m-d H:i:s");
     	$data['status']='1';
     	$id = $this->insert($data);
     	
     	//insert data to table fi_users_info by seakleang
     	$user_info['id'] = $id;
     	$db_global = new Application_Model_DbTable_DbGlobal();
     	$db_global->setName('fi_users_info');
     	$db_global->insert($user_info);
     	
    	return  $id;
	}	
	//update user
	public function updateUser($arr,$user_id)
	{
		$data=array(); 	
		//Sophen commented on 17 May 2012   	
     	foreach($arr as $key=>$value){
     		if($key=='title' || $key=='name' || $key=='cso_id'){
     			$user_info[$key] = $value;
     		}
     		elseif(!($key=='confirm_password' ||
     			 $key=='password' ||
     		     $key=='save'
     		  ) 	 
     		){     		
     			$data[$key]=$value;
     		}     	
     	}

     	//update data to table fi_users_info by seakleang
     	//print_r($user_id);exit;
     	$db_global = new Application_Model_DbTable_DbGlobal();
     	$db_global->setName('fi_users_info');
     	$where_info = $db_global->getAdapter()->quoteInto('id=?', $user_id);
     	$db_global->update($user_info, $where_info);
     	
		//Sophen add here
		$data['username']=$arr['username'];
		
     	$data['modified_date']=date("Y-m-d H:i:s");     	
    	$where=$this->getAdapter()->quoteInto('user_id=?',$user_id);
		$this->update($data,$where); 
	}
	//function dupdate field status user to force use become inaction
	public function inactiveUser($user_id)
	{
		$data=array('status'=>0);
		$where=$this->getAdapter()->quoteInto('user_id=?',$user_id);
		$this->update($data,$where);
	}
	public function userAuthenticate($username,$password)
	{
		try{
	              $db_adapter = $this->getDefaultAdapter(); 
	              $auth_adapter = new Zend_Auth_Adapter_DbTable($db_adapter);
	              
	              $auth_adapter->setTableName('rms_acl_user') // table where users are stored
	                           ->setIdentityColumn('username') // field name of user in the table
	                           ->setCredentialColumn('password') // field name of password in the table
	                           ->setCredentialTreatment('MD5(?) AND status=1'); // optional if password has been hashed
	 
	              $auth_adapter->setIdentity($username); // set value of username field
	              $auth_adapter->setCredential($password);// set value of password field
	 
	              //instantiate Zend_Auth class
	              $auth = Zend_Auth::getInstance();
	 
	              $result = $auth->authenticate($auth_adapter);
	 
	              if($result->isValid()){
	                  // do session store here and redirect
	                 
	                 /* $acl=new Application_Model_DbTable_Dbacl();
	                  $div_access=$acl->getDivisionAccessByUserName($username);
	                  if(!$div_access){
	                  	$auth->clearIdentity(); 
	                  	return false;
	                  }
					  else return true;*/
	              	
	              	  return true;				  
	              }else{
	                  // validation errors here
					  return false;
	              }
		}catch(Zend_Exception $ex){}
	}
}

