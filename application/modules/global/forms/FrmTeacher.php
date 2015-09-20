<?php 
Class Global_Form_FrmTeacher extends Zend_Dojo_Form {
	protected $tr;
	protected $tvalidate ;//text validate
	protected $filter;
	//protected $t_date;
	//protected $t_num;
	protected $text;
	//protected $check;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->tvalidate = 'dijit.form.ValidationTextBox';
		$this->filter = 'dijit.form.FilteringSelect';
		//$this->t_date = 'dijit.form.DateTextBox';
		//$this->t_num = 'dijit.form.NumberTextBox';
		$this->text = 'dijit.form.TextBox';
		//$this->check='dijit.form.CheckBox';
	}
	public function FrmTecher($_data=null){
	
		$_enname = new Zend_Dojo_Form_Element_TextBox('en_name');
		$_enname->setAttribs(array('dojoType'=>$this->tvalidate,'required'=>'true','class'=>'fullside',));
		
		$_khname = new Zend_Dojo_Form_Element_TextBox('kh_name');
		$_khname->setAttribs(array('dojoType'=>$this->text,'class'=>'fullside',));
		
		$_ensubject = new Zend_Dojo_Form_Element_TextBox('en_subject');
		$_ensubject->setAttribs(array('dojoType'=>$this->tvalidate,'required'=>'true','class'=>'fullside',));
		
		$_khsubject = new Zend_Dojo_Form_Element_TextBox('kh_subject');
		$_khsubject->setAttribs(array('dojoType'=>$this->text,'class'=>'fullside',));
		
		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status');
		$_status->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		$_status_opt = array(
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DACTIVE"));
		$_status->setMultiOptions($_status_opt);
		
		$_submit = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$_submit->setLabel("save"); 
		if(!empty($_data)){
			$_enname->setValue($_data['teacher_name_en']);
			$_khname->setValue($_data['teacher_name_kh']);
			$_ensubject->setValue($_data['subject_name_en']);
			$_khsubject->setValue($_data['subject_name_kh']);
			$_status->setValue($_data['is_active']);
		}
		$this->addElements(array($_enname,$_khname,$_ensubject,$_khsubject,$_status,$_submit));
		
		return $this;
		
	}
	
}