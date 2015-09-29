<?php

class Global_Model_DbTable_DbSituation extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_situation';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	public function addNewSituation($_data){
		$_arr=array(
				'situ_name'	  => $_data['situ_name'],
				'create_date' => Zend_Date::now(),
				'status'   => $_data['status'],
				'user_id'	  => $this->getUserId()
		);
		return  $this->insert($_arr);
	}
	public function getSituationById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_situation WHERE situ_id = ".$db->quote($id);
		$sql.=" LIMIT 1 ";
		$row=$db->fetchRow($sql);
		return $row;
	}
	public function updateSituation($data){
		$_arr=array(
				'situ_name'	  => $data['situ_name'],
				'create_date' => Zend_Date::now(),
				'status'   => $data['status'],
				'user_id'	  => $this->getUserId()
		);
		$where=$this->getAdapter()->quoteInto("situ_id=?", $data["id"]);
		$this->update($_arr, $where);
	}
	function getAllSituations($search=null){
		$db = $this->getAdapter();
		$sql = " SELECT situ_id AS id,situ_name, create_date,status,(SELECT  CONCAT(first_name,' ', last_name) FROM rms_users WHERE id=user_id )AS user_name
		
		FROM rms_situation
		WHERE 1 ";
		return $db->fetchAll($sql);	
	}
	public function addSituation($_data){//ajax
		$_arr=array(
				'situ_name'	  => $_data['txt_situation'],
				'create_date' => Zend_Date::now(),
				'status'   => 1,
				'user_id'	  => $this->getUserId()
		);
		return  $this->insert($_arr);
	}	
}

