<?php

class Application_Model_DbTable_DbGlobal extends Zend_Db_Table_Abstract
{
    // set name value
	public function setName($name){
		$this->_name=$name;
	}
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	
	/**
	 * get selected record of $sql
	 * @param string $sql
	 * @return array $row;
	 */
	public function getGlobalDb($sql)
  	{
  		$db=$this->getAdapter();
  		$row=$db->fetchAll($sql);  		
  		if(!$row) return NULL;
  		return $row;
  	}
  	
  	public function getGlobalDbRow($sql)
  	{
  		$db=$this->getAdapter();  		
  		$row=$db->fetchRow($sql);
  		if(!$row) return NULL;
  		return $row;
  	}
  	
  	public static function getActionAccess($action)
    {
    	$arr=explode('-', $action);
    	return $arr[0];    	
    }     
    
    public function isRecordExist($conditions,$tbl_name){
		$db=$this->getAdapter();		
		$sql="SELECT * FROM ".$tbl_name." WHERE ".$conditions." LIMIT 1"; 
		$row= count($db->fetchRow($sql));
		if(!$row) return NULL;
		return $row;	
    }
    /*for select 1 record by id of earch table by using params*/
    public function GetRecordByID($conditions,$tbl_name){
    	$db=$this->getAdapter();
    	$sql="SELECT * FROM ".$tbl_name." WHERE ".$conditions." LIMIT 1";
    	$row = $this->fetchRow($sql);
    	return $row;
    	$row= $db->fetchRow($sql);
    	return $row;
    }
    
    /**
     * insert record to table $tbl_name
     * @param array $data
     * @param string $tbl_name
     */
    public function addRecord($data,$tbl_name){
    	//print_r($data);exit;    	
    	$this->setName($tbl_name);
    	return $this->insert($data);
    }
    
    /**
     * update record to table $tbl_name
     * @param array $data
     * @param int $id
     * @param string $tbl_name
     */
    public function updateRecord($data,$id,$tbl_name){
    	//print_r($data);exit;
    	$this->setName($tbl_name);
    	$where=$this->getAdapter()->quoteInto('id=?',$id);
    	$this->update($data,$where);    	
    }
    
    public function DeleteRecord($tbl_name,$id){
    	$db = $this->getAdapter();
		$sql = "UPDATE ".$tbl_name." SET status=0 WHERE id=".$id;
		return $db->query($sql);
    } 

     public function DeleteData($tbl_name,$where){
    	$db = $this->getAdapter();
		$sql = "DELETE FROM ".$tbl_name.$where;
		return $db->query($sql);
    } 
    
    public function convertStringToDate($date, $format = "Y-m-d H:i:s")
    {
    	if(empty($date)) return NULL;
    	$time = strtotime($date);
    	return date($format, $time);
    }
    function getAllProvince($opt=null,$option=null){
    	$db= $this->getAdapter();
    	$sql="SELECT province_id AS id,province_en_name AS province_name FROM rms_province WHERE is_active=1 AND province_en_name!=''";
    	$rows =  $db->fetchAll($sql);
    	if($opt==null){
    		return $rows;
    	}else{
    		if($option!=null){
    			$opt_province = array(-1=>"Please Select Location");
    		}else{$opt_province=array();}
    		if(!empty($rows))foreach($rows AS $row) $opt_province[$row['id']]=$row['province_name'];
    		return $opt_province;
    	}
    }
    function getAllHighSchool($pro_id=null){
    	$db = $this->getAdapter();
    	$sql = " SELECT 
					CONCAT(school_name,' - '
					,(SELECT province_en_name FROM rms_province AS p 
					WHERE p.province_id= sp.province_id)) AS name,id,province_id
					FROM rms_school_province AS sp WHERE status=1 ";
    	if($pro_id!=null){
    		$sql.=" AND province_id = $pro_id";
    		return $db->fetchAll($sql);
    	}
    	$sql.=" ORDER BY sp.province_id ";
    	return $db->fetchAll($sql);
    }
    public function getMarjorById($major_id){ 
    	$db = $this->getAdapter();
    	$sql=" SELECT major_id AS id,major_enname AS name FROM `rms_major`
    	WHERE `dept_id` = $major_id ORDER BY major_id DESC";
    	return $db->fetchAll($sql);
    }
    public function getAllRoom(){
    	$db = $this->getAdapter();
    	$sql=" SELECT room_id AS id ,room_name As name FROM `rms_room` WHERE is_active=1 AND room_name!='' order by room_id DESC ";
    	return $db->fetchAll($sql);
    }
   
