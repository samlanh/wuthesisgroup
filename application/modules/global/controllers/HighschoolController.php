<?php
class Global_HighschoolController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	
	public function indexAction(){
		try{
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
						'status' => -1,
				);
		
			}
			$db = new Global_Model_DbTable_DbHightschool();
			$rs_rows= $db->getAllHighSchool($search);
		
			$glClass = new Application_Model_GlobalClass();
			$rs = $glClass->getImgActive($rs_rows, BASE_URL, true);
		
			$list = new Application_Form_Frmtable();
			$collumns = array("School Name","KH_PROVINCE","MODIFY_DATE","STATUS","BY_USER");
			$link=array(
					'module'=>'global','controller'=>'highschool','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs,array('school_name'=>$link,'province_name'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$frm = new Global_Form_FrmSearchMajor();
		$frm =$frm->searchProvinnce();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_search = $frm;
		
	}
	function addAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$_dbmodel = new Global_Model_DbTable_DbHightschool();
				$_dbmodel->addSchool($_data);
				if(!empty($_data['save_close'])){
					Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/global/highschool/index");
				}
				Application_Form_FrmMessage::message("INSERT_SUCCESS");
			 }catch (Exception $e) {
				Application_Form_FrmMessage::message("INSERT_FAIL");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$provine=new Global_Form_FrmAddSchool();
		$frm_school=$provine->FrmAddSchool();
		Application_Model_Decorator::removeAllDecorator($frm_school);
		$this->view->frm_school = $frm_school;
	}
	function editAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$_dbmodel = new Global_Model_DbTable_DbHightschool();
				$_dbmodel->updateSchool($_data);
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/highschool/index");
			}catch (Exception $e) {
				Application_Form_FrmMessage::message("INSERT_FAIL");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$id=$this->getRequest()->getParam("id");
		$db=new Global_Model_DbTable_DbHightschool();
		$row=$db->getSchoolById($id);
		$provine=new Global_Form_FrmAddSchool();
		$frm_school=$provine->FrmAddSchool($row);
		Application_Model_Decorator::removeAllDecorator($frm_school);
		$this->view->frm_school = $frm_school;
	}
	
}

