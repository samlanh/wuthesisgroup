<?php 
Class Foundation_Form_FrmComposition extends Zend_Dojo_Form {
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
	public function FrmAddComposition($data=null){
	
		$_Composition_title = new Zend_Dojo_Form_Element_TextBox('composition_title');
		$_Composition_title->setAttribs(array('dojoType'=>$this->tvalidate,'required'=>'true','class'=>'fullside',));
		
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
			
			$_Composition_title->setValue($data['situation_name']);
			$_status->setValue($data['is_active']);
		}
		$this->addElements(array($_Composition_title,$_status,$_submit));
		
		return $this;
		
	}
	
}