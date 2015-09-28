<?php

class Global_Model_DbTable_DbScholarship extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_scholarship';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	public function addNewScholarship($_data){
		$_arr=array(
				'title'	  => $_data['title'],
				'note'	  => $_data['note'],
				'type'	  => $_data['type'],
				'date' => Zend_Date::now(),
				'status'   => $_data['status'],
				'user_id'	  => $this->getUserId()
		);
		return  $this->insert($_arr);
	}
	public function getScholarshipById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_scholarship WHERE id = ".$db->quote($id);
		$sql.=" LIMIT 1 ";
		$row=$db->fetchRow($sql);
		return $row;
	}
	public function updateScholarship($data){
		$_arr=array(
				'title'	  => $data['title'],
				'note'	  => $data['note'],
				'type'	  => $data['type'],
				'date' => Zend_Date::now(),
				'status'   => $data['status'],
				'user_id'	  => $this->getUserId()
		);
		$where=$this->getAdapter()->quoteInto("id=?", $data["id"]);
		$this->update($_arr,$where);
	}
	function getAllScholarship($search=null){
		$db = $this->getAdapter();
		$sql = " SELECT id ,title, note,type,date,status,(SELECT  CONCAT(first_name,' ', last_name) FROM rms_users WHERE id=user_id )AS user_name
		FROM rms_scholarship
		WHERE 1 ";
		return $db->fetchAll($sql);	
	}	
}

