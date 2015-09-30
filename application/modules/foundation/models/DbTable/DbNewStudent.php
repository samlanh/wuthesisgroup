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
    	$sql = " SELECT stu_id AS id,stu_khname,
    			stu_enname,
    			(SELECT
				    	`rms_view`.`name_en`
				    	FROM `rms_view`
				    	WHERE ((`rms_view`.`type` = 2)
				    	AND (`rms_view`.`key_code` = `s`.`sex`))
				    	LIMIT 1) AS `sex`,
    			dob,phone,
    			(SELECT `rms_view`.`name_en`
			    	FROM `rms_view`
			    	WHERE ((`rms_view`.`type` = 3)
			    	AND (`rms_view`.`key_code` = `s`.`degree`))
			    	LIMIT 1) AS `degree`,
    			    (SELECT
				    	`f`.`en_name`
				    	FROM `rms_dept` `f`
				    	WHERE (`f`.`dept_id` = `s`.`faculty_id`)
				    	LIMIT 1) AS `faculty_name`,
				    `s`.`batch`         AS `batch`,
				    `s`.`year`          AS `year`,
				    `s`.`semester`      AS `semester`,
				    (SELECT
				    	`rms_view`.`name_en`
				    	FROM `rms_view`
				    	WHERE ((`rms_view`.`type` = 4)
				    	AND (`rms_view`.`key_code` = `s`.`session`))
				    	LIMIT 1) AS `session`,
    			    	(SELECT
					    	`rms_view`.`name_en`
					    	FROM `rms_view`
					    	WHERE ((`rms_view`.`type` = 1)
					    	AND (`rms_view`.`key_code` = `s`.`status`))
					    	LIMIT 1) AS `status`,
			    			(SELECT first_name FROM rms_users WHERE id=user_id )AS user_name
			    			FROM rms_student AS s
			    	WHERE is_stu_new = 1 ";
    	$order=" order by stu_id DESC ";
    	$where = '';
    	return $db->fetchAll($sql.$where.$order);
    	
//     	DELIMITER $$
    	
//     	ALTER ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_getallgroup` AS (
//     	SELECT
//     	`g`.`group_id`      AS `group_id`,
//     	`g`.`group_code`    AS `group_code`,
//     	(SELECT
//     	`rms_view`.`name_en`
//     	FROM `rms_view`
//     	WHERE ((`rms_view`.`type` = 3)
//     	AND (`rms_view`.`key_code` = `g`.`degree`))
//     	LIMIT 1) AS `degree`,
//     	(SELECT
//     	`m`.`major_enname`
//     	FROM `rms_major` `m`
//     	WHERE (`m`.`major_id` = `g`.`major_id`)
//     	LIMIT 1) AS `major_name`,
//     	`g`.`batch`         AS `batch`,
//     	`g`.`year`          AS `year`,
//     	`g`.`semester`      AS `semester`,
//     	(SELECT
//     	`rms_view`.`name_en`
//     	FROM `rms_view`
//     	WHERE ((`rms_view`.`type` = 4)
//     	AND (`rms_view`.`key_code` = `g`.`session`))
//     	LIMIT 1) AS `session`,
//     	`g`.`academic_year` AS `academic_year`,
//     	(SELECT
//     	`r`.`room_name`
//     	FROM `rms_room` `r`
//     	WHERE (`r`.`room_id` = `g`.`room_id`)) AS `room_name`,
//     	`g`.`amount_month`  AS `amount_month`,
//     	(SELECT
//     	`rms_view`.`name_en`
//     	FROM `rms_view`
//     	WHERE ((`rms_view`.`type` = 1)
//     	AND (`rms_view`.`key_code` = `g`.`status`))
//     	LIMIT 1) AS `status`
//     	FROM `rms_group` `g`
//     	ORDER BY `g`.`group_id` DESC)$$
    	
//     	DELIMITER ;
    	
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
    			'scholar_id'    => $_data['scholarship'],
    			'faculty_id'	=>$_data['dept'],
    			'major_id'		=> $_data['major'],
    			'batch'			=> $_data['batch'],
    			'session'		=> $_data['session'],
    			'year' 			=> $_data['year'],
    			'semester' 		=> $_data['semester'],
    			'father_phone' => $_data['father_phone'],
    			'mother_phone' 	=> $_data['mother_phone'],
    			'situation'	=> $_data['situation'],
    			'finish_bacc'	=> $_data['finish_bacc'],
    			'certificate_code' 	=> $_data['certificate_code'],
    			'bacc_score' 	=> $_data['bacc_score'],
    			'mention'   	=> $_data['mention'],
    			'school_location'  => $_data['school_location'],
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

