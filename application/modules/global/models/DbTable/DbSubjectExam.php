<?php

class Global_Model_DbTable_DbSubjectExam extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_subject';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	
	public function addNewSubjectExam($_data){
		$_arr=array(
				'subject_titlekh' => $_data['subject_kh'],
				'subject_titleen' => $_data['subject_en'],
				'date' 	=> Zend_Date::now(),
				'status'   	=> $_data['status'],
				'user_id'	  	=> $this->getUserId()
		);
		return  $this->insert($_arr);
	}
	public function updateSubjectExam($_data,$id){
		$_arr=array(
				'subject_titlekh' => $_data['subject_kh'],
				'subject_titleen' => $_data['subject_en'],
				'date' 	 => Zend_Date::now(),
				'status'   	 => $_data['status'],
				'user_id'	  	 => $this->getUserId()
		);
		$where=$this->getAdapter()->quoteInto("id=?", $id);
		$this->update($_arr, $where);
   }
	public function getSubexamById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_subject WHERE id = ".$db->quote($id);
		$sql.=" LIMIT 1";
		$row=$db->fetchRow($sql);
		return $row;
	}
	function getAllSujectName($search=null){
		$db = $this->getAdapter();
		$sql = " SELECT id,subject_titlekh,subject_titleen,date,status,
		(SELECT CONCAT(last_name,' ',first_name) FROM rms_users WHERE id=user_id) as user_name
		FROM rms_subject
		WHERE 1";
		$order=" order by id DESC";
		$where = '';
		if(!empty($search['title'])){
			$where.=" AND subject_titleen LIKE '%".$search['title']."%'";
			$where.=" AND subject_titlekh LIKE '%".$search['title']."%'";
		}
		if($search['status']>-1){
			$where.= " AND status = ".$search['status'];
		}
		
		return $db->fetchAll($sql.$where.$order);
	}
}

