<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applications extends MX_Controller {

    public function __construct(){

        parent::__construct();
        $this->load->model('Mdl_application');
        date_default_timezone_set("Asia/Manila");

        if(!$this->session->userdata('isLogin')) {
            header('location:'.base_url());
            die();
        }
    }

    public function index() {
        
        $data['generated_token'] = $this->security->get_csrf_hash();

        $this->load->view('standard_layout.php', $data);
    }

    public function file_base_url($file_name){
        $path = file_exists(('assets/images/system_upload/'.$file_name)) ? ('assets/images/system_upload/'.$file_name) : ('assets/images/system_upload/avatar.png') ;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    public function registered_list() {

        $datas = $this->Mdl_application->registered_list_table();

        $data = array();
        foreach ($datas as $row) {

            $date = new DateTime($row->date_created);

            $sub_array = array();
            $sub_array[] = "REF".date_format(date_create($row->date_created),"Ymd")."-".$row->id;
            $sub_array[] = strtoupper($row->first_name) . " " . strtoupper($row->middle_name) . " " . strtoupper($row->last_name);
            $sub_array[] = $date->format('Y-m-d h:i:s a');
            $sub_array[] = '<a class="btn btn-preview-info bg-blue btn-xs" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="bottom" title="Personal Details">
                                <i class="material-icons">done_all</i>
                            </a>';
            $data[] = $sub_array;
        }

        $output = array(  
            "draw"              => intval($this->input->post('draw')),  
            "recordsTotal"      => $this->Mdl_application->get_all_registered_data(),  
            "recordsFiltered"   => $this->Mdl_application->get_filtered_registered_data(),  
            "data"              => $data,
            "generated_token"   => $this->security->get_csrf_hash()
        );

        echo json_encode($output);
    }

    public function get_application_information(){

        $response['generated_token'] = $this->security->get_csrf_hash();
        $id = $this->input->post('id');
        $datas = $this->Mdl_application->get_application_information($id);
        if($datas['success']){
            $response['first_name'] = ucfirst($datas['data']->first_name);
            $response['middle_name'] = ucfirst($datas['data']->middle_name);
            $response['last_name'] = ucfirst($datas['data']->last_name);
            $response['birth_date'] = date_format(date_create($datas['data']->birth_date),"M d, Y");
            $response['email'] = $datas['data']->email;
            $response['id'] = $datas['data']->tmp_id;
            $response['image_name'] = ($datas['data']->image_name != null ? $this->file_base_url($datas['data']->image_name) : $this->file_base_url('avatar.png'));
            $response['mobile_no'] = $datas['data']->mobile_no;
            $response['market_address'] = ucfirst($datas['data']->market_address);
            $response['membership_type'] = ucfirst($datas['data']->membership_type);
            $response['payment_mode'] = ucfirst($datas['data']->payment_mode);
            $response['subscription_amount'] = number_format($datas['data']->subscription_amount, 2);
            $response['payment_per_mode'] = number_format($datas['data']->payment_per_mode, 2);
            $response['success'] = true;
        }else{
            $response['success'] = false;
            $response['msg'] = "Error while retreiving data!";
        }

        echo json_encode($response);
    }

    public function upload_image(){

        $response['generated_token'] = $this->security->get_csrf_hash();
        $applicant_id = $this->input->post('applicant-id');
        $applicant_name = str_replace(" ", "_", $this->input->post('applicant-name'));
        $path_parts = pathinfo($_FILES["file"]["name"]);
        $file_extension = $path_parts['extension'];

        //checking if images is exist in the server base on file name
        if ($applicant_name && $file_extension && file_exists("assets/images/system_upload" . "/" . ($applicant_name.".".$file_extension))){
            unlink("assets/images/system_upload" . "/" . ($applicant_name.".".$file_extension));
        }

        $config['upload_path']          = "assets/images/system_upload";
        $config['allowed_types']        = 'jpg|jpeg|png|JPG';
        $config['file_name']            = $applicant_name;

        $this->load->library('upload', $config);
        $this->load->library('image_lib');
        $this->upload->initialize($config);

        //this process is the copying the image from client side into server side
        if($this->upload->do_upload('file')){
            //resizing image
            $image_data =   $this->upload->data();
            $configer =  array(
              'image_library'   => 'gd2',
              'source_image'    =>  $image_data['full_path'],
              'maintain_ratio'  =>  FALSE,
              'width'           =>  256,
              'height'          =>  256,
            );
            $this->image_lib->clear();
            $this->image_lib->initialize($configer);
            $this->image_lib->resize();
            //end of resizing image

            //inserting the name of the image
            $insert_update = $this->Mdl_application->insert_update($applicant_id, ($applicant_name.".".$file_extension));
            if($insert_update){
                $response['msg'] = "Image saved!";
                $response['img'] = $this->file_base_url(($applicant_name.".".$file_extension));
                // $response['img_dir'] = base_url("assets/images/system_upload/".($applicant_name.".".$file_extension));
                $response['success'] = true;
            }
        }else{
            $response['msg'] = $error = array('error' => $this->upload->display_errors());
            $response['success'] = false;
        }
        
        echo json_encode($response);
    }

    public function approve_application(){

        $response['generated_token'] = $this->security->get_csrf_hash();
        $application_id = $this->input->post('application-id');
        // $balance = currentSubscriptionBalance(getTmcCodeBySubscriotnId($subscription_id));
        // if($balance != 0){
        //     $response['success'] = false;
        //     $response['msg'] = "Please make sure to Zero the balance of last subscription of this account!";
        //     echo json_encode($response); exit();
        // }
        $this->db->trans_start();
        $check_image = $this->Mdl_application->check_image($application_id);

        if($check_image['success']){
            $person_info_tmp = $check_image['data'];
            $subscription_amount = $person_info_tmp->subscription_amount;
            $id = $person_info_tmp->id;
            $payment_mode_id = $person_info_tmp->payment_mode_id;
            $payment_per_mode = $person_info_tmp->payment_per_mode;
            unset($person_info_tmp->id);
            unset($person_info_tmp->subscription_amount);
            unset($person_info_tmp->payment_mode_id);
            unset($person_info_tmp->payment_per_mode);
            unset($person_info_tmp->approved_flag);
            $insert_person_info = $this->Mdl_application->insert_person_info($person_info_tmp, $id);

            if($insert_person_info['success']){
                $tmc = "TMC-".date('Ym').sprintf('%05d', $insert_person_info['inserted_id']);
                $inserted_approved_member = $this->Mdl_application->insert_approved_member($insert_person_info['inserted_id'], $tmc);
                if($inserted_approved_member['success']){
                    $inserted_subscription_mst = $this->Mdl_application->insert_subscription_mst($inserted_approved_member['inserted_id'], $subscription_amount, $payment_mode_id, $payment_per_mode);

                    if($inserted_subscription_mst['success']){
                        $subscription_info = $this->Mdl_application->get_subscription_information($inserted_subscription_mst['inserted_id']);
                        if($subscription_info['success']){
                            $sub_amount = $subscription_info['data']->subscription_amount;
                            $payment_per_mode = $subscription_info['data']->payment_per_mode;
                            $total_terms =  round($sub_amount / $payment_per_mode);
                            if($total_terms < 1){
                                $response['success'] = false;
                                $response['msg'] = "Subscription amount is less than payment per mode and it is invalid!";
                                echo json_encode($response); exit();
                            }
                            $payment_mode = $subscription_info['data']->payment_mode_id;

                            $date_array = array();
                            $date = new DateTime();
                            for($i=1; $i <= $total_terms; $i++) { 
                                if($i == 1){
                                    switch($payment_mode){
                                        case '1':
                                            $date->modify('next sunday');
                                            $date->modify('next sunday');
                                            // $date = strtotime('next sunday', time());
                                        break;
                                        case '2':
                                            if($date->format('d') < 16){
                                                $date->modify('first day of next month');
                                                $date->modify('+14 day');
                                            }else{
                                                $date->modify('last day of next month');
                                            }
                                        break;
                                        case '3':
                                            $date->modify('last day of next month');
                                            // $date = strtotime('last day of this month', time());
                                        break;
                                    }
                                }else{
                                    switch($payment_mode){
                                        case '1':
                                            // Modify the date it contains
                                            $date->modify('next sunday');
                                            // $date = strtotime('next sunday', time());
                                        break;
                                        case '2':
                                            if($date->format('d') == 15){
                                                $date->modify('last day of this month');
                                            }else{
                                                $date->modify('first day of next month');
                                                $date->modify('+14 day');
                                            }
                                        break;
                                        case '3':
                                            // Modify the date it contains
                                            $date->modify('last day of next month');
                                            // $date = strtotime('last day of next month', time());
                                        break;
                                    }

                                }
                                $date_array[] = $date->format('Y-m-d');
                                // $display .=  $date->format('Y-m-d')." | ".number_format($sub_amount / $total_terms, 2)."<br>";
                            }
                            
                            $index = 1;
                            foreach ($date_array as $key => $due_date) {
                                $insert_ledger['subscription_id'] = $inserted_subscription_mst['inserted_id'];// subscription_id id
                                $insert_ledger['row_num'] = $index++;
                                $insert_ledger['due_date'] = $due_date;
                                $insert_ledger['amount_to_paid'] = $payment_per_mode;
                                $ledgers = $this->Mdl_application->member_ledger_header($insert_ledger);
                                if(++$key === count($date_array)){
                                    $update_flag = $this->Mdl_application->update_subscription_approve_flag($inserted_subscription_mst['inserted_id']);
                                    if($update_flag['success']){
                                        $this->db->trans_complete();
                                        $response['success'] = true;
                                        $response['msg'] = "Member approved!";
                                    }else{
                                        $response['success'] = false;
                                        $response['msg'] = "Error while updating subscription flag!";
                                    }
                                }
                            }
                        }else{
                            $response['success'] = false;
                            $response['msg'] = "Error while retreiving subscription info!";
                        }
                    }else{
                        $response['success'] = false;
                        $response['msg'] = "Error while inserting subscription info!";
                    }

                }else{
                    $response['success'] = false;
                    $response['msg'] = "Error while approving members!";
                }
            }else{
                $response['success'] = false;
                $response['msg'] = "Error while transfering members information!";
            }
        }else{
            $response['success'] = false;
            $response['msg'] = "Please upload member photo!";
        }

        echo json_encode($response);
    }
}