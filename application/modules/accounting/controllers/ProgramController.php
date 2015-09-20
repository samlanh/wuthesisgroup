<?php
class Accounting_ProgramController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
	private $type = array(1=>'service',2=>'program');
	public function init()
	{
		header('content-type: text/html; charset=utf8');
		defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function start(){
		return ($this->getRequest()->getParam('limit_satrt',0));
	}
	public function indexAction(){
		try{
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$search = array(
						'title' => $_data['title'],
						'txtsearch' => $_data['title'],
						'cate_name' => $_data['cate_name'],
						'status' => $_data['status_search'],
						'type' => $_data['type']);
			}
			else{
				$search='';
			}
			$db = new Accounting_Model_DbTable_DbProgram();
			$rs_rows = $db->getAllProgramNames($search);
			$list = new Application_Form_Frmtable();
			if(!empty($rs_rows)){
				$glClass = new Application_Model_GlobalClass();
				$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
			}
			else{
				$result = Application_Model_DbTable_DbGlobal::getResultWarning();
			}
			$collumns = array("PROGRAM_TITLE","TYPE","DISCRIPTION","STATUS","MODIFY_DATE","BY_USER");
			$link=array(
					'module'=>'accounting','controller'=>'program','action'=>'edit-program',
			);
			$this->view->list=$list->getCheckList(1, $collumns, $rs_rows,array('cate_name'=>$link,'title'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("APPLICATION_ERROR");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$frm = new Global_Form_FrmSearchMajor();
		$frm = $frm->frmSearchServiceProgram();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_search = $frm;
	}
public function addProgramAction(){
	if($this->getRequest()->isPost()){
			try{
				$_data = $this->getRequest()->getPost();
				$_model = new Accounting_Model_DbTable_DbProgram();
				$_model->addprogram($_data);
				Application_Form_FrmMessage::message("INSERT_SUCCESS");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}	
	$frm=new Accounting_Form_FrmProgram();
	$this->view->frm=$frm->addProgramName();
	Application_Model_Decorator::removeAllDecorator($frm->addProgramName());
}
public function editProgramAction(){
	$id=$this->getRequest()->getParam("id");
	$db = new Accounting_Model_DbTable_DbProgram();
	$row = $db->getProgramById($id);
	if($this->getRequest()->isPost())
	{
		try{
			$data = $this->getRequest()->getPost();
			$data["id"]=$id;
			$db = new Accounting_Model_DbTable_DbProgram();
			$row=$db->updateprogram($data);
			Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/accounting/program/index");
		}catch(Exception $e){
			Application_Form_FrmMessage::message("EDIT_FAIL");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	$obj=new Accounting_Form_FrmProgram();
	$frm=$obj->addProgramName($row);
	$this->view->frm=$frm;
	Application_Model_Decorator::removeAllDecorator($frm);
}
}