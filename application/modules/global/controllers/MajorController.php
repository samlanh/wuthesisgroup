<?php
class Global_MajorController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	
	
    public function indexAction()
    {
    	try{
	    	$_model = new Global_Model_DbTable_DbDept();
	    	if($this->getRequest()->isPost()){
	    		$_data=$this->getRequest()->getPost();
	    		
	    		//set value for display
		    	$search = array(
	    				'txtsearch' => $_data['title'],
		    			'title' => $_data['title'],
		    			'status' => $_data['status_search']
	    		);
	    		
    	   }
    	   else{
    		$search='';
    	   }
	    	$rs_rows= $_model->getAllMajorList($search);
	    
			$glClass = new Application_Model_GlobalClass();
	    	$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
	    	$list = new Application_Form_Frmtable();
	    	$collumns = array("PROGRAM_TITLE","TYPE","DISCRIPTION","SHORTCUT","MODIFY_DATE","STATUS","BY_USER");
	    	$link=array(
	    			'module'=>'global','controller'=>'major','action'=>'edit',
	    	);
	    	$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('major_enname'=>$link,'major_khname'=>$link));
	    	
	    	
	    	
	    	
    	}catch (Exception $e){
    		Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    	}
    	$frm = new Application_Form_FrmOther();
    	$frm_major = $frm->FrmAddMajor();
    	Application_Model_Decorator::removeAllDecorator($frm_major);
    	$this->view->add_major = $frm_major;
    	
    	$frm_search = new Global_Form_FrmSearchMajor();
    	$frm_search = $frm_search->FrmMajors();
    	Application_Model_Decorator::removeAllDecorator($frm_search);
    	$this->view->frm_search = $frm_search;
    	
    	
    	$frm_faculty=$frm->FrmAddDept(null);
    	Application_Model_Decorator::removeAllDecorator($frm_faculty);
    	$this->view->add_faculty =$frm_faculty;
    }
    public function addAction(){
    	if($this->getRequest()->isPost()){
    		$_data = $this->getRequest()->getPost();
    		try {
    			$_dbmodel = new Application_Model_DbTable_DbDept();
    			$_major_id = $_dbmodel->AddNewMajor($_data);
    			unset($formdata);
    			$return =  array('major_id' => $_major_id,"succ"=>"ការបញ្ចូលដោយជោគជ័យ");
    			print_r(Zend_Json::encode($return));
    			exit();
    		} catch (Exception $e) {
    			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    		}
    			
    	}
    }
    public function editAction(){
    	$id = $this->getRequest()->getParam("id");
    	if($this->getRequest()->isPost()){
    		try{
    		$_data = $this->getRequest()->getPost();
    		$_dbmodel = new Global_Model_DbTable_DbDept();
    		$_dbmodel ->updatMajorById($_data);
    		Application_Form_FrmMessage::Sucessfull("ការកែប្រែដោយជោគជ័យ", "/global/major/index");
    		}catch(Exception $e){
    			Application_Form_FrmMessage::message("Application Error");
    			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    		}
    	}
    	if(!empty($id)){
    		$frm = new Application_Form_FrmOther();
    		$db_model = new Global_Model_DbTable_DbDept();
    		$row=$db_model->getMajorById($id);
    		$frm->FrmAddMajor($row);
    		Application_Model_Decorator::removeAllDecorator($frm);
    		$this->view->frm_major = $frm;
    	}else{
    		
    	}
    }
    function addmajorAction(){
    	if($this->getRequest()->isPost()){
    			$_data = $this->getRequest()->getPost();
    			$_dbmodel = new Global_Model_DbTable_DbDept();
    			$_data['dept_id']=$_data['facultyid'];
    			$_data['major_enname']=$_data['major_en'];
    			$_data['major_khname']=$_data['major_kh'];
    			$_data['shortcut']=$_data['shortcut'];
    			$_data['status']=1;
    			$_id =$_dbmodel->AddNewMajor($_data);
    			print_r(Zend_Json::encode($_id));
    			exit();
    	}
    }
 
}

