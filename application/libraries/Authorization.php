<?php if( ! defined('BASEPATH') ) exit('NO direct script access allowed');


class Authorization{

	public function __construct()
	{
		$this->ci =& get_instance();
	}


	function check($uaid, $menuid, $action)
	{
		$this->ci->db->select('*');
        $this->ci->db->from('access_rights');      
        $this->ci->db->where('UAID', $uaid);      
        $this->ci->db->where('MenuId', $menuid);      
		$accessRights = $this->ci->db->get()->result();
		echo $menuid;
		echo $uaid;
		echo $action;
		exit();
		if($action == 'view'){
			return ($accessRights[0]->_view) ? true : false;
		}else if($action == 'create'){
			return ($accessRights[0]->_create) ? true : false;
		}else if($action == 'update'){
			return ($accessRights[0]->_update) ? true : false;
		}else if($action == 'delete'){
			return ($accessRights[0]->_delete) ? true : false;
		}
		else{
			return false;
		}
	}

}