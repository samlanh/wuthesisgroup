<?php
class Accounting_ProgramGepController extends Zend_Controller_Action {
	
    public function init()
    {    	
    	header('content-type: text/html; charset=utf8');
	}

    public function indexAction()
    {
    	
    }
    
	function addProgramTypeAction()
	{
		$item=new Accounting_Form_Frmitem();
		$frm_item=$item->FrmProgramType();
		Application_Model_Decorator::removeAllDecorator($frm_item);
		$this->view->frm_item = $frm_item;
	}	
   
}
