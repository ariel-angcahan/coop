<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logins extends MX_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {

		parent::__construct();
		$this->load->model('Mdl_login');
		date_default_timezone_set("Asia/Manila");
		
    	if ($this->session->isLogin) {
    		header('location:'.base_url('Dashboards'));
    	}
	}

    public function index() {
    	GET_REQUEST();
		$data['generated_token'] = $this->security->get_csrf_hash();
		$this->load->view('standard_layout', $data);
	}

	public function authentication() { 
		POST_REQUEST();
		activity_logs();
		
		$response = array('success' => false, 'msg'=> 'Internal Server Error');
		
		$form_data['username'] = strtolower($this->input->post('username'));
		$form_data['password'] = $this->token->encrypt($this->input->post('password'));

		$user_info = $this->Mdl_login->authenticate($form_data);

		if ($user_info['success']) {

			$sessiondata = array(
				'username' => $user_info['data']->UserName,
				'name' => $user_info['data']->lname.', '.$user_info['data']->fname,
				'avatar' => (empty($user_info['data']->avatar)) ? user_base_url('avatar.png') : user_base_url($user_info['data']->avatar),
				'RoleId' => $user_info['data']->RoleId,
				'UAId' => $user_info['data']->UAId,
				'TeachersId' => $user_info['data']->TeachersId,
				'isLogin' => true
			);

			$this->session->set_userdata($sessiondata);
			$response['success'] = true;
			$response['msg'] = 'Login success';
			$response['redirect'] = 'Dashboards';
			
		} else {
			$response['success'] = false;
			$response['msg'] = 'Invalid Username or password';
		}
		
		$response['generated_token'] = $this->security->get_csrf_hash();

		echo json_encode($response);
	}
  
	public function logout() {
		$this->session->sess_destroy();
		redirect(base_url());
	}
}
