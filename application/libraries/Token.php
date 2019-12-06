<?php if( ! defined('BASEPATH') ) exit('NO direct script access allowed');


class Token{

	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->library('session');
	}

	function generate_token($first = false)
	{
		 $token['generated_token'] = array(
        	'name' => $this->ci->security->get_csrf_token_name(),
        	'hash' => $this->ci->security->get_csrf_hash()
        );

		if($first)
		{
			$this->ci->session->set_userdata('primary_token', $this->ci->security->get_csrf_hash());
		}
		else
		{
			$this->ci->session->set_userdata('gen_token', $this->ci->security->get_csrf_hash());
		}
		
		return $token['generated_token'];
	}

	function check($token)
	{
		$ses_token = $this->ci->session->userdata('primary_token');
		
		if($token ===  $ses_token)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function headers(){
		return header("Access-Control-Allow-Origin: *");  
	}
	
	public function encrypt($password){

	   $cryptKey  = '9F0F521D9';     
	   $encrypted      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $password, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
	   return $encrypted ;
	}

}