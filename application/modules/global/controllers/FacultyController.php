<?php
class Global_FacultyController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	
    public function indexAction()
    {
    	$db_dept=new Global_Model_DbTable_DbDept();
    	if($this->getRequest()->isPost()){
    		$_data=$this->getRequest()->getPost();
    		$search = array(
    				'title' => $_data['title'],
    				'status' => $_data['status_search']);
    		$limit = $dept_session->limit;
    	}
    	else{
    		$search='';
    	}
    	
        $rs_rows = $db_dept->getAllFacultyList($search);
        $glClass = new Application_Model_GlobalClass();
        $rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
        
    	$list = new Application_Form_Frmtable();
    	$collumns = array("FACULTY_ENNAME","FACULTY_KHNAME","SHORTCUT","MODIFY_DATE","STATUS","BY_USER");
    	$link=array(
    			'module'=>'global','controller'=>'faculty','action'=>'edit',
    	);
    	$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('en_name'=>$link));
    	$frm = new Global_Form_FrmSearchMajor();
    	$frm = $frm->FrmDepartment();
    	Application_Model_Decorator::removeAllDecorator($frm);
    	$this->view->frm_search = $frm;
    	
    	$frm = new Application_Form_FrmOther();
    	$frm =  $frm->FrmAddDept(null);
    	Application_Model_Decorator::removeAllDecorator($frm);
    	$this->view->add_dept = $frm;
    }
    function addAction(){
    	$this->_redirect("/global/faculty");
    }
    public function editAction(){
    	if($this->getRequest()->isPost()){
    		try {
    			$_data = $this->getRequest()->getPost();
    			$_dbmodel = new Application_Model_DbTable_DbDept();
    			$_dbmodel->UpdateDepartment($_data);
    			Application_Form_FrmMessage::Sucessfull("ការកៃប្រែដោយជោគជ័យ !", "/global/faculty/index");
    			//$this->_redirect("");
    		} catch (Exception $e) {
    			$err =$e->getMessage();
    			Application_Form_FrmMessage::message("Application Error!");
    			Application_Model_DbTable_DbUserLog::writeMessageError($err);
    			echo $e->getMessage();exit();
    		}
    	}
    	$id= $this->getRequest()->getParam("id");
    	$_db = new Application_Model_DbTable_DbGlobal();
    	$_row =$_db->getDeptById($id);
    	$frm = new Application_Form_FrmOther();
    	$frm->FrmAddDept($_row);
    	Application_Model_Decorator::removeAllDecorator($frm);
    	$this->view->frm_dept = $frm;
    }
    function addfacultyAction(){
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$db = new Application_Model_DbTable_DbDept();
    		$faculty_id = $db->AddNewFaculty($data);
    		print_r(Zend_Json::encode($faculty_id));
    		exit();
    		
    	}
    }
  
}

