<?php
class Foundation_StudentController extends Zend_Controller_Action {
	
	
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
	}

    public function indexAction()
    {
    	
    
	}
	public function addStudentRoomAction(){
		$_frm_ASR=new Foundation_Form_FrmAddStudentRoom();
		$_ASR=$_frm_ASR->FrmAddStudentRoom();
		Application_Model_Decorator::removeAllDecorator($_ASR);
		$this->view->frm_AddStudentRoom = $_ASR;
	}
	function editStudentAction(){
		$_frm = new Foundation_Form_FrmStudent();
		$_frmstudent=$_frm->FrmStudent();	 
// 		$frm = new Registrar_Form_FrmRegister();
// 		$frm_register=$frm->FrmRegistarWU();
		Application_Model_Decorator::removeAllDecorator($_frmstudent);
		$this->view->frm_student = $_frmstudent;
		
// 		Application_Model_Decorator::removeAllDecorator($_frmstudent);
// 		$this->view->frm_student = $_frmstudent;
	}
	function rptStudentListAction(){
		
	}
	public  function addSituationAction(){
	if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$_dbmodel = new Foundation_Model_DbTable_DbSituation();
				$_subject_exam = $_dbmodel->addNewSituation($_data);
			Application_Form_FrmMessage::message("ការ​បញ្ចូល​ជោគ​ជ័យ !");
				 
			} catch (Exception $e) {
 				Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
			 
		}
		$frm=new Foundation_Form_FrmSituation();
		$this->view->frm_situation=$frm->FrmAddSituation();
	}
	public  function addCompositionAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$_dbmodel = new Foundation_Model_DbTable_DbComposition();
				$_subject_exam = $_dbmodel->addNewComposition($_data);
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​ជោគ​ជ័យ !");
					
			} catch (Exception $e) {
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		
		}
		$frm=new Foundation_Form_FrmComposition();
		$this->view->frm_composition=$frm->FrmAddComposition();
	}
	public  function addSubjectExamAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$_dbmodel = new Foundation_Model_DbTable_DbSubjectExam();
				$_subject_exam = $_dbmodel->addSubjectExam($_data);
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​ជោគ​ជ័យ !");
				 
			} catch (Exception $e) {
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
			 
		}
		$frm=new Foundation_Form_FrmSubjectExam();
		$this->view->frm_=$frm->FrmAddSubjectExam();
	}
	public function editSubjectExamAction(){
		$id=$this->getRequest()->getParam("id");
		$db = new Foundation_Model_DbTable_DbSubjectExam();
		$row = $db->getSubjectExamById($id);
		if($this->getRequest()->isPost())
		{
			try{
				$data = $this->getRequest()->getPost();
				$data["id"]=$id;
				$db = new Foundation_Model_DbTable_DbSubjectExam();
				$row=$db->updateSubjectExam($data);
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/foundation/student/index");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("EDIT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$obj=new Foundation_Form_FrmSubjectExam();
		$frm=$obj->FrmAddSubjectExam($row);
		$this->view->update_subject_exam=$frm;
		Application_Model_Decorator::removeAllDecorator($frm);
	}
	public function editCompositionAction(){
		$id=$this->getRequest()->getParam("id");
		$db = new Foundation_Model_DbTable_DbComposition();
		$row = $db->getCompositionById($id);
		if($this->getRequest()->isPost())
		{
			try{
				$data = $this->getRequest()->getPost();
				$data["id"]=$id;
				$db = new Foundation_Model_DbTable_DbComposition();
				$row=$db->updateComposition($data);
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/foundation/student/index");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("EDIT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$obj=new Foundation_Form_FrmComposition();
		$frm=$obj->FrmAddComposition($row);
		$this->view->frm_composition=$frm;
		Application_Model_Decorator::removeAllDecorator($frm);
	}
	public function editSituationAction(){
		$id=$this->getRequest()->getParam("id");
		$db = new Foundation_Model_DbTable_DbSituation();
		$row = $db->getSituById($id);
		if($this->getRequest()->isPost())
		{
			try{
				$data = $this->getRequest()->getPost();
				$data["id"]=$id;
				$db = new Foundation_Model_DbTable_DbSituation();
				$row=$db->updateStuation($data);
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/foundation/student/index");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("EDIT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$obj=new Foundation_Form_FrmSituation();
		$frm=$obj->FrmAddSituation($row);
		$this->view->frm_composition=$frm;
		Application_Model_Decorator::removeAllDecorator($frm);
	}
}
