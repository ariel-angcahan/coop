<?php 


class Profile extends CI_Model{
	

		function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function hasChild($parentId)
	{
		$parentId = $parentId;
		$sql = "select * from menu where Id = '$parentId' order by MenuName asc";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	function changeProfilePicture($image, $EmpId)
	{
		$this->db->where('EmpId', $EmpId);
    	$this->db->update('EmployeeMaster',$image);
	}

	function changePassword($EmpId, $formData)
	{
		$this->db->where('EmpId',$EmpId);
		$this->db->update('UserAccess', $formData);
	}

	function savePassword($form_data){
        $this->db->insert('sampleSave',$form_data);
	}

	function insert_update_image($user_id, $file_name){
		$response['success'] = false;
		$this->db->set('image_date', "NOW()", false);
		$this->db->set('avatar', $file_name);
		$this->db->where('UAId', $user_id);
    	$update = $this->db->update('ci_user_access');

    	if($update){
    		$response['success'] = true;
    	}

    	return $response;
	}

	function check_old_password($old_password){
		$response['success'] = false;
		$this->db->select('*');
		$this->db->from('ci_user_access');
		$this->db->where('Password', $this->token->encrypt($old_password));
		$this->db->where('UAId', $this->session->UAId);
		$result = $this->db->get()->result();
		if(!empty($result)){
			$response['success'] = true;
		}

		return $response;
	}

	function change_password($new_password){
		$response['success'] = false;
		$this->db->trans_start();
		$this->db->set('Password', $this->token->encrypt($new_password));
		$this->db->where('UAId', $this->session->UAId);
		$this->db->update('ci_user_access');
		$update = $this->db->affected_rows();

		if(!empty($update) && $update == 1){
			$response['success'] = true;
			$this->db->trans_complete();
		}

		return $response;

	}
}