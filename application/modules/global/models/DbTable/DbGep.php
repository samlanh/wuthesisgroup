<?php

class Global_Model_DbTable_DbGep extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_subject';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	public function AddNewSubjectGep($_data){
		$_arr=array(
				'subject_name' => $_data['subject'],
				'modify_date' => Zend_Date::now(),
				'is_active'   => $_data['status'],
				'user_id'	  => $this->getUserId()
		);
		return  $this->insert($_arr);
	}
}

