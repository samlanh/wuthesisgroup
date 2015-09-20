<?php

class Global_Model_DbTable_Dbquery extends Zend_Db_Table_Abstract
{
    //protected $_name = 'rms_major';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
    public function getAllSetting($search=""){
    	$db = $this->getAdapter();
    	$sql = "SELECT * FROM `rms_setting` ";
    	if(empty($search)){
    		return $sql;
    	}
    	return $sql;
    }
    private function _buildQuery($search = ''){
    	$sql = "SELECT
    	CONCAT(u.first_name,' ',u.last_name) As user_name,dept_id,en_name,kh_name,shortcut
    	,modify_date,user_id,is_active
    	FROM `rms_dept` AS d,rms_users AS u
    	WHERE d.user_id=u.id ";
    	$orderby = " ORDER BY en_name ";
    	if(empty($search)){
    		return $sql.$orderby;
    	}
    	$where = '';
    		
    	return $sql.$where.$orderby;
    }
    
    
    function settingList($start, $limit){
    	$db = $this->getAdapter();
    	$sql = $this->getAllSetting()." LIMIT ".$start.", ".$limit;
    	if ($limit == 'All') {
    		$sql = $this->getAllSetting();
    	}
    	return $db->fetchAll($sql);
    }
    
}

