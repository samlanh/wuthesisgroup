<?php
class Registrar_indexController extends Zend_Controller_Action {	
	
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
       $frm = new Registrar_Form_FrmRegister();
       $frm_register = $frm->FrmRegistarWU();
       Application_Model_Decorator::removeAllDecorator($frm_register);
       $this->view->frm_register = $frm_register;
//        $_marjor = array();
//        array_unshift($_marjor, array ( 'id' => -1, 'name' => 'បន្ថែម​អ្នក​ទទួល​ថ្មី') );
//        $this->view->marjorlist = $_marjor;
       
       $key = new Application_Model_DbTable_DbKeycode();
       $this->view->keycode=$key->getKeyCodeMiniInv(TRUE);
       $model = new Application_Form_FrmGlobal();
        $this->view->footer=$model->getReceiptFooter();
        
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
    
    public function getMajorsAction(){
    	if($this->getRequest()->isPost()){
    		$_data = $this->getRequest()->getPost();
    		$_db = new Application_Model_DbTable_DbGlobal();
    		$major_id=$_db->getMarjorById($_data['dept_id']);
//     		$model = new Application_Model_DbTable_DbGlobal();
//     		$_marjorlist = $model->getMarjorById();
    		print_r(Zend_Json::encode($major_id));
    		exit();
    	}
    }
}
