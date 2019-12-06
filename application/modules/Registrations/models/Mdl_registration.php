<?php 

class Mdl_registration extends CI_Model{
	
	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function register_information($form_data){

		$flag['success'] = false;

		$insert = $this->db->insert('person_info_tmp', $form_data);

		if($insert){
			$flag['id'] = $this->db->insert_id();
			$flag['success'] = true;
			$flag['exist'] = false;
		}

		return $flag;

	}

	function insert_school($entry_school){
		$flag['success'] = false;

		$this->db->select('SchoolId');
		$this->db->from('school_mst');
		$this->db->where('SchoolName', $entry_school);
		$id = $this->db->get()->result();

		if($id){
			$insert = $id[0]->SchoolId;
		}else{
	        $this->db->set('SchoolName', mb_strtoupper($entry_school, "UTF-8"));
	        $this->db->insert('school_mst');
	        $insert = $this->db->insert_id();
		}

       	if($insert){
       		$flag['success'] = true;
       		$flag['school_id'] = $insert;
       	}

        return $flag;
	}

	function insert_company($entry_company){
		$flag['success'] = false;

		$this->db->select('CompanyId');
		$this->db->from('company_mst');
		$this->db->where('Company_name', $entry_company);
		$id = $this->db->get()->result();

		if($id){
			$insert = $id[0]->CompanyId;
		}else{

	        $this->db->set('Company_name', mb_strtoupper($entry_company, "UTF-8"));
	        $this->db->insert('company_mst');
	        $insert = $this->db->insert_id();
		}

       	if($insert){
       		$flag['success'] = true;
       		$flag['company_id'] = $insert;
       	}
       	
        return $flag;
	}

	function get_school_list(){

		$flag['success'] = false;
		$this->db->select('*');
		$this->db->from('school_mst');
		$data = $this->db->get()->result();

		if($data){
			$flag['success'] = true;
			$flag['data'] = $data;
		}

		return $flag;
	}

	function get_company_list(){

		$flag['success'] = false;
		$this->db->select('*');
		$this->db->from('company_mst');
		$data = $this->db->get()->result();

		if($data){
			$flag['success'] = true;
			$flag['data'] = $data;
		}

		return $flag;
	}

	function registered_list(){

		$flag['success'] = false;
		$this->db->select('PersonnelId,LastName,FirstName,MiddleInitial');
		$this->db->from('personnelMaster');
		$this->db->where('UserIdRegistered', $this->session->user_id);
		$this->db->where('SerialNumber IS NULL', false, false);
		$this->db->order_by('PersonnelId', 'DESC');
		$data = $this->db->get()->result();

		if($data){
			$flag['success'] = true;
			$flag['data'] = $data;
		}

		return $flag;
	}

	function remove_registered_info($registered_id){

		$flag['success'] = false;
            
        $delete = $this->db->delete('personnelMaster', array('PersonnelId' => $registered_id));

        if($delete){
            $flag['success'] = true;
        }

        return $flag;
	}

	function region_list(){
		$this->db->select('*');
		$this->db->from('refregion');
		$this->db->order_by('regDesc', 'asc');
		return $this->db->get()->result();
	}

	function province_list($region_code){
		$this->db->select('*');
		$this->db->from('refprovince');
		$this->db->where('regCode', $region_code);
		$this->db->order_by('provDesc', 'asc');
		return $this->db->get()->result();
	}

	function city_list($province_code){
		$this->db->select('*');
		$this->db->from('refcitymun');
		$this->db->where('provCode', $province_code);
		$this->db->order_by('citymunDesc', 'asc');
		return $this->db->get()->result();
	}

	function barangay_list($city_code){
		$this->db->select('*');
		$this->db->from('refbrgy');
		$this->db->where('citymunCode', $city_code);
		$this->db->order_by('brgyDesc', 'asc');
		return $this->db->get()->result();
	}

	function check_if_registered($first_name,$last_name){
		$flag = false;
		$this->db->select('*');
		$this->db->from('person_info as pi');
		$this->db->join('person_info_tmp as pit', 'pi.last_name = pit.last_name and pi.first_name = pit.first_name and pi.last_name = pit.last_name');
		$this->db->where("(pi.first_name = '".$first_name."' or pit.first_name = '".$first_name."')", null, false);
		$this->db->where("(pi.last_name = '".$last_name."' or pit.last_name = '".$last_name."')", null, false);
		$rows = $this->db->get()->result();

		if(!empty($rows)){
			$flag = true;
		}

		return $flag;
	}

	function get_city_zip_code($city_code){
		$this->db->limit(1);
		$this->db->select("zip_code");
		$this->db->from("refcitymun");
		$this->db->where("citymunCode", $city_code);
		return $this->db->get()->result();
	}

	function market_list(){

		$this->db->select('*');
		$this->db->from('market_addr_mst');
		return $this->db->get()->result();
	}

	function membership_type_list(){

		$this->db->select('*');
		$this->db->from('membership_mst');
		return $this->db->get()->result();
	}

	function payment_mode(){

		$this->db->select('*');
		$this->db->from('payment_mode_mst');
		return $this->db->get()->result();
	}

	function stall_list(){

		$this->db->select('*');
		$this->db->from('stall_mst');
		return $this->db->get()->result();
	}

	function gender_list(){

		$this->db->select('*');
		$this->db->from('gender_mst');
		return $this->db->get()->result();
	}
}	