<?php 

class AccessRight extends CI_Model{
	

	function __construct() {
		parent::__construct();
		$this->load->database();

	}

    // function get_AccessRights($id)
    // {
    //     $this->db->select('UserAccess.*');
    //     $this->db->from('UserAccess');
    //     $this->db->join('AccessRights', 'AccessRights.UAId = UserAccess.UAId');
    //     $this->db->join('Menu', 'Menu.MenuId = AccessRights.MenuId');
    //     $this->db->where('UserAccess.EmpId',$id);
    //     return $this->db->get()->result();
    // }

    function get_UAId($EmpId)
    {
        $this->db->select('UAId');
        $this->db->from('UserAccess');
        $this->db->where('EmpId',$EmpId);
        return $this->db->get()->result();
    }

    function get_role_list()
    {
        $this->db->select('RoleId,Role');
        $this->db->from('mst_role');
        return $this->db->get()->result();
    }

    function get_menu_parent($UAId)
    {
        $this->db->select('menu.*, access_rights.*');
        $this->db->from('menu');
        $this->db->join('access_rights', 'menu.MenuId = access_rights.MenuId');
        $this->db->where('menu.ParentId',0);
        $this->db->where('access_rights.UAId',$UAId);
        $this->db->order_by('menu.MenuName', 'ASC');
        return $this->db->get()->result();
    }

    function hasChild($UAId,$MenuId)
    {

        $this->db->select('menu.MenuName');
        $this->db->from('menu');
        $this->db->join('access_rights', 'access_rights.MenuId = menu.MenuId');
        $this->db->where('menu.ParentId', $MenuId);
        return $this->db->get()->num_rows() > 0 ? true : false;
    }

    function get_menu_child($UAId,$MenuId)
    {
        $this->db->select('menu.*,access_rights.*');
        $this->db->from('menu');
        $this->db->join('access_rights', 'access_rights.MenuId = menu.MenuId');
        $this->db->where('access_rights.UAId',$UAId);
        $this->db->where('menu.ParentId',$MenuId);
        $this->db->order_by('menu.MenuName', 'ASC');
        return $this->db->get()->result();
    }

    function stat_update($id, $stat, $interface, $rid)
    {
        $flag['success'] = false;
        $this->db->set($interface, $stat);
        $this->db->where('Id',$id);
        $this->db->update('access_rights');

        if($this->db->affected_rows()){
            $flag['success'] = true;
        }
        return $flag;
    }

    // role parent menu
    function role_get_menu_parent($RoleId, $deleted)
    {
        $this->db->distinct();
        $this->db->select('menu.MenuId, menu.MenuName, menu.ParentId, menu.MenuIcon, access_rights.RoleId, access_rights.MenuId, access_rights._view, access_rights._create, access_rights._update, access_rights._delete');
        $this->db->from('access_rights');
        $this->db->join('menu', 'access_rights.MenuId = menu.MenuId');        
        $this->db->where('access_rights.RoleId', $RoleId);
        $this->db->where('menu.isParent', 1);
        $this->db->order_by('access_rights.MenuId', 'ASC');

        return $this->db->get()->result();
        
    }

    // role child menus
    function role_get_menu_child($ParentId, $RoleId)
    {
        $this->db->distinct();
        $this->db->select('menu.MenuId, menu.MenuName, menu.ParentId, menu.MenuIcon, access_rights.RoleId, access_rights.MenuId, access_rights._view, access_rights._create, access_rights._update, access_rights._delete');
        $this->db->from('access_rights');
        $this->db->join('menu', 'access_rights.MenuId = menu.MenuId');
        $this->db->where('access_rights.RoleId', $RoleId);
        // $this->db->where('menu.ParentId IN (select distinct ar.MenuId from access_rights as ar join menu as m on ar.MenuId = m.MenuId where m.isParent = 1 and ar.RoleId = 1)', null, false);
        $this->db->where('menu.ParentId', $ParentId);
        $this->db->order_by('access_rights.MenuId', 'ASC');


        return $this->db->get()->result();
    }

    function ar_update($stat, $interface, $rid, $menuId)
    {
        $flag['success'] = false;
        $this->db->set($interface, $stat);
        $this->db->where('RoleId',$rid);
        $this->db->where('MenuId',$menuId);
        $this->db->update('access_rights');

        if($this->db->affected_rows()){
            $flag['success'] = true;
        }

        return $flag;
    }

    // function count_interface($interface, $menuId, $roleId ,$trues, $falses)
    // {

    //     $this->db->select($interface);
    //     $this->db->from('AccessRights');
    //     $this->db->where('MenuId', $menuId);
    //     $this->db->where('RoleId', $roleId);
    //     $this->db->where($interface, $falses);
    //     $falses = $this->db->get()->result();

    //     $this->db->select($interface);
    //     $this->db->from('AccessRights');
    //     $this->db->where('MenuId', $menuId);
    //     $this->db->where('RoleId', $roleId);
    //     $this->db->where($interface, $trues);
    //     $trues = $this->db->get()->result();


    //     return (count($trues) > count($falses)) ? 1 : 0;
    // }

}	
	
?>