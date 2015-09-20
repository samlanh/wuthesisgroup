<?php 
Class Accounting_Form_FrmServicePrice extends Zend_Dojo_Form {
	protected $tr=null;
	protected $tvalidate=null ;//text validate
	protected $filter=null;
	protected $t_num=null;
	protected $text=null;
	protected $date=null;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->tvalidate = 'dijit.form.ValidationTextBox';
		$this->filter = 'dijit.form.FilteringSelect';
		$this->t_num = 'dijit.form.NumberTextBox';
		$this->text = 'dijit.form.TextBox';
		$this->date = 'dijit.form.DateTextBox';
		
	}
	public function FrmSetServicePrice($data=null){
		$_db = new Application_Model_DbTable_DbGlobal();
		$_year   = $this->_year;
		$_session= $this->_session;
		
		$_batch =  new Zend_Dojo_Form_Element_NumberTextBox("batch");
		$_batch -> setAttribs(array(
				'dojoType'=>$this->t_num,
				'required'=>'true',
				'class'=>'fullside',));
// 		$this->_year->setAttribs(array(
// 				'data-dojo-Type'=>$this->tvalidate,
// 				'data-dojo-props'=>"regExp:'[0-5]{1}',
// 				'required':true,'class':'fullside',
// 				'invalidMessage':'អាចបញ្ជូលពី 1 ដល់  5'"));
		
		$_degree =  new Zend_Dojo_Form_Element_FilteringSelect('degree');
		$_degree->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect','class'=>'fullside','Onchange'=>'CheckDegree()'
		
		));
		$arr_opt = $_db->getAllDegree();
		$_degree->setMultiOptions($arr_opt);
		
		
		$_remark = new Zend_Dojo_Form_Element_TextBox('remark');
		$_remark->setAttribs(array(
				'dojoType'=>$this->text,'class'=>'fullside',
		));
		
		$rows = $_db->getAllFecultyName();
		array_unshift($rows, array('dept_id'=>-1,'en_name'=>"Add New"));
		$opt = "";
		if(!empty($rows))foreach($rows AS $row) $opt[$row['dept_id']]=$row['en_name'];
		 
		$_faculty = new Zend_Dojo_Form_Element_FilteringSelect("faculty");
		$_faculty->setMultiOptions($opt);
		$_faculty->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',
				'onchange'=>'showPopupDept()',));
			  
		  $_term = new Zend_Dojo_Form_Element_FilteringSelect("payment_term");
		  $opt_term = $_db->getAllPaymentTerm();
		  $_term->setMultiOptions($opt_term);
		  $_term->setAttribs(array(
		  		'dojoType'=>$this->filter,
		  		'required'=>'true',
		  		'class'=>'fullside',));
		  
		  $_create_date = new Zend_Dojo_Form_Element_DateTextBox("create_date");
		  $_create_date->setAttribs(array(
		  		'dojoType'=>$this->date,
		  		'required'=>'true',
		  		'class'=>'fullside',));
		  $_create_date->setValue(date("Y-m-d"));
		  
		  $_rank = new Zend_Dojo_Form_Element_FilteringSelect("rank");
		  $opt_rank = $_db->getAllMention();
		  $_rank->setMultiOptions($opt_rank);
		  $_rank->setAttribs(array(
		  		'dojoType'=>$this->filter,
		  		'required'=>'true',
		  		'class'=>'fullside',));
		
		$this->addElements(array( $_degree,$_faculty,$_batch,$_year,$_session,$_term,$_remark,$_rank,$_create_date ));
		return $this;
	}
	public function frmAddServiceCharge($data=null){
		$db =new Application_Model_DbTable_DbGlobal();
		$rows = $db->getServiceType(1);
		array_unshift($rows,array('id' => '-1',"title"=>$this->tr->translate("ADD")) );
		$opt = "";
		if(!empty($rows))foreach($rows AS $row) $opt[$row['id']]=$row['title'];
		$_service_name = new Zend_Dojo_Form_Element_FilteringSelect("service_name");
		$_service_name->setMultiOptions($opt);
		$_service_name->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',
				'onchange'=>'PopupServiceCate();'));
		
		$_status = new Zend_Dojo_Form_Element_FilteringSelect("status");
		$_status->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',));
		$_rs = $db->AllStatus();
		$_status->setMultiOptions($_rs);
		
		$_create_date = new Zend_Dojo_Form_Element_DateTextBox("create_date");
		$_create_date->setAttribs(array(
				'dojoType'=>$this->date,
				'required'=>'true',
				'class'=>'fullside',));
		$_create_date->setValue(date("Y-m-d"));
		$id= new Zend_Form_Element_Hidden("id");
		if($data!=null){
			$id->setValue($data[0]['service_id']);
			
			$_service_name->setValue($data[0]['ser_cate_id']);
			$_status->setValue($data[0]['status']);
			$_create_date->setValue($data[0]['ser_cate_id']);
			$originalDate = $data[0]['create_date'];
			$newDate = date("Y-m-d", strtotime($originalDate));
			$_create_date->setValue($newDate);
			
		}
		$this->addElements(array($_service_name,$_status,$id,$_create_date));
		return $this;
	}
	public function frmAddProgramCharge($data=null){
		$db =new Application_Model_DbTable_DbGlobal();
		$rows = $db->getServiceType(2);
		array_unshift($rows,array('id' => '-1',"title"=>$this->tr->translate("ADD")) );
		$opt = "";
		if(!empty($rows))foreach($rows AS $row) $opt[$row['id']]=$row['title'];
		$_service_name = new Zend_Dojo_Form_Element_FilteringSelect("service_name");
		$_service_name->setMultiOptions($opt);
		$_service_name->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',
				'onchange'=>'PopupServiceCate();'));
		
		$_status = new Zend_Dojo_Form_Element_FilteringSelect("status");
		$_status->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',));
		$_rs = $db->AllStatus();
		$_status->setMultiOptions($_rs);
		
		$_status_hour = new Zend_Dojo_Form_Element_FilteringSelect("type_hour");
		$_status_hour->setAttribs(array(
				'dojoType'=>$this->filter,
				'required'=>'true',
				'class'=>'fullside',));
		$_rs = $db->AllStatusHour();
		$_status_hour->setMultiOptions($_rs);
		
		$_total_hour = new Zend_Dojo_Form_Element_NumberTextBox("total_hour");
		$_total_hour->setAttribs(array(
				'dojoType'=>$this->t_num,
				'required'=>'true',
				'class'=>'fullside',));
		
		$_create_date = new Zend_Dojo_Form_Element_DateTextBox("create_date");
		$_create_date->setAttribs(array(
				'dojoType'=>$this->date,
				'required'=>'true',
				'class'=>'fullside',));
		$_create_date->setValue(date("Y-m-d"));
		$id= new Zend_Form_Element_Hidden("id");
		if($data!=null){
			$id->setValue($data[0]['service_id']);
			
			$_service_name->setValue($data[0]['ser_cate_id']);
			$_status->setValue($data[0]['status']);
			$_create_date->setValue($data[0]['ser_cate_id']);
			$originalDate = $data[0]['create_date'];
			$newDate = date("Y-m-d", strtotime($originalDate));
			$_create_date->setValue($newDate);
			
		}
		$this->addElements(array($_service_name,$_status,$id,$_create_date,$_status_hour,$_total_hour));
		return $this;
	}
	
}