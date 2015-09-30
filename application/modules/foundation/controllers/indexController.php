<?php 
class Foundation_indexController extends Zend_Controller_Action {
	
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function start(){
		return ($this->getRequest()->getParam('limit_satrt',0));
	}
    function indexAction(){
		try{
			$start = $this->start();
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$search = array(
						'txtsearch' => '',
						'title' => $_data['title'],
						'status' => $_data['status_search'],
						'subjec_name'=>$_data['subjec_name'],
				);
			}
			else{
					$search = array(
						'txtsearch' => '',
						'title' =>'',
						'status' =>-1,
						'subjec_name'=>'',
				);
			}
			$db = new Foundation_Model_DbTable_DbNewStudent();
			$rows= $db->getAllStudent($search);
			}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		
		    $list = new Application_Form_Frmtable();
			$collumns = array("NAME_KH","NAME_EN","SEX","DOB","PHONE","DEGREE","FACULTIES","BATCH","YEAR","SEMESTER","SESSION","STATUS","BY_USER");
			$link=array(
					'module'=>'foundation','controller'=>'index','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rows,array('stu_khname'=>$link,'stu_enname'=>$link));
		
		$frm = new Global_Form_FrmSearchMajor();
		$frm = $frm->frmSearchTeacher();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_search = $frm;
	}
	function addAction(){
		$_model =new Foundation_Model_DbTable_DbNewStudent();
		if($this->getRequest()->isPost()){
			try{
			$_data = $this->getRequest()->getPost();
			
			$_model->addNewStudent($_data);
			if(!empty($_data['save_close'])){
				Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/foundation/index");
			}
				Application_Form_FrmMessage::message("INSERT_SUCCESS");
			}catch (Exception $e){
				Application_Form_FrmMessage::message("INSERT_FAIL");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
			$_frm = new Foundation_Form_FrmStudent();
			$_frmstudent=$_frm->FrmStudent();
			Application_Model_Decorator::removeAllDecorator($_frmstudent);
			$this->view->frm_student = $_frmstudent;
			$_db = new Application_Model_DbTable_DbGlobal();
			
			$comp = $_db->getallComposition();
			array_unshift($comp, array ( 'id' => -1, 'name' => 'បន្ថែម​អ្នក​ទទួល​ថ្មី') );
			$this->view->compo=$comp;
			
			$situation = $_db->getallSituation();
			array_unshift($situation, array ( 'id' => -1, 'name' => 'បន្ថែម​អ្នក​ទទួល​ថ្មី') );
			$this->view->situation=$situation;
			
			$school = $_db->getAllHighSchool();
			array_unshift($school, array ( 'id' => -1, 'name' => 'បន្ថែម​អ្នក​ទទួល​ថ្មី','province_id'=>-1) );
			$this->view->highschool=$school;
			
			$scholarship = $_db->getallScholarship();
			array_unshift($scholarship, array ( 'id' => -1, 'name' => 'បន្ថែម​អ្នក​ទទួល​ថ្មី','province_id'=>-1) );
			$this->view->scholarship=$scholarship;
			
		}
		function editAction(){
			$_model =new Foundation_Model_DbTable_DbNewStudent();
			if($this->getRequest()->isPost()){
				try{
					$_data = $this->getRequest()->getPost();
						
					$_model->addNewStudent($_data);
					if(!empty($_data['save_close'])){
						Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/foundation/index");
					}
					Application_Form_FrmMessage::message("INSERT_SUCCESS");
				}catch (Exception $e){
					Application_Form_FrmMessage::message("INSERT_FAIL");
					$err =$e->getMessage();
					Application_Model_DbTable_DbUserLog::writeMessageError($err);
				}
			}
			$_frm = new Foundation_Form_FrmStudent();
			$_frmstudent=$_frm->FrmStudent();
			Application_Model_Decorator::removeAllDecorator($_frmstudent);
			$this->view->frm_student = $_frmstudent;
			$_db = new Application_Model_DbTable_DbGlobal();
				
			$comp = $_db->getallComposition();
			array_unshift($comp, array ( 'id' => -1, 'name' => 'បន្ថែម​អ្នក​ទទួល​ថ្មី') );
			$this->view->compo=$comp;
				
			$situation = $_db->getallSituation();
			array_unshift($situation, array ( 'id' => -1, 'name' => 'បន្ថែម​អ្នក​ទទួល​ថ្មី') );
			$this->view->situation=$situation;
				
			$school = $_db->getAllHighSchool();
			array_unshift($school, array ( 'id' => -1, 'name' => 'បន្ថែម​អ្នក​ទទួល​ថ្មី','province_id'=>-1) );
			$this->view->highschool=$school;
				
			$scholarship = $_db->getallScholarship();
			array_unshift($scholarship, array ( 'id' => -1, 'name' => 'បន្ថែម​អ្នក​ទទួល​ថ្មី','province_id'=>-1) );
			$this->view->scholarship=$scholarship;
				
		}
 		public function getMajorsAction(){
    	if($this->getRequest()->isPost()){
    		$_data = $this->getRequest()->getPost();
    		$_db = new Application_Model_DbTable_DbGlobal();
    		$majors=$_db->getMarjorById($_data['dept_id']);
    		array_unshift($majors, array ( 'id' => -1, 'name' => 'បន្ថែម​អ្នក​ទទួល​ថ្មី') );
    		print_r(Zend_Json::encode($majors));
    		exit();
    	}
   	 }
   	 public function getSchoolAction(){
   	 	if($this->getRequest()->isPost()){
   	 		$_data = $this->getRequest()->getPost();
   	 		$_db = new Application_Model_DbTable_DbGlobal();
   	 		$school = $_db->getAllHighSchool($_data['province_id']);
   	 		array_unshift($school, array ( 'id' => -1, 'name' => 'បន្ថែម​អ្នក​ទទួល​ថ្មី') );
   	 		print_r(Zend_Json::encode($school));
   	 		exit();
   	 	}
   	 }
   	 public function addSchoolAction(){
   	 	if($this->getRequest()->isPost()){
   	 		$_data = $this->getRequest()->getPost();
   	 		$_dbmodel = new Global_Model_DbTable_DbHightschool();
			$id = $_dbmodel->addNewschool($_data);
   	 		//array_unshift($school, array ( 'id' => -1, 'name' => 'បន្ថែម​អ្នក​ទទួល​ថ្មី') );
   	 		print_r(Zend_Json::encode($id));
   	 		exit();
   	 	}
   	 }
   	 public function addScholarshipAction(){
   	 	if($this->getRequest()->isPost()){
   	 		$_data = $this->getRequest()->getPost();
   	 			$_dbmodel = new Global_Model_DbTable_DbScholarship();
				$id = $_dbmodel->ajaxaddScholarship($_data);
   	 		print_r(Zend_Json::encode($id));
   	 		exit();
   	 	}
   	 }
   	 
   	 

}
