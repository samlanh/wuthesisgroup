<?php 
Class Global_Form_FrmTeacher extends Zend_Dojo_Form {
	protected $tr;
	protected $tvalidate ;//text validate
	protected $filter;
	protected $t_date;
	//protected $t_num;
	protected $text;
	//protected $check;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->tvalidate = 'dijit.form.ValidationTextBox';
		$this->filter = 'dijit.form.FilteringSelect';
		$this->t_date = 'dijit.form.DateTextBox';
		//$this->t_num = 'dijit.form.NumberTextBox';
		$this->text = 'dijit.form.TextBox';
		//$this->check='dijit.form.CheckBox';
	}
	public function FrmTecher($_data=null){
	
		$_enname = new Zend_Dojo_Form_Element_TextBox('en_name');
		$_enname->setAttribs(array('dojoType'=>$this->tvalidate,'required'=>'true','class'=>'fullside',));
		
		$_khname = new Zend_Dojo_Form_Element_TextBox('kh_name');
		$_khname->setAttribs(array('dojoType'=>$this->tvalidate,'required'=>'true','class'=>'fullside',));
		
		$code = new Zend_Dojo_Form_Element_TextBox('code');
		$code->setAttribs(array('dojoType'=>$this->text,'class'=>'fullside','readOnly'=>'readOnly'));
		$db = new Application_Model_DbTable_DbGlobal();
		$code_num = $db->getTeacherCode();
		$code->setValue($code_num);
		
		$phone = new Zend_Dojo_Form_Element_TextBox('phone');
		$phone->setAttribs(array('dojoType'=>$this->text,'class'=>'fullside',));
		
		$sex = new Zend_Dojo_Form_Element_FilteringSelect('sex');
		$sex->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		$options=array(1=>"M",2=>"F");
		$sex->setMultiOptions($options);
		
		$dob = new Zend_Dojo_Form_Element_DateTextBox('dob');
		$dob->setAttribs(array('dojoType'=>$this->t_date,'class'=>'fullside',));
		
		$_adress = new Zend_Dojo_Form_Element_TextBox('address');
		$_adress->setAttribs(array('dojoType'=>$this->text,'class'=>'fullside',));
		
		
		
		$db = new Application_Model_DbTable_DbGlobal();
		$db->getAllProvince();
		$rows = $db->getAllProvince();
		$opt= "";
 		if(!empty($rows))foreach($rows AS $row) $opt[$row['id']]=$row['province_name'];
			$pob = new Zend_Dojo_Form_Element_FilteringSelect('pob');
			$pob->setAttribs(array('dojoType'=>$this->filter,'class'=>'pob','class'=>'fullside'));
		$pob->setMultiOptions($opt);
		
		
		
		$_email = new Zend_Dojo_Form_Element_TextBox('email');
		$_email->setAttribs(array('dojoType'=>$this->text,'class'=>'fullside',));
		
		$_degree = new Zend_Dojo_Form_Element_FilteringSelect('degree');
		$_degree->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		
		$_note =  new Zend_Dojo_Form_Element_TextBox('note');
		$_note->setAttribs(array('dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside'));
		
		$_degree=  new Zend_Dojo_Form_Element_FilteringSelect('degree');
		$_degree->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		
		$degree_opt = $db->getAllDegree();
		$_degree->setMultiOptions($degree_opt);
		
		$_photo = new Zend_Form_Element_File('photo');
		
		
		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status');
		$_status->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		$_status_opt = array(
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DACTIVE"));
		$_status->setMultiOptions($_status_opt);
		
		$_submit = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$_submit->setLabel("save"); 
		
		$id=  new Zend_Form_Element_Hidden('id');
		if(!empty($_data)){
			$id->setValue($_data['id']);
			$code->setValue($_data['teacher_code']);
			$_enname->setValue($_data['teacher_name_en']);
			$_khname->setValue($_data['teacher_name_kh']);
			$sex->setValue($_data['sex']);
			$phone->setValue($_data['phone']);
			$pob->setValue($_data['pob']);
			$dob->setValue($_data['dob']);
			$_adress->setValue($_data['address']);
			$_email->setValue($_data['email']);
			$_degree->setValue($_data['degree']);
			$_note->setValue($_data['note']);
			$_status->setValue($_data['status']);
		}
		$this->addElements(array($id,$_enname,$_note,$_khname,$pob,$code,$phone,$sex,$dob,$_adress,$_email,$_degree,$_photo,$_status,$_submit));
		
		return $this;
		
	}
	
}