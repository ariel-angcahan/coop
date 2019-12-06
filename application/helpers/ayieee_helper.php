<?php 
if(!defined('BASEPATH') ) exit('NO direct script access allowed');
define("cryptography_flag", false);
define("chiperCode", "chiperCode");
define("method", "aes-256-cbc");

class aySec{
    
    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->encryption->initialize(array('driver' => 'openssl'));
    }

    public function post($string){
        return $this->ci->input->post($string);
    }

    public function get($string){
        return $this->ci->input->get($string);
    }

    public function get_user_role($role_id){
        $this->ci->db->limit(1);
        $this->ci->db->select("*");
        $this->ci->db->from("ci_mst_role");
        $this->ci->db->where("RoleId", $role_id);
        $result = $this->ci->db->get()->result();

        return $result[0]->Role;
    }

    public function activity_logs(){
        $this->ci->db->trans_start();
        $post_data = implode(', ', array_map(
            function ($v, $k) {
                return sprintf("%s='%s'", $k, $v); 
            }, $_POST, array_keys($_POST)
        ));
        $this->ci->db->set('user_id', $this->ci->session->UAId);
        $this->ci->db->set('visitor_ipaddr', $_SERVER['REMOTE_ADDR']);
        $this->ci->db->set('uri', $_SERVER['PHP_SELF']);
        $this->ci->db->set('data', encrypt($post_data));
        $this->ci->db->set('datetime', 'NOW()', false);
        $this->ci->db->insert('ci_system_logs');
        $this->ci->db->trans_complete();
    }

    public function get_csrf_hash(){
        return $this->ci->security->get_csrf_hash();
    }

    public function isConfirmed($password){
        $response['success'] = false;
        $this->ci->db->limit(1);
        $this->ci->db->select("*");
        $this->ci->db->from("ci_user_access");
        $this->ci->db->where("UserName", $this->ci->session->username);
        $this->ci->db->where("Password", encrypt_login($password));
        $result = $this->ci->db->get()->result();

        if(!empty($result)){
            $response['data'] = $result[0];
            $response['success'] = true;
        }

        return $response;
    }

    public function encrypt($string){
        return $this->ci->encryption->encrypt($string);
    }


    public function decrypt($string){
        return $this->ci->encryption->decrypt($string);
    }
}

function convertTime($time){
    return date('h:i A', strtotime($time));
}

function isConfirmed($password){
    $aySec = new aySec();
    $result = $aySec->isConfirmed($password);
    if($result['success']){
        if($result['data']->RoleId != 1){
            $response['generated_token'] = $aySec->get_csrf_hash();
            $response['success'] = false;
            $response['msg'] = 'You dont have this permission to proceed!';
            echo json_encode($response);
            exit();
        } 
    }else{
        $response['generated_token'] = $aySec->get_csrf_hash();
        $response['success'] = false;
        $response['msg'] = 'Invalid password!';
        echo json_encode($response);
        exit();
    }
}

// function get_csrf_hash(){
//     $aySec = new aySec();
//     return $aySec->get_csrf_hash();
// }

function isModified($id){
    $aySec = new aySec();
    if(!is_numeric($id)){
        $response['generated_token'] = $aySec->get_csrf_hash();
        $response['success'] = false;
        $response['msg'] = 'Please do not modify any data!';
        echo json_encode($response);
        exit();
    }
}

function activity_logs(){
    $aySec = new aySec();
    $aySec->activity_logs();
}

function get_user_role($role_id){
    $aySec = new aySec();
    return $aySec->get_user_role($role_id);
}

function empty_request($arrPost){
    $arrMsg = array();
    foreach ($arrPost as $key => $value) {
        if(empty($value)){
            array_push($arrMsg, $key);
        }
    }
    return $arrMsg;
}

function post($string, $hash_flag = false){

    $aySec = new aySec();
    $response;
    if($hash_flag){
        $response = decrypt($aySec->post($string));
    }else{
        $response = $aySec->post($string);
    }

    return $response;
}

function get($string, $hash_flag = false){
    $aySec = new aySec();
    $response;
    if($hash_flag){
        $response = decrypt($aySec->get($string));
    }else{
        $response = $aySec->get($string);
    }
    return $response;
}

function file_base_url($file_path){
    $path = file_exists(('assets/images/system_upload/'.$file_path)) ? ('assets/images/system_upload/'.$file_path) : ('assets/images/system_upload/avatar.png') ;
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    return 'data:image/' . $type . ';base64,' . base64_encode($data);
}

function user_base_url($file_path){
    $path = file_exists(('assets/images/user_img/'.$file_path)) ? ('assets/images/user_img/'.$file_path) : ('assets/images/user_img/avatar.png') ;
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    return 'data:image/' . $type . ';base64,' . base64_encode($data);
}

function encrypt($string){

    if(!cryptography_flag){
        return $string;
        exit();
    }

    $aySec = new aySec();
    return $aySec->encrypt($string);

	// // Must be exact 32 chars (256 bit)
	// $password = substr(hash('sha256', chiperCode, true), 0, 32);

	// // IV must be exact 16 chars (128 bit)
	// $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

	// // av3DYGLkwBsErphcyYp+imUW4QKs19hUnFyyYcXwURU=
	// $encrypted = base64_encode(openssl_encrypt($string, method, $password, OPENSSL_RAW_DATA, $iv));

	// // My secret message 1234
	// // $decrypted = openssl_decrypt(base64_decode($encrypted), $method, $password, OPENSSL_RAW_DATA, $iv);

	// return $encrypted;
}

function decrypt($string){

    if(!cryptography_flag){
        return $string;
        exit();
    }

    $aySec = new aySec();
    return $aySec->decrypt($string);

	// // Must be exact 32 chars (256 bit)
	// $password = substr(hash('sha256', chiperCode, true), 0, 32);

	// // IV must be exact 16 chars (128 bit)
	// $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

	// // av3DYGLkwBsErphcyYp+imUW4QKs19hUnFyyYcXwURU=
	// // $encrypted = base64_encode(openssl_encrypt($string, $method, $password, OPENSSL_RAW_DATA, $iv));

	// // My secret message 1234
	// $decrypted = openssl_decrypt(base64_decode($string), method, $password, OPENSSL_RAW_DATA, $iv);

	// return $decrypted;
}

function serial_reader(){

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


function strToHex($string){
    $hex='';
    for ($i=0; $i < strlen($string); $i++){
        $hex .= dechex(ord($string[$i]));
    }
    return $hex;
}


function hexToStr($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}
    
function encrypt_login($password){

   $cryptKey  = '9F0F521D9';     
   $encrypted      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $password, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
   return $encrypted ;
}




function POST_REQUEST(){
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        HEADER("LOCATION:".base_url());
        exit();
    }
}

function GET_REQUEST(){
    if($_SERVER['REQUEST_METHOD'] !== 'GET'){
        HEADER("LOCATION:".base_url());
        exit();
    }
}