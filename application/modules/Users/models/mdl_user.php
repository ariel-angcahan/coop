<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_user extends CI_Model {
	
	function __construct() {


		parent::__construct();
		$this->load->database();
	}

    function make_query() {
        
        $role_id = $this->session->RoleId;

		$this->db->select('UAId,UserName,lname,fname,RoleId');
		$this->db->from('ci_user_access');
		$this->db->where('deleted', 0);
		if ($role_id == 2) { // system admin
			$this->db->where('RoleId NOT 1', null, false);
		}

        if (isset($_POST["search"]["value"])) {
            $this->db->like("UserName", $this->input->post('search[value]'));
        }
    }

    function user_list_table() {

        $this->make_query();
        if ($_POST["length"] != -1) {
            $this->db->limit($this->input->post('length'),$this->input->post('start'));
        }

        return $this->db->get()->result();
    }

    function get_filtered_data() {

        $this->make_query();

        return $this->db->get()->num_rows();
    }

    function get_all_data() {

        $role_id = $this->session->RoleId;

		$this->db->select('UAId,UserName,lname,fname,RoleId');
		$this->db->from('ci_user_access');
		$this->db->where('deleted', 0);
		if ($role_id == 2) { // system admin
			$this->db->where('RoleId NOT 1', null, false);
		}
        return $this->db->count_all_results();
    }

    // used by autorights; should be removed in production
	function get_users() {

		$this->db->select('UAId,RoleId');
		$this->db->from('ci_user_access');
		$this->db->where('deleted', 0);

		return $this->db->get()->result();
	}

	function get_roles($roleId = null) {

        $role_id = $this->session->RoleId;

		$this->db->select('*');
		$this->db->from('ci_mst_role');
		$this->db->where('deleted', 0);
		// if(!empty($roleId)){
		// 	$this->db->where('RoleId', $roleId);
		// }
		// if ($role_id == 5) { // store manager
		// 	$this->db->where('RoleId', 6);
		// }

		return $this->db->get()->result();
	}

	// function get_departments() {

 //        $department_id = $this->session->userdata('department_id');
 //        $role_id = $this->session->RoleId;

	// 	$this->db->select('*');
	// 	$this->db->from('department_master');
	// 	$this->db->where('deleted', 0);
	// 	if ($role_id == 5) { // store manager
	// 		$this->db->where('id', $department_id);
	// 	}

	// 	return $this->db->get()->result();
	// }

	function check_username($user) {

		$this->db->select('UserName');
		$this->db->from('ci_user_access');
		$this->db->where('UserName', $user);
		$this->db->where('deleted', 0);

		return $db->get()->result();
	}

	function get_user($UAId) {

		$this->db->select('*');
		$this->db->from('ci_user_access');
		$this->db->where('UAId', $UAId);
		$this->db->where('deleted', 0);

		return $this->db->get()->result()[0];
	}

	function get_menus() {

		$this->db->select('MenuId,MenuName,security_level,hidden_from,special_access');
		$this->db->from('ci_menu');

		return $this->db->get()->result();
	}

	function set_default_rights($data) {

		$this->db->insert_batch('ci_access_rights', $data);

		return $this->db->affected_rows() > 0 ? true : false;
	}

	function edit_default_rights($data) {

        $this->db->trans_start();

		foreach ($data as $menu) {
            $this->db->where('UAId', $menu['UAId']);
            $this->db->where('MenuId', $menu['MenuId']);
            $this->db->update('ci_access_rights', $menu);
		}

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $flag['success'] = false;
        } else {
            $flag['success'] = true;
        }

        return $flag;
	}

	function add_user($form_data, $UAId) {

        if (!empty($UAId)) {
            $this->db->where('UAId', $UAId);
            $this->db->update('ci_user_access',$form_data);
        } else {

            $this->db->insert('ci_user_access', $form_data);
		}
		
		if ($this->db->affected_rows()) {
			if (!empty($this->db->insert_id()))
				return $this->db->insert_id(); // add user
			else
				return true; // edit user
		}
	}

	function delete_user($id) {

        $this->db->where('UAId', $id);
		$this->db->update('ci_user_access', array('deleted' => 1) );

		return ($this->db->affected_rows() > 0) ? true : false;
	}

	function check_username2($username){
		$response['success'] = false;
		$this->db->select('*');
		$this->db->from('ci_user_access');
		$this->db->where('UserName', $username);
		$data = $this->db->get()->result();
		if(!empty($data)){
			$response['success'] = true;
		}

		return $response;
	}

	function update_image($user_access_id, $file_name){
		$response['success'] = false;
		$this->db->set('avatar', $file_name);
		$this->db->set('image_date', 'NOW()', false);
		$this->db->where('UAId', $user_access_id);
		$update = $this->db->update('ci_user_access');
		if($update){
			$response['success'] = true;
		}

		return $response;

	}


	function update_profile($formdata, $where){
		$response['success'] = false;
		$this->db->where('UAId', $where);
		$update = $this->db->update('ci_user_access', $formdata);
		if($update){
			$response['success'] = true;
		}
		return $response;
	}

	function update_password($UAId ,$old_pass ,$new_pass){
		$response['success'] = false;
		$this->db->set('Password', $new_pass);
		$this->db->where('UAId', $UAId);
		$this->db->where('Password', $old_pass);
		$this->db->update('ci_user_access');
		$update = $this->db->affected_rows();
		if($update == 1){
			$response['success'] = true;
		}
		return $response;
	}

}