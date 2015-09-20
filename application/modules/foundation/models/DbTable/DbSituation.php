<?php

class Foundation_Model_DbTable_DbSituation extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_situation';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	
	public function addNewSituation($_data){
		$_arr=array(
				'situ_name'	  => $_data['situation_name'],
				'create_date' => Zend_Date::now(),
				'status'   => $_data['status'],
				'user_id'	  => $this->getUserId()
		);
		return  $this->insert($_arr);
	}
	public function updateStuation($_data){
		$_arr=array(
				'situ_name'	  => $_data['situation_name'],
				'create_date' => Zend_Date::now(),
				'status'   => $_data['status'],
				'user_id'	  => $this->getUserId()
		);
		$where=$this->getAdapter()->quoteInto("situ_id=?", $_data["id"]);
		$this->update($_arr, $where);
	}
	public function getSituById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_room WHERE room_id = ".$id;
		$sql.=" LIMIT 1";
		return $db->fetchRow($sql);
	}
	private function _buildQuery($search = ''){
		$sql = " SELECT
				  room_id,
				  room_name,
				  modify_date,
				  user_name,is_active
				FROM rms_room,
				  rms_users
				WHERE rms_room.user_id = rms_users.id ";
		if(empty($search)){
			return $sql;
		}
		echo $sql;
		return $sql;
	}
	function getAllSitu($start, $limit){
		$db = $this->getAdapter();
		$sql = $this->_buildQuery()." LIMIT ".$start.", ".$limit;
		if ($limit == 'All') {
			$sql = $this->_buildQuery();
		}
		 $row = $db->fetchAll($sql);
		 return $row;
	}
	
}

