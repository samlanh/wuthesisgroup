<?php 
Class Global_Form_FrmAddSchool extends Zend_Dojo_Form {
	protected $tr;
	protected $tvalidate ;//text validate
	protected $filter;
	protected $t_date;
	protected $t_num;
	protected $text;
	//protected $check;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->tvalidate = 'dijit.form.ValidationTextBox';
		$this->filter = 'dijit.form.FilteringSelect';
		$this->t_date = 'dijit.form.DateTextBox';
		$this->t_num = 'dijit.form.NumberTextBox';
		$this->text = 'dijit.form.TextBox';
		//$this->check='dijit.form.CheckBox';
	}
	public function FrmAddSchool($data=null){
	
		$_classname = new Zend_Dojo_Form_Element_TextBox('schoolname');
		$_classname->setAttribs(array('dojoType'=>$this->tvalidate,'required'=>'true','class'=>'fullside',));
		
		$_province=  new Zend_Dojo_Form_Element_FilteringSelect('province');
		$_province->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		$_db = new Application_Model_DbTable_DbGlobal();
		$rows_school = $_db->getGlobalDb("SELECT province_id,province_en_name FROM rms_province ");
		$opt_school = "";
		if(!empty($rows_school))foreach($rows_school AS $row) $opt_school[$row['province_id']]=$row['province_en_name'];
		
		$_province->setMultiOptions($opt_school);
		
		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status');
		$_status->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		$_status_opt = array(
				1=>$this->tr->translate("ACTIVE"),
				2=>$this->tr->translate("DACTIVE"));
		$_status->setMultiOptions($_status_opt);
		
		$_submit = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$_submit->setLabel("save"); 
		if(!empty($data)){
			//print_r($data);exit();
			
			$_classname->setValue($data['room_name']);
			$_status->setValue($data['is_active']);
		}
		$this->addElements(array($_classname,$_status,$_submit,$_province));
		
		return $this;
		
	}
	
}