<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboards extends MX_Controller {

    public function __construct(){

        parent::__construct();
        $this->load->model('Mdl_dashboard');
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
}