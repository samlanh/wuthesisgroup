<?php 
Class Global_Form_FrmProvince extends Zend_Dojo_Form {
	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function FrmProvince($data=null){
		
		$_en_province = new Zend_Dojo_Form_Element_TextBox('en_province');
		$_en_province->setAttribs(array('dojoType'=>'dijit.form.ValidationTextBox',
				'required'=>'true','missingMessage'=>'Invalid Module!','class'=>'fullside'
				));
		$_kh_province = new Zend_Dojo_Form_Element_TextBox('kh_province');
		$_kh_province->setAttribs(array('dojoType'=>'dijit.form.TextBox','class'=>'fullside'
		));
		$_arr = array(1=>$this->tr->translate("ACTIVE"),0=>$this->tr->translate("DACTIVE"));
		$_status = new Zend_Dojo_Form_Element_FilteringSelect("status");
		$_status->setMultiOptions($_arr);
		$_status->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'required'=>'true',
				'missingMessage'=>'Invalid Module!',
				'class'=>'fullside'));
		if(!empty($data)){
			$_en_province->setValue($data['province_en_name']);
			$_kh_province->setValue($data['province_kh_name']);
			$_status->setValue($data['is_active']);
		}
		$this->addElements(array($_en_province,$_kh_province, $_status));
		return $this;
		
	}
	
	
}