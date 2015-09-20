<?php

class Registrar_Model_DbTable_DbGetStudentInfo extends Zend_Db_Table_Abstract
{
    protected $_name = 'rms_student';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	public function getAllStudent(){
		$db = $this->getAdapter();
		$sql = "SELECT stu_id AS id,stu_card,stu_enname as name,stu_khname,sex,dob,pob,degree FROM rms_student ";
		return $db->fetchAll($sql);
	}
	public function getStudentById($student_card){
		$db = $this->getAdapter();
		//$student_card=$db->quote($student_card);
		$sql = "SELECT stu_id AS id,stu_card,stu_enname,stu_khname,sex,dob,phone,degree,batch,year,session,faculty_id,major_id,mention
			FROM rms_student WHERE stu_card ='".$student_card."' limit 1";
		//return $sql;
		return $db->fetchRow($sql);
	}
}

