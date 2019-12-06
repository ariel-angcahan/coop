<?php 
class Utility extends CI_Model{
	
	function __construct() {
		parent::__construct();

		$this->load->database();
	}

	function get_menu_parent($role_id, $UAId)
	{
		$this->db->select('ci_menu.MenuId,ci_menu.MenuName, ci_menu.MenuIcon, ci_menu.TargetUrl');
		$this->db->from('ci_menu');
		$this->db->join('ci_access_rights', 'ci_access_rights.MenuId = ci_menu.MenuId');
		$this->db->where('ci_access_rights.RoleId',$role_id);
        $this->db->where('ci_access_rights._view', 1);
        $this->db->where('ci_access_rights.UAId', $UAId);
		$this->db->where('ci_menu.ParentId', 0);
		return $this->db->get()->result();
	}

	function hasChild($role_id, $menu_id, $UAId)
	{
		$this->db->select('ci_menu.MenuName');
		$this->db->from('ci_menu');
		$this->db->join('ci_access_rights', 'ci_access_rights.MenuId = ci_menu.MenuId');
        $this->db->where('ci_access_rights.RoleId',$role_id);
		$this->db->where('ci_menu.ParentId',$menu_id);
        $this->db->where('ci_access_rights._view', 1);
        $this->db->where('ci_access_rights.UAId', $UAId);
		return $this->db->get()->num_rows() > 0 ? true : false;
	}

	function get_menu_child($role_id, $menu_id, $UAId)
	{
		$this->db->select('ci_menu.MenuId,ci_menu.MenuName, ci_menu.MenuIcon, ci_menu.TargetUrl');
		$this->db->from('ci_menu');
		$this->db->join('ci_access_rights', 'ci_access_rights.MenuId = ci_menu.MenuId');
		$this->db->where('ci_access_rights.RoleId',$role_id);
		$this->db->where('ci_menu.ParentId',$menu_id);
        $this->db->where('ci_access_rights._view', 1);
        $this->db->where('ci_access_rights.UAId', $UAId);
		$this->db->order_by('ci_menu.MenuId', 'ASC');
		return $this->db->get()->result();
	}

	function notification_list(){

		$this->db->select('*');
		$this->db->from('ci_notification_master');
		$this->db->where('notification_to', $this->session->department_id);
		return $this->db->get()->result();
	}
}
	

?>