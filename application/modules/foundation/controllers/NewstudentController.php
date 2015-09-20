<?php
class Foundation_NewstudentController extends Zend_Controller_Action {
	
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
    public function init()
    {    	
    	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function start(){
		return ($this->getRequest()->getParam('limit_satrt',0));
	}
	public function indexAction(){
		try{
			$newstudent_session=Application_Model_DbTable_DbGlobal::SessionNavigetor('new-student');
			if(empty($newstudent_session->limit)){
				$newstudent_session->limit =  Application_Form_FrmNavigation::getLimit();
				$newstudent_session->lock();
			}
			$limit = $newstudent_session->limit;
			$start = $this->start();
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$newstudent_session->unlock();
				$newstudent_session->limit =  $_data['rows_per_page'];
				$newstudent_session->lock();
					
				//set value for display
				$search = array(
						'txtsearch' => $newstudent_session->txtsearch,
						'title' => $_data['title'],
						'status' => $_data['status_search'],
						'subjec_name'=>$_data['subjec_name'],
				);
				$limit = $newstudent_session->limit;
			}
			else{
				$search='';
			}
			$db = new Foundation_Model_DbTable_DbNewStudent();
			$teacher= $db->getAllNewStudent($search, $start, $limit);
			$record_count=$teacher[1];
			$row_num = $start;
			if(!empty($teacher)){
				foreach ($teacher[0] as $i => $rs) {
					$result[$i] = array(
							'id' 	  	   => $rs['id'],
							'num' 	  	   => (++$row_num),
							'stu_khname' => $rs['stu_khname'],
							'stu_enname' => $rs['stu_enname'],
							'sex' => $rs['sex'],
							'stu_card' => $rs['stu_card'],
							'dob' => $rs['dob'],
							'phone' => $rs['phone'],
							'degree'  => Application_Model_DbTable_DbGlobal::getAllDegreeById($rs["degree"]),
							'major_id'=>$rs["major_name"],
							'session'  => Application_Model_DbTable_DbGlobal::getSessionById($rs["session"]),
							'status'  => $this->activelist[$rs["status"]],
							'create_date'=>$rs["create_date"],
							'user_name'  => $rs["user_name"],
					);
				}
			}
			else{
				$result = Application_Model_DbTable_DbGlobal::getResultWarning();
			}
			$gride  = new Application_Form_Frmlist();
			$collumn = array("NAME_KH","NAME_EN","SEX","ID_NUMBER","DOB","PHONE","DEGREE","MAJORS","SESSION","STATUS","CREATED_DATE","BY_USER");
			$this->view->grideview = $gride->grideView(BASE_URL."/foundation/newstudent/edit-student","/foundation/newstudent/index",$collumn,@$result,$start,$limit,$record_count);
	
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$frm = new Application_Form_FrmOther();
		$this->view->add_major = $frm->FrmAddMajor(null);
		$frm = new Global_Form_FrmSearchMajor();
		$frm = $frm->frmSearchTeacher();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_search = $frm;
	}
	
function editStudentAction(){
	$id = $this->getRequest()->getParam("id");
	    if($this->getRequest()->isPost()){
	    	$_data = $this->getRequest()->getPost();
	    	$_model =new Foundation_Model_DbTable_DbNewStudent();
	    	//print_r($_data);exit();
	    	$_model->updateStudentINfo($_data,$id);
	    }
	    if(!empty($id)){
	    	$_model = new Foundation_Model_DbTable_DbNewStudent();
	    	$row = $_model->getStudentInfoById($id);
	    	$_frm = new Foundation_Form_FrmStudent();
	    	$_frmstudent=$_frm->FrmStudent($row);
	    	Application_Model_Decorator::removeAllDecorator($_frmstudent);
	    	$this->view->frm_student = $_frmstudent;
	    }
	    else{
	    	Application_Form_FrmMessage::Sucessfull("GET_STUDNET_INFO_FAILED", "/foundation/newstudent/index");
	    }
	}
	
	

}
