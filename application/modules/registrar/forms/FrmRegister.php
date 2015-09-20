<?php 
Class Registrar_Form_FrmRegister extends Zend_Dojo_Form {
	protected $tr=null;
	protected $tvalidate=null ;//text validate
	protected $filter=null;
	protected $t_date=null;
	protected $t_num=null;
	protected $text=null;
	protected $_degree=null;
	protected $_khname=null;
	protected $_enname=null;
	protected $_phone=null;
	protected $_batch=null;
	protected $_year=null;
	protected $_session=null;
	protected $_dob=null;
	protected $_pay_date=null;
	protected $_remark=null;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->tvalidate = 'dijit.form.ValidationTextBox';
		$this->filter = 'dijit.form.FilteringSelect';
		$this->t_date = 'dijit.form.DateTextBox';
		$this->t_num = 'dijit.form.NumberTextBox';
		$this->text = 'dijit.form.TextBox';
		
		$this->_khname = new Zend_Dojo_Form_Element_TextBox('kh_name');
		$this->_khname->setAttribs(array(
				'dojoType'=>$this->text,'class'=>'fullside',
		));
		
		$this->_enname = new Zend_Dojo_Form_Element_TextBox('en_name');
		$this->_enname->setAttribs(array('dojoType'=>$this->tvalidate,
				'required'=>'true',
				'class'=>'fullside',));
		
		$this->_dob = new Zend_Dojo_Form_Element_DateTextBox('dob');
		
		$date = date("Y-m-d")-20;
		$this->_dob->setAttribs(array(
				'data-dojo-Type'=>"dijit.form.DateTextBox",
				//'dojoType'=>"dijit.form.DateTextBox",
				'data-dojo-props'=>"value:'$date','class':'fullside','name':'dob'",
				'required'=>true));
		$this->_dob->setValue($date);
		
		$this->_phone = new Zend_Dojo_Form_Element_TextBox('phone');
		$this->_phone->setAttribs(array(
					'data-dojo-Type'=>$this->tvalidate,
					'data-dojo-props'=>"regExp:'[0-9]{9,10}',
				    'name':'phone',
					'class':'fullside',
				 	'placeHolder': '012345678',
				 	'invalidMessage':'មិនមាន​  ចន្លោះ ឬ សញ្ញា​ពិសេស រឺលើសចំនួនឡើយ'"));
		
		$this->_degree =  new Zend_Dojo_Form_Element_FilteringSelect('degree');
		$this->_degree->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'onchange'=>'getTuitionFee();'
		
		));
		$arr_opt = Application_Model_DbTable_DbGlobal::getAllDegreeById();
// 		$arr_opt = array(
// 				1=>$this->tr->translate("ASSOCIATE"),
// 				2=>$this->tr->translate("BACHELOR"),
// 				3=>$this->tr->translate('MASTER'),
// 				4=>$this->tr->translate('DOCTORATE'));
		$this->_degree->setMultiOptions($arr_opt);
		
		
		$this->_batch =  new Zend_Dojo_Form_Element_NumberTextBox("batch");
		$this->_batch->setAttribs(array(
				'onclick'=>'alert(3)',
				'data-dojo-Type'=>$this->tvalidate,'onclick'=>'alert(2)',
				'data-dojo-props'=>"regExp:'[0-9]{1,2}','required':true,
				'name':'batch',
				'onclick':'alert(1)',
				'class':'fullside',
				'invalidMessage':'អាចបញ្ជូលពី 1 ដល់ 99'"));		
		
		
		$this->_year =  new Zend_Dojo_Form_Element_TextBox("year");
		$this->_year->setAttribs(array(
				'data-dojo-Type'=>$this->tvalidate,
				'data-dojo-props'=>"regExp:'[0-5]{1}',
				'name':'year',
				'required':true,'class':'fullside',
				'invalidMessage':'អាចបញ្ជូលពី 1 ដល់  5'"));
		
		$this->_session = new Zend_Dojo_Form_Element_FilteringSelect("session");
		$opt_session = array(
				1=>$this->tr->translate('MORNING'),
				2=>$this->tr->translate('AFTERNOON'),
				3=>$this->tr->translate('EVERNING'),
				4=>$this->tr->translate('WEEKEND')
		);
		$this->_session->setMultiOptions($opt_session);
		$this->_session->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',));
		//	$pay_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d')+45, date('Y')));
		$this->_pay_date = new Zend_Dojo_Form_Element_DateTextBox('dob');
		$this->_pay_date->setAttribs(array('dojoType'=>$this->t_date,'class'=>'fullside',
				'constraints'=>'{datePattern:"dd/MM/yyyy"'
				));
		$this->_remark = new Zend_Dojo_Form_Element_TextBox('remark');
		$this->_remark->setAttribs(array(
				'dojoType'=>$this->text,'class'=>'fullside',
		));
		
		$this->_pay_date = new Zend_Dojo_Form_Element_TextBox('pay_date');
		$this->_pay_date->setAttribs(array('dojoType'=>$this->t_date,'class'=>'fullside',
				//	'constraints'=>'{datePattern:"dd/MM/yyyy"'
		));
	}
	public function FrmRegistarWU($data=null){
		$_degree = $this->_degree;
		$_khname = $this->_khname;
		$_enname = $this->_enname;
		$_phone  = $this->_phone;
		$_batch  = $this->_batch;
		$_year   = $this->_year;
		$_session= $this->_session;
		$_dob = $this->_dob;
		$_pay_date=$this->_pay_date;
		$_remark=$this->_remark;
		
		$_dob->setValue(date("Y-m-d"));
		
		$_invoice_no = new Zend_Dojo_Form_Element_TextBox('reciept_no');
		$_invoice_no->setAttribs(array('dojoType'=>$this->tvalidate,'class'=>'fullside',
				//'onkeyup'=>'CheckReceipt()'
				'required'=>'true',
				'class'=>'fullside'
				));
		
		$rs_metion_opt = Application_Model_DbTable_DbGlobal::getAllMention();
		$metion = new Zend_Dojo_Form_Element_FilteringSelect('metion');
		$metion->setAttribs(array('dojoType'=>$this->filter,
				'class'=>'fullside',
				'onchange'=>'getTuitionFee();'));
		$metion->setMultiOptions($rs_metion_opt);
		
		$_new_student = new Zend_Form_Element_Checkbox('is_new');
		$_new_student->setAttribs(array('dojoType'=>"dijit.form.CheckBox",
				'class'=>'fullside',
				'Onchange'=>"getNewStudent();"));
		
		$_is_hold = new Zend_Form_Element_Checkbox('is_hold');
		$_is_hold->setAttribs(array('dojoType'=>"dijit.form.CheckBox",
				'class'=>'fullside',
			));
		//$_is_hold->setValue(1);
		
		$_year_one = new Zend_Dojo_Form_Element_TextBox('is_year_one');
		$_year_one->setAttribs(array('dojoType'=>"dijit.form.CheckBox",
				'class'=>'fullside',
				'Onchange'=>"getNewStudent();"));
		
		$_studid = new Zend_Dojo_Form_Element_TextBox('stu_id');
		$_studid->setAttribs(array('dojoType'=>$this->text,'class'=>'fullside',));
		
		$_sex =  new Zend_Dojo_Form_Element_FilteringSelect('sex');
		$_sex->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		$sex_opt = array(
				1=>$this->tr->translate("MALE"),
				2=>$this->tr->translate("FEMALE"));
		$_sex->setMultiOptions($sex_opt);
		
		$_db = new Application_Model_DbTable_DbGlobal();
		$rows = $_db->getAllFecultyName();
		$opt = '' ;//array(-1=>$this->tr->translate("SELECT_DEPT"));
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
		
		  $_term = new Zend_Dojo_Form_Element_FilteringSelect("payment_term");
		  $opt_term = $_db->getAllPaymentTerm();
// 		  $opt_term = array(
// 		  		1=>$this->tr->translate('QUARTER'),
// 		  		2=>$this->tr->translate('SEMESTER'),
// 		  		3=>$this->tr->translate('YEAR'),
// 		  		4=>$this->tr->translate('OTHER')
// 		  );
		  $_term->setMultiOptions($opt_term);
		  $_term->setAttribs(array(
		  		'dojoType'=>$this->filter,
		  		'required'=>'true',
		  		'class'=>'fullside',
		  		'onchange'=>'getTuitionFee();'));
		
		$_fee = new Zend_Dojo_Form_Element_NumberTextBox('tuitionfee');
		$_fee->setAttribs(array(
				'dojoType'=>$this->t_num,
				'required'=>'true','class'=>'fullside',
				'onkeyup'=>'CheckAmount();'
				));

		$_disc = new Zend_Dojo_Form_Element_NumberTextBox('discount');
		$_disc->setAttribs(array(
				'dojoType'=>$this->text,
				'class'=>'fullside',
				'onkeyup'=>'CheckAmount();'));
		$_disc->setValue(0);
		
		$_paid = new Zend_Dojo_Form_Element_NumberTextBox('payment_paid');
		$_paid->setAttribs(array(
				'dojoType'=>$this->t_num,
				'required'=>'true','class'=>'fullside',));
		
		$_paid_kh = new Zend_Dojo_Form_Element_Textarea('paid_kh');
		$_paid_kh->setAttribs(array(
				'dojoType'=>$this->text,'class'=>'fullside',));
		
		$this->addElements(array(
			  $_year_one,$_new_student,$_invoice_no, $_pay_date, $_khname, $_enname,$_studid, $_sex,$_dob,$_degree,$metion,
			  $_phone,$_dept,$_major,$_batch,$_year,$_session,$_term,$_fee,$_disc,$_paid,$_paid_kh,$_remark,$_is_hold ));
		
		return $this;
	}
	public function FrmStudentRequest($data=null){
		
		$_degree = $this->_degree;
		$_khname = $this->_khname;
		$_enname = $this->_enname;
		$_phone  = $this->_phone;
		$_batch  = $this->_batch;
		$_year   = $this->_year;
		$_session= $this->_session;
		$_dob = $this->_dob;
		$_pay_date=$this->_pay_date;
		$_remark = $this->_remark;
		
		$_reciept_no = new Zend_Dojo_Form_Element_TextBox('reciept_no');
		$_reciept_no->setAttribs(array('dojoType'=>$this->t_num,'class'=>'fullside',
				//'onkeyup'=>'CheckReceipt()'
				'dojoType'=>$this->t_num,
				'required'=>'true',
				'class'=>'fullside'
				));
		
		$_studid = new Zend_Dojo_Form_Element_TextBox('stu_id');
		$_studid->setAttribs(array('dojoType'=>$this->text,'class'=>'fullside',));
		
		$_pob = new Zend_Dojo_Form_Element_TextBox('pob');
		$_pob->setAttribs(array('dojoType'=>$this->text,'class'=>'fullside',));
		
		$_cur_add = new Zend_Dojo_Form_Element_TextBox('current_add');
		$_cur_add->setAttribs(array('dojoType'=>$this->text,'class'=>'fullside',));
		
		$_fee = new Zend_Dojo_Form_Element_NumberTextBox('payment_paid');
		$_fee->setAttribs(array(
				'dojoType'=>$this->t_num,
				'required'=>'true','class'=>'fullside',));
		
		$_db = new Application_Model_DbTable_DbGlobal();
		$rows = $_db->getAllFecultyName();
		//$rows = $_db->getGlobalDb('SELECT en_name,dept_id FROM rms_dept WHERE is_active=1 AND en_name !="" ');
		$opt = "";
		if(!empty($rows))foreach($rows AS $row) $opt[$row['dept_id']]=$row['en_name'];
			
		$_dept = new Zend_Dojo_Form_Element_FilteringSelect("dept");
		$_dept->setMultiOptions($opt);
		$_dept->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				//'missingMessage'=>'Invalid Module!',
				'class'=>'fullside',));
		
		$rows = $_db->getAllstudentRequest();
		$re_opt = "";
		if(!empty($rows))foreach($rows AS $row) $re_opt[$row['service_id']]=$row['title'];
			
		$_request = new Zend_Dojo_Form_Element_FilteringSelect("request_id");
		$_request->setMultiOptions($re_opt);
		$_request->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',));
		$_save = new Zend_Dojo_Form_Element_Button('$this->tr->translate("SAVE_PAYMENT")');
		$_save->setAttribs(array(
				'dojoType'=>'dijit.form.Button',
				));$_save->setValue("save");
		$this->addElements(array(
				$_reciept_no, $_pay_date,$_pob,$_khname, $_enname,$_studid,$_dob,$_degree,
				$_phone,$_dept,$_batch,$_year,$_session,$_fee,$_cur_add,$_remark,$_request,$_save
		));
		
		return $this;
	}
	
}