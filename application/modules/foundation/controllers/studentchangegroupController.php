<?php
class Foundation_studentchangegroupController extends Zend_Controller_Action {
	
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$search=array(
				'txtsearch'	=>$data['adv_search'],	
					);
		}else{
			$search=array(
				'txtsearch'	=>'',
			);
		}
		
		
		$db_student= new Foundation_Model_DbTable_DbStudentChangeGroup();
		$rs_rows = $db_student->selectAllStudentChangeGroup($search);
		$list = new Application_Form_Frmtable();
			if(!empty($rs_rows)){
			} 
			else{
				$result = Application_Model_DbTable_DbGlobal::getResultWarning();
			}
			$collumns = array("STUDENT_ID","NAME_KH","NAME_EN","SEX","FROM_GROUP","TO_GROUP","MOVING_DATE","NOTE");
			$link=array(
					'module'=>'foundation','controller'=>'studentchangegroup','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('code'=>$link,'kh_name'=>$link,'en_name'=>$link));
		$this->view->adv_search=$search;	
	}
	function addAction(){
		if($this->getRequest()->isPost()){
			try{
				$_data = $this->getRequest()->getPost();
				$_add = new Foundation_Model_DbTable_DbStudentChangeGroup();
 				$_add->addStudentChangeGroup($_data);
 				if(!empty($_data['save_close'])){
 					Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/foundation/studentchangegroup");
 				}
				Application_Form_FrmMessage::message("INSERT_SUCCESS");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		
		$_add = new Foundation_Model_DbTable_DbStudentChangeGroup();
		
		$this->view->rs = $add =$_add->getAllStudentID();
// 		print_r($this->view->rs);exit();
		$this->view->row = $add =$_add->getAllStudentChangeGroup();
		
// 		$_db = new Application_Model_DbTable_DbGlobal();
// 		$this->view->degree = $rows = $_db->getAllFecultyName();
// 		$this->view->occupation = $row =$_db->getOccupation();
// 		$this->view->province = $row =$_db->getProvince();
	}
	public function editAction(){
		$id=$this->getRequest()->getParam("id");
		$db= new Foundation_Model_DbTable_DbStudentChangeGroup();
		$row = $this->view->rows = $db->getAllStudentChangeGroupById($id);
		
		
		if($this->getRequest()->isPost())
		{
			try{
				$data = $this->getRequest()->getPost();
				$data["id"]=$id;
				$db = new Foundation_Model_DbTable_DbStudentChangeGroup();
				$row=$db->updateStudentChangeGroup($data);
				
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/foundation/studentchangegroup/index");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("EDIT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}	
		
		$_add = new Foundation_Model_DbTable_DbStudentChangeGroup();
		
		$this->view->rs = $add =$_add->getAllStudentID();
		
		$this->view->row = $add =$_add->getAllStudentChangeGroup();
		
		
	}

	function getGradeAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Foundation_Model_DbTable_DbStudent();
			$grade = $db->getAllGrade($data['dept_id']);
			//print_r($grade);exit();
			//array_unshift($makes, array ( 'id' => -1, 'name' => 'បន្ថែមថ្មី') );
			print_r(Zend_Json::encode($grade));
			exit();
		}
	}
	
	function getGroupAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Foundation_Model_DbTable_DbStudentChangeGroup();
			$grade = $db->getStudentChangeGroupById($data['from_group']);
			print_r(Zend_Json::encode($grade));
			exit();
		}
	}
	
	function getToGroupAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Foundation_Model_DbTable_DbStudentChangeGroup();
			$grade = $db->getStudentChangeGroup1ById($data['to_group']);
			print_r(Zend_Json::encode($grade));
			exit();
		}
	}
	
	function getStudentAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Foundation_Model_DbTable_DbStudentChangeGroup();
			$grade = $db->getStudentInfoById($data['studentid']);
			print_r(Zend_Json::encode($grade));
			exit();
		}
	}
	
}
