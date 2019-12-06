<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utilities extends MX_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Utility');
		date_default_timezone_set("Asia/Manila");
    }


    public function menu()
	{
		$role_id = $this->session->RoleId;
		$UAId = $this->session->UAId;
		$parentMenus = $this->Utility->get_menu_parent($role_id, $UAId);
		
		$menu = "<ul class='list'>";
		$menu .=	"<li class='header menu-header'>MAIN NAVIGATION</li>";
				$x=1; 
				foreach($parentMenus as $parentMenu){
					
					if($x==1){ 
						$menu .= '<li '. $this->setActiveMenu(base_url($parentMenu->TargetUrl), $parentMenu->MenuId) .'>';
						$x++;
					}else{
						$menu .= '<li '. $this->setActiveMenu(base_url($parentMenu->TargetUrl), $parentMenu->MenuId) .'>';
						
					}
							if($this->Utility->hasChild($role_id, $parentMenu->MenuId, $UAId))
							{

								$menu .= '<a href="'. $parentMenu->TargetUrl .'" class="menu-toggle toggled smallpads">';
								$menu .=  '<i class="material-icons">'. $parentMenu->MenuIcon .'</i>';
								$menu .= '<span>'. $parentMenu->MenuName.'</span>';
							}else{
								$menu .= '<a href="'. base_url($parentMenu->TargetUrl) .'" class="smallpads">';
								$menu .=  '<i class="material-icons">'. $parentMenu->MenuIcon .'</i>';
								$menu .= '<span>'. $parentMenu->MenuName .'</span>';
							}
				    $menu .=  '</a>';
				    		
				    		if($this->Utility->hasChild($role_id, $parentMenu->MenuId, $UAId))
				    		{
								
				    			$menu .= '<ul class="ml-menu">';
				    			$childMenus = $this->Utility->get_menu_child($role_id, $parentMenu->MenuId, $UAId);
				    			$menu .= $this->append_menu_child($role_id, $childMenus, $UAId);
				    			$menu .= '</ul>';
				    		}
				    		
								
					$menu .= '</li>';
					
				}

		$menu .='</ul>';
		echo $menu;
	}

	public function append_menu_child($role_id, $childMenus, $UAId)
	{
		$menu = '';
		foreach($childMenus as $childMenu){
			
			
			$menu .= '<li '. $this->setActiveMenu(base_url($childMenu->TargetUrl), $childMenu->MenuId) .'>';
			
						if($this->Utility->hasChild($role_id, $childMenu->MenuId, $UAId))
						{
							$menu .= '<a href="'. $childMenu->TargetUrl .'" class="menu-toggle toggled">';
							$menu .=  '<i class="material-icons">'. $childMenu->MenuIcon .'</i>'; 
							$menu .= '<span>'. $childMenu->MenuName .'</span>';
						}else{
							$menu .= '<a href="'. base_url($childMenu->TargetUrl) .'">';
							$menu .=  '<i class="material-icons">'. $childMenu->MenuIcon .'</i>';
							$menu .= '<span>'. $childMenu->MenuName .'</span>';
						}
			$menu .=	'</a>';
						if($this->Utility->hasChild($role_id, $childMenu->MenuId, $UAId))
			    		{
			    			$menu .= '<ul class="ml-menu">';
			    			$childMenuslvl3 = $this->Utility->get_menu_child($role_id, $childMenu->MenuId, $UAId);
			    			$menu .= $this->append_menu_child2($role_id, $childMenuslvl3, $UAId);
			    			$menu .= '</ul>';
			    		}
			$menu .= '</li>';
			
		}
		return $menu;
	}

	public function append_menu_child2($role_id, $childMenus, $UAId)
	{
		$menu = '';
		foreach($childMenus as $childMenu){
			
			$menu .= '<li '. $this->setActiveMenu(base_url($childMenu->TargetUrl), $childMenu->MenuId) .'>';
			
			if($this->Utility->hasChild($role_id, $childMenu->MenuId, $UAId))
			{
				$menu .= '<a href="'. base_url($childMenu->TargetUrl) .'" class="menu-toggle toggled">';
				$menu .=  '<i class="material-icons">'. $childMenu->MenuIcon .'</i>';
				$menu .= '<span>'. $childMenu->MenuName .'</span>';
			}else{
				$menu .= '<a href="'. base_url($childMenu->TargetUrl) .'">';
				$menu .=  '<i class="material-icons">'. $childMenu->MenuIcon .'</i>';
				$menu .= '<span>'. $childMenu->MenuName .'</span>';
			}

			$menu .=	'</a>';
						if($this->Utility->hasChild($role_id, $childMenu->MenuId, $UAId))
			    		{
			    			$childMenuslvl3 = $this->Utility->get_menu_child($role_id, $childMenu->MenuId, $UAId);
			    			$menu .= $this->append_menu_child($role_id, $childMenuslvl3, $UAId);
			    		}
			$menu .= '</li>';
			
		}
		return $menu;
	}

	public function setActiveMenu($url, $menuId)
	{
		if( $url == base_url(uri_string()) )
		{
			$this->session->set_userdata('SmenuId', $menuId);
			return 'class="ayieee-active"';
			// return 'class="active"';
		}
	}

	public function getAccessID()
	{
		$responce['access_id']	= (!$this->session->userdata('isLogin')) ? null : $this->session->EmpId;
		$responce['generated_token'] = $this->token->generate_token();
        echo json_encode($responce);
	}

	public function getNotifications()
	{
		$notif = modules::load('Notifications/Notifications/');
		$remain = $this->input->post('remain');
		echo json_encode($notif->getNotifications(true, true, $remain));
	}

	public function setStatus()
	{
		$notif = modules::load('Notifications/Notifications/');

		$notif_to = $this->session->EmpId;
		$ref_id = $this->input->post('ref_id'); 
		$ref_type = $this->input->post('ref_type'); 

		$notif->updateStatus($notif_to, $ref_id, $ref_type); 
		$responce['generated_token'] = $this->token->generate_token();
        echo json_encode($responce);
	}



    public function notification_list(){

        $datas = $this->Utility->notification_list();

        $html = "";
        foreach ($datas as $data) {
            $posted_date = new DateTime();
            $posted_date = $posted_date->diff(new DateTime($data->date));
            $years = ($posted_date->format('%y') != 0) ? $posted_date->format('%y years').', ' : ' ';
            $months = ($posted_date->format('%m') != 0) ? $posted_date->format('%m months').', ' : ' ';
            $days = ($posted_date->format('%d') != 0) ? $posted_date->format('%d days').', ' : ' ';
            $hours = ($posted_date->format('%h') != 0) ? $posted_date->format('%h hours').', ' : ' ';
            $minutes = ($posted_date->format('%i') != 0) ? $posted_date->format('%i minutes').', ' : ' ';
            $seconds = ($posted_date->format('%s') != 0) ? $posted_date->format('%s seconds') : ' ';
            $posted_date = $years.$months.$days.$hours.$minutes.$seconds;

            $html .='<li>
                        <a href="'.$data->url.'" class="waves-effect waves-block">
                            <div class="icon-circle bg-light-green">
                                <i class="material-icons">person_add</i>
                            </div>
                            <div class="menu-info">
                                <h4>'.$data->header_title.'</h4>
                                <p>
                                    <i class="material-icons">access_time</i>'.$posted_date.'
                                </p>
                            </div>
                        </a>
                    </li>';
        }

        $response['data'] = $html;
        $response['count'] = count($datas);
        $response['generated_token'] = $this->security->get_csrf_hash();

        echo json_encode($response);
    }
}