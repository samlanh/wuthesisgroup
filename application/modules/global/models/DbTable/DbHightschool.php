<?php

class Global_Model_DbTable_DbHightschool extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_school_province';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
    public function addSchool($_data){
    	$_arr=array(
    			'province_id'	  => $_data['province'],
    			'school_name'	  => $_data['schoolname'],
    			'create_date' => Zend_Date::now(),
    			'status'   => $_data['status'],
    			'user_id'	  => $this->getUserId()
    	);
    	
    	$this->insert($_arr);
    }
   
	public function getSchoolById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_school_province WHERE id = ".$id;
		$sql.=" LIMIT 1";
		$row=$db->fetchRow($sql);
		return $row;
	}
    public function updateSchool($_data){
    	$_arr=array(
    			'province_id'	  => $_data['province'],
    			'school_name'	  => $_data['schoolname'],
    			'create_date' => Zend_Date::now(),
    			'status'   => $_data['status'],
    			'user_id'	  => $this->getUserId()
    	);
    	$where=$this->getAdapter()->quoteInto("id=?", $_data['id']);
    	$this->update($_arr, $where);
    }
    function getAllHighSchool($search=null){
    	$db = $this->getAdapter();
    	$sql = " SELECT s.id ,s.school_name,
					(SELECT p.province_kh_name FROM rms_province AS p WHERE s.province_id=p.province_id LIMIT 1) AS province_name,
					s.create_date,s.status,
					(SELECT CONCAT(last_name,' ',first_name) FROM rms_users WHERE id=s.user_id )AS user_name
					 FROM `rms_school_province` AS s  ";
    	$order=" order by s.id DESC";
    	$where = '';
    	if(!empty($search['title'])){
//     		$where.=" AND ( province_en_name LIKE '%".$search['title']."%' OR province_kh_name LIKE '%".$search['title']."%') ";
    	}
    	if($search['status']>-1){
    		$where.= " AND s.status = ".$db->quote($search['status']);
    	}
    	return $db->fetchAll($sql.$where.$order);
    }
    public function addNewschool($_data){//ajax
    	$_arr=array(
    			'province_id'	  => $_data['province'],
    			'school_name'	  => $_data['school_name'],
    			'create_date' => Zend_Date::now(),
    			'status'   => 1,
    			'user_id'	  => $this->getUserId()
    	);
    	 
    	return $this->insert($_arr);
    }
     
}

