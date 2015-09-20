<?php

class RsvAcl_UserAccessController extends Zend_Controller_Action
{

	const REDIRECT_URL = '/rmsAcl/user-access';
	
    public function init()
    {
        /* Initialize action controller here */
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());  
    }


    public function indexAction()
    {
    // action body
    	try {
    		$db_tran=new Application_Model_DbTable_DbGlobal();
    		$db = new RsvAcl_Model_DbTable_DbUserType();
    		$result = $db->getAlluserType();
    		    		
    		$list = new Application_Form_Frmtable();
    		if(!empty($result)){
    			$glClass = new Application_Model_GlobalClass();
    			$result = $glClass->getImgActive($result, BASE_URL, true);
    		}
    		else{
    			$result = Application_Model_DbTable_DbGlobal::getResultWarning();
    		}
    		$collumns = array("USER_TYPE","PARENT","STATUS");
    		$link=array(
    				'module'=>'rsvAcl','controller'=>'userAccess','action'=>'view-user-access',
    		);
    		$this->view->list=$list->getCheckList(0,$collumns, $result,array('user_type'=>$link,'title'=>$link));
    		
    		
    		if (empty($result)){
    			$result = array('err'=>1, 'msg'=>'មិនទាន់មានទិន្នន័យនៅឡើយ!');
    		}		
    	} catch (Exception $e) {
    		$result = Application_Model_DbTable_DbGlobal::getResultWarning();
    	}
        
    }
    
