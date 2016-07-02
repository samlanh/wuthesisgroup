
<?php
class Foundation_StudentGroupController extends Zend_Controller_Action {
	
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
			$db = new Foundation_Model_DbTable_DbGroup();
			
			if($this->getRequest()->isPost()){
				$search=$this->getRequest()->getPost();
				$this->view->adv_search=$search;
			}
			else{
				$search = array(
						'adv_search' => '',
						);
			}
			
			$rs= $db->getGroupDetail($search);
			$list = new Application_Form_Frmtable();
			
			if(!empty($rs)){
			}
			else{
				$result = Application_Model_DbTable_DbGlobal::getResultWarning();
			}
			$collumns = array("GROUP_ID","YEARS","SEMESTER","DEGREE","GRADE","SESSION","ROOM_NAME","START_DATE","END_DATE","NOTE","STATUS","AMOUNT_STUDENT");
			$link=array(
					'module'=>'foundation','controller'=>'studentgroup','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs,array('group_code'=>$link,'room_name'=>$link));
	}
	function addAction(){
		$db = new 	Foundation_Model_DbTable_DbStudent();
		try{
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				// print_r($_data);exit();
				$search = array(
						'degree' => $_data['degree'],
						'grade' => $_data['grade'],
						'session' => $_data['session'],
						'academy'=> $_data['academy']);
				
				$rs =$db->getSearchStudent($search);
				$this->view->rs = $rs;
			}else{
				$search = array(
						'degree' => 0,
						'grade' => 0,
						'session' => 0,
						'academy'=> 0);
				//$rs = $db->getSearchStudent($search);
			}
			
			$this->view->value=$search;
	
		}catch(Exception $e){
			Application_Form_FrmMessage::message("APPLICATION_ERROR");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$dbstudent = new Foundation_Model_DbTable_DbStudent();
		$this->view->academy = $dbstudent->getAllYear();
		
		$_db = new Application_Model_DbTable_DbGlobal();
		$this->view->degree = $_db->getAllFecultyName();
		$group = new Foundation_Model_DbTable_DbGroup();
		$group_option = $group->getGroup();
		array_unshift($group_option, array ( 'id' => -1, 'name' => 'បន្ថែមថ្មី') );
		$this->view->group = $group_option;
		$this->view->room = $group->getRoom();
		$db=new Application_Model_DbTable_DbGlobal();
		$this->view->rs_session=$db->getSession();
	}
	public function submitAction(){
		if($this->getRequest()->isPost()){
			try{
				$_data = $this->getRequest()->getPost();
				
				$db = new Foundation_Model_DbTable_DbGroup();
				$db->addStudentGroup($_data);
				Application_Form_FrmMessage::message("INSERT_SUCCESS");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$this->_redirect('/foundation/studentgroup/add');
	}
	
	public function submit1Action(){
		$id=$this->getRequest()->getParam("id");
		if($this->getRequest()->isPost()){
			try{
				$_data = $this->getRequest()->getPost();
				$db = new Foundation_Model_DbTable_DbGroup();
				$row = $db->editStudentGroup($_data, $id);
				
				Application_Form_FrmMessage::message("INSERT_SUCCESS");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$this->_redirect('/foundation/studentgroup/index');
	}
	function editAction(){
		$id=$this->getRequest()->getParam("id");
		$db = new 	Foundation_Model_DbTable_DbStudent();
		$_db = new Foundation_Model_DbTable_DbGroup();
		$g_id = $_db->getGroupById($id);
		
		//print_r($g_id);exit();
		
		$this->view->id = $g_id;
		$row = $_db->getStudentGroup($id);
		$this->view->rr = $row;
			try{
				if($this->getRequest()->isPost()){
					$_data=$this->getRequest()->getPost();
					$_data=$this->getRequest()->getPost();
					$search = array(
							'degree' => $_data['degree'],
							'grade' => $_data['grade'],
							'session' => $_data['session'],
							'academy'=> $_data['academy']);
					$rs =$db->getSearchStudent($search);
					$this->view->rs = $rs;
				}else{
					$search = array(
							'degree' => 0,
							'grade' => 0,
							'session' => 0,
							'academy'=> 0);
				}
			
				$this->view->value=$search;
		
			}catch(Exception $e){
				Application_Form_FrmMessage::message("APPLICATION_ERROR");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		$dbstudent = new Foundation_Model_DbTable_DbStudent();
		$this->view->academy = $dbstudent->getAllYear();
		$_db = new Application_Model_DbTable_DbGlobal();
		$this->view->degree = $_db->getAllFecultyName();
		$group = new Foundation_Model_DbTable_DbGroup();
		$group_option = $group->getGroupToEdit();
		
		//print_r($group_option);exit();
		
		array_unshift($group_option, array ( 'id' => -1, 'name' => 'បន្ថែមថ្មី') );
		$this->view->group = $group_option;
		$this->view->room = $group->getRoom();
		$db=new Application_Model_DbTable_DbGlobal();
		$this->view->rs_session=$db->getSession();
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
	
	function addGroupAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Foundation_Model_DbTable_DbGroup();
			$group = $db->addGroup($data);
			$result = array("id"=>$group);
			print_r(Zend_Json::encode($group));
			exit();
		}
	}

	
}
