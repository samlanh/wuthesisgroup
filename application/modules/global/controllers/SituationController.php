<?php
class Global_SituationController extends Zend_Controller_Action {
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
			$db_dept=new Global_Model_DbTable_DbSituation();
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$search = array(
						'situ_name' => $_data['situ_name'],
						'status' => $_data['status_search']);
			}
			else{
				$search = array(
						'situ_name' => '',
						'status' => -1);
			}
			$db = new Global_Model_DbTable_DbSituation();
			$rs_rows= $db->getAllSituations();
		
			$glClass = new Application_Model_GlobalClass();
			$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
		
			$list = new Application_Form_Frmtable();
			$collumns = array("SITU_NAME","CREATE_DATE","STATUS","BY_USER");
			$link=array(
					'module'=>'global','controller'=>'situation','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('situ_name'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$frm = new Global_Form_FrmSearchMajor();
		$frms =$frm->FrmsearchSituation();
		Application_Model_Decorator::removeAllDecorator($frms);
		$this->view->frm_search = $frms;
	}
	public function addAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$_dbmodel = new Global_Model_DbTable_DbSituation();
				$_major_id = $_dbmodel->addNewSituation($_data);
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
				$db = new Global_Model_DbTable_DbSituation();
				$db->updateSituation($data);
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/situation/index");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("EDIT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$id=$this->getRequest()->getParam("id");
		$db = new Global_Model_DbTable_DbSituation();
		$this->view->rs=$db->getSituationById($id);
	}
	
}