    function getAllMajor(){
    	$db = $this->getAdapter();
    	$sql = "SELECT major_id AS id ,CONCAT(major_enname,'-',major_khname) AS name,CONCAT(major_enname,'-',major_khname) AS major_name FROM `rms_major` 
    				WHERE is_active=1 AND (major_khname!='' OR major_enname!='') Order BY major_id DESC";
    	return $db->fetchAll($sql);
    }   
    public static function getResultWarning(){
          return array('err'=>1,'msg'=>'មិន​ទាន់​មាន​ទន្និន័យ​នូវ​ឡើយ​ទេ!');	
   }
   public function getDeptById($dept_id){
   		$db = $this->getAdapter();
   		$sql=" SELECT * FROM `rms_dept`
   		WHERE `dept_id` = ".$db->quote($dept_id);
   		return $db->fetchRow($sql);
   }
   public function getAllFecultyName(){
   	$db = $this->getAdapter();
   	$sql ="SELECT DISTINCT en_name,dept_id,shortcut FROM rms_dept WHERE is_active=1 AND en_name!='' ORDER BY dept_id DESC";
   	return $db->fetchAll($sql);
   }
   public function getAllServiceItemsName($status=1,$type=null){
   	$db = $this->getAdapter();
   	if($status==1){
   		
   		$sql ="SELECT DISTINCT title,service_id FROM rms_program_name WHERE title!='' AND status=1 ORDER BY title";
   		
   	}else{
   		
   		$sql ="SELECT DISTINCT title,service_id AS id FROM rms_program_name WHERE title!='' ORDER BY title";
   	
   	}
   return $db->fetchAll($sql);
   }
   public function getAllstudentRequest($type=null){
   	$db = $this->getAdapter();
   	if($type!=null){
   		$sql = "SELECT service_id,pn.title FROM `rms_program_type` AS pt,`rms_program_name` AS pn
   		WHERE pt.id = pn.ser_cate_id AND pt.type=$type
   		AND pn.status = 1 AND pn.title!=''";
   		return $db->fetchAll($sql);
   	}else{
   	$sql = 'SELECT service_id,pn.title FROM `rms_program_type` AS pt,`rms_program_name` AS pn 
   			WHERE pt.id = pn.ser_cate_id AND pt.type=1 
   				AND pn.status = 1 AND pn.title!=""';
   	}
   	return $db->fetchAll($sql);
   }
   public function getAllsubject(){
   	$db = $this->getAdapter();
   	$sql = "SELECT id ,CONCAT(subject_titleen,'-',subject_titlekh) AS  subject_name 
   			FROM `rms_subject` WHERE status=1 AND(subject_titleen!='' OR subject_titlekh!='')";
   	return $db->fetchAll($sql);
   }
   public function getAllTeacherSubject(){
   	$db = $this->getAdapter();
   	$sql = "SELECT ts.id,
      				(SELECT CONCAT(t.teacher_name_kh,'-',t.teacher_name_kh)
   				FROM `rms_teacher` AS t WHERE t.id=ts.teacher_id  AND status=1 LIMIT 1) AS teacher_name ,
      				(SELECT CONCAT(s.subject_titleen,'-',s.subject_titlekh)
   				FROM `rms_subject` AS s WHERE s.id=ts.subject_id  AND status=1 LIMIT 1) AS subject_name
   			FROM `rms_teacher_subject` AS ts WHERE status=1";
   	return $db->fetchAll($sql);
   }
   function getAllDept($search, $start, $limit){
   	$db = $this->getAdapter();
   	$sql = $this->_buildQuery($search)." LIMIT ".$start.", ".$limit;
   	if ($limit == 'All') {
   		$sql = $this->_buildQuery($search);
   	}
   	return $db->fetchAll($sql);
   }
   
