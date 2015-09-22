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
    			'module'=>'global','controller'=>'index','action'=>'edit-dept',
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
    public function addDepartmentAction(){
    	if($this->getRequest()->isPost()){
    		$_data = $this->getRequest()->getPost();
    		try {
    			$_dbmodel = new Application_Model_DbTable_DbDept();
    			$_model = new Application_Model_DbTable_DbGlobal();
    			$_dept_id = $_dbmodel->AddNewDepartment($_data);
//     			$rs=$_model->isRecordExist("en_name='".$_data['en_name']."'", "rms_dept");
//     			if($rs==){
//     				$rs =  array("succ"=>"ទិន្នន័យមានរួចរាល់ !");
//     			}else{
//     				$_dept_id = $_dbmodel->AddNewDepartment($_data);
//     				$rs =  array("succ"=>"ការបញ្ចូលដោយជោកជ័យ !");
//     			}
    			$rs =  array('id'=>$_dept_id,"succ"=>"ការបញ្ចូលដោយជោគជ័យ !");
    			print_r(Zend_Json::encode($rs));
    			exit();
    		} catch (Exception $e) {
    			$err =$e->getMessage();
    			Application_Model_DbTable_DbUserLog::writeMessageError($err);
    		}
    	}
    	$frm = new Application_Form_FrmOther();
    	$this->view->frm_dept = $frm->FrmAddDept(null);
    }
    public function editDeptAction(){
       	if($this->getRequest()->isPost()){
    		try {
    			$_data = $this->getRequest()->getPost();
    			$_dbmodel = new Application_Model_DbTable_DbDept();
    			$_dbmodel->UpdateDepartment($_data);
    			Application_Form_FrmMessage::Sucessfull("ការកៃប្រែដោយជោគជ័យ !", "/global/index/dept-list");
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
    public function majorListAction()
    {
    	try{
	    	$_model = new Global_Model_DbTable_DbDept();
	    	if($this->getRequest()->isPost()){
	    		$_data=$this->getRequest()->getPost();
	    		
	    		//set value for display
		    	$search = array(
	    				'txtsearch' => $_data['title'],
		    			'title' => $_data['title'],
		    			'status' => $_data['status_search']
	    		);
	    		
    	   }
    	   else{
    		$search='';
    	   }
	    	$rs_rows= $_model->getAllMajorList($search);
	    
			$glClass = new Application_Model_GlobalClass();
	    	$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
	    	$list = new Application_Form_Frmtable();
	    	$collumns = array("PROGRAM_TITLE","TYPE","DISCRIPTION","SHORTCUT","MODIFY_DATE","STATUS","BY_USER");
	    	$link=array(
	    			'module'=>'global','controller'=>'index','action'=>'edit-major',
	    	);
	    	$this->view->list=$list->getCheckList(1, $collumns, $rs_rows,array('major_enname'=>$link,'major_khname'=>$link));
	    	
	    	
	    	
	    	
    	}catch (Exception $e){
    		Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    	}
    	$frm = new Application_Form_FrmOther();
    	$this->view->add_major = $frm->FrmAddMajor(null);
    	$frm = new Global_Form_FrmSearchMajor();
    	$frm = $frm->FrmMajors();
    	Application_Model_Decorator::removeAllDecorator($frm);
    	$this->view->frm_search = $frm;
    	
    	$frm = new Application_Form_FrmOther();
    	$this->view->add_faculty = $frm->FrmAddDept(null);
    }
    public function addMajorAction(){
    	if($this->getRequest()->isPost()){
    		$_data = $this->getRequest()->getPost();
    		try {
    			$_dbmodel = new Application_Model_DbTable_DbDept();
    			$_major_id = $_dbmodel->AddNewMajor($_data);
    			unset($formdata);
    			$return =  array('major_id' => $_major_id,"succ"=>"ការបញ្ចូលដោយជោគជ័យ");
    			print_r(Zend_Json::encode($return));
    			exit();
    		} catch (Exception $e) {
    			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    		}
    			
    	}
    }
    public function editMajorAction(){
    	$id = $this->getRequest()->getParam("id");
    	if($this->getRequest()->isPost()){
    		try{
    		$_data = $this->getRequest()->getPost();
    		$_dbmodel = new Global_Model_DbTable_DbDept();
    		$_dbmodel ->updatMajorById($_data);
    		Application_Form_FrmMessage::Sucessfull("ការកែប្រែដោយជោគជ័យ", "/global/index/major-list");
    		}catch(Exception $e){
    			Application_Form_FrmMessage::message("Application Error");
    			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    		}
    	}
    	if(!empty($id)){
    		$frm = new Application_Form_FrmOther();
    		$db_model = new Global_Model_DbTable_DbDept();
    		$row=$db_model->getMajorById($id);
    		$frm->FrmAddMajor($row);
    		Application_Model_Decorator::removeAllDecorator($frm);
    		$this->view->frm_major = $frm;
    	}else{
    		
    	}
    }
   public function teacherListAction(){
   	try{
   		$db = new Global_Model_DbTable_DbTeacher();
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
   					'subjec_name'=>-1,
   					'status' => -1);
   		}
   		$rs_rows= $db->getAllTeacher($search);
   		$glClass = new Application_Model_GlobalClass();
   		$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
   		 
   		$list = new Application_Form_Frmtable();
   		$collumns = array("TEACHER_KH_NAME","TEACHER_EN_NAME","SUBJECT_TEACH_KH","SUBJECT_TEACH_EN","MODIFY_DATE","STATUS","BY_USER");
   		
   		$link=array(
   				'module'=>'global','controller'=>'index','action'=>'edit-teacher',
   		);
   		$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('teacher_name_kh'=>$link,'teacher_name_en'=>$link));
   	}catch (Exception $e){
   		Application_Form_FrmMessage::message("Application Error");
   		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
   	}
   	$frm = new Application_Form_FrmOther();
   	$this->view->add_major = $frm->FrmAddMajor(null);
   	$frm = new Global_Form_FrmSearchMajor();
   	$frm = $frm->frmSearchTeacher();
   	Application_Model_Decorator::removeAllDecorator($frm);
   	$this->view->frm_search = $frm;
   }
   function addTeacherAction()
   {
   	if($this->getRequest()->isPost()){
   		$_data = $this->getRequest()->getPost();
   		try {
   			$_dbmodel = new Global_Model_DbTable_DbTeacher();
   			$_major_id = $_dbmodel->AddNewTeacher($_data);
   			Application_Form_FrmMessage::message("ការ​បញ្ចូល​ជោគ​ជ័យ !");
   
   		} catch (Exception $e) {
   			Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
   			$err =$e->getMessage();
   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
   		}
   	}
   	$tsub=new Global_Form_FrmTeacher();
   	$frm_techer=$tsub->FrmTecher();
   	Application_Model_Decorator::removeAllDecorator($frm_techer);
   	$this->view->frm_techer = $frm_techer;
   }
   public function editTeacherAction()
   {
   	$id=$this->getRequest()->getParam("id");
   	$db=new Global_Model_DbTable_DbTeacher();
   	$row=$db->getTeacherById($id);
   	if($this->getRequest()->isPost())
   	{
   		try{
	   		$data = $this->getRequest()->getPost();
	   		$data["id"]=$id;
	   		$db = new Global_Model_DbTable_DbTeacher();
	   		$db->updateTeacher($data);
	   		Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/index/teacher-list");
   		}catch(Exception $e){
   			Application_Form_FrmMessage::message("EDIT_FAIL");
   			$err =$e->getMessage();
   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
   		}
   	}
   	$obj=new Global_Form_FrmTeacher();
   	$frm_update=$obj->FrmTecher($row);
   	$this->view->update_teacher=$frm_update;
   	Application_Model_Decorator::removeAllDecorator($frm_update);
   }
   public function roomListAction(){
   	try{
   		$db_dept=new Global_Model_DbTable_DbDept();
   		if($this->getRequest()->isPost()){
   			$_data=$this->getRequest()->getPost();
   			$search = array(
   					'title' => $_data['title'],
   					'status' => $_data['status_search']);
   		}
   		else{
   			$search = array(
   					'title' => '',
   					'status' => -1);
   		}
   		$db = new Global_Model_DbTable_DbRoom();
   		$rs_rows= $db->getAllRooms($search);
   		
   		$glClass = new Application_Model_GlobalClass();
   		$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
   		
   		$list = new Application_Form_Frmtable();
   		$collumns = array("ROOM_NAME","MODIFY_DATE","STATUS","BY_USER");
   		$link=array(
   				'module'=>'global','controller'=>'index','action'=>'edit-room',
   		);
   		$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('room_name'=>$link));
   	}catch (Exception $e){
   		Application_Form_FrmMessage::message("Application Error");
   		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
   	}
	   	$frm = new Global_Form_FrmSearchMajor();
	   	$frm =$frm->searchRoom();
	   	Application_Model_Decorator::removeAllDecorator($frm);
	   	$this->view->frm_search = $frm;
   }
   function addRoomAction()
   {
   	if($this->getRequest()->isPost()){
   		$_data = $this->getRequest()->getPost();
   		try {
   			$_dbmodel = new Global_Model_DbTable_DbRoom();
   			$_major_id = $_dbmodel->addNewRoom($_data);
   			Application_Form_FrmMessage::message("ការ​បញ្ចូល​ជោគ​ជ័យ !");
   
   		} catch (Exception $e) {
   			Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
   			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
   		}
   
   	}
	   	$classname=new Global_Form_FrmAddClass();
	   	$frm_classname=$classname->FrmAddClass();
	   	Application_Model_Decorator::removeAllDecorator($frm_classname);
	   	$this->view->frm_classname = $frm_classname;
   }
   public function editRoomAction()
   {
	   	$id=$this->getRequest()->getParam("id");
	   	$db = new Global_Model_DbTable_DbRoom();
	   	$row = $db->getRoomById($id);
	   	if($this->getRequest()->isPost())
	   	{
	   		try{
		   		$data = $this->getRequest()->getPost();
		   		$data["id"]=$id;
		   		$db = new Global_Model_DbTable_DbRoom();
		   		$db->updateRoom($data);
		   		Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/index/room-list");
	   		}catch(Exception $e){
	   			Application_Form_FrmMessage::message("EDIT_FAIL");
	   			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
	   		}
	   	}
	   	$obj=new Global_Form_FrmAddClass();
	   	$frm_room=$obj->FrmAddClass($row);
	   	$this->view->update_room=$frm_room;
	   	Application_Model_Decorator::removeAllDecorator($frm_room);
   }
   public function subjectListAction(){
   	try{
   		$db = new Global_Model_DbTable_DbSubjectExam();
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
   					'subjec_name'=>-1,
   					'status' => -1);
   		}
   		$rs_rows = $db->getAllSujectName($search);
   		$glClass = new Application_Model_GlobalClass();
   		$rs = $glClass->getImgActive($rs_rows, BASE_URL, true);
   		
   		
   		$list = new Application_Form_Frmtable();
   		$collumns = array("SRMS_SUBJECT_EXAM_LIST","MODIFY_DATE","STATUS","BY_USER");
   		$link=array(
   				'module'=>'global','controller'=>'index','action'=>'edit-subject-exam',
   		);
   		$this->view->list=$list->getCheckList(0, $collumns, $rs,array('subj_exam_name'=>$link));
   		 
   	}catch (Exception $e){
   		Application_Form_FrmMessage::message("Application Error");
   		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
   	}
   	$frm = new Global_Form_FrmSearchMajor();
   	$frm =$frm->SubjectExam();
   	Application_Model_Decorator::removeAllDecorator($frm);
   	$this->view->frm_search = $frm;
   }
   function addSubjectExamAction()
   {
   	if($this->getRequest()->isPost()){
   		$_data = $this->getRequest()->getPost();
   		try {
   			$_dbmodel = new Global_Model_DbTable_DbSubjectExam();
   			$_dbmodel->addNewSubjectExam($_data);
   			Application_Form_FrmMessage::message("INSERT_SUCCESS");
   		} catch (Exception $e) {
   			Application_Form_FrmMessage::message("INSERT_FAIL");
   			$err =$e->getMessage();
   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
   		}
   	}
   	$subject_exam=new Global_Form_FrmAddSubjectExam();
   	$frm_subject_exam=$subject_exam->FrmAddSubjectExam();
   	Application_Model_Decorator::removeAllDecorator($frm_subject_exam);
   	$this->view->frm_subject_exam = $frm_subject_exam;
   }
   function editSubjectExamAction()
   {
   	$id = $this->getRequest()->getParam("id");
   	if($this->getRequest()->isPost()){
   		$_data = $this->getRequest()->getPost();
   		try {
   			$data["id"]=$id;
   			$_dbmodel = new Global_Model_DbTable_DbSubjectExam();
   			$_dbmodel->updateSubjectExam($_data,$id);
   			Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/index/subject-list");
   		} catch (Exception $e) {
   			Application_Form_FrmMessage::message("INSERT_FAIL");
   			$err =$e->getMessage();
   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
   		}
   	}
   	$_dbmoddel = new Global_Model_DbTable_DbSubjectExam();
   	$row = $_dbmoddel->getSubexamById($id);
   	$subject_exam=new Global_Form_FrmAddSubjectExam();
   	$frm_subject_exam=$subject_exam->FrmAddSubjectExam($row);
   	Application_Model_Decorator::removeAllDecorator($frm_subject_exam);
   	$this->view->frm_subject_exam = $frm_subject_exam;
   }
  function addProvinceAction()
  {
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$_dbmodel = new Global_Model_DbTable_DbProvince();
				$_dbmodel->addNewProvince($_data);
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/index/subject-list");
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
	public function provinceListAction(){
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
					'module'=>'global','controller'=>'index','action'=>'edit-province',
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
	public function editProvinceAction()
	{
		$id=$this->getRequest()->getParam("id");
		$db=new Global_Model_DbTable_DbProvince();
		$row=$db->getProvinceById($id);
		if($this->getRequest()->isPost())
		{
			$data = $this->getRequest()->getPost();
			$db = new Global_Model_DbTable_DbProvince();
			$db->updateProvince($data,$id);
			Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/index/province-list");
		}
		$frm= new Global_Form_FrmProvince();
		$update=$frm->FrmProvince($row);
		$this->view->frm_province=$update;
		Application_Model_Decorator::removeAllDecorator($update);
	}
	
	function addSubjectGepAction()
	{
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$_dbmodel = new Global_Model_DbTable_DbGep();
				$_major_id = $_dbmodel->AddNewSubjectGep($_data);
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​ជោគ​ជ័យ !");
	
			} catch (Exception $e) {
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
	
			}
	
		}
		$tsub=new Global_Form_FrmGep();
		$frm_gep=$tsub->FrmGep();
		Application_Model_Decorator::removeAllDecorator($frm_gep);
		$this->view->frm_gep = $frm_gep;
	}
	public function calculatorAction(){
		$frm_cal = new Global_Form_FrmCal();
		$myform = $frm_cal -> FrmCalculator();
		Application_Model_Decorator::removeAllDecorator($myform);
		$this->view->frm_cal = $myform;
		$key = new Application_Model_DbTable_DbKeycode();
		$this->view->keycode=$key->getKeyCodeMiniInv(TRUE);
	}
	public function iconAction(){
		
	}
	function addSchoolAction()
	{
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$_dbmodel = new Global_Model_DbTable_DbProvince();
				$_dbmodel->addNewProvince($_data);
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/index/subject-list");
			}catch (Exception $e) {
				Application_Form_FrmMessage::message("INSERT_FAIL");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$provine=new Global_Form_FrmAddSchool();
		$frm_school=$provine->FrmAddSchool();
		Application_Model_Decorator::removeAllDecorator($frm_school);
		$this->view->frm_school = $frm_school;
	}
}