public function viewUserAccessAction()
    {   
    	/* Initialize action controller here */
    	if($this->getRequest()->getParam('id')){
    		
    		$id = $this->getRequest()->getParam('id');
    		$db = new RsvAcl_Model_DbTable_DbUserType();        
        	$userAccessQuery = "SELECT user_type_id, user_type, status from rms_acl_user_type where user_type_id=".$id;
    		$rows = $db->getUserTypeInfo($userAccessQuery);	
	    	$this->view->rs=$rows;   		
	    	 	
	    	//Add filter search
	    	$gc = new Application_Model_GlobalClass();
	    	// For list all module
	    	$sql = "SELECT DISTINCT acl.`module` FROM `rms_acl_acl` AS acl";
	    	$this->view->optoin_mod =  $gc->getOptonsHtml($sql, "module", "module");
	    	// For list all controller
	    	$sql = "SELECT DISTINCT acl.`controller` FROM `rms_acl_acl` AS acl WHERE acl.`status` = 1";
	    	$this->view->optoin_con =  $gc->getOptonsHtml($sql, "controller", "controller");
	    	// For List all action
	    	$sql = "SELECT DISTINCT acl.`action` FROM `rms_acl_acl` AS acl WHERE acl.`status` = 1";
	    	$this->view->optoin_act =  $gc->getOptonsHtml($sql, "action", "action");
	    	//For Status enable or disable
	    	$this->view->optoin_status =  $gc->getYesNoOption();
	    	
	    	$where = " ";
	    	$status = null;
	    	
	    	if($this->getRequest()->isPost()){
	    		$post = $this->getRequest()->getPost();
	    		
	    		if(!empty($post['fmod'])){
	    			$where .= " AND acl.`module` = '" . $post['fmod'] . "' ";	 	    			
	    		}
	    		if(!empty($post['fcon'])){
	    			$where .= " AND acl.`controller` = '" . $post['fcon'] . "' ";
	    		}
	    		if(!empty($post['fact'])){
	    			$where .= " AND acl.`action` = '" . $post['fact'] . "' ";
	    		}
	    		if(!empty($post['fstatus'])){
	    			$status = ($post['fstatus'] === "Yes")? 1 : 0;	    			
	    			//$where .= " AND  acl.`status` = " . $st ;
	    		}
	    		$this->view->datafiter = $post;
	    		//echo $where; exit;
	    	}
	    	
	    	
	    	
	         //Sophen add here
			//to assign project list in view
			$db_acl=new Application_Model_DbTable_DbGlobal();
			
			$sqlNotParentId = "SELECT user_type_id FROM `rms_acl_user_type` WHERE `parent_id` =".$id;
			$notParentId = $db_acl->getGlobalDb($sqlNotParentId);
			$usernotparentid = $notParentId[0]['user_type_id'];
			    	
			
			if($id == 1){
				//Display all for admin id = 1
				//Do not change admin id = 1 in database 
				//Otherwise, it error
				$sql = "select acl.acl_id,CONCAT(acl.module,'/', acl.controller,'/', acl.action) AS user_access
						from rms_acl_acl as acl 
						WHERE 1 " . $where;
			}
    		    			
			else {
				//Display all of his/her parent access	
				$sql="SELECT acl.acl_id, CONCAT(acl.module,'/', acl.controller,'/', acl.action) AS user_access, acl.status 
						FROM rms_acl_user_access AS ua 
						INNER JOIN rms_acl_user_type AS ut ON (ua.user_type_id = ut.parent_id)
						INNER JOIN rms_acl_acl AS acl ON (acl.acl_id = ua.acl_id) WHERE ut.user_type_id =".$id . $where;	
			}
			//echo $sql; exit;			
			$acl=$db_acl->getGlobalDb($sql);
			$acl = (is_null($acl))? array(): $acl;
			//print_r($acl);
			$this->view->acl=$acl;			
			
			
			
			
			if(!$usernotparentid){
				///Display only of his/her parent access	and not have user_type_id of user access in user type parent id
				//ua.user_type_id != ut.parent_id
				$sql_acl = "SELECT acl.acl_id, CONCAT(acl.module,'/', acl.controller,'/', acl.action) AS user_access, acl.status 
							FROM rms_acl_user_access AS ua 
							INNER JOIN rms_acl_user_type AS ut ON (ua.user_type_id = ut.user_type_id)
							INNER JOIN rms_acl_acl AS acl ON (acl.acl_id = ua.acl_id) WHERE ua.user_type_id =".$id . $where;
			}else{
				//Display only he / she access in rms_acl_user_access
				$sql_acl = "SELECT acl.acl_id, CONCAT(acl.module,'/', acl.controller,'/', acl.action) AS user_access, acl.status 
							FROM rms_acl_user_access AS ua 
							INNER JOIN rms_acl_user_type AS ut ON (ua.user_type_id = ut.parent_id)
							INNER JOIN rms_acl_acl AS acl ON (acl.acl_id = ua.acl_id) WHERE ua.user_type_id =".$id . $where;
			}			
						
			$acl_name=$db_acl->getGlobalDb($sql_acl);
			$acl_name = (is_null($acl_name))? array(): $acl_name;
			
// 			$imgnone='<img src="'.BASE_URL.'/images/icon/none.png"/>';
// 			$imgtick='<img src="'.BASE_URL.'/images/icon/tick.png"/>';
			
			$rows= array();
			$num = 1;
			foreach($acl as $com){
				$img='<img src="'.BASE_URL.'/images/icon/none.png" id="img_'.$com['acl_id'].'" onclick="changeStatus('.$com['acl_id'].','.$id.');" class="pointer"/>';
				$tmp_status = 0;
				foreach($acl_name as $read){
					if($read['acl_id']==$com['acl_id']){
						$img='<img src="'.BASE_URL.'/images/icon/tick.png" id="img_'.$com['acl_id'].'" onclick="changeStatus('.$com['acl_id'].', '.$id.');" class="pointer"/>';
						$tmp_status = 1;
						break;
					}
			
				}
				if(!empty($status) || $status === 0){
					if($tmp_status !== $status) continue;
				}
				$rows[] = array('num'=> $num++,'acl_id'=>$com['acl_id'],"user_access"=> $com['user_access'], 'status'=>$tmp_status) ;
			}	 
			 
// 			$list=new Application_Form_Frmlist();
// 			$tr = Application_Form_FrmLanguages::getCurrentlanguage();
// 			$columns=array($tr->translate('URL'), $tr->translate('STATUS'));
// 			$this->view->acl_name = $list->getCheckList('radio', $columns, $rows);
			
        	$db_tran=new Application_Model_DbTable_DbGlobal();
        	 
        	//create sesesion
        	$session_transfer=new Zend_Session_Namespace('search_user-access-acl');
        	if(empty($session_transfer->limit)){
        		$session_transfer->limit =  Application_Form_FrmNavigation::getLimit();
        		$session_transfer->lock();
        	}
        
        	if($this->getRequest()->isPost() && $this->getRequest()->getParam("btsave") !== "Search"){
        		$formdata=$this->getRequest()->getPost();
        		$session_transfer->unlock();
        		$session_transfer->limit =  $formdata['rows_per_page'];
        		$session_transfer->lock();
        	}
        	//start page nevigation
        	$limit = $session_transfer->limit;
        	$start = $this->getRequest()->getParam('limit_satrt',0);
        	$result = array();
        	if ($limit == "All") {
        		$result=$rows;
        	}
        	else{
	        	for ($i = $start; $i < ($start + $limit) ;$i++){
	        		if(count($rows) == $i) break;
	        		$result[]= $rows[$i];        		
	        	}
        	}
        	$record_count = count($rows);
        	
        	
        	if (empty($result)){
        		$result = array('err'=>1, 'msg'=>'áž˜áž·áž“â€‹áž‘áž¶áž“áŸ‹â€‹áž˜áž¶áž“â€‹áž‘áž“áŸ’áž“áž·áž“áŸ�áž™â€‹áž“áž¼ážœâ€‹áž¡áž¾áž™â€‹áž‘áŸ�!');
        	}
        	
        	$this->view->list = Zend_Json::encode($result);
        	$page = new Application_Form_FrmNavigation();
        	$page->init(self::REDIRECT_URL. "/view-user-access?id=".$id, $start, $limit, $record_count, "&");
        	$this->view->nevigation = $page->navigationPage();
        	$this->view->rows_per_page = $page->getRowsPerPage($limit, 'frmlist');
        	$this->view->result_row = $page->getResultRows();
	    	
    	}  	 
    	
    }
	public function addUserAccessAction()
		{
			$form=new RsvAcl_Form_FrmUserAccess();	
			$db = new Application_Model_DbTable_DbGlobal();
			$this->view->form=$form;
			
			if($this->getRequest()->isPost())
			{
				$db=new RsvAcl_Model_DbTable_DbUserAccess();	
				$post=$this->getRequest()->getPost();			
				//if(!$db->isUserExist($post['username'])){
					
						$id=$db->insertUserAccess($post);
						//write log file 
				             $userLog= new Application_Model_Log();
				    		 $userLog->writeUserLog($id);
				     	  //End write log file
				
						//Application_Form_FrmMessage::message('One row affected!');
						Application_Form_FrmMessage::redirector('/rmsAcl/user-access/index');																			
				//}else {
				//	Application_Form_FrmMessage::message('User had existed already');
				//}
			}
		}
    
	public function editUserAccessAction()
    {	
    	$id=$this->getRequest()->getParam('id');
    	if(!$id)$id=0;    	
		$session=new Zend_Session_Namespace('auth');
		$session->user_type_id=$id;
    	$session->lock();	
    	
   		$form = new RsvAcl_Form_FrmUserAccess();
   		//echo "it works"; exit;
   		
    	$db = new RsvAcl_Model_DbTable_DbUserAccess();
    	$sql = "select user_type_id, user_type  from rms_acl_user_type where user_type_id=".$id;
        $rs = $db->getUserAccessInfo($sql);
        //print_r($rs); exit;
               
	
		//Sophen add here
			//to assign project list in view
			$db_acl=new Application_Model_DbTable_DbGlobal();
			
			$sqlNotParentId = "SELECT user_type_id FROM `rms_acl_user_type` WHERE `parent_id` =".$id;
			$notParentId = $db_acl->getGlobalDb($sqlNotParentId);
			$usernotparentid = $notParentId[0]['user_type_id'];			    	
	    	//print $usernotparentid; exit;
	    	
		if($id == 1){
				$sql_acl = "select acl.acl_id,CONCAT(acl.module,'/', acl.controller,'/', acl.action) AS user_access from rms_acl_acl as acl";
		}if(!$usernotparentid){
				$sql_acl = "SELECT acl.acl_id, CONCAT(acl.module,'/', acl.controller,'/', acl.action) AS user_access, acl.status FROM rms_acl_user_access AS ua 
					INNER JOIN rms_acl_user_type AS ut ON (ua.user_type_id = ut.user_type_id)
					INNER JOIN rms_acl_acl AS acl ON (acl.acl_id = ua.acl_id) WHERE ua.user_type_id =".$id;
		}else {
    			$sql_acl = "SELECT acl.acl_id, CONCAT(acl.module,'/', acl.controller,'/', acl.action) AS user_access, acl.status FROM rms_acl_user_access AS ua 
					INNER JOIN rms_acl_user_type AS ut ON (ua.user_type_id = ut.parent_id)
					INNER JOIN rms_acl_acl AS acl ON (acl.acl_id = ua.acl_id) WHERE ua.user_type_id =".$id;
		}
	    //print $sql_acl; exit;
		$acl_name=$db_acl->getGlobalDb($sql_acl);
		
		//print_r($acl_name); exit;
		
		if($acl_name!=''){ $form->setAcl($acl_name);}
				
		Application_Model_Decorator::setForm($form, $rs);
		
		$this->view->form = $form;
		
		$rows= array();
		for($i=1;$i<=$form->getPlus();$i++){
			$rows[] = array($i, $form->getElement('acl_id_'.$i)->getLabel(), $form->getElement('acl_id_'.$i)) ;
		}
		
		$list=new Application_Form_Frmlist();
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$columns=array($tr->translate('URL'), $tr->translate('STATUS'));
		$this->view->form_layout = $list->getCheckList('radio', $columns, $rows);
		
    	$this->view->id = $id;
    	
    	if($this->getRequest()->isPost())
		{
			$post=$this->getRequest()->getPost();
			//if($rs[0]['']==$post['username']){	
					$db_user = new RsvAcl_Model_DbTable_DbUserType();

					print_r($post); exit;
					//print $rs[0]['user_type_id']; exit;
					$db_user->updateUserTypeAccess($post['user_type'],$rs[0]['user_type_id']);								
					
					$db->assignAcl($post,$rs[0]['user_type_id'], $form->getPlus());
					 		//write log file 
				             $userLog= new Application_Model_Log();
				    		 $userLog->writeUserLog($id);
				     	  //End write log file
					//Application_Form_FrmMessage::message('One row affected!');
					Application_Form_FrmMessage::redirector('/rmsAcl/user-access/index');																																				
				
			/*}else{
				if(!$db->isUserExist($post['username'])){
					$db->updateUser($post,$rs[0]['user_id']);
					 //write log file 
				        //$userLog= new RsvLogging_Model_RsvLogging();
				    	//$userLog->writeUserLog($user_id);
				     //End write log file
					Application_Form_FrmMessage::message('One row affected!');
					Application_Form_FrmMessage::redirector('/rmsAcl/user/index');						
				}else {
					Application_Form_FrmMessage::message('User had existed already');
				}
			}*/
		}
    }

    public function updateStatusAction(){
    	if($this->getRequest()->isPost()){
    		$post=$this->getRequest()->getPost();
    		$db = new RsvAcl_Model_DbTable_DbUserAccess();
    		$user_type_id =  $post['user_type_id'];
    		$acl_id = $post['acl_id'];
    		$status = $post['status'];
    		
    		$data=array('acl_id'=>$acl_id, 'user_type_id'=>$user_type_id);
    		
    		if($status === "yes"){
    			$where="user_type_id='".$user_type_id."' AND acl_id='". $acl_id . "'";
    			$db->delete($where);    		
    			echo "no";	
    		}
    		elseif($status === "no"){
    			$db->insert($data);    		
    			echo "yes";
    		}
    		
    		//write log file
    		$userLog= new Application_Model_Log();
    		$userLog->writeUserLog($acl_id);
    	}
    	exit();
    }
}

