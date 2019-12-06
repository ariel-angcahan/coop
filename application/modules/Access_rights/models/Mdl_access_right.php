<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_access_right extends CI_Model {

	function __construct() {

		parent::__construct();
		$this->load->database();
	}

    function list_query() {
        
        $this->db->select('UAId,fname,lname');
        $this->db->from('ci_user_access');
        $this->db->where('deleted', 0);

        // UAId || Lname || Fname || 3
        if (isset($_POST["order"][0])) {

            $column = $_POST["order"][0]["column"];
            $dir = $_POST["order"][0]["dir"];

            switch ($column) {

                case 0:
                    $this->db->order_by('UAId', $dir);
                    break;
                case 1:
                    $this->db->order_by('lname', $dir);
                    break;
                case 2:
                    $this->db->order_by('fname', $dir);
                    break;
                default:
                    $this->db->order_by('UAId', 'ASC');
                    break;
            }
        }

        if (isset($_POST["search"]["value"])) {

            $search = $_POST["search"]["value"];

            $this->db->group_start();
                if (is_numeric($search)) $this->db->like("UAId", $search);
                $this->db->or_like("lname", $search);
                $this->db->or_like("fname", $search);
            $this->db->group_end();
        }
    }

    function user_list_table() {

        $this->list_query();
        
        if ($_POST["length"] != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
        
        return $this->db->get()->result();
    }

    function get_filtered_data() {

        $this->list_query();
        $query = $this->db->get();

        return $query->num_rows();
    }

    function get_all_data() {

        $this->db->select('UAId,fname,lname');
        $this->db->from('ci_user_access');
        $this->db->where('deleted', 0);

        return $this->db->count_all_results();  
    }

    function get_UAId($EmpId) {

        $this->db->select('UAId');
        $this->db->from('UserAccess');
        $this->db->where('EmpId',$EmpId);

        return $this->db->get()->result();
    }

    function get_role_list() {

        $this->db->select('RoleId,Role');
        $this->db->from('ci_mst_role');

        return $this->db->get()->result();
    }

    function get_menu_parent($UAId) {

        $this->db->select('ci_menu.*, ci_access_rights.*');
        $this->db->from('ci_menu');
        $this->db->join('ci_access_rights', 'ci_menu.MenuId = ci_access_rights.MenuId');
        $this->db->where('ci_menu.ParentId',0);
        $this->db->where('ci_access_rights.UAId',$UAId);
        $this->db->order_by('ci_menu.MenuName', 'ASC');

        return $this->db->get()->result();
    }

    function hasChild($UAId,$MenuId) {

        $this->db->select('ci_menu.MenuName');
        $this->db->from('ci_menu');
        $this->db->join('ci_access_rights', 'ci_access_rights.MenuId = ci_menu.MenuId');
        $this->db->where('ci_menu.ParentId', $MenuId);

        return $this->db->get()->num_rows() > 0 ? true : false;
    }

    function get_menu_child($UAId,$MenuId) {

        $this->db->select('ci_menu.*,ci_access_rights.*');
        $this->db->from('ci_menu');
        $this->db->join('ci_access_rights', 'ci_access_rights.MenuId = ci_menu.MenuId');
        $this->db->where('ci_access_rights.UAId',$UAId);
        $this->db->where('ci_menu.ParentId',$MenuId);
        $this->db->order_by('ci_menu.MenuName', 'ASC');

        return $this->db->get()->result();
    }

    function stat_update($id, $stat, $interface, $rid) {

        $flag['success'] = false;
        $this->db->set($interface, $stat);
        $this->db->where('Id',$id);
        $this->db->update('ci_access_rights');

        if ($this->db->affected_rows()) {
            $flag['success'] = true;
        }

        return $flag;
    }

    // role parent menu
    function role_get_menu_parent($RoleId, $deleted) {

        $this->db->distinct();
        $this->db->select('ci_menu.MenuId, ci_menu.MenuName, ci_menu.ParentId, ci_menu.MenuIcon, ci_access_rights.RoleId, ci_access_rights.MenuId, ci_access_rights._view, ci_access_rights._create, ci_access_rights._update, ci_access_rights._delete');
        $this->db->from('ci_access_rights');
        $this->db->join('ci_menu', 'ci_access_rights.MenuId = ci_menu.MenuId');        
        $this->db->where('ci_access_rights.RoleId', $RoleId);
        $this->db->where('ci_menu.isParent', 1);
        $this->db->order_by('ci_access_rights.MenuId', 'ASC');

        return $this->db->get()->result();
    }

    // role child menus
    function role_get_menu_child($ParentId, $RoleId) {

        $this->db->distinct();
        $this->db->select('ci_menu.MenuId, ci_menu.MenuName, ci_menu.ParentId, ci_menu.MenuIcon, ci_access_rights.RoleId, ci_access_rights.MenuId, ci_access_rights._view, ci_access_rights._create, ci_access_rights._update, ci_access_rights._delete');
        $this->db->from('ci_access_rights');
        $this->db->join('ci_menu', 'ci_access_rights.MenuId = ci_menu.MenuId');
        $this->db->where('ci_access_rights.RoleId', $RoleId);
        // $this->db->where('ci_menu.ParentId IN (select distinct ar.MenuId from ci_access_rights as ar join menu as m on ar.MenuId = m.MenuId where m.isParent = 1 and ar.RoleId = 1)', null, false);
        $this->db->where('ci_menu.ParentId', $ParentId);
        $this->db->order_by('ci_access_rights.MenuId', 'ASC');

        return $this->db->get()->result();
    }

    function ar_update($stat, $interface, $rid, $menuId) {
        
        $flag['success'] = false;
        $this->db->set($interface, $stat);
        $this->db->where('RoleId',$rid);
        $this->db->where('MenuId',$menuId);
        $this->db->update('ci_access_rights');

        if ($this->db->affected_rows()) {
            $flag['success'] = true;
        }

        return $flag;
    }
}	
