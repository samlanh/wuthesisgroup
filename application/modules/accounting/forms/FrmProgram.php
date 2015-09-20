<?php 
Class Accounting_Form_FrmProgram extends Zend_Dojo_Form {
	protected $tr;
	protected $tvalidate ;//text validate
	protected $filter;
	protected $t_num;
	protected $text;
	protected $tarea;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->tvalidate = 'dijit.form.ValidationTextBox';
		$this->filter = 'dijit.form.FilteringSelect';
		$this->text = 'dijit.form.TextBox';
		//$this->tarea = 'dijit.form.Textarea';
		$this->tarea = 'dijit.form.SimpleTextarea';
	}
	public function addProgramName($data=null){
		$_db = new Application_Model_DbTable_DbGlobal();
		$rows=$_db->getServiceType();
		$opt='';
		if(!empty($rows))foreach($rows AS $row) $opt[$row['id']]=$row['title'];
		
		$_type_service = new Zend_Dojo_Form_Element_FilteringSelect('title');
		$_type_service->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		$_type_service->setMultiOptions($opt);
		
		$_type = new Zend_Dojo_Form_Element_FilteringSelect('type');
		$_status_type = array(
				"-1"=>"",
				1=>$this->tr->translate("SERVICE"),
				2=>$this->tr->translate("PROGRAM"));
		$_type->setMultiOptions($_status_type);
		
		$_type->setAttribs(array('dojoType'=>$this->filter,
				'class'=>'fullside','required'=>'true',
				'onchange'=>'getProgramByType();'));
		
		$_title = new Zend_Dojo_Form_Element_TextBox('add_title');
		$_title->setAttribs(array('dojoType'=>$this->tvalidate,'required'=>'true','class'=>'fullside',));
		
		$_desc = new Zend_Dojo_Form_Element_Textarea('desc');
		$_desc->setAttribs(array('dojoType'=>$this->tarea,'class'=>'fullside',
		'style'=>'width:96%;min-height:50px;'));
		//$_desc->setAttribs(array('dojoType'=>$this->tarea,'class'=>'fullside',	));
		
		
		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status');
		$_status->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		$_status_opt = array(
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DACTIVE"));
		$_status->setMultiOptions($_status_opt);
		if (!empty($data)){
			$_title->setValue($data['title']);
			$_desc->setValue($data['desc']);
			$_title->setValue($data['title']);
			$_type->setValue($data['ser_cate_id']);
			$_type_service->setValue($data['service_id']);
			$_status->setValue($data['status']);
		}
		$this->addElements(array($_type,$_type_service,$_title,$_desc,$_status));
		
		return $this;
		
	}
	
	
}