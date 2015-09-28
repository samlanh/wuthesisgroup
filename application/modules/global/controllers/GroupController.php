<?php
class Global_GroupController extends Zend_Controller_Action {
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
						'title' => $_data['title']);
			}
			else{
				$search = array(
						'title' => '');
			}
			$db = new Global_Model_DbTable_DbGroup();
			$rs_rows= $db->getAllGroup($search);
			$list = new Application_Form_Frmtable();
			
			$collumns = array("GROUP CODE","DEGREE","FACULTY","BATCH","YEAR","SEMESTER","SESSION","ACADEMIC","ROOM","AMOUNT","STATUS");
			
			$link=array(
					'module'=>'global','controller'=>'group','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('group_code'=>$link,'degree'=>$link,'major_name'=>$link));
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
	function addAction(){
		if($this->getRequest()->isPost()){
			try {
				$data = $this->getRequest()->getPost();
				$db= new Global_Model_DbTable_DbGroup();
				$db->AddNewGroup($data);
				if(!empty($data['save_close'])){
					Application_Form_FrmMessage::Sucessfull("ការ​បញ្ចូល​ជោគ​ជ័យ !", '/global/group');
				}
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​ជោគ​ជ័យ !");
			} catch (Exception $e) {
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		
	 $db = new Application_Model_DbTable_DbGlobal();
	 $this->view->degree = $db->getAllDegree();	
	$faculty =  $db->getAllMajor();
	 array_unshift($faculty, Array('id'=> -1 ,'name' =>'Add New'));
	 $this->view->faculty =$faculty;
	 
	 $room = $db->getAllRoom();
	 array_unshift($room, Array('id'=> -1 ,'name' =>'Add New'));
	 $this->view->room =$room;
	
	 
	 $db = new Application_Model_GlobalClass();
	 $this->view->subject_opt = $db->getTeachersunjectOption();
	 
	 $tsub=new Global_Form_FrmTeacher();
	 $frm_techer=$tsub->FrmTecher();
	 Application_Model_Decorator::removeAllDecorator($frm_techer);
	 $this->view->frm_techer = $frm_techer;
	}
	function editAction(){
		$db= new Global_Model_DbTable_DbGroup();
		
		if($this->getRequest()->isPost()){
			try {
				$data = $this->getRequest()->getPost();
				
				$db->AddNewGroup($data);
				if(!empty($data['save_close'])){
					Application_Form_FrmMessage::Sucessfull("ការ​បញ្ចូល​ជោគ​ជ័យ !", '/global/group');
				}
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​ជោគ​ជ័យ !");
			} catch (Exception $e) {
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		
		$id=$this->getRequest()->getParam("id");
		$this->view->row = $db->getGroupById($id);
		
		$db = new Application_Model_DbTable_DbGlobal();
		$this->view->degree = $db->getAllDegree();
		$faculty =  $db->getAllMajor();
		array_unshift($faculty, Array('id'=> -1 ,'name' =>'Add New'));
		$this->view->faculty =$faculty;
	
		$room = $db->getAllRoom();
		array_unshift($room, Array('id'=> -1 ,'name' =>'Add New'));
		$this->view->room =$room;
	
		$db = new Application_Model_GlobalClass();
		$this->view->subject_opt = $db->getTeachersunjectOption();
	
		$tsub=new Global_Form_FrmTeacher();
		$frm_techer=$tsub->FrmTecher();
		Application_Model_Decorator::removeAllDecorator($frm_techer);
		$this->view->frm_techer = $frm_techer;
	}
}

