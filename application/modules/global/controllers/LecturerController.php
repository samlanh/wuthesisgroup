<?php
class Global_LecturerController extends Zend_Controller_Action {
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
			$db = new Global_Model_DbTable_DbTeacher();
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
			$rs_rows= $db->getAllTeacher($search);
			$glClass = new Application_Model_GlobalClass();
			$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
	
			$list = new Application_Form_Frmtable();
			$collumns = array("TEACHER_KH_NAME","TEACHER_EN_NAME","sex","phone","degree","STATUS","BY_USER");
			 
			$link=array(
					'module'=>'global','controller'=>'lecturer','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('teacher_name_kh'=>$link,'teacher_name_en'=>$link));
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
	function addAction()
	{
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$_dbmodel = new Global_Model_DbTable_DbTeacher();
				$_major_id = $_dbmodel->AddNewTeacher($_data);
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​ជោគ​ជ័យ !");
				 
			} catch (Exception $e) {
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$tsub=new Global_Form_FrmTeacher();
		$frm_techer=$tsub->FrmTecher();
		Application_Model_Decorator::removeAllDecorator($frm_techer);
		$this->view->frm_techer = $frm_techer;
	}
	public function editAction()
	{
		$id=$this->getRequest()->getParam("id");
		$db=new Global_Model_DbTable_DbTeacher();
		$row=$db->getTeacherById($id);
		if($this->getRequest()->isPost())
		{
			try{
				$data = $this->getRequest()->getPost();
				$data["id"]=$id;
				$db = new Global_Model_DbTable_DbTeacher();
				$db->updateTeacher($data);
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/lecturer/index");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("EDIT_FAIL");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$obj=new Global_Form_FrmTeacher();
		$frm_update=$obj->FrmTecher($row);
		$this->view->update_teacher=$frm_update;
		Application_Model_Decorator::removeAllDecorator($frm_update);
	}
}

