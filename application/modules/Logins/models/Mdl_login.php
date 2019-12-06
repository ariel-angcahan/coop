<?php 


class Mdl_login extends CI_Model{
	

		function __construct() {
		parent::__construct();
		$this->load->database();
		
		//$this->ora_db = $this->load->database('ora', TRUE);
		//$this->load->dbutil();
	}

	//function start 

	function authenticate($form)
	{
		$flag['success'] = false;
		$this->db->select('*');
		$this->db->from('ci_user_access'); 
		$this->db->where('UserName', $form['username']);
		$this->db->where('Password', $form['password']);
		$this->db->where('deleted', 0);

		$data = $this->db->get()->result();


		if(!empty($data))
		{
			$flag['success'] = true;
			$flag['data'] = $data[0];
		}
		return $flag;
	}

	function get_user_info($id, $user_type)
	{	
		$flag['success'] = true;
	}

	function get_department_name($department_id){

		$flag['success'] = false;
		$this->db->select('department_name');
		$this->db->from('ci_department_master');
		$this->db->where('id', $department_id);
		$data = $this->db->get()->result();

		if(!empty($data)){
			$flag['success'] = true;
			$flag['data'] = $data[0];
		}
		return $flag;
	}

}
	

?>