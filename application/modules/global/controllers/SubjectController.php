<?php
class Global_SubjectController extends Zend_Controller_Action {
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
	public function indexAction(){
		try{
			$db = new Global_Model_DbTable_DbSubjectExam();
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$search = array(
						'title' => $_data['title'],
						'subjec_name'=>$_data['subjec_name'],
						'status' => $_data['status_search']);
			}
			else{
				$search = array(
						'title' => '',
						'subjec_name'=>-1,
						'status' => -1);
			}
			$rs_rows = $db->getAllSujectName($search);
			$glClass = new Application_Model_GlobalClass();
			$rs = $glClass->getImgActive($rs_rows, BASE_URL, true);
			 
			 
			$list = new Application_Form_Frmtable();
			$collumns = array("SRMS_SUBJECT_EXAM_LIST","MODIFY_DATE","STATUS","BY_USER");
			$link=array(
					'module'=>'global','controller'=>'subject','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs,array('subj_exam_name'=>$link));
	
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$frm = new Global_Form_FrmSearchMajor();
		$frm =$frm->SubjectExam();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_search = $frm;
	}
	function addAction()
	{
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$_dbmodel = new Global_Model_DbTable_DbSubjectExam();
				$_dbmodel->addNewSubjectExam($_data);
				Application_Form_FrmMessage::message("INSERT_SUCCESS");
			} catch (Exception $e) {
				Application_Form_FrmMessage::message("INSERT_FAIL");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$subject_exam=new Global_Form_FrmAddSubjectExam();
		$frm_subject_exam=$subject_exam->FrmAddSubjectExam();
		Application_Model_Decorator::removeAllDecorator($frm_subject_exam);
		$this->view->frm_subject_exam = $frm_subject_exam;
	}
	function editAction()
	{
		$id = $this->getRequest()->getParam("id");
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$data["id"]=$id;
				$_dbmodel = new Global_Model_DbTable_DbSubjectExam();
				$_dbmodel->updateSubjectExam($_data,$id);
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/subject/index");
			} catch (Exception $e) {
				Application_Form_FrmMessage::message("INSERT_FAIL");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$_dbmoddel = new Global_Model_DbTable_DbSubjectExam();
		$row = $_dbmoddel->getSubexamById($id);
		$subject_exam=new Global_Form_FrmAddSubjectExam();
		$frm_subject_exam=$subject_exam->FrmAddSubjectExam($row);
		Application_Model_Decorator::removeAllDecorator($frm_subject_exam);
		$this->view->frm_subject_exam = $frm_subject_exam;
	}
}

