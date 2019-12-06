<?php if( ! defined('BASEPATH') ) exit('NO direct script access allowed');


class AyieeeSecurity{

	public function __construct()
	{
		// parent::__construct();
		$this->ci =& get_instance();
		$this->ci->load->library('session');
	}

	public function strToHex($string){
	    $hex='';
	    for ($i=0; $i < strlen($string); $i++){
	        $hex .= dechex(ord($string[$i]));
	    }
	    return $hex;
	}


	public function hexToStr($hex){
	    $string='';
	    for ($i=0; $i < strlen($hex)-1; $i+=2){
	        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
	    }
	    return $string;
	}

	// public function index(){

	// 	if($_SESSION['hahahahasula'] == true || $this->serial_checker()['flag'] == true){

	// 		$_SESSION['hahahahasula'] = true;
	// 		return true;

	// 	}else{
	// 		echo "System is not registered!</br>Please contact the developer ang give this Code: ".$this->serial_reader(); exit();
	// 	}

	// }

	public function encrypt($string){
		return $this->encryption->encrypt($string."ariel");
	}

	public function encrypt_original($string){

		$password = 'chiperCode';
		$method = 'aes-256-cbc';

		// Must be exact 32 chars (256 bit)
		$password = substr(hash('sha256', $password, true), 0, 32);

		// IV must be exact 16 chars (128 bit)
		$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

		// av3DYGLkwBsErphcyYp+imUW4QKs19hUnFyyYcXwURU=
		$encrypted = base64_encode(openssl_encrypt($string, $method, $password, OPENSSL_RAW_DATA, $iv));

		// My secret message 1234
		// $decrypted = openssl_decrypt(base64_decode($encrypted), $method, $password, OPENSSL_RAW_DATA, $iv);

		return $encrypted;
	}

	public function decrypt($string){
		return $this->encryption->decrypt($string);
	}

	public function decrypt_original($string){

		$password = 'chiperCode';
		$method = 'aes-256-cbc';

		// Must be exact 32 chars (256 bit)
		$password = substr(hash('sha256', $password, true), 0, 32);

		// IV must be exact 16 chars (128 bit)
		$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

		// av3DYGLkwBsErphcyYp+imUW4QKs19hUnFyyYcXwURU=
		// $encrypted = base64_encode(openssl_encrypt($string, $method, $password, OPENSSL_RAW_DATA, $iv));

		// My secret message 1234
		$decrypt = openssl_decrypt(base64_decode($string), $method, $password, OPENSSL_RAW_DATA, $iv);

		return $decrypted;
	}

	public function serial_reader(){

		$server = explode(" ", php_uname());

		if($server[0] === "Windows"){

			$array = explode(" ", trim(substr(shell_exec("wmic DISKDRIVE GET SerialNumber 2>&1"), 13)));
			return $array[0];
		}else{
			$array = explode(" ", trim(substr(shell_exec("udevadm info --query=all --name=/dev/sda | grep ID_SERIAL"), 13)));
			print_r($array); exit();
			return $array[0];
		}
	}

	public function serial_checker(){

		$response['flag'] = false;

		$this->ci->db->select('*');
		$this->ci->db->from('Codeigniter');
		$count_rows = $this->ci->db->count_all_results();

		if($count_rows == 1){

			$this->ci->db->select('id');
			$this->ci->db->from('Codeigniter');
			$this->ci->db->where('isRegistered', 1);
			$this->ci->db->where('hash', $this->serial_reader());
			$this->ci->db->where('token', str_replace("-", "_", $this->serialize($this->clean($this->encrypt($this->serial_reader())))));
			$data = $this->ci->db->get()->result();

			if(empty(count($data) == 1)){
							header("HTTP/1.1 404 Not Found");$response['flag'] = true;
			}
		}else{

			$this->ci->db->set('hash', $this->serial_reader());
			$this->ci->db->set('isRegistered', 0);
			$this->ci->db->insert('Codeigniter');
		}

		return $response;
	}

	public function serialize($string){

		$sub_string = substr($string, 0, 20);
		$pieces = str_split($sub_string, 5);

		return strtoupper(implode("-", $pieces));
	}

	public function clean($string) {

   		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    	return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
}