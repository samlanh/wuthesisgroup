<?php

class Global_Model_DbTable_DbSubjectExam extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_subject_exam';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	
	public function addNewSubjectExam($_data){
		$_arr=array(
				'subj_exam_name' => $_data['subject_exam'],
				'modify_date' 	=> Zend_Date::now(),
				'is_active'   	=> $_data['status'],
				'user_id'	  	=> $this->getUserId()
		);
		return  $this->insert($_arr);
	}
	public function updateSubjectExam($_data,$id){
		$_arr=array(
				'subj_exam_name' => $_data['subject_exam'],
				'modify_date' 	 => Zend_Date::now(),
				'is_active'   	 => $_data['status'],
				'user_id'	  	 => $this->getUserId()
		);
		$where=$this->getAdapter()->quoteInto("subexam_id=?", $id);
		$this->update($_arr, $where);
   }
	public function getSubexamById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_subject_exam WHERE subexam_id = ".$db->quote($id);
		$sql.=" LIMIT 1";
		$row=$db->fetchRow($sql);
		return $row;
	}
	function getAllSujectName($search=null){
		$db = $this->getAdapter();
		$sql = " SELECT subexam_id as id,subj_exam_name,modify_date,is_active status,
		(SELECT CONCAT(last_name,' ',first_name) FROM rms_users WHERE id=user_id) as user_name
		FROM rms_subject_exam
		WHERE 1";
		$order=" order by subj_exam_name";
		$where = '';
		if(!empty($search['title'])){
			$where.=" AND subj_exam_name LIKE '%".$search['title']."%'";
		}
		if($search['status']>-1){
			$where.= " AND is_active = ".$search['status'];
		}
		
		return $db->fetchAll($sql.$where.$order);
	}
}