   function getCountDept($search=''){
   	$db = $this->getAdapter();
   	$sql = $this->_buildQuery();
   	if(!empty($search)){
   		$sql = $this->_buildQuery($search);
   	}
   	$_result = $db->fetchAll($sql);
   	return count($_result);
   }
   public function getGlobalResultList($sql,$sql_count){
   	$db = $this->getAdapter();
   	$rows= $db->fetchAll($sql);
   	$_count = count($db->fetchAll($sql_count));
   	return array(0=>$rows,1=>$_count);
//get all result by param 0 ,get count record by param1
   }
   
   /*@author Mok Channy
    * for use session navigetor 
    * */
   public static function SessionNavigetor($name_space,$array=null){
   	$session_name = new Zend_Session_Namespace($name_space);
   	return $session_name;   	
   }
   public function getAllDegree($id=null){
	   $rs = array(
	   		    2=>$this->tr->translate("BACHELOR"),
	   			1=>$this->tr->translate("ASSOCIATE"),
	   			3=>$this->tr->translate('MASTER'),
	   			4=>$this->tr->translate('DOCTORATE'),
	   			//5=>$this->tr->translate('INTERNATION_PROGRAM')
	   		
	   );
	   if($id==null)return $rs; 
	   return $rs[$id];
   }
   public static  function getAllStatus($id=null){
   	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
   	$rs = array(
   			1=>$tr->translate("ACTIVE"),
   			0=>$tr->translate("DEACTIVE"));
   	if($id==null)return $rs;
   	return $rs[$id];
   }
   public function AllStatus($id=null){
   	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
   	$rs = array(
   			1=>$tr->translate("ACTIVE"),
   			0=>$tr->translate("DEACTIVE"));
   	if($id==null)return $rs;
   	return $rs[$id];
   }
   public function AllStatusHour($id=null){
   	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
   	$rs = array(
   			1=>$tr->translate("FULL_TIME"),
   			0=>$tr->translate("PART_TIME"));
   	if($id==null)return $rs;
   	return $rs[$id];
   }
   public static function getAllDegreeById($id=null){
   	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
   	$rs = array(
   			1=>$tr->translate("ASSOCIATE"),
   			2=>$tr->translate("BACHELOR"),
   			3=>$tr->translate('MASTER'),
   			4=>$tr->translate('DOCTORATE'),
   			5=>$tr->translate('INTERNATION_PROGRAM'));
   	if($id==null)return $rs;
   	return $rs[$id];
   }
   public function getAllPaymentTerm($id=null){
   	$opt_term = array(
   			1=>$this->tr->translate('QUARTER'),
   			2=>$this->tr->translate('SEMESTER'),
   			3=>$this->tr->translate('YEAR'),
   			4=>$this->tr->translate('FULL_FEE')
   	);
   	if($id==null)return $opt_term;
   	else return $opt_term[$id]; 
   }
   public function getAllServicePayment($id=null){
   	$opt_term = array(
   		1=>$this->tr->translate('RIEL'),
   		2=>$this->tr->translate('PRICE1'),
   		3=>$this->tr->translate('PRICE2'));
   	if($id==null)return $opt_term;
   	else return $opt_term[$id];
   }
   public function getAllGEPPrgramPayment($id=null){
   	$opt_term = array(
   			1=>$this->tr->translate('FEE'),
   			2=>$this->tr->translate('2TERM'),
   			3=>$this->tr->translate('3TERM'));
   	if($id==null)return $opt_term;
   	else return $opt_term[$id];
   }
   public static function getSessionById($id=null){
   	$tr= Application_Form_FrmLanguages::getCurrentlanguage();
   	$arr_opt = array(
   			1=>$tr->translate('MORNING'),
			2=>$tr->translate('AFTERNOON'),
			3=>$tr->translate('EVERNING'),
			4=>$tr->translate('WEEKEND'));
   	if($id!=null){
   		return $arr_opt[$id];
   	}return $arr_opt;
   	
   }
   public static function getAllMention($id=null){
   	$tr= Application_Form_FrmLanguages::getCurrentlanguage();
    $opt_rank = array(
		  		1=>$tr->translate('A'),
		  		2=>$tr->translate('B'),
		  		3=>$tr->translate('C'),
		  		4=>$tr->translate('D'),
		  		5=>$tr->translate('E'),
		  );
    if($id==null)return $opt_rank;
    else return $opt_rank[$id];
   }
   public function getServiceType($type=null){
   	$db = $this->getAdapter();
   	$sql ="SELECT DISTINCT title,id FROM rms_program_type WHERE title!='' AND status=1 ";
   	if(!empty($type)){$sql.=" AND type=$type";}
   	$order = " ORDER BY title";
   	return $db->fetchAll($sql.$order);
   }
   public function getAllTypeCategory($id = null){
   	$_status_type = array(
   			1=>$this->tr->translate("SERVICE"),
   			2=>$this->tr->translate("PROGRAM"));
   	if($id==null)return $_status_type;
   	else return $_status_type[$id];
    
   }
   public function getServicTypeByName($cate_title,$type){
   	$db = $this->getAdapter();
   	$sql ="SELECT * FROM rms_program_type WHERE title!='' AND title='".$cate_title."' AND type= $type";
   	return $db->fetchRow($sql);
   }
   public function getServiceFeeByServiceWtPayType($service_id,$pay_type){
   	$sql = "SELECT * FROM rms_servicefee_detail WHERE service_id = $service_id AND pay_type =$pay_type LIMIT 1";
   	return $this->getAdapter()->fetchRow($sql);
   }
   public function getRate(){
   	$_db = $this->getAdapter();
   	$_sql = "SELECT * FROM rms_rate ";
   	return $_db->fetchRow($_sql);
   }
   public  function getTutionFeebyCondition($data){
   	$db = $this->getAdapter();
   	//for bachelor
   	$degree = $data['degree'];
   	$metion = $data['metion'];
   	$batch = $data['batch'];
   	$faculty_id = $data['faculty_id'];
   	$payment_type = $data['payment_term'];
   	if($degree==2){
   		$sql = " SELECT tuition_fee FROM `rms_tuitionfee` AS f,`rms_tuitionfee_detail` AS fd
   		WHERE f.fee_id = fd.fee_id AND metion = $metion AND  degree =$degree AND
   		batch = $batch AND faculty_id = $faculty_id AND `payment_type`=$payment_type LIMIT 1";
   	}else{
   		$sql = "SELECT tuition_fee FROM `rms_tuitionfee` AS f,`rms_tuitionfee_detail` AS fd
   		WHERE f.fee_id = fd.fee_id AND metion = $faculty_id AND  degree =$degree AND
   		batch = $batch AND `payment_type`=$payment_type";
   	}
   	return $db->fetchOne($sql);
   	
   }
   function getTeacherCode(){
	   	$db = $this->getAdapter();
	   	$sql ="SELECT COUNT(id) AS number FROM `rms_teacher` LIMIT 1 ";
	   	$acc_no = $db->fetchOne($sql);
	   	 
	   	$new_acc_no= (int)$acc_no+1;
	   	$acc_no= strlen((int)$acc_no+1);
	   	$pre="L";
	   	for($i = $acc_no;$i<3;$i++){
	   		$pre.='0';
	   	}
	   	return $pre.$new_acc_no;
   }
   function getPrefixCode(){
   	$db  = $this->getAdapter();
   	$sql = " SELECT prefix FROM `ln_branch` ";
   	return $db->fetchOne($sql);
   }
   function getallComposition(){
   	$db  = $this->getAdapter();
   	$sql = " SELECT occupation_id AS id,occu_name AS name FROM `rms_occupation` WHERE occu_name!='' AND status=1 ORDER BY id DESC ";
   	return $db->fetchAll($sql);
   	
   }
   function getallSituation(){
   	$db  = $this->getAdapter();
   	$sql = " SELECT situ_id AS id ,situ_name AS name FROM `rms_situation` WHERE situ_name!='' AND status=1 ORDER BY id DESC ";
   	return $db->fetchAll($sql);
   }
  
   
   
   
}
?>