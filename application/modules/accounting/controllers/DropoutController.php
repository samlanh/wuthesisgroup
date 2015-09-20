<?php
class Accounting_DropoutController extends Zend_Controller_Action {
	
    public function init()
    {    	
    	header('content-type: text/html; charset=utf8');
	}

    public function indexAction()
    {
    	
    }
    public function addDropAction()
    {
    	$frm = new accounting_Form_FrmDrop();
    	$this->view->frmdrop = $frm->AddDropStudent();
    	Application_Model_Decorator::removeAllDecorator($frm);
    	
    }
   
}
