<?php
class Global_ProvinceController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function start(){
		return ($this->getRequest()->getParam('limit_satrt',0));
	}
	public function indexAction(){
		try{
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$search = array(
						'title' => $_data['title'],
						'subjec_name'=>$_data['subjec_name'],
						'status' => $_data['status_search']);
			}
			else{
	
				$search = array(
						'title' => '',
						'status' => -1,
				);
	
			}
			$db = new Global_Model_DbTable_DbProvince();
			$rs_rows= $db->getAllProvince($search);
	
			$glClass = new Application_Model_GlobalClass();
			$rs = $glClass->getImgActive($rs_rows, BASE_URL, true);
	
			$list = new Application_Form_Frmtable();
			$collumns = array("EN_PROVINCE","KH_PROVINCE","MODIFY_DATE","STATUS","BY_USER");
			$link=array(
					'module'=>'global','controller'=>'province','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs,array('province_kh_name'=>$link,'province_en_name'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$frm = new Global_Form_FrmSearchMajor();
		$frm =$frm->searchProvinnce();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_search = $frm;
	}
	
	function addAction()
	{
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$_dbmodel = new Global_Model_DbTable_DbProvince();
				$_dbmodel->addNewProvince($_data);
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/province/index");
			}catch (Exception $e) {
				Application_Form_FrmMessage::message("INSERT_FAIL");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$provine=new Global_Form_FrmProvince();
		$frm_province=$provine->FrmProvince();
		Application_Model_Decorator::removeAllDecorator($frm_province);
		$this->view->frm_province = $frm_province;
	}
	
	public function editAction()
	{
		$id=$this->getRequest()->getParam("id");
		$db=new Global_Model_DbTable_DbProvince();
		$row=$db->getProvinceById($id);
		if($this->getRequest()->isPost())
		{
			$data = $this->getRequest()->getPost();
			$db = new Global_Model_DbTable_DbProvince();
			$db->updateProvince($data,$id);
			Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/province/index");
		}
		$frm= new Global_Form_FrmProvince();
		$update=$frm->FrmProvince($row);
		$this->view->frm_province=$update;
		Application_Model_Decorator::removeAllDecorator($update);
	}
}

