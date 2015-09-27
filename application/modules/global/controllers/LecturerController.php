<?php
class Global_LecturerController extends Zend_Controller_Action {
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	
	public function indexAction(){
		try{
			$db = new Global_Model_DbTable_DbTeacher();
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$search = array(
						'title' => $_data['title']);
			}
			else{
				$search = array(
						'title' => '');
			}
			$rs_rows= $db->getAllTeacher($search);
			$list = new Application_Form_Frmtable();
			$collumns = array("CODE","TEACHER_KH_NAME","TEACHER_EN_NAME","sex","phone","email","degree","STATUS","BY_USER");
			 
			$link=array(
					'module'=>'global','controller'=>'lecturer','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('teacher_code'=>$link,'teacher_name_kh'=>$link,'teacher_name_en'=>$link));
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
				if(!empty($_data['save_close'])){
					Application_Form_FrmMessage::Sucessfull("ការ​បញ្ចូល​ជោគ​ជ័យ !", '/global/lecturer');
				}
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
		$db = new Application_Model_GlobalClass();
		$this->view->subject_opt = $db->getsunjectOption();
		
	}
	public function editAction()
	{
		$id=$this->getRequest()->getParam("id");
		if($this->getRequest()->isPost())
		{
			try{
				$data = $this->getRequest()->getPost();
				$data["id"]=$id;
				$db = new Global_Model_DbTable_DbTeacher();
				$db->updateTeacher($data);
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/lecturer");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("EDIT_FAIL");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}

		$db=new Global_Model_DbTable_DbTeacher();
		$row=$db->getTeacherById($id);
		
		$tsub=new Global_Form_FrmTeacher();
		$frm_techer=$tsub->FrmTecher($row);
		Application_Model_Decorator::removeAllDecorator($frm_techer);
		$this->view->frm_techer = $frm_techer;
		$dbs = new Application_Model_GlobalClass();
		$this->view->subject_opt = $dbs->getsunjectOption();
		
		$this->view->teacher_subject = $db->getallSubjectTeacherById($id);
	}
	function addteacherAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Global_Model_DbTable_DbTeacher();
			$id = $db->addTeacherSubject($data);
			print_r(Zend_Json::encode($id));
			exit();
		}
	}
}

