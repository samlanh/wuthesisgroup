<?php

class Global_Model_DbTable_DbTeacher extends Zend_Db_Table_Abstract
{
    protected $_name = 'rms_teacher';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	public function AddNewTeacher($_data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$_arr=array(
					'teacher_code' => $_data['code'],
					'teacher_name_en' => $_data['en_name'],
					'teacher_name_kh' => $_data['kh_name'],
					'sex' => $_data['sex'],
					'phone' => $_data['phone'],
					'dob' => $_data['dob'],
					'pob' => $_data['pob'],
					'address' => $_data['address'],
					'email' => $_data['email'],
					'degree' => $_data['degree'],
					//'photo' => $_data['kh_subject'],
					'note'=>$_data['note'],
					'date' => Zend_Date::now(),
					'status'   => $_data['status'],
					'user_id'	  => $this->getUserId()
			);
			
			$teacher_id = $this->insert($_arr);
			$this->_name='rms_teacher_subject';
			$ids = explode(',', $_data['record_row']);
			foreach ($ids as $i){
				$arr = array(
						'subject_id'=>$_data['subject_id'.$i],
						'teacher_id'=>$teacher_id,
						'status'   => $_data['status'],
						'date' => Zend_Date::now(),
						'user_id'	  => $this->getUserId()
						
				);
				$this->insert($arr);
			}
			return $db->commit();
		}catch (Exception $e){
			$db->rollBack();
		}
	}
	public function getTeacherById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_teacher WHERE id = ".$db->quote($id);
		$sql.=" LIMIT 1";
		$row=$db->fetchRow($sql);
		return $row;
	}
	public function getallSubjectTeacherById($teacher_id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM `rms_teacher_subject` WHERE teacher_id= ".$db->quote($teacher_id);
		return $db->fetchAll($sql);;
	}
	public function updateTeacher($_data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
		$_arr=array(
					'teacher_code' => $_data['code'],
					'teacher_name_en' => $_data['en_name'],
					'teacher_name_kh' => $_data['kh_name'],
					'sex' => $_data['sex'],
					'phone' => $_data['phone'],
					'dob' => $_data['dob'],
					'pob' => $_data['pob'],
					'address' => $_data['address'],
					'email' => $_data['email'],
					'degree' => $_data['degree'],
					//'photo' => $_data['kh_subject'],
					'note'=>$_data['note'],
					'date' => Zend_Date::now(),
					'status'   => $_data['status'],
					'user_id'	  => $this->getUserId()
		);
		$where=$this->getAdapter()->quoteInto("id=?", $_data["id"]);
		$this->update($_arr, $where);
		
		$this->_name='rms_teacher_subject';
		$ids = explode(',', $_data['record_row']);
		foreach ($ids as $i){
			$arr = array(
					'subject_id'=>$_data['subject_id'.$i],
					'teacher_id'=>$_data["id"],
					'status'   => $_data['status'.$i],
					'date' => Zend_Date::now(),
					'user_id'	  => $this->getUserId()
		
			);
			if(!empty($_data['subexist_id'.$i])){
				$where=$this->getAdapter()->quoteInto("id=?", $_data['subexist_id'.$i]);
				$this->update($arr, $where);
			}else{
				$this->insert($arr);
			}
		}
		return $db->commit();
		}catch (Exception $e){
			$db->rollBack();
			echo $e->getMessage();exit();
		}
	}
	
	function getAllTeacher($search){
		$db = $this->getAdapter();
		$sql = ' SELECT * FROM `v_getallteacher` WHERE 1';
		$where = '';
		if(!empty($search['title'])){
		    $s_where = array();
			$s_search = addslashes(trim($search['title']));
			
			$s_where[] = " teacher_code LIKE '%{$s_search}%'";
			$s_where[] = " teacher_name_en LIKE '%{$s_search}%'";
			$s_where[] = " teacher_name_kh LIKE '%{$s_search}%'";
			
			$s_where[] = " sex LIKE '%{$s_search}%'";
			$s_where[] = " phone LIKE '%{$s_search}%'";
			$s_where[] = " email LIKE '%{$s_search}%'";
			$s_where[] = " degree LIKE '%{$s_search}%'";
			$s_where[] = " status LIKE '%{$s_search}%'";
			$s_where[] = " user_name LIKE '%{$s_search}%'";
			
			$where .=' AND ('.implode(' OR ',$s_where).')';
			
		}
		return $db->fetchAll($sql.$where);
	}
	public function addTeacherSubject($_data){//ajax
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			
			$_arr=array(
					'teacher_code' => $_data['code'],
					'teacher_name_en' => $_data['en_name'],
					'teacher_name_kh' => $_data['kh_name'],
					'sex' => $_data['sex'],
					'phone' => $_data['phone'],
					'dob' => $_data['dob'],
					'pob' => $_data['pob'],
					'address' => $_data['address'],
					'email' => $_data['email'],
					'degree' => $_data['degree'],
					'note'=>$_data['note'],
					'date' => Zend_Date::now(),
					'status'   => 1,
					'user_id'	  => $this->getUserId()
			);
			$teacher_id = $this->insert($_arr);
			
			$this->_name='rms_subject';
			$_arr=array(
					'subject_titlekh' => $_data['subject_kh'],
					'subject_titleen' => $_data['subject_en'],
					'date' 	=> Zend_Date::now(),
					'status'   	=> 1,
					'user_id'	  	=> $this->getUserId()
			);
			 $subject_id = $this->insert($_arr);
	
			$this->_name='rms_teacher_subject';
				$arr = array(
						'subject_id'=>$subject_id,
						'teacher_id'=>$teacher_id,
						'status'   => 1,
						'date' => Zend_Date::now(),
						'user_id'	  => $this->getUserId()
	
				);
			$teacher_subject = $this->insert($arr);
			$db->commit();
			return $teacher_subject;
		}catch (Exception $e){
			$db->rollBack();
			Application_Model_DbTable_DbUserLog::writeMessageError($err =$e->getMessage());
		}
	}
	
}

