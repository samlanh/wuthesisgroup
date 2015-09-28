<?php

class Global_Model_DbTable_DbGroup extends Zend_Db_Table_Abstract
{
    protected $_name = 'rms_group';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	public function AddNewGroup($_data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$_arr=array(
					'group_code' => $_data['group_code'],
					'room_id' => $_data['room'],
					'batch' => $_data['batch'],
					'year' => $_data['year'],
					'semester' => $_data['semester'],
					'session' => $_data['session'],
					'degree' => $_data['group_degree'],
					'major_id' => $_data['major'],
					'amount_month' => $_data['amountmonth'],
// 					'is_check' => $_data['degree'],
					'start_date' => $_data['start_date'],
					'academic_year' => $_data['academic'],
					'expired_date'=>$_data['end_date'],
					'date' => date("Y-m-d"),
					'status'   => $_data['status'],
					'note'   => $_data['note'],
					'user_id'	  => $this->getUserId()
			);
			
			$group_data = $this->insert($_arr);
			$this->_name='rms_group_subject_detail';
			$ids = explode(',', $_data['record_row']);
			foreach ($ids as $i){
				$arr = array(
						'group_id'=>$group_data['group_id'],
						'subject_id'=>$_data['subject_id'.$i],
						'status'   => $_data['status'],
						'note'   => $_data['note'.$i],
						'date' => date("Y-m-d"),
						'user_id'	  => $this->getUserId()
						
				);
				$this->insert($arr);
			}
			return $db->commit();
		}catch (Exception $e){
			$db->rollBack();
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	public function getGroupById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_group WHERE group_id = ".$db->quote($id);
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
	
	function getAllGroup($search){
		$db = $this->getAdapter();
		$sql = ' SELECT * FROM `v_getallgroup` WHERE 1';
		$where = '';
		if(!empty($search['title'])){
		    $s_where = array();
			$s_search = addslashes(trim($search['title']));
			$s_where[] = " group_code LIKE '%{$s_search}%'";
			$s_where[] = " degree LIKE '%{$s_search}%'";
			$s_where[] = " major_name LIKE '%{$s_search}%'";
			$s_where[] = " batch = '{$s_search}'";
			$s_where[] = " year = '{$s_search}'";
			$s_where[] = " semester = '{$s_search}'";
			$s_where[] = " session LIKE '%{$s_search}%'";
			$s_where[] = " academic_year LIKE '%{$s_search}%'";
			$s_where[] = " room_name LIKE '%{$s_search}%'";
			$where .=' AND ('.implode(' OR ',$s_where).')';
			
		}
		return $db->fetchAll($sql.$where);
	}
	
}

