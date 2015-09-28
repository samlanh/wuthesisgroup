<?php
class Global_ScholarshipController extends Zend_Controller_Action {
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
			$db_dept=new Global_Model_DbTable_DbScholarship();
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$search = array(
						'title' => $_data['title'],
						'note' => $_data['note'],
						'type' => $_data['type'],
						'status' => $_data['status_search']);
			}
			else{
				$search = array(
						'title' => '',
						'note' => '',
						'type' => '',
						'status' => -1);
			}
			$db = new Global_Model_DbTable_DbScholarship();
			$rs_rows= $db->getAllScholarship();
		
			$glClass = new Application_Model_GlobalClass();
			$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
		
			$list = new Application_Form_Frmtable();
			$collumns = array("TITLE","NOTE","TYPE","CREATE_DATE","STATUS","BY_USER");
			$link=array(
					'module'=>'global','controller'=>'scholarship','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('title'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$frm = new Global_Form_FrmSearchMajor();
		$frms =$frm->FrmsearchScholarship();
		Application_Model_Decorator::removeAllDecorator($frms);
		$this->view->frm_search = $frms;
	}
	public function addAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$_dbmodel = new Global_Model_DbTable_DbScholarship();
				$_major_id = $_dbmodel->addNewScholarship($_data);
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​ជោគ​ជ័យ !");
					
			} catch (Exception $e) {
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		
		}
	}
	public function editAction(){
		if($this->getRequest()->isPost())
		{
			try{
				$data = $this->getRequest()->getPost();
				$db = new Global_Model_DbTable_DbScholarship();
				$db->updateScholarship($data);
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/scholarship/index");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("EDIT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$id=$this->getRequest()->getParam("id");
		$db = new Global_Model_DbTable_DbScholarship();
		$this->view->rs=$db->getScholarshipById($id);
	}
	
}

