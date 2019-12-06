<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profiles extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Manila");
		
		if(!$this->session->userdata('isLogin')) {
            header('location:'.base_url());
            die();
		}
		$this->session->set_userdata('startPointer', 0);
		$this->load->model('Profile');
	}

	public function index()
	{	
		$data['generated_token'] = $this->security->get_csrf_hash();
		$data['title'] = 'System | Human Resource Information System';
    	$this->load->view('standard_layout', $data);
	}  

	public function savePassword()
	{
        activity_logs();
		$data = $this->input->post('password');
		$formData = array(
			'password' => $data 
		);
		$this->Profile->savePassword($formData);

		echo json_encode(array('success' => true, 'msg'=> 'successfully inserted', 'generated_token'=> $this->token->generate_token()));
        exit();
	}

	public function changeProfilePicture()
	{
        activity_logs();
		if(!$this->authorization->check($this->session->UAId, $this->session->SmenuId, 'create'))
        {
            echo json_encode(array('success' => false, 'msg'=> 'Access Denied! You have no rights to perform this action.', 'generated_token'=> $this->token->generate_token()));
            exit();
        }
		$fileTypes = array("image/jpg","image/JPG","image/png","image/PNG","image/gif","image/GIF","image/jpeg","image/JPEG"); 
		$user_img = 'assets/images/user_img/avatar.png';

		$defaultImage = base_url('/assets/images/user_img/avatar.png');

		$token = $this->token->generate_token();
		$response = array('success' => false, 'msg'=> '', 'generated_token' => $token, 'defaultImg' => $defaultImage);
	
		if(!empty($_FILES['fileEmp'])){
            $extension = explode('/',$_FILES['fileEmp']['type'][0]);
			if(in_array($_FILES['fileEmp']['type'][0], $fileTypes)){
				$uniqFileName = $this->session->EmpId.'.'.$extension[1];
				if(move_uploaded_file($_FILES['fileEmp']['tmp_name'][0], 'assets/images/user_img/'.$uniqFileName)){
					$user_img = 'assets/images/user_img/'.$uniqFileName;
					$formData = array('EmpImage' => $user_img);
					$this->Profile->changeProfilePicture($formData, $this->session->EmpId);
				}
			}
			else{
				$response['success'] = false;
				$response['msg'] = 'File Type not supported';
                echo json_encode($response); exit();
			}
		}
		
        echo json_encode($response);
	}

	public function changePassword()
	{
        activity_logs();
		$response['success'] = false;
		$response['generated_token'] = $this->token->generate_token();

		if(!$this->authorization->check($this->session->UAId, $this->session->SmenuId, 'create'))
        {
            echo json_encode(array('success' => false, 'msg'=> 'Access Denied! You have no rights to perform this action.', 'generated_token'=> $this->token->generate_token()));
            exit();
		}
		
		$password = $this->input->post('password');
		$cpassword = $this->input->post('cpassword');

		if(empty($password)){
			echo json_encode(array('success' => false, 'msg'=> 'Please enter password', 'generated_token'=> $this->token->generate_token()));
            exit();
		}
		if(empty($cpassword)){
			echo json_encode(array('success' => false, 'msg'=> 'Password Not Match', 'generated_token'=> $this->token->generate_token()));
            exit();
		}

		if($password == $cpassword){
			$formData = array(
				'Password' => $password
			);
			$this->Profile->changePassword($this->session->EmpId, $formData);
			$response['success'] = true;
		}else{
			$response['msg'] = 'Password Not Match'; 
		}

		echo json_encode($response);    
	}

    public function upload_image(){
        activity_logs();
        $response['generated_token'] = $this->security->get_csrf_hash();
        $user_id = $this->session->UAId;
        $path_parts = pathinfo($_FILES["file"]["name"]);
        $file_extension = $path_parts['extension'];

        //checking if images is exist in the server base on file name
        if (file_exists("assets/images/user_img/" . ($user_id.".".$file_extension))){
            unlink("assets/images/user_img/" . ($user_id.".".$file_extension));
        }

        $config['upload_path']          = "assets/images/user_img";
        $config['allowed_types']        = 'jpg|jpeg|png|JPG';
        $config['file_name']            = $user_id;

        $this->load->library('upload', $config);
        $this->load->library('image_lib');
        $this->upload->initialize($config);

        //this process is the copying the image from client side into server side
        if($this->upload->do_upload('file')){
            //resizing image
            $image_data =   $this->upload->data();
            $config =  array(
              'image_library'   => 'gd2',
              'source_image'    =>  $image_data['full_path'],
              'maintain_ratio'  =>  FALSE,
              'width'           =>  256,
              'height'          =>  256,
            );
            $this->image_lib->clear();
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            //end of resizing image

            //inserting the name of the image
            $insert_update = $this->Profile->insert_update_image($user_id, ($user_id.".".$file_extension));
            if($insert_update['success']){
                $response['msg'] = "Image saved!";
                $response['img'] = user_base_url(($user_id.".".$file_extension));
	            $sessiondata = array(
					'avatar' =>  user_base_url(($user_id.".".$file_extension))
				);
				$this->session->set_userdata($sessiondata);
                $response['success'] = true;
            }
        }else{
            $response['msg'] = $error = array('error' => $this->upload->display_errors());
            $response['success'] = false;
        }
        
        echo json_encode($response);
    }

    public function change_password(){
        $response['generated_token'] = $this->security->get_csrf_hash();
    	$old_password = $this->input->post('old-password');
    	$new_password = $this->input->post('new-password');
    	$confirm_password = $this->input->post('confirm-password');

    	$errArr = array();
    	$errCount = 0;
    	if(empty($old_password)){
    		array_push($errArr, 'Old Password');
    		$errCount++;
    	}
    	if(empty($new_password)){
    		array_push($errArr, 'Password');
    		$errCount++;
    	}
    	if(empty($confirm_password)){
    		array_push($errArr, 'Confirm Password');
    		$errCount++;
    	}

    	if($errCount != 0){
            $response['msg'] = implode(', ', $errArr).' is empty!';
            $response['success'] = false;
            echo json_encode($response); exit();
    	}

    	if($new_password !== $confirm_password){
            $response['msg'] = "Confirm password is not match!";
            $response['success'] = false;
            echo json_encode($response); exit();
    	}

    	$check = $this->Profile->check_old_password($old_password)['success'];

    	if($check){
	    	$update = $this->Profile->change_password($new_password)['success'];
	    	if($update){
	            $response['msg'] = "Password update successfully!";
	            $response['success'] = true;
	    	}else{
	            $response['msg'] = "Password not chnage!";
	            $response['success'] = false;
	    	}
    	}else{
            $response['msg'] = "Old password is not match to the current password!";
            $response['success'] = false;
    	}

        echo json_encode($response);
    }
}
