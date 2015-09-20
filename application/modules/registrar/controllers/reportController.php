<?php
class Registrar_reportController extends Zend_Controller_Action {
	
	
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	
	}

    public function indexAction()
    {
        
    }
    public function wuRegisterAction()
    {
       $frm = new Registrar_Form_FrmRegister();
       $frm_register=$frm->FrmRegistarWU();
       Application_Model_Decorator::removeAllDecorator($frm_register);
       $this->view->frm_register = $frm_register;
       $key = new Application_Model_DbTable_DbKeycode();
       $this->view->keycode=$key->getKeyCodeMiniInv(TRUE);
       
    }
    public function wuRequestAction()
    {
    	$frm = new Registrar_Form_FrmRegister();
    	$frm_request=$frm->FrmStudentRequest();
    	Application_Model_Decorator::removeAllDecorator($frm_request);
    	$this->view->frm_request = $frm_request;
    	$key = new Application_Model_DbTable_DbKeycode();
    	$this->view->keycode=$key->getKeyCodeMiniInv(TRUE);
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
    public function rptDailyAction()
    {
    
    }
}
