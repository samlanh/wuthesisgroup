<?php 
Class Foundation_Form_FrmStudent extends Zend_Dojo_Form {
	protected $tr=null;
	protected $tvalidate=null ;//text validate
	protected $filter=null;
	protected $t_date=null;
	protected $t_num=null;
	protected $text=null;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->tvalidate = 'dijit.form.ValidationTextBox';
		$this->filter = 'dijit.form.FilteringSelect';
		$this->t_date = 'dijit.form.DateTextBox';
		$this->t_num = 'dijit.form.NumberTextBox';
		$this->text = 'dijit.form.TextBox';
	}
	public function FrmStudent($data=null){
		
		$_studid = new Zend_Dojo_Form_Element_TextBox('stu_card');
		$_studid->setAttribs(array('dojoType'=>$this->tvalidate,'class'=>'fullside','required'=>'true',));
		
		$_khname = new Zend_Dojo_Form_Element_TextBox('kh_name');
		$_khname->setAttribs(array(
				'dojoType'=>$this->tvalidate,'class'=>'fullside','required'=>'true',
		));
		
		$_enname = new Zend_Dojo_Form_Element_TextBox('en_name');
		$_enname->setAttribs(array('dojoType'=>$this->tvalidate,
				'required'=>'true',
				'class'=>'fullside',));
		
		$_sex =  new Zend_Dojo_Form_Element_FilteringSelect('sex');
		$_sex->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		$sex_opt = array(
				1=>$this->tr->translate("MALE"),
				2=>$this->tr->translate("FEMALE"));
		$_sex->setMultiOptions($sex_opt);
		
		 
		$_dob = new Zend_Dojo_Form_Element_TextBox('dob');
		$_dob->setAttribs(array(
				'dojoType'=>$this->t_date,'class'=>'fullside','required'=>'true',
				//'constraints'=>'{datePattern:"dd/MM/yyyy"'
		));
		//$_dob->setValue('value',"2/7/2017");
		
		
		$newdate = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d')+45, date('Y')));
		
	   // echo $newdate;	
	    	
	    //$_dob->setValue(date("Y-m-d"));					
		
		$_phone = new Zend_Dojo_Form_Element_TextBox('phone');
		$_phone->setAttribs(array('dojoType'=>$this->text,'class'=>'fullside',));
		
		$_degree =  new Zend_Dojo_Form_Element_FilteringSelect('degree');
		$arr_opt = array(
				1=>$this->tr->translate("ASSOCIATE"),
				2=>$this->tr->translate("BACHELOR"),
				3=>$this->tr->translate('MASTER'),
				4=>$this->tr->translate('DOCTORATE'));
		$_degree ->setMultiOptions($arr_opt);
		
		$_degree->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect','class'=>'fullside',
		
		));
		
		$_batch =  new Zend_Dojo_Form_Element_NumberTextBox("batch");
		$_batch->setAttribs(array(
				'dojoType'=>$this->t_num,
				'required'=>'true',
				'class'=>'fullside',));
		
		$_year =  new Zend_Dojo_Form_Element_TextBox("year");
		$_year->setAttribs(array(
				'dojoType'=>$this->t_num,
				'required'=>'true',
				'class'=>'fullside',));
		
		$_session = new Zend_Dojo_Form_Element_FilteringSelect("session");
		$opt_session = array(
				1=>$this->tr->translate('MORNING'),
				2=>$this->tr->translate('AFTERNOON'),
				3=>$this->tr->translate('EVERNING'),
				4=>$this->tr->translate('WEEKEND')
		);
		$_session->setMultiOptions($opt_session);
		$_session->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',));
		
		
		$_db = new Application_Model_DbTable_DbGlobal();
		$rows = $_db->getGlobalDb('SELECT dept_id,en_name FROM rms_dept WHERE is_active=1 AND en_name !="" ');
		$opt = "";
		if(!empty($rows))foreach($rows AS $row) $opt[$row['dept_id']]=$row['en_name'];
		 
		$_dept = new Zend_Dojo_Form_Element_FilteringSelect("dept");
		$_dept->setMultiOptions($opt);
		$_dept->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',
				'onchange'=>'changeMajor();'));
		
		$opt_marjor = array(-1=>$this->tr->translate("SELECT_MAJOR"));
		$_major = new Zend_Dojo_Form_Element_FilteringSelect("major");
		$_major->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside'));
		
		$_situation = new Zend_Dojo_Form_Element_FilteringSelect("situation");
		$opt_situation = array(1=>$this->tr->translate("កម្មករ ឫ និយោជិក"),2=>$this->tr->translate("កសិករ"),
				3=>$this->tr->translate("អ្នកលក់ដូរតូចតាច"),4=>$this->tr->translate("បុគ្គលិកក្រុមហ៊ន"),
				5=>$this->tr->translate("បុគ្គលិកអង្គការក្រៅរដ្ឋាភិបាល"),6=>$this->tr->translate("បុគ្គលិកក្រុមហ៊ន"),
				7=>$this->tr->translate("បុគ្គលិកអង្គការអន្តរជាតិ"),8=>$this->tr->translate("កំព្រាឪពុក"),
				9=>$this->tr->translate("កំព្រាម្តាយ"),10=>$this->tr->translate("ឪពុកឫម្តាយជរា"),
				11=>$this->tr->translate("ផ្សេងៗ")
				);
		$_situation->setMultiOptions($opt_situation);
		$_situation->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',));
		
		$_father_tel = new Zend_Dojo_Form_Element_TextBox('father_phone');
		$_father_tel->setAttribs(array('dojoType'=>$this->t_num,'class'=>'fullside',));
		
		$_mother_tel = new Zend_Dojo_Form_Element_TextBox('mother_phone');
		$_mother_tel->setAttribs(array('dojoType'=>$this->t_num,'class'=>'fullside',));
		
		$_bacc_exam = new Zend_Dojo_Form_Element_TextBox('finish_bacc');
		$_bacc_exam->setAttribs(array('dojoType'=>$this->t_num,'class'=>'fullside',));
		
		$_bacc_code = new Zend_Dojo_Form_Element_TextBox('certificate_code');
		$_bacc_code->setAttribs(array(
				'dojoType'=>$this->t_num,
				'class'=>'fullside',
				'onKeyup'=>'CheckBaccCode();'));
		
		$_bacc_score = new Zend_Dojo_Form_Element_TextBox('bacc_score');
		$_bacc_score->setAttribs(array(
				'dojoType'=>$this->t_num,
				'class'=>'fullside',
				));
		
		$_from_school = new Zend_Dojo_Form_Element_FilteringSelect("situation");
		$opt_school = array(1=>$this->tr->translate("កម្មករ ឫ និយោជិក"),2=>$this->tr->translate("កសិករ"),
				3=>$this->tr->translate("អ្នកលក់ដូរតូចតាច"),4=>$this->tr->translate("បុគ្គលិកក្រុមហ៊ន"),
				5=>$this->tr->translate("បុគ្គលិកអង្គការក្រៅរដ្ឋាភិបាល"),6=>$this->tr->translate("បុគ្គលិកក្រុមហ៊ន"),
				7=>$this->tr->translate("បុគ្គលិកអង្គការអន្តរជាតិ"),8=>$this->tr->translate("កំព្រាឪពុក"),
				9=>$this->tr->translate("កំព្រាម្តាយ"),10=>$this->tr->translate("ឪពុកឫម្តាយជរា"),
				11=>$this->tr->translate("ផ្សេងៗ")
		);
		$_from_school->setMultiOptions($opt_school);
		$_from_school->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',));
		
		$_remark = new Zend_Dojo_Form_Element_Textarea('remark');
		$_remark->setAttribs(array(
				'dojoType'=>$this->text,'class'=>'fullside',
		));
		
		$rows_school = $_db->getGlobalDb("SELECT 
					CONCAT(school_name,' - '
					,(SELECT province_en_name FROM rms_province AS p 
					WHERE p.province_id= sp.province_id)) AS school_province,school_id 
					FROM rms_school_province AS sp WHERE status=1 ORDER BY sp.province_id");
		$opt_school = "";
		if(!empty($rows_school))foreach($rows_school AS $row) $opt_school[$row['school_id']]=$row['school_province'];
		$_from_school = new Zend_Dojo_Form_Element_FilteringSelect("from_school");
		$_from_school->setMultiOptions($opt_school);
		$_from_school->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',));
		
		$rows_provice = $_db->getGlobalDb("SELECT province_id,province_en_name FROM rms_province WHERE is_active=1 AND province_en_name!=''");
		$opt_province = "";
		if(!empty($rows_provice))foreach($rows_provice AS $row) $opt_province[$row['province_id']]=$row['province_en_name'];
		$_pob = new Zend_Dojo_Form_Element_FilteringSelect("pob");
		$_pob->setMultiOptions($opt_province);
		$_pob->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',));
		
		$_student_add = new Zend_Dojo_Form_Element_FilteringSelect("student_add");
		$_student_add->setMultiOptions($opt_province);
		$_student_add->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',));
		
		
		$_student_from = new Zend_Dojo_Form_Element_FilteringSelect("student_from");
		$_student_from->setMultiOptions($opt_province);
		$_student_from->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',));
		
		$_curr_add = new Zend_Dojo_Form_Element_FilteringSelect("curr_add");
		$_curr_add->setMultiOptions($opt_school);
		$_curr_add->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',));
		
		$_composition = new Zend_Dojo_Form_Element_FilteringSelect("composition");
		$opt_compo = array(1=>$this->tr->translate("សិស្សទើបចាប់បាកឌុប"),2=>$this->tr->translate("ប្រជាជនធម្មតាគ្មានការងារធ្វើ"),
				3=>$this->tr->translate("ពាណិជ្ជករ"),4=>$this->tr->translate("មន្រ្តីរាជការ"),
				5=>$this->tr->translate("បុគ្គលិកក្រុមហ៊ុន"),6=>$this->tr->translate("កសិករ"),
				7=>$this->tr->translate("សមាសភាពដទៃទៀត"));
		$_composition->setMultiOptions($opt_compo);
		$_composition->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',));
		$_age = new Zend_Dojo_Form_Element_TextBox('age');
		$_age->setAttribs(array('dojoType'=>$this->t_num,'class'=>'fullside',));
		
		$_mention = new Zend_Dojo_Form_Element_FilteringSelect("mention");
		$opt_rank = array(1=>"A",2=>"B",3=>"C",4=>"D",5=>"E",5=>"F");
		$_mention->setMultiOptions($opt_rank);
		$_mention->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',));
		
		$_arr = array(1=>$this->tr->translate("ACTIVE"),0=>$this->tr->translate("DACTIVE"));
		$_status = new Zend_Dojo_Form_Element_FilteringSelect("status");
		$_status->setMultiOptions($_arr);
		$_status->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'required'=>'true',
				'missingMessage'=>'Invalid Module!',
				'class'=>'fullside'));
		
		$this->addElements(array(
			  $_khname, $_enname,$_studid, $_sex,$_dob,$_degree,$_phone,
			  $_dept,$_batch,$_year,$_session,$_dept,$_major,$_from_school,$_student_add,$_student_from,$_situation,$_father_tel,
			  $_mother_tel, $_bacc_exam, $_bacc_code, $_bacc_score, $_from_school,$_remark,
			  $_pob, $_curr_add, $_composition, $_age, $_mention, $_status));
		if(!empty($data)){
			$_khname->setValue($data['stu_khname']);
			$_enname->setValue($data['stu_enname']);
			$_studid->setValue($data['stu_card']);
			$_sex->setValue($data['sex']);
			//echo date('m/d/Y');
			$_dob->setValue(date('m/d/Y'));
			$_dob->setValue($data['dob']);
			$_degree->setValue($data['degree']);
			$_dept->setValue($data['major_id']);
			$_phone->setValue($data['phone']);
			$_session->setValue($data['session']);
			$_batch->setValue($data['batch']);
			echo $data['year'];
			$_year->setValue($data['year']);
			$_pob->setValue($data['pob']);
			$_student_add->setValue($data['student_add']);
			$_student_from->setValue($data['from_school']);
			$_situation->setValue($data['situation']);
			$_from_school->setValue($data['from_school']);
			$_father_tel->setValue($data['father_phone']);
			$_mother_tel->setValue($data['mother_phone']);
			$_bacc_exam->setValue($data['finish_bacc']);
			$_bacc_score->setValue($data['bacc_score']);
			$_bacc_code->setValue($data['certificate_code']);
			$_mention->setValue($data['mention']);
			$_from_school->setValue($data['from_school']);
			$_composition->setValue($data['composition']);
			$_status->setValue($data['status']);
			$_remark->setValue($data['remark']);
			//echo $data['composition'];exit();
		}
		
		return $this;
	}
}