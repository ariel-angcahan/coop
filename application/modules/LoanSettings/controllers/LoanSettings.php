<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoanSettings extends MX_Controller {

    public function __construct(){

        parent::__construct();
        $this->load->model('Mdl_loanSettings');
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

    public function update(){
        $rate = post("rate");
        $para = post("para");

        if(!empty($para)){
            switch ($para) {
                case 'loanable-rate':
                    # code...
                    break;
                case 'interest-rate':
                    # code...
                    break;
                case 'grt':
                    # code...
                    break;
                default:
                    break;
            }
        }
    }
}