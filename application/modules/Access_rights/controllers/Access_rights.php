<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access_rights extends MX_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model('Mdl_access_right');
        $this->load->library('Interceptor');
        date_default_timezone_set("Asia/Manila");

        if(!$this->session->userdata('isLogin')) {
            header('location:'.base_url());
            die();
        }
        if (!$this->interceptor->getMenuAccessRight($this->session->RoleId,uri_string())) {
            header('location:'.base_url('Dashboard'));
            die();
        }
    }

    public function user() {

        $data['generated_token'] = $this->security->get_csrf_hash();
        $data['title'] = 'System | User Access Rights';

    	$this->load->view('User',$data);
    }

    public function role() {

        $data['generated_token'] = $this->security->get_csrf_hash();
        $data['role_list'] = $this->get_role_list();
        $data['title'] = 'System | Role Access Rights';

        $this->load->view('Role',$data);
    }

    public function get_role_list() {

        $html='';
        $roleLists = $this->Mdl_access_right->get_role_list();
        foreach ($roleLists as $roleList) {
            $html .= '<option RoleId="'.$roleList->RoleId.'">'.$roleList->Role.'</option>';
        }

        return $html;
    }

    public function menu() {

        $response['success'] = false;

        $UAId = $this->input->post('EmpId');

        $menu = '';
        $parentMenus = $this->Mdl_access_right->get_menu_parent($UAId);

        foreach ($parentMenus as $parentMenu) {
            $menu .='<tr data-id="'.$parentMenu->MenuId.'">
                        <td>
                            <div class="tt" data-tt-id="'. $parentMenu->MenuId .'" data-tt-parent=""><span class="margin-5 material-icons">'.$parentMenu->MenuIcon.'</span>'. $parentMenu->MenuName .'</div>
                        </td>
                        <td>'.$this->access_rights_icon($parentMenu->_view, $parentMenu->Id, '_view', $UAId, $parentMenu->isParent).'</td>
                        <td>'.$this->access_rights_icon($parentMenu->_create, $parentMenu->Id, '_create', $UAId, $parentMenu->isParent).'</td>
                        <td>'.$this->access_rights_icon($parentMenu->_update, $parentMenu->Id, '_update', $UAId, $parentMenu->isParent).'</td>
                        <td>'.$this->access_rights_icon($parentMenu->_delete, $parentMenu->Id, '_delete', $UAId, $parentMenu->isParent).'</td>
                    </tr>';

            if ($this->Mdl_access_right->hasChild($UAId, $parentMenu->MenuId)) {
                $childMenus = $this->Mdl_access_right->get_menu_child($UAId, $parentMenu->MenuId);
                $menu .= $this->append_menu_child($UAId, $childMenus, $parentMenu->MenuId);
            }
        }

        $response['data'] = $menu;
        $response['generated_token'] = $this->security->get_csrf_hash();
        $response['success'] = true;

        echo json_encode($response);
    }

    public function append_menu_child($UAId, $childMenus, $parentId) {

        $menu = '';
        foreach ($childMenus as $childMenu) {
            
            if ($this->Mdl_access_right->hasChild($UAId, $childMenu->MenuId)) {
                $menu .='<tr>
                            <td>
                                <div class="tt" data-tt-id="'. $childMenu->MenuId .'" data-tt-parent="'.$parentId.'"><span class="margin-5 material-icons">'. $childMenu->MenuIcon .'</span>'. $childMenu->MenuName .'</div>
                            </td>
                            <td>'.$this->access_rights_icon($childMenu->_view, $childMenu->Id, '_view', $UAId, $childMenu->isParent).'</td>
                            <td>'.$this->access_rights_icon($childMenu->_create, $childMenu->Id, '_create', $UAId, $childMenu->isParent).'</td>
                            <td>'.$this->access_rights_icon($childMenu->_update, $childMenu->Id, '_update', $UAId, $childMenu->isParent).'</td>
                            <td>'.$this->access_rights_icon($childMenu->_delete, $childMenu->Id, '_delete', $UAId, $childMenu->isParent).'</td>
                        </tr>';

                $parentId2 = $childMenu->MenuId;
                $childMenuslvl3 = $this->Mdl_access_right->get_menu_child($UAId, $childMenu->MenuId);
                $menu .= $this->append_menu_child($UAId, $childMenuslvl3, $parentId2);
            } else {
                $menu .='<tr>
                            <td>
                                <div class="tt" data-tt-id="'. $childMenu->MenuId .'" data-tt-parent="'.$parentId.'"><span class="margin-5 material-icons">'. $childMenu->MenuIcon .'</span>'. $childMenu->MenuName .'</div>
                            </td>
                            <td>'.$this->access_rights_icon($childMenu->_view, $childMenu->Id, '_view', $UAId, $childMenu->isParent).'</td>
                            <td>'.$this->access_rights_icon($childMenu->_create, $childMenu->Id, '_create', $UAId, $childMenu->isParent).'</td>
                            <td>'.$this->access_rights_icon($childMenu->_update, $childMenu->Id, '_update', $UAId, $childMenu->isParent).'</td>
                            <td>'.$this->access_rights_icon($childMenu->_delete, $childMenu->Id, '_delete', $UAId, $childMenu->isParent).'</td>
                        </tr>';
            }
            
        }

        return $menu;
    }

    public function access_rights_icon($status, $id, $interface, $UAId, $isParent) {

        if ($isParent && $interface != '_view') {
            $res = '<i class="material-icons fa-check fa-notAllowed">security</i> ';
        } else {
            $res = $status == 1 ? '<i data-rid="'. $id .'" data-menu-id="'. $id .'" data-infa="'. $interface .'" class="material-icons fa-check has-rights fa-trig col-light-blue">security</i> ' : '<i data-rid="'. $UAId .'" data-menu-id="'. $id .'" data-infa="'. $interface .'" class="material-icons fa-times no-rights fa-trig col-red">security</i>';
        }
        
        return $res;
    }

    public function stat_update() {

        $id = $this->input->post('res_id');
        $rid = $this->input->post('rid');
        $stat = ($this->input->post('status') != 1) ? '' : 1;
        $interface = $this->input->post('infa');
        $response['success'] = false;

        $res = $this->Mdl_access_right->stat_update($id, $stat, $interface, $rid);

        if ($res['success']) {
            $response['success'] = true;
            $response['generated_token'] = $this->security->get_csrf_hash();
        }

        echo json_encode($response);
    }

    //For Role 
    public function getRoleAccessRights() {

        $response['success'] = false;
        $RoleId = $this->input->post('RoleId');
        $menu = '';
        $parentMenus = $this->Mdl_access_right->role_get_menu_parent($RoleId, 0, 0);

        foreach ($parentMenus as $parentMenu) {

            $menu .='<tr data-id="'.$parentMenu->MenuId.'">
                        <td>
                            <div class="tt" data-tt-id="'. $parentMenu->MenuId .'" data-tt-parent=""><span class="margin-5 material-icons">'.$parentMenu->MenuIcon.'</span>'. $parentMenu->MenuName .'</div>
                        </td>
                        <td>'.$this->role_access_rights_icon($parentMenu->_view, $parentMenu->RoleId, '_view', $parentMenu->MenuId).'</td>
                        <td>'.$this->role_access_rights_icon($parentMenu->_create, $parentMenu->RoleId, '_create', $parentMenu->MenuId).'</td>
                        <td>'.$this->role_access_rights_icon($parentMenu->_update, $parentMenu->RoleId, '_update', $parentMenu->MenuId).'</td>
                        <td>'.$this->role_access_rights_icon($parentMenu->_delete, $parentMenu->RoleId, '_delete', $parentMenu->MenuId).'</td>
                    </tr>';

            if ($this->Mdl_access_right->hasChild('', $parentMenu->MenuId)) {
                $childMenus = $this->Mdl_access_right->role_get_menu_child($parentMenu->MenuId, $RoleId);
                $menu .= $this->role_append_menu_child($RoleId, $childMenus, $parentMenu->MenuId);
            }
        }

        $response['data'] = $menu;
        $response['success'] = true;
        $response['generated_token'] = $this->security->get_csrf_hash();

        echo json_encode($response);
    }

    public function role_append_menu_child($RoleId, $childMenus, $parentId) {

        $menu = '';
        foreach ($childMenus as $childMenu) {
            
            if ($this->Mdl_access_right->hasChild('', $childMenu->MenuId)) {
                $menu .='<tr>
                            <td>
                                <div class="tt" data-tt-id="'. $childMenu->MenuId .'" data-tt-parent="'.$parentId.'"><span class="margin-5 material-icons">'. $childMenu->MenuIcon .'</span>'. $childMenu->MenuName .'</div>
                            </td>
                            <td>'.$this->role_access_rights_icon($childMenu->_view, $RoleId, '_view' , $childMenu->MenuId).'</td>
                            <td>'.$this->role_access_rights_icon($childMenu->_create, $RoleId, '_create' , $childMenu->MenuId).'</td>
                            <td>'.$this->role_access_rights_icon($childMenu->_update, $RoleId, '_update' , $childMenu->MenuId).'</td>
                            <td>'.$this->role_access_rights_icon($childMenu->_delete, $RoleId, '_delete' , $childMenu->MenuId).'</td>
                        </tr>';

                $parentId2 = $childMenu->MenuId;
                $childMenuslvl3 = $this->Mdl_access_right->role_get_menu_child($parentId2, $RoleId);
                // echo "<pre>";
                // print_r($childMenuslvl3); exit();
                $menu .= $this->role_append_menu_child($RoleId, $childMenuslvl3, $parentId2);
            } else {
                $menu .='<tr>
                            <td>
                                <div class="tt" data-tt-id="'. $childMenu->MenuId .'" data-tt-parent="'.$parentId.'"><span class="margin-5 material-icons">'. $childMenu->MenuIcon .'</span>'. $childMenu->MenuName .'</div>
                            </td>
                            <td>'.$this->role_access_rights_icon($childMenu->_view,   $RoleId, '_view' , $childMenu->MenuId).'</td>
                            <td>'.$this->role_access_rights_icon($childMenu->_create, $RoleId, '_create' , $childMenu->MenuId).'</td>
                            <td>'.$this->role_access_rights_icon($childMenu->_update, $RoleId, '_update' , $childMenu->MenuId).'</td>
                            <td>'.$this->role_access_rights_icon($childMenu->_delete, $RoleId, '_delete' , $childMenu->MenuId).'</td>
                        </tr>';                
            }
        }

        return $menu;
    }

    public function role_access_rights_icon($status, $id, $interface, $menuId) {

        $res = $status == 1 ? '<i data-rid="'. $id .'" data-menu-id="'. $menuId .'" data-infa="'. $interface .'" class="material-icons fa-check has-rights fa-trig">security</i> ' : '<i data-rid="'. $id .'" data-menu-id="'. $menuId .'" data-infa="'. $interface .'" class="material-icons fa-times no-rights fa-trig">security</i>';

        return $res;
    }

    public function ar_update() {

        $rid = $this->input->post('rid');
        $stat = $this->input->post('status');
        $interface = $this->input->post('infa');
        $menuId = $this->input->post('menuId');
        $response['success'] = false;
        $response['generated_token'] = $this->security->get_csrf_hash();

        $res = $this->Mdl_access_right->ar_update($stat, $interface, $rid, $menuId);

        if ($res['success']) {
            $response['success'] = true;
        }

        echo json_encode($response);
    }

    public function employee_list() {

        $datas = $this->Mdl_access_right->user_list_table();

        $data = array();
        $LNo = 1;
        foreach ($datas as $user) {

            $row = array();
            $row[] = $user->UAId;
            $row[] = ucwords($user->lname);
            $row[] = ucwords($user->fname);
            $row[] = '<button type="button" id="'. $user->UAId .'" class="btn btn-primary btn-xs waves-effect accessRightOpen">
                                <i class="material-icons">search</i>
                            </button>';
            $data[] = $row;
            $LNo++;
        }

        $output = array(  
            'draw'              => intval($this->input->post('draw')),  
            'recordsTotal'      => $this->Mdl_access_right->get_all_data(),  
            'recordsFiltered'   => $this->Mdl_access_right->get_filtered_data(),  
            'data'              => $data,
            'generated_token'   => $this->security->get_csrf_hash()
        );

       echo json_encode($output);
    }
}