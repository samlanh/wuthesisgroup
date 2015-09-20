<?php

class Global_Model_DbTable_DbDept extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_major';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	private function sqlDept($search = ''){
		$db = $this->getAdapter();
		$sql = " SELECT dept_id,en_name,kh_name,shortcut,modify_date,is_active,
		       (SELECT CONCAT(last_name,' ',first_name) FROM rms_users WHERE user_id=id ) AS user_name
		       FROM rms_dept WHERE 1 ";
		$orderby = " ORDER BY en_name ";
		if(empty($search)){
			return $sql.$orderby;
		}
		$where = ' ';
		if(!empty($search['title'])){
			$where.=" AND ( en_name LIKE '%".$db->quote($search['title'])."%' OR kh_name LIKE '%".$db->quote($search['title'])."%') ";
		}
		if($search['status']>-1){
			$where.= " AND is_active = ".$db->quote($search['status']);
		}
		return $sql.$where.$orderby;
	}
	function getAllDept($search, $start, $limit){        
    	$sql_rs = $this->sqlDept($search)." LIMIT ".$start.", ".$limit;
		if ($limit == 'All') {
			$sql_rs = $this->sqlDept($search);
		}
		$sql_count = $this->sqlDept();
		if(!empty($search)){
			$sql_count = $this->sqlDept($search);
		}
		$_db = new Application_Model_DbTable_DbGlobal();
		return($_db ->getGlobalResultList($sql_rs,$sql_count));
		
	}	
	 function getAllFacultyList($search = ''){
		$db = $this->getAdapter();
		$sql = " SELECT dept_id,en_name,kh_name,shortcut,modify_date,is_active as status,
		       (SELECT CONCAT(last_name,' ',first_name) FROM rms_users WHERE user_id=id ) AS user_name
		       FROM rms_dept WHERE 1 ";
		$orderby = " ORDER BY en_name ";
		if(empty($search)){
			return $db->fetchAll($sql.$orderby);
		}
		$where = ' ';
		if(!empty($search['title'])){
			$where.=" AND ( en_name LIKE '%".$db->quote($search['title'])."%' OR kh_name LIKE '%".$db->quote($search['title'])."%') ";
		}
		if($search['status']>-1){
			$where.= " AND is_active = ".$db->quote($search['status']);
		}
		return $db->fetchAll($sql.$where.$orderby);
	}
	public function AddNewDepartment($_data){
		$this->_name='rms_dept';
		$_arr=array(
				'en_name'	  => $_data['en_name'],
				'kh_name'	  => $_data['kh_name'],
				'shortcut'    => $_data['shortcut'],
				'modify_date' => Zend_Date::now(),
				'is_active'   => $_data['status'],
				'user_id'	  => $this->getUserId()
		);
		return  $this->insert($_arr);
	}
	
	public function getAllMajorList($search=''){
		$db = $this->getAdapter();
		$sql = " SELECT m.major_id AS id, m.major_enname,m.major_khname
       ,(select d.en_name from rms_dept AS d where m.dept_id=d.dept_id )AS dept_name
       ,m.shortcut,m.modify_date,m.is_active as status,
       (select first_name from rms_users where id=m.user_id) AS user_name
       FROM rms_major AS m WHERE 1";
		$order=" order by m.major_enname ,dept_name";
		$where = '';
		if(empty($search)){
			return $db->fetchAll($sql.$order);
		}
		if(!empty($search['title'])){
			$where.=" AND ( m.major_enname LIKE '%".$db->quote($search['title'])."%' OR m.major_khname LIKE '%".$db->quote($search['title'])."%') ";
		}
		if($search['status']>-1){
			$where.= " AND m.is_active = ".$db->quote($search['status']);
		}
		return $db->fetchAll($sql.$where.$order);
	}
	
	public function sqlMajor($search=''){
		$db = $this->getAdapter();
		$sql = " SELECT m.major_id AS id, m.major_enname,m.major_khname
       ,(select d.en_name from rms_dept AS d where m.dept_id=d.dept_id )AS dept_name
       ,m.shortcut,m.modify_date,m.is_active,
       (select first_name from rms_users where id=m.user_id) AS user_name
       FROM rms_major AS m WHERE 1";
		$order=" order by m.major_enname ,dept_name";
		$where = '';
		if(empty($search)){
			return $sql.$order;
		}
		if(!empty($search['title'])){
			$where.=" AND ( m.major_enname LIKE '%".$db->quote($search['title'])."%' OR m.major_khname LIKE '%".$db->quote($search['title'])."%') ";
		}
		if($search['status']>-1){
			$where.= " AND m.is_active = ".$db->quote($search['status']);
		}
		return $sql.$where.$order;
	}
	function getAllMajors($search, $start, $limit){
		
		$sql_rs = $this->sqlMajor($search)." LIMIT ".$start.", ".$limit;
		if ($limit == 'All') {
			$sql_rs = $this->sqlMajor($search);
		}
		$sql_count = $this->sqlMajor();
		if(!empty($search)){
			$sql_count = $this->sqlMajor($search);
		}
		$_db = new Application_Model_DbTable_DbGlobal();
		return($_db ->getGlobalResultList($sql_rs,$sql_count));
// 		return array(0=>$rows,1=>$_count);//get all result by param 0 ,get count record by param 1
	}
	
	public function AddNewMajor($_data){
		$this->_name='rms_major';
			$_arr=array(
					'dept_id'	  => $_data['dept_id'],
					'major_enname'  => $_data['major_enname'],
					'major_khname'  => $_data['major_khname'],
					'shortcut'	  => $_data['shortcut'],
					'modify_date' => Zend_Date::now(),
					'is_active'	  => $_data['status'],
					'user_id'	  => $this->getUserId()
			);
			return  $this->insert($_arr);
	}
	public function getMajorById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_major WHERE major_id = ".$db->quote($id);
		return($db->fetchRow($sql));
		
	}
	public function updatMajorById($_data){
		$this->_name='rms_major';
		$_arr=array(
				'dept_id'	  => $_data['dept'],
				'major_enname'  => $_data['major_enname'],
				'major_khname'  => $_data['major_khname'],
				'shortcut'	  => $_data['shortcut'],
				'modify_date' => Zend_Date::now(),
				'is_active'	  => $_data['status'],
				'user_id'	  => $this->getUserId()
		);
		$where = $this->getAdapter()->quoteInto("major_id=?", $_data["major_id"]);
		$this->update($_arr, $where);
	}

}



