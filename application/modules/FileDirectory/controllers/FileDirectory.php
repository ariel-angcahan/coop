<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FileDirectory extends MX_Controller {

    public function __construct(){

        parent::__construct();
        $this->load->model('Mdl_filedirectory');
        date_default_timezone_set("Asia/Manila");
        $this->load->library('session');
    }


    public function index() {
        $data['generated_token'] = $this->security->get_csrf_hash();

        if($this->session->userdata('dir_isLogin')) {

            $data['id'] = $this->session->dir_id;
            $data['username'] = $this->session->dir_username;
            $data['password'] = $this->session->dir_password;
            $data['name'] = ucwords($this->session->dir_lastname.", ".$this->session->dir_firstname." ".$this->session->dir_middlename);
            $data['isLogin'] = $this->session->dir_isLogin;
            $data['list'] = $this->map_directory();
            $this->load->view('standard_layout.php', $data);
        }else{
            $this->load->view('login.php', $data);
        }
    }

    public function authenticate(){
        $response['generated_token'] = $this->security->get_csrf_hash();
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $errMsg = array();
        $errCount = 0;

        if(empty($username)){
            array_push($errMsg, 'Username');
            $errCount++;
        }

        if(empty($password)){
            array_push($errMsg, 'password');
            $errCount++;
        }

        if(empty($errCount)){

            $result = $this->Mdl_filedirectory->authenticate($username, $password);

            if($result['success']){
                $data = $result['data'];

                $sessiondata = array(
                    "dir_id" => $data->id,
                    "dir_username" => $data->username,
                    "dir_password" => $data->password,
                    "dir_fisrtname" => $data->fisrtname,
                    "dir_middlename" => $data->middlename,
                    "dir_lastname" => $data->lastname,
                    "dir_isLogin" => true
                );

                $this->session->set_userdata($sessiondata);
                $response['success'] = true;
            }else{
                $response['success'] = false;
                $response['msg'] = 'Invalid username or password!';
            }
        }else{
            $response['success'] = false;
            $response['msg'] = implode(', ', $errMsg)." is empty!";
        }

        echo json_encode($response);
    }

    private function map_directory(){
        $username = $this->session->dir_username;
        $id = $this->session->dir_id;

        $dir    = 'FTP/'.$username."-".$id;

        $scanned_directory = array_diff(scandir($dir), array('..', '.'));

        $html = '';
        foreach ($scanned_directory as $key => $value) {
            $key = $key - 1;
            $html .= '<tr>
                    <td>'.$key.'</td>
                    <td>'.$value.'</td>
                    <td>'.$dir.'</td>
                    <td>
                        <a href="'. base_url($dir.'/'.$value).'" download>download</a>
                    </td>
                </tr>';
        }

        return $html;
    }

    public function logout(){
        $this->session->sess_destroy();
        header("LOCATION: ".base_url('FileDirectory'));
    }
}