<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_filedirectory extends CI_Model{
	
	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function authenticate($username, $password){
		$response['success'] = false;

		$result = $this->db->select('*')
				->from('tbl_user')
				->where('username', $username)
				->where('password', $password)
				->get()
				->result();

		if(!empty($result)){
			$response['data'] = $result[0];
			$response['success'] = true;
		}

		return $response;
	}
}	