<?php 
Class accounting_Form_FrmDrop extends Zend_Dojo_Form {
	
	public function AddDropStudent(){
		
		$name = new Zend_Dojo_Form_Element_TextBox('Name');
		$name->setAttribs(array('dojoType'=>'dijit.form.ValidationTextBox','class'=>'fullside',));
		//$this->addElements(array($name));
		$_type = new Zend_Dojo_Form_Element_FilteringSelect('type');
		$_type->setAttribs(array('dojoType'=>'dijit.form.FilteringSelect','class'=>'fullside',));
		$_type_opt = array(
				1=>Application_Form_FrmLanguages::getCurrentlanguage()->translate("Suspended"),
				2=>Application_Form_FrmLanguages::getCurrentlanguage()->translate("Drop"));
		$_type->setMultiOptions($_type_opt);
		
		$date=new Zend_dojo_form_element_datetextbox('txtdate');
		$date->setAttribs(array('dojoType'=>'dijit.form.DateTextBox','class'=>'fullside',));
		$reason=new Zend_Dojo_Form_Element_Textarea('reason');
		$reason->setAttribs(array('dojoType'=>'dijit.form.Textarea','rows'=>'4','style'=>'border:1px solid #ccc'));

		$calldate = new Zend_Dojo_Form_Element_dateTextBox('calldate');
		$calldate->setAttribs(array('dojoType'=>'dijit.form.DateTextBox','class'=>'fullside'));
		
		$this->addElements(array($name,$_type,$date,$reason,$calldate));
		return $this;
		
	}
	
}