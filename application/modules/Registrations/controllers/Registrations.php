<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Registrations extends MX_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Mdl_registration');
        date_default_timezone_set("Asia/Manila");
        // if(!$this->session->userdata('isLogin'))
        // {
        //     header('location:'.base_url());
        //     die();
        // }
    }

    public function index(){
        $data['get_school_list'] = $this->get_school_list();
        $data['get_company_list'] = $this->get_company_list();
        $data['token'] = $this->security->get_csrf_hash();
        $this->load->view('standard_layout.php', $data);
    }
    public function register_information(){
        $errFlag = 0;
        $errArr = array();

        if(empty($this->input->post('last_name'))){
            $errFlag++;
            array_push($errArr, 'Lastname');
        }
        if(empty($this->input->post('first_name'))){
            $errFlag++;
            array_push($errArr, 'Firstname');
        }
        if(empty($this->input->post('middle_name'))){
            $errFlag++;
            array_push($errArr, 'Middlename');
        }
        if(empty($this->input->post('gender'))){
            $errFlag++;
            array_push($errArr, 'Gender');
        }
        if(empty($this->input->post('birth_date'))){
            $errFlag++;
            array_push($errArr, 'Birth Date');
        }
        if(empty($this->input->post('birth_place'))){
            $errFlag++;
            array_push($errArr, 'Birth Place');
        }
        if(empty($this->input->post('email'))){
            $errFlag++;
            array_push($errArr, 'Email');
        }
        if(empty($this->input->post('mobile_no'))){
            $errFlag++;
            array_push($errArr, 'Mobile #');
        }
        if(empty($this->input->post('country'))){
            $errFlag++;
            array_push($errArr, 'Country');
        }
        if(empty($this->input->post('region'))){
            $errFlag++;
            array_push($errArr, 'Region');
        }
        if(empty($this->input->post('province'))){
            $errFlag++;
            array_push($errArr, 'Province');
        }
        if(empty($this->input->post('city'))){
            $errFlag++;
            array_push($errArr, 'City');
        }
        if(empty($this->input->post('barangay'))){
            $errFlag++;
            array_push($errArr, 'Barangay');
        }
        if(empty($this->input->post('zip_code'))){
            $errFlag++;
            array_push($errArr, 'Zip Code');
        }
        if(empty($this->input->post('street'))){
            $errFlag++;
            array_push($errArr, 'Street');
        }
        if(empty($this->input->post('market_id'))){
            $errFlag++;
            array_push($errArr, 'Market');
        }
        if(empty($this->input->post('membership_type'))){
            $errFlag++;
            array_push($errArr, 'Membership Type');
        }
        if(empty($this->input->post('subscription_amount'))){
            $errFlag++;
            array_push($errArr, 'Subscriptiom Amount');
        }
        if(empty($this->input->post('payment_mode'))){
            $errFlag++;
            array_push($errArr, 'Payment Mode');
        }
        if(empty($this->input->post('payment_per_mode'))){
            $errFlag++;
            array_push($errArr, 'Payment Per Mode');
        }

        if($errFlag != 0){
            $response['success'] = false;
            $response['msg'] = implode(", ", $errArr)." is Empty!";
            echo json_encode($response); exit();
        }

        $dataNull = 0;
        $form_data['last_name'] = !empty($this->input->post('last_name')) ? $this->input->post('last_name') : $dataNull++;
        $form_data['first_name'] = !empty($this->input->post('first_name')) ? $this->input->post('first_name') : $dataNull++;
        $form_data['middle_name'] = !empty($this->input->post('middle_name')) ? $this->input->post('middle_name') : $dataNull++;
        $form_data['gender_id'] = !empty($this->input->post('gender')) ? $this->input->post('gender') : $dataNull++;
        $form_data['birth_date'] = !empty(date_format(date_create($this->input->post('birth_date')),"Y-m-d")) ? date_format(date_create($this->input->post('birth_date')),"Y-m-d") : $dataNull++;
        $form_data['birth_place'] = !empty($this->input->post('birth_place')) ? $this->input->post('birth_place') : $dataNull++;
        $form_data['email'] = !empty($this->input->post('email')) ? $this->input->post('email') : $dataNull++;
        $form_data['mobile_no'] = !empty($this->input->post('mobile_no')) ? $this->input->post('mobile_no') : $dataNull++;
        $form_data['countryCode'] = !empty($this->input->post('country')) ? $this->input->post('country') : $dataNull++;
        $form_data['regionCode'] = !empty($this->input->post('region')) ? $this->input->post('region') : $dataNull++;
        $form_data['provinceCode'] = !empty($this->input->post('province')) ? $this->input->post('province') : $dataNull++;
        $form_data['cityCode'] = !empty($this->input->post('city')) ? $this->input->post('city') : $dataNull++;
        $form_data['barangayCode'] = !empty($this->input->post('barangay')) ? $this->input->post('barangay') : $dataNull++;
        $form_data['zip_code'] = !empty($this->input->post('zip_code')) ? $this->input->post('zip_code') : $dataNull++;
        $form_data['street'] = !empty($this->input->post('street')) ? $this->input->post('street') : $dataNull++;
        $form_data['market_addr_id'] = !empty($this->input->post('market_id')) ? $this->input->post('market_id') : $dataNull++;
        $form_data['stall_no_id'] = !empty($this->input->post('stall_no_id')) ? $this->input->post('stall_no_id') : null;
        $form_data['stall_description'] = !empty($this->input->post('stall_description')) ? $this->input->post('stall_description') : null;
        $form_data['membership_type_id'] = !empty($this->input->post('membership_type')) ? $this->input->post('membership_type') : $dataNull++;
        $form_data['subscription_amount'] = !empty($this->input->post('subscription_amount')) ? $this->input->post('subscription_amount') : $dataNull++;
        $form_data['payment_mode_id'] = !empty($this->input->post('payment_mode')) ? $this->input->post('payment_mode') : $dataNull++;
        $form_data['payment_per_mode'] = !empty($this->input->post('payment_per_mode')) ? $this->input->post('payment_per_mode') : $dataNull++;
        $form_data['mother_last_name'] = !empty($this->input->post('mother_last_name')) ? $this->input->post('mother_last_name') : $dataNull++;
        $form_data['mother_first_name'] = !empty($this->input->post('mother_first_name')) ? $this->input->post('mother_first_name') : $dataNull++;
        $form_data['mother_maiden'] = !empty($this->input->post('mother_maiden')) ? $this->input->post('mother_maiden') : $dataNull++;
        $form_data['father_last_name'] = !empty($this->input->post('father_last_name')) ? $this->input->post('father_last_name') : $dataNull++;
        $form_data['father_first_name'] = !empty($this->input->post('father_first_name')) ? $this->input->post('father_first_name') : $dataNull++;
        $form_data['father_middle_name'] = !empty($this->input->post('father_middle_name')) ? $this->input->post('father_middle_name') : $dataNull++;
        $form_data['date_created'] = date("Y-m-d H:i:s");

        if($dataNull == 0){
            if($form_data['subscription_amount'] < $form_data['payment_per_mode']){
                $response['success'] = false;
                $response['msg'] = "Subscription amount is less than payment per mode and it is invalid!";
                echo json_encode($response); exit();
            }
            $selected_school_id = $this->input->post('selected_school_id');
            $entry_school = $this->input->post('entry_school');
            $selected_company_id = $this->input->post('selected_company_id');
            $entry_company = $this->input->post('entry_company');
            $errFlag = false;
            if(!empty($selected_school_id) || !empty($entry_school)){
                if(!empty($selected_school_id) && $selected_school_id != 0){
                    $form_data['school_id'] = $selected_school_id;
                }else if(!empty($entry_school) && $selected_school_id == 0){
                    $school_id = $this->Mdl_registration->insert_school($entry_school);
                    if($school_id['success']){
                        $form_data['school_id'] = !empty($school_id['school_id']) ? $school_id['school_id'] : 0;
                    }else{
                        $errFlag = true;
                        $response['msg'] = "Error while inserting School name!";
                    }
                }else{
                        $errFlag = true;
                        $response['msg'] = "Error while inserting School name! no id found";
                }
            }else if(!empty($selected_company_id) || !empty($entry_company)){
                if(!empty($selected_company_id) && $selected_company_id != 0){
                    $form_data['company_id'] = $selected_company_id;
                }else if(!empty($entry_company) && $selected_company_id == 0){
                    $company_id = $this->Mdl_registration->insert_company($entry_company);
                    if($company_id['success']){
                        $form_data['company_id'] = !empty($company_id['company_id']) ? $company_id['company_id'] : 0;
                    }else{
                        $errFlag = true;
                        $response['msg'] = "Error while inserting Company name!";
                    }
                }else{
                        $errFlag = true;
                        $response['msg'] = "Error while inserting Company name! no id found";
                        $response['company_id'] = "Error while inserting Company name! no id found";
                }
            }
            if($errFlag == false){
                $datas = $this->Mdl_registration->register_information($form_data);
                if($datas['success'] == true){
                    $response['success'] = true;
                    $response['redirect'] = base_url("Infos?ref=".$datas['id']);
                    $response['msg'] = strtoupper($form_data['last_name'].", ".$form_data['first_name']." ".$form_data['middle_name'])." is now registered!";
                // }else if($datas['success'] == false && $datas['exist'] == false){
                }else if($datas['success'] == false){
                    $response['success'] = false;
                    $response['msg'] = "Error while registering ".strtoupper($form_data['last_name'].", ".$form_data['first_name']." ".$form_data['middle_name']);
                }else if($datas['exist'] == true){
                    $response['success'] = false;
                    $response['msg'] = "User ".strtoupper($form_data['last_name'].", ".$form_data['first_name']." ".$form_data['middle_name'])." is already Registered!";
                }
            }else{
                $response['success'] = false;
            }
        }else{
            $response['success'] = false;
            $response['msg'] = $dataNull;
        }

        $response['token'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }
    public function get_school_list(){
        $datas = $this->Mdl_registration->get_school_list();
        $html = '<option value="0">NO SCHOOL NAME FOUND</option>';
        if($datas['success']){
            $html = '<option value="0" selected>Select School Name</option>';
            foreach($datas['data'] as $data) {
                $html .= "<option value=".$data->SchoolId.">".$data->SchoolName."</option>";
            }
        }
        return $html;
    }
    public function get_company_list(){
        $datas = $this->Mdl_registration->get_company_list();
        $html = '<option value="0">NO COMPANY NAME FOUND</option>';
        if($datas['success']){
            $html = '<option value="0" selected>Select Company Name</option>';
            foreach($datas['data'] as $data) {
                $html .= "<option value=".$data->CompanyId.">".$data->Company_name."</option>";
            }
        }
        return $html;
    }
    public function registered_list(){
        $datas = $this->Mdl_registration->registered_list();
        if($datas['success']){
            $name = array();
            $id = array();
            foreach ($datas['data'] as $data) {
                $name[] = mb_strtoupper($data->LastName.", ".$data->FirstName." ".$data->MiddleInitial, "UTF-8");
                $id[] = $data->PersonnelId;
            }
            $response['name'] = $name;
            $response['id'] =  $id;
            $response['success'] = true;
        }else{
            $response['success'] = false;
            $response['msg'] = 'Error while getting data!';
        }
        $response['token'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }
    public function remove_registered_info(){
        $registered_id = $this->input->post('registered_id');
        $datas = $this->Mdl_registration->remove_registered_info($registered_id);
        if($datas['success']){
            $response['success'] = true;
            $response['msg'] = "Information successfully removed!";
        }else{
            $response['success'] = false;
            $response['msg'] = "Error while removing information!";
        }
        $response['token'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }
    public function region_list()
    {
        // $region_code = $this->input->post('region_code');
        $datas = $this->Mdl_registration->region_list();
        $array = array();
        foreach ($datas as $data) {
            $sub_array = array();
            $sub_array['regCode'] = $data->regCode;
            $sub_array['regDesc'] = strtoupper($data->regDesc);
            $array[] = $sub_array;
        }
        $response['data'] = $array;
        $response['token'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }
    public function province_list()
    {
        $region_code = $this->input->post('region_code');
        $datas = $this->Mdl_registration->province_list($region_code);
        $array = array();
        foreach ($datas as $data) {
            $sub_array = array();
            $sub_array['provDesc'] = strtoupper($data->provDesc);
            $sub_array['provCode'] = $data->provCode;
            $array[] = $sub_array;
        }
        $response['data'] = $array;
        $response['token'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }
    public function city_list()
    {
        $province_code = $this->input->post('province_code');
        $datas = $this->Mdl_registration->city_list($province_code);
        $array = array();
        foreach ($datas as $data) {
            $sub_array = array();
            $sub_array['citymunDesc'] = strtoupper($data->citymunDesc);
            $sub_array['citymunCode'] = $data->citymunCode;
            $array[] = $sub_array;
        }
        $response['data'] = $array;
        $response['token'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }

    public function barangay_list()
    {
        $city_code = $this->input->post('city_code');
        $datas = $this->Mdl_registration->barangay_list($city_code);
        $array = array();
        foreach ($datas as $data) {
            $sub_array = array();
            $sub_array['brgyDesc'] = strtoupper($data->brgyDesc);
            $sub_array['brgyCode'] = $data->brgyCode;
            $array[] = $sub_array;
        }
        $response['zip_code'] = $this->get_city_zip_code($city_code);
        $response['data'] = $array;
        $response['token'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }

    public function get_city_zip_code($city_code){

        return $this->Mdl_registration->get_city_zip_code($city_code)[0]->zip_code;

    }

    public function check_if_registered(){
        $first_name = strtoupper($this->input->post('first_name'));
        $last_name = strtoupper($this->input->post('last_name'));
        $check = $this->Mdl_registration->check_if_registered($first_name,$last_name);
        if($check){
            $response['msg'] = "$last_name, $first_name is already registered!";
            $response['success'] = true;
        }else{
            $response['success'] = false;
        }
        $response['token'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }

    public function market_list(){

        $datas = $this->Mdl_registration->market_list();
        $array = array();
        foreach ($datas as $data) {
            $sub_array = array();
            $sub_array['id'] = $data->id;
            $sub_array['address'] = strtoupper($data->address);
            $array[] = $sub_array;
        }

        $response['data'] = $array;
        $response['token'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }

    public function membership_type_list(){

        $datas = $this->Mdl_registration->membership_type_list();
        $array = array();
        foreach ($datas as $data) {
            $sub_array = array();
            $sub_array['id'] = $data->id;
            $sub_array['type'] = strtoupper($data->type);
            $array[] = $sub_array;
        }

        $response['data'] = $array;
        $response['token'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }

    public function payment_mode(){

        $datas = $this->Mdl_registration->payment_mode();
        $array = array();
        foreach ($datas as $data) {
            $sub_array = array();
            $sub_array['id'] = $data->id;
            $sub_array['mode'] = strtoupper($data->mode);
            $array[] = $sub_array;
        }

        $response['data'] = $array;
        $response['token'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    } 

    public function stall_list(){

        $datas = $this->Mdl_registration->stall_list();
        $array = array();
        foreach ($datas as $data) {
            $sub_array = array();
            $sub_array['id'] = $data->id;
            $sub_array['stall_no'] = strtoupper($data->stall_no);
            $array[] = $sub_array;
        }

        $response['data'] = $array;
        $response['token'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    } 

    public function gender_list(){

        $datas = $this->Mdl_registration->gender_list();
        $array = array();
        foreach ($datas as $data) {
            $sub_array = array();
            $sub_array['id'] = $data->id;
            $sub_array['description'] = strtoupper($data->description);
            $array[] = $sub_array;
        }

        $response['data'] = $array;
        $response['token'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    } 
}