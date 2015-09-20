<?php
class Global_globalsController extends Zend_Controller_Action {
	public function addServicesAction(){
		if($this->getRequest()->isPost()){
			try{
				$_data = $this->getRequest()->getPost();
				$_model = new Accounting_Model_DbTable_DbProgram();
				$id=$_model->addprogram($_data);
				$_model = new Application_Model_GlobalClass();
				if(!empty($_data['is_program'])){
					$service_option = $_model->getAllServiceItemOption(2);
				}else{
					$service_option = $_model->getAllServiceItemOption();
				}
				
				$rs =  array('id'=>$id,"msg"=>"INSERT_SUCCESS",'service_option'=>$service_option);
				print_r(Zend_Json::encode($rs));
				exit();
				
			}catch(Exception $e){
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
	}
	public function addProgramAction(){
		if($this->getRequest()->isPost()){
			try{
				$_data = $this->getRequest()->getPost();
				$_model = new Accounting_Model_DbTable_DbProgram();
				$id=$_model->addprogram($_data);
				$_model = new Application_Model_GlobalClass();
				$service_option = $_model->getAllServiceItemOption();
				$rs =  array('id'=>$id,"msg"=>"INSERT_SUCCESS",'service_option'=>$service_option);
				print_r(Zend_Json::encode($rs));
				exit();
	
			}catch(Exception $e){
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
	}
	public function addServiceCategoryAction(){
		if($this->getRequest()->isPost()){
			try{
				$_data = $this->getRequest()->getPost();
				$_model = new Application_Model_DbTable_DbGlobalinsert();
				$id=$_model->insertSerViceProgramType($_data);
				$_model = new Application_Model_DbTable_DbGlobal();
				$service_type = $_model->getServiceType(1);
				$rs =  array('id'=>$id,"msg"=>"INSERT_SUCCESS","service_type"=>$service_type);
				print_r(Zend_Json::encode($rs));
				exit();
			}catch(Exception $e){
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
	}
	public function getfacultyOptionAction(){
		if($this->getRequest()->isPost()){
			try{
					$_model = new Application_Model_GlobalClass();
					$option = $_model ->getAllFacultyOption();
					$rs =  array("msg"=>"INSERT_SUCCESS","option"=>$option);
					print_r(Zend_Json::encode($rs));
					exit();
				}catch(Exception $e){
					Application_Form_FrmMessage::message("INSERT_FAIL");
					Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
				}
	     }
   }
   public function getTuitionfeeAction(){
   	if($this->getRequest()->isPost()){
   		try{
   			$post = $this->getRequest()->getPost();
   			$_model = new Application_Model_DbTable_DbGlobal();
   			$rs= $_model->getTutionFeebyCondition($post);
   			$rs =  array("msg"=>"INSERT_SUCCESS","fee"=>$rs);
   			print_r(Zend_Json::encode($rs));
   			exit();
   		}catch(Exception $e){
   			Application_Form_FrmMessage::message("GETDATA_FAIL");
   			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
   		}
   	}
   }
   public function getStudentinfoAction(){
   	if($this->getRequest()->isPost()){
   		$_data = $this->getRequest()->getPost();
   		$_db = new Registrar_Model_DbTable_DbGetStudentInfo();
   		$_rs_student = $_db->getStudentById($_data['student_card']);
   		print_r(Zend_Json::encode($_rs_student));
   		exit();
   	}
   }
	
}

