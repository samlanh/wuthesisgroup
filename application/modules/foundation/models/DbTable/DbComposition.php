<?php

class Foundation_Model_DbTable_DbComposition extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_composition';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	
	public function addNewComposition($_data){
		$_arr=array(
				'composition_name'	  => $_data['composition_name'],
				'create_date' => Zend_Date::now(),
				'status'   => $_data['status'],
				'user_id'	  => $this->getUserId()
		);
		return  $this->insert($_arr);
	}
	public function updateComposition($_data){
		$_arr=array(
				'composition_name'	  => $_data['composition_name'],
				'create_date' => Zend_Date::now(),
				'status'   => $_data['status'],
				'user_id'	  => $this->getUserId()
		);
		$where=$this->getAdapter()->quoteInto("composition_id=?", $_data["id"]);
		$this->update($_arr, $where);
	}
	public function getCompositionById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_composition WHERE composition_id = ".$id;
		$sql.=" LIMIT 1";
		return $db->fetchRow($sql);
	}
	private function _buildQuery($search = ''){
		$sql = " SELECT
				  composition_id,
				  composition_name,
				  create_date,
				  user_name,status
				FROM rms_composition,
				  rms_users
				WHERE rms_composition.user_id = rms_users.id ";
		if(empty($search)){
			return $sql;
		}
		echo $sql;
		return $sql;
	}
	function getAllcomposition($start, $limit){
		$db = $this->getAdapter();
		$sql = $this->_buildQuery()." LIMIT ".$start.", ".$limit;
		if ($limit == 'All') {
			$sql = $this->_buildQuery();
		}
		 $row = $db->fetchAll($sql);
		 return $row;
	}
}

