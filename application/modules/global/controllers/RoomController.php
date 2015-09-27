<?php
class Global_RoomController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db_dept=new Global_Model_DbTable_DbDept();
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$search = array(
						'title' => $_data['title'],
						'status' => $_data['status_search']);
			}
			else{
				$search = array(
						'title' => '',
						'status' => -1);
			}
			$db = new Global_Model_DbTable_DbRoom();
			$rs_rows= $db->getAllRooms($search);
			 
			$glClass = new Application_Model_GlobalClass();
			$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
			 
			$list = new Application_Form_Frmtable();
			$collumns = array("ROOM_NAME","MODIFY_DATE","STATUS","BY_USER");
			$link=array(
					'module'=>'global','controller'=>'room','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('room_name'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$frm = new Global_Form_FrmSearchMajor();
		$frm =$frm->searchRoom();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_search = $frm;
	}
   function addAction()
   {
   	if($this->getRequest()->isPost()){
   		$_data = $this->getRequest()->getPost();
   		try {
   			$_dbmodel = new Global_Model_DbTable_DbRoom();
   			$_major_id = $_dbmodel->addNewRoom($_data);
   			Application_Form_FrmMessage::message("ការ​បញ្ចូល​ជោគ​ជ័យ !");
   
   		} catch (Exception $e) {
   			Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
   			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
   		}
   
   	}
	   	$classname=new Global_Form_FrmAddClass();
	   	$frm_classname=$classname->FrmAddClass();
	   	Application_Model_Decorator::removeAllDecorator($frm_classname);
	   	$this->view->frm_classname = $frm_classname;
   }
   public function editAction()
   {
	   	$id=$this->getRequest()->getParam("id");
	   	$db = new Global_Model_DbTable_DbRoom();
	   	$row = $db->getRoomById($id);
	   	if($this->getRequest()->isPost())
	   	{
	   		try{
		   		$data = $this->getRequest()->getPost();
		   		$data["id"]=$id;
		   		$db = new Global_Model_DbTable_DbRoom();
		   		$db->updateRoom($data);
		   		Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/room/index");
	   		}catch(Exception $e){
	   			Application_Form_FrmMessage::message("EDIT_FAIL");
	   			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
	   		}
	   	}
	   	$obj=new Global_Form_FrmAddClass();
	   	$frm_room=$obj->FrmAddClass($row);
	   	$this->view->update_room=$frm_room;
	   	Application_Model_Decorator::removeAllDecorator($frm_room);
   }
   function addroomAction()//ajax
   {
   	if($this->getRequest()->isPost()){
   			$_data = $this->getRequest()->getPost();
   			$_dbmodel = new Global_Model_DbTable_DbRoom();
   			$roomid = $_dbmodel->addAjaxRoom($_data);
   			print_r(Zend_Json::encode($roomid));
   			exit();
   	}
   }
}

