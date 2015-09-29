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
				//set value for display
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
// 			if(!empty($teacher)){
// 				foreach ($teacher[0] as $i => $rs) {
// // 					$result[$i] = array(
// // 							'id' 	  	   => $rs['id'],
// // 							'num' 	  	   => (++$row_num),
// // 							'stu_khname' => $rs['stu_khname'],
// // 							'stu_enname' => $rs['stu_enname'],
// // 							'sex' => $rs['sex'],
// // 							'stu_card' => $rs['stu_card'],
// // 							'dob' => $rs['dob'],
// // 							'phone' => $rs['phone'],
// // 							'degree'  => Application_Model_DbTable_DbGlobal::getAllDegreeById($rs["degree"]),
// // 							'major_id'=>$rs["major_name"],
// // 							'session'  => Application_Model_DbTable_DbGlobal::getSessionById($rs["session"]),
// // 							'status'  => $this->activelist[$rs["status"]],
// // 							'create_date'=>$rs["create_date"],
// // 							'user_name'  => $rs["user_name"],
// // 					);
// 				}
// 			}
// 			else{
// 				$result = Application_Model_DbTable_DbGlobal::getResultWarning();
// 			}
			}catch (Exception $e){
			echo $e->getMessage();exit();
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		
		    $list = new Application_Form_Frmtable();
			$collumns = array("EN_PROVINCE","KH_PROVINCE","MODIFY_DATE","STATUS","BY_USER","EN_PROVINCE","KH_PROVINCE","MODIFY_DATE","STATUS","BY_USER");
			$link=array(
					'module'=>'global','controller'=>'province','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rows,array('province_kh_name'=>$link,'province_en_name'=>$link));
		
		
		$frm = new Application_Form_FrmOther();
		$this->view->add_major = $frm->FrmAddMajor(null);
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
   	 

}
