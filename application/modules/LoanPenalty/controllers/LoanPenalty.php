<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoanPenalty extends MX_Controller {

    public function __construct(){

        parent::__construct();
        $this->load->model('Mdl_penalty');
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

    public function loan_penalty_list(){
        POST_REQUEST();
        $datas = $this->Mdl_penalty->loan_penalty_list_table();

        $data = array();
        foreach ($datas as $index => $row) {

            $sub_array = array(
                ++$index,
                $row->due_date,
                $row->installment_amount,
                $row->balance,
                $row->balance,
                '<div class="btn-group">
                    <button type="button" class="btn dropdown-toggle bg-purple btn-show-loan-list" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-id="'.encrypt($row->id).'">
                        APPROVE
                    </button>
                </div>'
            );

            array_push($data, $sub_array);
        }

        $output = array(  
            "draw"              => intval($this->input->post('draw')),  
            "recordsTotal"      => $this->Mdl_penalty->get_filtered_loan_penalty_data(),  
            "recordsFiltered"   => $this->Mdl_penalty->get_all_loan_penalty_data(),  
            "data"              => $data,
            "generated_token"   => $this->security->get_csrf_hash()
        );

        echo json_encode($output);
        
    }
}