<?php

class Foundation_Model_DbTable_DbRoom extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_room';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	
	public function addNewRoom($_data){
		$_arr=array(
				'room_name'	  => $_data['classname'],
				'modify_date' => Zend_Date::now(),
				'is_active'   => $_data['status'],
				'user_id'	  => $this->getUserId()
		);
		return  $this->insert($_arr);
	}
	public function updateRoom($_data){
		$_arr=array(
				'room_name'	  => $_data['classname'],
				'modify_date' => Zend_Date::now(),
				'is_active'   => $_data['status'],
				'user_id'	  => $this->getUserId()
		);
		$where=$this->getAdapter()->quoteInto("room_id=?", $_data["id"]);
		$this->update($_arr, $where);
	}
	public function getRoomById($id){
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
	function getAllRoom($start, $limit){
		$db = $this->getAdapter();
		$sql = $this->_buildQuery()." LIMIT ".$start.", ".$limit;
		if ($limit == 'All') {
			$sql = $this->_buildQuery();
		}
		 $row = $db->fetchAll($sql);
		 return $row;
	}
	function countAllRoom($search=''){
		$db = $this->getAdapter();
		$sql = $this->_buildQuery();
		if(!empty($search)){
			$sql = $this->_buildQuery($search);
		}
		$_result = $db->fetchAll($sql);
		return count($_result);
	}
}

