<?php

class Registrar_Model_DbTable_DbwuRegister extends Zend_Db_Table_Abstract
{
    protected $_name = 'rms_student';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	public function AddNewStudent($_data){
		try{
			print_r($_data);exit();
		$db = $this->getAdapter();
		$db->beginTransaction();
		$is_hold  =0;
		if(!empty($_data['is_hold'])){
			$is_hold = $_data['is_hold'];
		}
		if(!empty($_data['is_new'])){
			$_isnewstudent = $_data['is_new'];
		}
		$_arr=array(
				'stu_type'		=>1,
				'stu_code'		=>2222,
				'fm_user'		=>0,
				'stu_card'		=>'WU-001',
				'mention'		=> $_data['metion'],
				'stu_enname' 	=> $_data['en_name'],
				'stu_khname' 	=> $_data['kh_name'],
				'stu_card' 		=> $_data['stu_id'],
				'sex' 			=> $_data['sex'],
				'dob' 			=> $_data['dob'],
				'phone'			=> $_data['phone'],
				'degree'		=> $_data['degree'],
				'faculty_id' 	=> $_data['dept'],
				'year'		    => $_data['year'],
				'batch'			=> $_data['batch'],
				'session'		=> $_data['session'],
			    'create_date' 	=> Zend_Date::now(),
				'is_stu_new'	=> $_isnewstudent,
				'is_hold'  		=> $is_hold,
				'is_subspend'	=>1,
				'is_exam'		=>0,
				'modify_date' 	=> Zend_Date::now(),
				'status'   		=> 1,
				'remark' 		=>$_data['remark'],
				'user_id'	  	=> $this->getUserId()
		);
		 $this->insert($_arr);
		 $_name = 'rms_student_org';
		 
		 $this->insert($_arr);
		 $stu_id = $db->lastInsertId();
		 $is_new=0;
		if(!empty($_data['is_new'])){
			$is_new=$_data['is_new'];
		}
		 $arr = array(
		 		'stu_id'=>$stu_id,
		 		'is_new'=>$is_new,
		 		'payment_term'=>$_data['payment_term'],
		 		'amount'=>$_data['tuitionfee'],
		 		'discount_percent'=>$_data['discount'],
		 		'discount_fix'=>$_data['discount'],
		 		'scholarship'=>$_data['remark'],
		 		'net_tution_fee'=>$_data['tuitionfee'],
		 		'total'=>$_data['tuitionfee'],
		 		'paid_amount'=>$_data['tuitionfee'],
		 		'amount_in_khmer'=>$_data['paid_kh'],
		 		'forward_from_prev'=>$_data['tuitionfee'],
		 		'balance_due'=>$_data['tuitionfee'],
		 		'references'=>$_data['remark'],
		 		'user_id'	  	=> $this->getUserId(),
		 		'create_date' 	=> Zend_Date::now(),
		 		);
		 $this->_name='rms_student_payment';
		 $this->insert($arr);
		 $db->commit();
		}catch(Exception $e){
		 	$db->rollBack();
		}
	}	
	public function getGaneratInvoiceWU(){
		$db = $this->getAdapter();
		$sql = "SELECT invoice FROM rms_student_payment ORDER BY pay_id DESC LIMIT 1";
		$rs = $db->fetchOne($sql);
		$invoice = (int)$rs;
		$invoice_new = (int)$rs+1;
		$invoice = strlen($invoice);
		$pre = '';
		for($i=$invoice;$i<6;$i++){
			$pre.='0';
		}
		return $pre.$invoice_new;
	}
}

