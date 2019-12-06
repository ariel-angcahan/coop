<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class Index extends MX_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Mdl_index');
        date_default_timezone_set("Asia/Manila");
        
        if ($this->session->isLogin) {
            header('location:'.base_url('Dashboards'));
        }
    }

    public function index(){
        $data['list'] = $this->last_registered();
        $this->load->view('standard_argon_layout.php', $data);
    }

    public function last_registered(){
        $result = $this->Mdl_index->last_registered();
        $html = '';
        foreach ($result as $key => $value) {
            $date = new DateTime($value->date_created);
            $html .= '<tr>
                        <td>'.(++$key).'</td>
                        <td>'.ucwords($value->name).'</td>
                        <td>'.$date->format('M. d, Y').'</td>
                        <td><i class="ni ni-check-bold"></i> Approved</td>
                    </tr>';
        }

        return $html;
    }

    // public function index(){
    //     $data['token'] = $this->security->get_csrf_hash();
    //     $data['list'] = $this->last_registered();
    //     $this->load->view('standard_layout.php', $data);
    // }

    // public function last_registered(){
    // 	$result = $this->Mdl_index->last_registered();
    // 	$html = '';
    // 	foreach ($result as $key => $value) {
    // 		$date = new DateTime($value->date_created);
    // 		$html .= '<tr>
    //                     <td>'.(++$key).'</td>
    //                     <td>'.ucwords($value->name).'</td>
    //                     <td>'.$date->format('M. d, Y').'</td>
    //                     <td><span class="label bg-green">Approved</span></td>
    //                 </tr>';
    // 	}

    // 	return $html;
    // }
}