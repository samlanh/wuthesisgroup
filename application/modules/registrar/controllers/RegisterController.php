<?php
class Registrar_RegisterController extends Zend_Controller_Action {
	
	
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	
	}

    public function indexAction()
    {
        
    }
    public function addAction()
    {
      if($this->getRequest()->isPost()){
      	$_data = $this->getRequest()->getPost();
      	$_model = new Registrar_Model_DbTable_DbwuRegister();
      	//print_r($_data);exit();
      	$_model->AddNewStudent($_data);
      }
       $frm = new Registrar_Form_FrmRegister();
       $frm_register=$frm->FrmRegistarWU();
       Application_Model_Decorator::removeAllDecorator($frm_register);
       $this->view->frm_register = $frm_register;
//        $_marjor =array();
//        $this->view->marjorlist = $_marjor;
//        $model = new Application_Model_DbTable_DbGlobal();
//        $_marjorlist = $model->getMarjorById();
       
//        $this->view->marjorlist = $_marjorlist;
       
       $key = new Application_Model_DbTable_DbKeycode();
       $this->view->keycode=$key->getKeyCodeMiniInv(TRUE);
       $model = new Application_Form_FrmGlobal();
       $this->view->footer=$model->getReceiptFooter();
       
       $this->view->invoice_no = Application_Model_GlobalClass::getInvoiceNo();
      // echo Application_Model_GlobalClass::getInvoiceNo();
       
       $__student_card = array();
       $this->view->student_card = $__student_card;
       $db = new Registrar_Model_DbTable_DbwuRegister();
       $this->view->invoice_num = $db->getGaneratInvoiceWU();
      // echo $db->getGaneratInvoiceWU();
    }
    public function wuReceiptAction()
    {
    	$frm = new Registrar_Form_FrmRecept();
    	$frm_recept=$frm->FrmRecept();
    	Application_Model_Decorator::removeAllDecorator($frm_recept);
    	$this->view->frm_recept = $frm_recept;
    	//$key = new Application_Model_DbTable_DbKeycode();
    	//$this->view->keycode=$key->getKeyCodeMiniInv(TRUE);
    }
    public function getStudentinfoallAction(){
    	if($this->getRequest()->isPost()){
    		$_db = new Registrar_Model_DbTable_DbGetStudentInfo();
    		$_rs_student = $_db->getAllStudent();
    		print_r(Zend_Json::encode($_rs_student));
    		exit();
    	}
    }
   
}
