<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MX_Controller {

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
		$this->load->model('Mdl_user');
        $this->load->library('Interceptor');
		date_default_timezone_set("Asia/Manila");
        
        if (!$this->session->userdata('isLogin')) {
            header('location:'.base_url());
            die();
        }
        if (!$this->interceptor->getMenuAccessRight($this->session->RoleId,uri_string())) {
            header('location:'.base_url('Dashboard'));
            die();
        }
	}

    public function index() {

        $data['roles'] = $this->roles_list();
        $data['generated_token'] = $this->security->get_csrf_hash();
        $this->load->view('standard_layout', $data);
	}
    
    public function users_list() {

        $datas = $this->Mdl_user->user_list_table();
        // $deplist = $this->Mdl_user->get_departments();
        $roles = $this->Mdl_user->get_roles();

        $data = array();
        $LNo = 1;
        foreach ($datas as $EmpList) {

            $row = array();
            $row[] = ucwords($EmpList->fname . ' ' . $EmpList->lname);
            $row[] = $EmpList->UserName;
            $row[] = ucwords($this->get_role($EmpList->RoleId, $roles));
            $row[] = '<div class="btn-group">
                        <button type="button" class="btn bg-blue btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i class="material-icons">more_vert</i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a href="javascript:void(0);" class="btn_edit_user waves-effect waves-block load_letter" data-id="'.encrypt($EmpList->UAId).'">Edit User</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="btn-delete-user waves-effect waves-block load_letter" data-id="'.encrypt($EmpList->UAId).'">Remove User</a>
                            </li>
                        </ul>
                    </div>';
            $data[] = $row;
            $LNo++;
        }

       $output = array(  
            'draw'              => intval($this->input->post('draw')),
            'recordsTotal'      => $this->Mdl_user->get_all_data(),
            'recordsFiltered'   => $this->Mdl_user->get_filtered_data(),
            'data'              => $data,
            'generated_token'   => $this->security->get_csrf_hash()
       );

       echo json_encode($output);
    }

    private function roles_list($RoleId = null) {

        $roles = $this->Mdl_user->get_roles($RoleId);

        $html = '<option value="">SELECT ROLE</option>';
        foreach ($roles as $role) {
            $selected = '';
            if($RoleId === $role->RoleId){
                $selected = 'selected';
            }
            $html .= '<option value="'.encrypt($role->RoleId).'" '.$selected.'>'.$role->Role.'</option>';
        }

        return $html;
    }

    private function department_list() {

        // $departments = $this->Mdl_user->get_departments();

        $html = '';
        foreach ($departments as $dept) {
            $html .= '<option value="'.$dept->id.'">'.$dept->department_name.'</option>';
        }

        return $html;
    }

    public function check_username() {
        activity_logs();
        $user = $this->input->post('username');

        if (!empty($user)) {
            $flag = $this->Mdl_user->check_username($user);

            empty($flag) ? $response['success'] = true : $response['success'] = false;
        }

        $response['generated_token'] = $this->security->get_csrf_hash();

        json_encode($response);
    }

    private function set_default_rights($id, $roleId) {
        activity_logs();
        $menuItem = $this->Mdl_user->get_menus();
        $access_rights = [];

        foreach ($menuItem as $menu) {

            $hidden = explode(",", $menu->hidden_from); // explode menu exclusion list
            $access = explode(",", $menu->special_access); // explode menu special access list

            if (in_array($roleId, $access) || ($roleId <= $menu->security_level && ( $menu->hidden_from == null || !in_array($roleId, $hidden) )) ) {
                $user = array('RoleId' => $roleId,
                    'UAId' => $id,
                    'MenuId' => $menu->MenuId,
                    '_view' => 1,
                    '_create' => 1,
                    '_update' => 1,
                    '_delete' => 1
                );
                $access_rights[] = $user;
            } else {
                $user = array('RoleId' => $roleId,
                    'UAId' => $id,
                    'MenuId' => $menu->MenuId,
                    '_view' => 0,
                    '_create' => 0,
                    '_update' => 0,
                    '_delete' => 0
                );
                $access_rights[] = $user;
            }
        }

        return $this->Mdl_user->set_default_rights($access_rights);
    }

    private function edit_default_rights($id, $roleId) {
        activity_logs();
        $menuItem = $this->Mdl_user->get_menus();
        $access_rights = [];

        foreach ($menuItem as $menu) {

            $hidden = explode(",", $menu->hidden_from); // explode menu exclusion list
            $access = explode(",", $menu->special_access); // explode menu special access list

            if (in_array($roleId, $access) || ($roleId <= $menu->security_level && ( $menu->hidden_from == null || !in_array($roleId, $hidden) )) ) {
                $user = array('RoleId' => $roleId,
                    'UAId' => $id,
                    'MenuId' => $menu->MenuId,
                    '_view' => 1,
                    '_create' => 1,
                    '_update' => 1,
                    '_delete' => 1
                );
                $access_rights[] = $user;
            } else {
                $user = array('RoleId' => $roleId,
                    'UAId' => $id,
                    'MenuId' => $menu->MenuId,
                    '_view' => 0,
                    '_create' => 0,
                    '_update' => 0,
                    '_delete' => 0
                );
                $access_rights[] = $user;
            }
        }

        $flag = $this->Mdl_user->edit_default_rights($access_rights);

        return $flag;
    }

    public function get_user() {

        $response['success'] = false;
        $UAId = post('UAId', true);

        if (!empty($UAId)) {
            $user = $this->Mdl_user->get_user($UAId);
            unset($user->Password);
            unset($user->deleted);
            unset($user->email);
            $response_data['RoleId'] = $user->RoleId;
            $response_data['UAId'] = $user->UAId;
            $response_data['UserName'] = $user->UserName;
            $response_data['avatar'] = user_base_url($user->avatar);
            $response_data['RoleId'] = $user->RoleId;
            $response_data['fname'] = ucfirst($user->fname);
            $response_data['lname'] = ucfirst($user->lname);
            $response_data['image_date'] = date_format(date_create($user->image_date),"d-M-Y");
            $response['user'] = $response_data;

            $response['role'] = $this->roles_list($user->RoleId);
            $response['success'] = true;
        }

        $response['generated_token'] = $this->security->get_csrf_hash();

        echo json_encode($response);
    }

    private function get_dept($id, $depts) {

        foreach ($depts as $dept) {
            if ($dept->id == $id) {
                return $dept->department_name;
            }
        }
    }

    private function get_role($id, $roles) {

        foreach ($roles as $role) {
            if ($role->RoleId == $id) {
                return $role->Role;
            }
        }
    }

    public function delete_user() {
        activity_logs();
        $id = post('UAId', true);

        if (!empty($id)) {
            $flag = $this->Mdl_user->delete_user($id);

            $flag ? $response['success'] = true : $response['success'] = false;
        }

        $response['generated_token'] = $this->security->get_csrf_hash();

        echo json_encode($response);
    }

    public function autorights() {
        activity_logs();
        $users = $this->Mdl_user->get_users();
        $menuItem = $this->Mdl_user->get_menus();

        foreach ($users as $key) {
            $access_rights = [];

            foreach ($menuItem as $menu) {

                $hidden = explode(",", $menu->hidden_from); // explode menu exclusion list
                $access = explode(",", $menu->special_access); // explode menu special access list

                if (in_array($key->RoleId, $access) || ($key->RoleId <= $menu->security_level && ( $menu->hidden_from == null || !in_array($key->RoleId, $hidden) )) ) {
                    $user = array('RoleId' => $key->RoleId,
                        'UAId' => $key->UAId,
                        'MenuId' => $menu->MenuId,
                        '_view' => 1,
                        '_create' => 1,
                        '_update' => 1,
                        '_delete' => 1,
                        'deleted' => 0
                    );
                    $access_rights[] = $user;
                } else {
                    $user = array('RoleId' => $key->RoleId,
                        'UAId' => $key->UAId,
                        'MenuId' => $menu->MenuId,
                        '_view' => 0,
                        '_create' => 0,
                        '_update' => 0,
                        '_delete' => 0,
                        'deleted' => 0
                    );
                    $access_rights[] = $user;
                }
            }
            
            $flag = $this->Mdl_user->set_default_rights($access_rights);
        }

        header('location:'.base_url());
    }

    public function check_username2(){
        activity_logs();
        $response['generated_token'] = $this->security->get_csrf_hash();
        $username = $this->input->post('username');
        $user = $this->Mdl_user->check_username2($username)['success'];
        if($user){
            $response['success'] = true;
            $response['msg'] = 'Username is already exist!';
        }else{
            $response['success'] = false;
        }
        echo json_encode($response);
    }


    public function update_image(){
        activity_logs();
        $response['generated_token'] = $this->security->get_csrf_hash();
        $user_access_id = post('user-access-id', true);
        $path_parts = pathinfo($_FILES["file"]["name"]);
        $file_extension = $path_parts['extension'];

        //checking if images is exist in the server base on file name
        if (file_exists("assets/images/user_img/" . ($user_access_id.".".$file_extension))){
            unlink("assets/images/user_img/" . ($user_access_id.".".$file_extension));
        }

        $config['upload_path']          = "assets/images/user_img";
        $config['allowed_types']        = 'jpg|jpeg|png|JPG';
        $config['file_name']            = $user_access_id;

        $this->load->library('upload', $config);
        $this->load->library('image_lib');
        $this->upload->initialize($config);

        //this process is the copying the image from client side into server side
        if($this->upload->do_upload('file')){
            //resizing image
            $image_data =   $this->upload->data();
            $configer =  array(
              'image_library'   => 'gd2',
              'source_image'    =>  $image_data['full_path'],
              'maintain_ratio'  =>  FALSE,
              'width'           =>  256,
              'height'          =>  256,
            );
            $this->image_lib->clear();
            $this->image_lib->initialize($configer);
            $this->image_lib->resize();
            //end of resizing image

            //inserting the name of the image
            $insert_update = $this->Mdl_user->update_image($user_access_id, ($user_access_id.".".$file_extension));
            if($insert_update){
                $response['msg'] = "Image saved!";
                $response['img'] = user_base_url(($user_access_id.".".$file_extension));
                $response['success'] = true;
            }
        }else{
            $response['msg'] = $error = array('error' => $this->upload->display_errors());
            $response['success'] = false;
        }
        $response['generated_token'] = $this->security->get_csrf_hash();
        
        echo json_encode($response);
    }

    public function update_profile(){
        activity_logs();
        $response['generated_token'] = $this->security->get_csrf_hash();
        foreach ($_POST as $key => $value) {
            if(empty($value)){
                $response['msg'] = "Some field is empty!";
                $response['success'] = false;
                echo json_encode($response);
                exit();
            }
        }
        $where = post('edit-user-id', true);
        $formdata['fname'] = post('edit-first-name', false);
        $formdata['lname'] = post('edit-last-name', false);
        $formdata['RoleId'] = post('select-edit-role', true);

        $update = $this->Mdl_user->update_profile($formdata, $where)['success'];

        if($update){
            $response['msg'] = "Profile saved!";
            $response['success'] = true;
        }else{
            $response['msg'] = "Error while saving profile!";
            $response['success'] = false;
        }
        echo json_encode($response);
    }

    public function update_password(){
        activity_logs();
        foreach ($_POST as $key => $value) {
            if(empty($value)){
                $response['msg'] = "Some field is empty!";
                $response['success'] = false;
                echo json_encode($response);
                exit();
            }
        }
        $UAId = post('edit-user-id', true);
        $old_pass = $this->token->encrypt(post('edit-old-password', false));
        $new_pass = $this->token->encrypt(post('edit-new-password', false));

        $update = $this->Mdl_user->update_password($UAId ,$old_pass ,$new_pass)['success'];

        if($update){
            $response['msg'] = "Password saved!";
            $response['success'] = true;
        }else{
            $response['msg'] = "Invalid old password!";
            $response['success'] = false;
        }
        $response['generated_token'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }



    public function do_upload() {
        activity_logs();
        $response['success'] = false;
        $response['generated_token'] = $this->security->get_csrf_hash();

        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $lname = $this->input->post('lname');
        $fname = $this->input->post('fname');
        $role = post('role', true);

        if (!empty($username) && !empty($password) && !empty($lname) && !empty($fname) && !empty($role)) {
            $valid_form = true;
        } else {
            $valid_form = false;
        }

        if ($valid_form) { // only initiate if all parameters are valid

            $user['UserName'] = strtolower($username);
            $user['Password'] = $this->token->encrypt($password);
            $user['lname'] = strtolower($lname);
            $user['fname'] = strtolower($fname);
            $user['RoleId'] = $role;
            if(empty($_POST['file'])){
                $user['avatar'] = 'default.png';
            }

            $id = $this->Mdl_user->add_user($user, null);

            if (!empty($_FILES['file'])) { // user selected image
                $user_access_id = $id;
                $path_parts = pathinfo($_FILES["file"]["name"]);
                $file_extension = $path_parts['extension'];

                //checking if images is exist in the server base on file name
                if (file_exists("assets/images/user_img/" . ($user_access_id.".".$file_extension))){
                    unlink("assets/images/user_img/" . ($user_access_id.".".$file_extension));
                }

                $config['upload_path']          = "assets/images/user_img";
                $config['allowed_types']        = 'jpg|jpeg|png|JPG';
                $config['file_name']            = $user_access_id;

                $this->load->library('upload', $config);
                $this->load->library('image_lib');
                $this->upload->initialize($config);

                //this process is the copying the image from client side into server side
                if($this->upload->do_upload('file')){
                    //resizing image
                    $image_data =   $this->upload->data();
                    $configer =  array(
                      'image_library'   => 'gd2',
                      'source_image'    =>  $image_data['full_path'],
                      'maintain_ratio'  =>  FALSE,
                      'width'           =>  256,
                      'height'          =>  256,
                    );
                    $this->image_lib->clear();
                    $this->image_lib->initialize($configer);
                    $this->image_lib->resize();

                    $insert_update = $this->Mdl_user->update_image($user_access_id, ($user_access_id.".".$file_extension));
                    if($insert_update){
                        $response['avatar'] = user_base_url(($user_access_id.".".$file_extension));
                    }else{
                        $response['avatar'] = user_base_url('default.png');
                    }
                }
            }else{
                $response['avatar'] = user_base_url('default.png');
            }

            if (!empty($id)) {
                
                $check = $this->set_default_rights($id, $user['RoleId']);

                if ($check) {
                    $response['success'] = true;
                    $response['msg'] = 'New user added successfully!';
                }
            }
        }else{
            $response['success'] = false;
            $response['msg'] = 'Some fields are empty!';
        }

        echo json_encode($response);
    }

}
