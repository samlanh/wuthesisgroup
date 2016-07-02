<?php

class Foundation_Model_DbTable_DbGroupStudentChangeGroup extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'rms_group_student_change_group';
	public function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	}
	
	
	
	public function getfromGroup(){
		$db = $this->getAdapter();
		$sql = "SELECT group_code,id FROM `rms_group` where status = 1 and degree IN (2,3,4)  ";
// 		$orderby = " ORDER BY stu_code ";
		return $db->fetchAll($sql);
	}
	
	public function gettoGroup(){
		$db = $this->getAdapter();
		$sql = "SELECT group_code,id FROM `rms_group` where status = 1 ";
		// 		$orderby = " ORDER BY stu_code ";
		return $db->fetchAll($sql);
	}
	
	
	
	public function selectAllStudentChangeGroup($search){
		$_db = $this->getAdapter();
		$sql = "SELECT rms_group_student_change_group.id,(select group_code from rms_group where rms_group.id=rms_group_student_change_group.from_group) as group_code,
				(select major_enname from rms_major where rms_major.major_id=(select grade from rms_group where rms_group.id=rms_group_student_change_group.from_group) limit 1) as grade,
				(select name_en from rms_view where rms_view.type=4 and rms_view.key_code=(select session from rms_group where rms_group.id=rms_group_student_change_group.from_group) limit 1 ) as session,
				
				
				(select group_code from rms_group where rms_group.id=rms_group_student_change_group.to_group) as to_group_code,
				(select major_enname from rms_major where rms_major.major_id=(select grade from rms_group where rms_group.id=rms_group_student_change_group.to_group) limit 1) as to_grade,
				(select name_en from rms_view where rms_view.type=4 and rms_view.key_code=(select session from rms_group where rms_group.id=rms_group_student_change_group.to_group) limit 1 ) as to_session,
				
				moving_date,rms_group_student_change_group.note
		
				FROM `rms_group_student_change_group`,rms_group where rms_group.id=rms_group_student_change_group.from_group and rms_group.degree IN (2,3,4)";
		
		$order_by=" order by id DESC";
		$where=" ";
		if(empty($search)){
			return $_db->fetchAll($sql.$order_by);
		}
		if(!empty($search['txtsearch'])){
			$s_where = array();
			$s_search = addslashes(trim($search['txtsearch']));
			$s_where[] = " (select group_code from rms_group where rms_group.id=rms_group_student_change_group.from_group limit 1) LIKE '%{$s_search}%'";
			$s_where[] = " (select group_code from rms_group where rms_group.id=rms_group_student_change_group.to_group limit 1) LIKE '%{$s_search}%'";
			$s_where[] = " (SELECT major_enname FROM rms_major WHERE rms_major.major_id=(select grade from rms_group where rms_group.id=
							rms_group_student_change_group.from_group limit 1)) LIKE '%{$s_search}%'";
			$s_where[] = " (SELECT major_enname FROM rms_major WHERE rms_major.major_id=(select grade from rms_group where rms_group.id=
							rms_group_student_change_group.to_group limit 1)) LIKE '%{$s_search}%'";
			
			$s_where[] = " (SELECT name_en FROM rms_view WHERE rms_view.type=4 and key_code=(select session from rms_group where rms_group.id=
							rms_group_student_change_group.to_group limit 1)) LIKE '%{$s_search}%'";
			$s_where[] = " (SELECT name_en FROM rms_view WHERE rms_view.type=4 and key_code=(select session from rms_group where rms_group.id=
							rms_group_student_change_group.from_group limit 1)) LIKE '%{$s_search}%'";
			
			//$s_where[] = " en_name LIKE '%{$s_search}%'";
			$where .=' AND ( '.implode(' OR ',$s_where).')';
		}
		
		return $_db->fetchAll($sql.$where.$order_by);
