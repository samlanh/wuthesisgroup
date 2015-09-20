<?php 
Class Foundation_Form_FrmAddStudentRoom extends Zend_Dojo_Form {
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
	public function FrmAddStudentRoom($data=null){
		$_start_date = new Zend_Dojo_Form_Element_DateTextBox('dob');
		$date = date("Y-MM-d"); 
		$_start_date->setAttribs(array( 'data-dojo-type'=>"dijit.form.DateTextBox",
				 'data-dojo-props'=>"value:'$date','class':'fullside'", 'required'=>true));
		$_expire_date = new Zend_Dojo_Form_Element_TextBox('expire_date');
		$_expire_date->setAttribs(array(
				'dojoType'=>$this->t_date,'class'=>'fullside','required'=>'true',
				//	'constraints'=>'{datePattern:"dd/MM/yyyy"'
		));
		$_check=new Zend_Dojo_Form_Element_RadioButton("check");
		$_check->setMultiOptions(array( '1' => 'check', '0' => 'allow'));
// 		$_check=$this->createElement('radio','Choose'); 
// 		$_check->setLabel('Choose:')->addMultiOptions(array( 'check' => 'check', 'allow' => 'allow' ))
// 		 ->setSeparator('');
		//$_allow=new Zend_Dojo_Form_Element_RadioButton("allow");
		$_db_room = new Application_Model_DbTable_DbGlobal();
		$rows_room = $_db_room->getGlobalDb('SELECT room_id,room_name FROM rms_room WHERE is_active=1 AND room_name !="" ');
		$_room_opt = "";
		if(!empty($rows_room))foreach($rows_room AS $row) $_room_opt[$row['room_id']]=$row['room_name'];
		$_room_name = new Zend_Dojo_Form_Element_FilteringSelect("room_name");
		$_room_name->setMultiOptions($_room_opt);
		$_room_name->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				//'missingMessage'=>'Invalid Module!',
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
		$rows = $_db->getGlobalDb('SELECT major_id,major_enname FROM rms_major WHERE is_active=1 AND major_enname !="" ');
		$opt = "";
		if(!empty($rows))foreach($rows AS $row) $opt[$row['major_id']]=$row['major_enname'];
		 
		$_major = new Zend_Dojo_Form_Element_FilteringSelect("major");
		$_major->setMultiOptions($opt);
		$_major->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				//'missingMessage'=>'Invalid Module!',
				'class'=>'fullside',));
		
		
		$this->addElements(array($_start_date,$_check,$_room_name,$_session,$_major,$_expire_date));
		
		return $this;
	}
}