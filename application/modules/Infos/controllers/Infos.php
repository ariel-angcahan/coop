<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Infos extends MX_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Mdl_info');
        date_default_timezone_set("Asia/Manila");
        
        if ($this->session->isLogin) {
            header('location:'.base_url('Dashboards'));
        }
    }

    public function index(){
    	$data["body"] = $this->get_info($this->input->get('ref'));
        $data['token'] = $this->security->get_csrf_hash();
        $this->load->view('standard_layout.php', $data);
    }

    public function get_info($id){

        $datas = $this->Mdl_info->get_info($id);
        $html = "";

        if($datas["success"]){
            $html =     "<tr>
                            <th>CODE:</th>
                            <td>REF".date_format(date_create($datas["data"]->date_created),"Ymd")."-".$datas["data"]->id."</td>
                        </tr>
                        <tr>
                            <th>NAME:</th>
                            <td id='name'>".strtoupper($datas["data"]->first_name)." ".strtoupper($datas["data"]->middle_name)." ".strtoupper($datas["data"]->last_name)."</td>
                        </tr>
                        <tr>
                            <th>CONTACT:</th>
                            <td>".strtoupper($datas["data"]->mobile_no)." / ".strtoupper($datas["data"]->email)."</td>
                        </tr>
                        <tr>
                            <th>MARKET:</th>
                            <td>".strtoupper($datas["data"]->market)."</td>
                        </tr>
                        <tr>
                            <th>STALL LOCATION:</th>
                            <td>".(!empty($datas["data"]->stall_location) ? strtoupper($datas["data"]->stall_location) : strtoupper($datas["data"]->stall_description))."</td>
                        </tr>
                        <tr>
                            <th>MEMBERSHIP:</th>
                            <td>".strtoupper($datas["data"]->membership_type)."</td>
                        </tr>
                        <tr>
                            <th>SUBSCRIPTION:</th>
                            <td>".number_format($datas["data"]->subscription_amount, 2)."</td>
                        </tr>
                        <tr>
                            <th>PAYMENT MODE:</th>
                            <td>".strtoupper($datas["data"]->payment_mode)."</td>
                        </tr>
                        <tr>
                            <th>WEEKLY PAYMENT:</th>
                            <td>".number_format($datas["data"]->payment_per_mode, 2)."</td>
                        </tr>";

        }else{
            $html = "No data found!";
        }

        return $html;
    }
}