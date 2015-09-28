<?php

class Foundation_Model_DbTable_DbNewStudent extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_student';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
    public function sqlGetNewStudent($search=''){
    	$sql = " SELECT stu_id AS id,stu_khname,stu_enname,sex,stu_card,
    	dob,phone,degree,
    	(SELECT shortcut FROM  rms_major AS m WHERE m.major_id=s.major_id )AS major_name
    	,session,status,create_date,
    	(SELECT first_name FROM rms_users WHERE id=user_id )AS user_name
    	FROM rms_student AS s
    	WHERE is_stu_new = 1 ";
    	$order=" order by stu_card ";
    	$where = '';
    	if(empty($search)){
    		return $sql.$order;
    	}
//     	if(!empty($search['title'])){
//     		$where.=" AND room_name LIKE '%".$search['title']."%'";
//     	}
//     	if($search['status']>-1){
//     		$where.= " AND is_active = ".$search['status'];
//     	}
    	return $sql.$where.$order;
    }
    function getAllStudent($search){
    	$db = $this->getAdapter();
    	$sql = " SELECT stu_id AS id,stu_khname,stu_enname,sex,stu_card,
			    	dob,phone,degree,
			    	(SELECT shortcut FROM  rms_major AS m WHERE m.major_id=s.major_id )AS major_name
			    	,session,status,create_date,
			    	(SELECT first_name FROM rms_users WHERE id=user_id )AS user_name
			    	FROM rms_student AS s
			    	WHERE is_stu_new = 1 ";
    	$order=" order by stu_card ";
    	$where = '';
    	return $db->fetchAll($sql.$where.$order);
    	
    }
    public function getStudentInfoById($id){
    	$db = $this->getAdapter();
    	$sql=" SELECT *
    	FROM rms_student
    	WHERE is_stu_new = 1 and ".$db->quoteInto("stu_id=?", $id);
    	return $db->fetchRow($sql);
    }
    public function addNewStudent($_data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
    		$_arr=array(
    			'stu_enname' 	=> $_data['en_name'],
    			'stu_khname' 	=> $_data['kh_name'],
    			'stu_card' 		=> $_data['stu_card'],
    			'sex' 			=> $_data['sex'],
    			'dob' 			=> $_data['dob'],
    			'pob' 			=> $_data['pob'],
    			'phone'			=> $_data['phone'],
    			'degree'		=> $_data['degree'],
    			//'major_id'		=> $_data['major_id'],
    			'batch'			=> $_data['batch'],
    			'session'		=> $_data['session'],
    			'year' 			=> $_data['year'],
    			'father_phone' => $_data['father_phone'],
    			'mother_phone' 	=> $_data['mother_phone'],
    			'situation'	=> $_data['situation'],
    			'finish_bacc'	=> $_data['finish_bacc'],
    			'certificate_code' 	=> $_data['certificate_code'],
    			'bacc_score' 	=> $_data['bacc_score'],
    			'mention'   	=> $_data['mention'],
    			'from_school'   	=> $_data['from_school'],
    			'mention'   	=> $_data['mention'],
    			'student_add'   	=> $_data['student_add'],
    			 
    			'certificate_code'	=> $_data['certificate_code'],
    			'from_school'   => $_data['from_school'],
		    	'modify_date' 	=> Zend_Date::now(),
		    	'remark'   		=> $_data['remark'],
		    	'status'   		=> $_data['status'],
		    	'user_id'	  	=> $this->getUserId(),
    			);
    		$this->insert($_arr);
    		$db->commit();
    	 }catch (Exception $e) {
    	 	$db->rollback();
    	 	$err =$e->getMessage();
    	 	Application_Model_DbTable_DbUserLog::writeMessageError($err);
    	}
    }
    public function updateStudentINfo($_data,$by_id){
    	//echo $_data['major_id'];
    	$_arr=array(
    			'stu_enname' 	=> $_data['en_name'],
    			'stu_khname' 	=> $_data['kh_name'],
    			'stu_card' 		=> $_data['stu_card'],
    			'sex' 			=> $_data['sex'],
    			'dob' 			=> $_data['dob'],
    			'pob' 			=> $_data['pob'],
    			'phone'			=> $_data['phone'],
    			'degree'		=> $_data['degree'],
    			//'major_id'		=> $_data['major_id'],
    			'batch'			=> $_data['batch'],
    			'session'		=> $_data['session'],
    			'year' 			=> $_data['year'],
    			'father_phone' => $_data['father_phone'],
    			'mother_phone' 	=> $_data['mother_phone'],
    			'situation'	=> $_data['situation'],
    			'finish_bacc'	=> $_data['finish_bacc'],
    			'certificate_code' 	=> $_data['certificate_code'],
    			'bacc_score' 	=> $_data['bacc_score'],
    			'mention'   	=> $_data['mention'],
    			'from_school'   	=> $_data['from_school'],
    			'mention'   	=> $_data['mention'],
    			'student_add'   	=> $_data['student_add'],
    			
    			'certificate_code'	=> $_data['certificate_code'],
    			'from_school'   => $_data['from_school'],
    			//'is_stu_new'	=> $_data['composition'],
    			'modify_date' 	=> Zend_Date::now(),
    			'remark'   		=> $_data['remark'],
    			'status'   		=> $_data['status'],
    			'user_id'	  	=> $this->getUserId(),
    			
    	);
    	$where = $this->getAdapter()->quoteInto("stu_id=?", $by_id);
    	$this->update($_arr, $where);
    }
	
}

