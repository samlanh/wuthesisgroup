<?php

class Application_Form_FrmPopupGlobal extends Zend_Dojo_Form
{

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
		$this->tarea = 'dijit.form.SimpleTextarea';
	}
	public function addProgramName($data=null,$type=null){
		$_title = new Zend_Dojo_Form_Element_TextBox('title');
		$_title->setAttribs(array('dojoType'=>$this->tvalidate,'required'=>'true','class'=>'fullside',));
		
		$_db = new Application_Model_DbTable_DbGlobal();
		if(!empty($type)){
			$rows = $_db->getServiceType(2);
		}else{
			$rows = $_db->getServiceType(1);
		}
		
		//array_unshift($rows,array('id' => '-1',"title"=>$this->tr->translate("ADD")) );
		$opt = "";
		if(!empty($rows))foreach($rows AS $row) $opt[$row['id']]=$row['title'];
		$_service_name = new Zend_Dojo_Form_Element_FilteringSelect("type");
		$_service_name->setMultiOptions($opt);
		$_service_name->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',));
		
		$_desc = new Zend_Dojo_Form_Element_Textarea('desc');
		$_desc->setAttribs(array('dojoType'=>$this->tarea,'class'=>'fullside','style'=>'width:96%;min-height:50px;'	));
		
		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status_program');
		$_status->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		$_status_opt = array(
				1=>$this->tr->translate("ACTIVE"),
				2=>$this->tr->translate("DACTIVE"));
		$_status->setMultiOptions($_status_opt);
		if (!empty($data)){
			$_title->setValue($data['title']);
			$_desc->setValue($data['desc']);
		}
		$this->addElements(array($_service_name,$_title,$_desc,$_status));
		return $this;
	}
	public function addProServiceCategory($data=null){
		$_title = new Zend_Dojo_Form_Element_ValidationTextBox('servicetype_title');
		$_title->setAttribs(array('dojoType'=>$this->tvalidate,'class'=>'fullside','required'=>'true'));
	
		$_tem_desc = new Zend_Dojo_Form_Element_TextBox('item_desc');
		$_tem_desc->setAttribs(array('dojoType'=>$this->text,'required'=>'true','class'=>'fullside',));
	
		$_status = new Zend_Dojo_Form_Element_FilteringSelect('sertype_status');
		$_status->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		$_status_opt = array(
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DACTIVE"));
		$_status->setMultiOptions($_status_opt);
		
		$_type = new Zend_Dojo_Form_Element_FilteringSelect('ser_type');
		$_status_type = array(
				1=>$this->tr->translate("SERVICE"),
				2=>$this->tr->translate("PROGRAM"));
		$_type->setMultiOptions($_status_type);
		
		$_type->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		
		$_id = new Zend_Form_Element_Hidden('id');
		$_id->setAttribs(array('dojoType'=>$this->text));
		
		if($data !=null){
			$_id->setValue($data['id']);
			$_title->setValue($data['title']);
			$_tem_desc->setValue($data['item_desc']);
			$_status->setValue($data['status']);
			$_type->setValue($data['type']);
			
		}
		$this->addElements(array($_title,$_tem_desc,$_status,$_type,$_id));
	
		return $this;
	
	}
}