// 		(select name_kh from `rms_view` where `rms_view`.`type`=6 and `rms_view`.`key_code`=`rms_student_change_group`.`status`)AS status
	}
	
	public function getAllGroupStudentChangeGroupById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_group_student_change_group WHERE id =".$id;
		return $db->fetchRow($sql);
	}
	
	public function getCondition($data){
		$db=$this->getAdapter();
		$sql="select * from rms_group_student_change_group where rms_group_student_change_group.from_group=".$data['from_group']." and rms_group_student_change_group.to_group=".$data['to_group'];
		return $db->fetchRow($sql);
	}
	
	public function addGroupStudentChangeGroup($_data){
			try{	
				$_db= $this->getAdapter();
				$con = $this->getCondition($_data);
				if($con!=''){
					$identity = explode(',', $_data['identity']);
					$array_checkbox=explode(',', $con['array_checkbox']);
					$result = array_merge($array_checkbox,$identity);
					$final_array = implode(",", $result);
					//print_r($final_array);exit();
					
					$arra=array(
						'array_checkbox'	=>	$final_array,
							);
					
					$where = ' from_group='.$_data['from_group'].' and to_group='.$_data['to_group'];
					
					$this->update($arra, $where);
					
				}else{
					$_arr= array(
							'user_id'		=>$this->getUserId(),
							'from_group'	=>$_data['from_group'],
							'to_group'		=>$_data['to_group'],
							'moving_date'	=>$_data['moving_date'],
							'note'			=>$_data['note'],
							'status'		=>$_data['status'],
							'array_checkbox'=>$_data['identity'],
							);
					$id = $this->insert($_arr);
				}
				
				$this->_name='rms_group_detail_student';
					$idsss=explode(',', $_data['identity']);
					foreach ($idsss as $k){
						$stu=array(
								'is_pass'		=>1,
						);
						$where=" stu_id=".$_data['stu_id_'.$k];
						$this->update($stu, $where);
					}
					

				$this->_name = 'rms_student';

					$group_detail = $this->getGroupDetail($_data['to_group']);
					$idss=explode(',', $_data['identity']);
					foreach ($idss as $j){
						$array=array(
								'session'		=>$group_detail['session'],
								'degree'		=>$group_detail['degree'],
								'grade'			=>$group_detail['grade'],
								'academic_year'	=>$group_detail['academic_year'],
								);
						$where = " stu_id=".$_data['stu_id_'.$j];
						$this->update($array, $where);
					}
					
					$this->_name='rms_group_detail_student';
					$ids=explode(',', $_data['identity']);
					foreach ($ids as $i){
						$arr=array(
								'group_id'	=>$_data['to_group'],
								'stu_id'	=>$_data['stu_id_'.$i],
								'user_id'	=>$this->getUserId(),
								'status'	=>1,
								'date'		=>date('Y-m-d'),
								'type'		=>1,
								'old_group'	=>$_data['from_group'],
						);
						$this->insert($arr);
					}
					
					
				$this->_name = 'rms_group';
					$group=array(
							'is_use'	=>0
							);
					$where=" id=".$_data['from_group'];
					$this->update($group, $where);
					
				return $_db->commit();
					
			}catch(Exception $e){
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
	}
	
	function getGroupDetail($group_id){
		$db = $this->getAdapter();
		$sql="select academic_year,grade,session,degree from rms_group where rms_group.id=".$group_id;
		return $db->fetchRow($sql);
		
	}
	
	
	public function updateStudentChangeGroup($_data,$id){
// 		print_r($_data);exit();
		try{	
			$_arr=array(
						'user_id'=>$this->getUserId(),
						'from_group'=>$_data['from_group'],
						'to_group'=>$_data['to_group'],
						'moving_date'=>$_data['moving_date'],
						'note'=>$_data['note'],
						'status'=>$_data['status']
					);
			$where=" id = ".$id;
			$this->update($_arr, $where);
			
		}catch(Exception $e){
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	
	
	function getAllStudentFromGroup($from_group){
		$db=$this->getAdapter();
		$sql="select gds.stu_id as stu_id,st.stu_enname,st.stu_khname,st.stu_code,
			 (select name_en from rms_view where rms_view.type=2 and rms_view.key_code=st.sex) as sex
			 from rms_group_detail_student as gds,rms_student as st where is_pass=0 and gds.stu_id=st.stu_id and gds.group_id=$from_group";
		return $db->fetchAll($sql);
	}
	
	
	function getAllStudentFromGroupUpdate($from_group){
		$db=$this->getAdapter();
		$sql="select gds.stu_id as stu_id,st.stu_enname,st.stu_khname,st.stu_code,
		(select name_en from rms_view where rms_view.type=2 and rms_view.key_code=st.sex) as sex
		from rms_group_detail_student as gds,rms_student as st where gds.stu_id=st.stu_id and gds.group_id=$from_group";
		return $db->fetchAll($sql);
	}
	
	function getGroupStudentChangeGroup1ById($id,$type){
		$db = $this->getAdapter();
		$sql = "SELECT start_date,expired_date,
		(select CONCAT(from_academic,'-',to_academic,'(',generation,')') from rms_tuitionfee where rms_tuitionfee.id=rms_group.academic_year )AS year ,
		(select major_enname from `rms_major` where `rms_major`.`major_id`=`rms_group`.`grade`)AS grade,
		(select en_name from rms_dept where rms_dept.dept_id=rms_group.degree) as degree,
		(select name_en from `rms_view` where `rms_view`.`type`=4 and `rms_view`.`key_code`=`rms_group`.`session`)AS session
		FROM `rms_group` WHERE  id=$id";
		return $db->fetchRow($sql);
	}
	
	
	
	
	
}

