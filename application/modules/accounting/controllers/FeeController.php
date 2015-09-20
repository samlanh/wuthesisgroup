<?php
class Accounting_feeController extends Zend_Controller_Action {
	public function init()
    {    	
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
    public function indexAction()
    {
    	try{
    		if($this->getRequest()->isPost()){
    			$_data = $this->getRequest()->getPost();
    			$search = array(
    					'title' => $session_servicetype->txtsearch,
    					'txtsearch' => $_data['title'],
    					'status' => $_data['status_search'],
    					'type' => $_data['type'],
    			);
    			$limit = $session_servicetype->limit;
    		}
    		else{
    			$search='';
    		}
    		$db = new Accounting_Model_DbTable_DbTuitionFee();
    		$service= $db->getAllTuitionFee($search);
    		$model = new Application_Model_DbTable_DbGlobal();
    		$row=0;$indexterm=1;$key=0;
    		if(!empty($service)){
    			foreach ($service as $i => $rs) {
    				$rows = $db->getFeebyOther($rs['id']);
    				$fee_row=1;
    				if(!empty($rows))foreach($rows as $payment_tran){
    							if($payment_tran['payment_type']==1){
    								$rs_rows[$key]=$this->headAddRecordTuitionFee($rs,$key);
    								$term = $model->getAllPaymentTerm($fee_row);
    								$rs_rows[$key]['quarter'] = $payment_tran['tuition_fee'];
    								$key_old=$key;
    								$key++;
    							}elseif($payment_tran['payment_type']==2){
    								$term = $model->getAllPaymentTerm($payment_tran['payment_type']);
    								$rs_rows[$key_old]['semester'] = $payment_tran['tuition_fee'];
    								
    							}elseif($payment_tran['payment_type']==3){
    								$term = $model->getAllPaymentTerm($payment_tran['payment_type']);
    								$rs_rows[$key_old]['year'] = $payment_tran['tuition_fee'];
    							}
    							else{
    								$term = $model->getAllPaymentTerm($payment_tran['payment_type']);
    								$rs_rows[$key_old]['full_fee'] = $payment_tran['tuition_fee'];
    							}
    							if($rs['degree_type']==1){
    									$rs_rows[$key_old]['faculty_name']= Application_Model_DbTable_DbGlobal::getAllMention($payment_tran['metion']);
    							}
    							else{
    								$r_facu = $model->getDeptById($payment_tran['metion']);
    								$rs_rows[$key_old]['faculty_name']= $r_facu['en_name'];
    								}			
    				}
    			}
    		}
    		else{
    			$result = Application_Model_DbTable_DbGlobal::getResultWarning();
    		}
    		$pay_term = $model->getAllPaymentTerm();
    		$payment_term='';
    		foreach ($pay_term as $value){
    			$payment_term.='"'.$value.'",';
    		}
    		$list = new Application_Form_Frmtable();
    		$collumns = array("DEGREE","FACULTY/METION","BATCH","STATUS","QUARTER","SEMESTER","YEAR","FULL_FEE");
    		$link=array(
    				'module'=>'accounting','controller'=>'fee','action'=>'edit-feetuition',
    		);
    		$urlEdit = BASE_URL ."/product/index/update";
    		$this->view->list=$list->getCheckList(1, $collumns, $rs_rows, array('degree'=>$link));
    	}catch (Exception $e){
    		Application_Form_FrmMessage::message("APPLICATION_ERROR");
    		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    	}
    	$frm = new Global_Form_FrmSearchMajor();
    	$frm = $frm->frmSearchTutionFee();
    	Application_Model_Decorator::removeAllDecorator($frm);
    	$this->view->frm_search = $frm;
    }
    public function headAddRecordTuitionFee($rs,$key){
    	$result[$key] = array(
    						'id' 	  	  	=> $rs['id'],
    						'degree' 		=> Application_Model_DbTable_DbGlobal::getAllDegreeById($rs['degree']),
    						'faculty_name' 	=> ($rs['faculty_name']),
    						'batch' => $rs['batch'],
    						'status'	   => Application_Model_DbTable_DbGlobal::getAllStatus($rs['status'])
    				);
    	return $result[$key];
    }
    public function addFeetuitionAction()
    {
    	if($this->getRequest()->isPost()){
    		try {
	    		$_data = $this->getRequest()->getPost();
	    		$_model = new Accounting_Model_DbTable_DbTuitionFee();
	    		$rs =  $_model->addTuitionFee($_data);
    		if(!empty($rs))Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/accounting/fee/add-feetuition");
    		}catch(Exception $e){
    			Application_Form_FrmMessage::message("INSERT_FAIL");
	   			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    		}
    	}
    	$frm = new Accounting_Form_FrmServicePrice();
    	$frm_set_pric=$frm->FrmSetServicePrice();
    	Application_Model_Decorator::removeAllDecorator($frm_set_pric);
    	$this->view->frm_set_price = $frm_set_pric;
    	$_model = new Application_Model_GlobalClass();
    	$this->view->all_metion = $_model ->getAllMetionOption();
    	$this->view->all_faculty = $_model ->getAllFacultyOption();
    	$model = new Application_Model_DbTable_DbGlobal();
    	$this->view->payment_term = $model->getAllPaymentTerm();
    	
    	$frm = new Application_Form_FrmOther();
    	$frm =  $frm->FrmAddDept(null);
    	Application_Model_Decorator::removeAllDecorator($frm);
    	$this->view->add_dept = $frm;
    }
 	public function editFeetuitionAction()
    {
    	if($this->getRequest()->isPost()){
    		try {
	    		$_data = $this->getRequest()->getPost();
	    		$_model = new Accounting_Model_DbTable_DbTuitionFee();
	    		$rs =  $_model->addTuitionFee($_data);
    		if(!empty($rs))Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/accounting/fee/add-feetuition");
    		}catch(Exception $e){
    			Application_Form_FrmMessage::message("INSERT_FAIL");
	   			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    		}
    	}
    		$id = $this->getRequest()->getParam('id');
    		if(!empty($id)){
    			$_model = new Accounting_Model_DbTable_DbTuitionFee();
    			$_rs =  $_model->addTuitionFee($_data);
    		}else{
    			$this->_redirect("/accounting/fee/");
    		}
	    	$frm = new Accounting_Form_FrmServicePrice();
	    	$frm_set_pric=$frm->FrmSetServicePrice();
	    	Application_Model_Decorator::removeAllDecorator($frm_set_pric);
	    	$this->view->frm_set_price = $frm_set_pric;
	    	$_model = new Application_Model_GlobalClass();
	    	$this->view->all_metion = $_model ->getAllMetionOption();
	    	$this->view->all_faculty = $_model ->getAllFacultyOption();
	    	$model = new Application_Model_DbTable_DbGlobal();
	    	$this->view->payment_term = $model->getAllPaymentTerm();
    }
}
