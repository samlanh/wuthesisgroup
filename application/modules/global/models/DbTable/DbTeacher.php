<?php

class Global_Model_DbTable_DbTeacher extends Zend_Db_Table_Abstract
{
    protected $_name = 'rms_teacher';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	public function AddNewTeacher($_data){
		$_arr=array(
				'teacher_name_en' => $_data['en_name'],
				'teacher_name_kh' => $_data['kh_name'],
				'subject_name_en' => $_data['en_subject'],
				'subject_name_kh' => $_data['kh_subject'],
				'modify_date' => Zend_Date::now(),
				'is_active'   => $_data['status'],
				'user_id'	  => $this->getUserId()
		);
		return  $this->insert($_arr);
	}
	public function getTeacherById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_teacher WHERE teacher_id = ".$db->quote($id);
		$sql.=" LIMIT 1";
		$row=$db->fetchRow($sql);
		return $row;
	}
	public function updateTeacher($_data){
		$_arr=array(
				'teacher_name_en' => $_data['en_name'],
				'teacher_name_kh' => $_data['kh_name'],
				'subject_name_en' => $_data['en_subject'],
				'subject_name_kh' => $_data['kh_subject'],
				'modify_date' => Zend_Date::now(),
				'is_active'   => $_data['status'],
				'user_id'	  => $this->getUserId()
		);
		$where=$this->getAdapter()->quoteInto("teacher_id=?", $_data["id"]);
		$this->update($_arr, $where);
	}
	
	function getAllTeacher($search){
		$db = $this->getAdapter();
		$sql = " SELECT teacher_id AS id, teacher_name_en,teacher_name_kh,
		subject_name_en,subject_name_kh, modify_date, is_active as status ,
		(SELECT first_name from rms_users where id=user_id )AS user_name FROM rms_teacher
		WHERE 1";
		$order=" order by teacher_name_en";
		$where = '';
		if(!empty($search['title'])){
			$where.=" AND ( teacher_name_en LIKE '%".$search['title']."%'
			OR teacher_name_kh LIKE '%".$search['title']."%')";
		}
		if($search['status']>-1){
			$where.= " AND is_active = ".$search['status'];
		}
		if($search['subjec_name']>-1){
			$where.= " AND subject_name_en = '".$search['subjec_name']."'";
		}
		return $db->fetchAll($sql.$where.$order);
	}
	
}

