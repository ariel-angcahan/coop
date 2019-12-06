<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Interceptor {

	private $menus;

	public function __construct() {
		$this->ci =& get_instance();
		$this->ci->load->library('session');
		if (empty($this->menus)) {
			$this->menus = $this->getMenuAccessLevel();
		}
	}

	function getMenuAccessLevel() {

		$this->ci->db->select('MenuId,MenuName,TargetUrl,security_level,hidden_from,special_access');
		$this->ci->db->from('ci_menu');
		return $this->ci->db->get()->result();
	}

	function getMenuAccessRight($roleId, $uri) {

		$status = false;

		foreach ($this->menus as $menu) {
			
            $hidden = explode(",", $menu->hidden_from); // explode menu exclusion list
            $access = explode(",", $menu->special_access); // explode menu special access list
            
			//~ uri should follow target url format AND user should not be in excluded roles
            if (strpos($uri,$menu->TargetUrl) > -1 && ($menu->hidden_from == null || !in_array($roleId, $hidden)) ) {

            	if ($roleId <= $menu->security_level) {
            		$status = true;
            	} else if (in_array($roleId, $access)) {
            		$status = true;
            	} else {
            		$this->ci->db->select('_view');
            		$this->ci->db->from('ci_access_rights');
            		$this->ci->db->where('UAId', $this->ci->session->UAId);
            		$this->ci->db->where('MenuId', $menu->MenuId);

            		$_view = $this->ci->db->get()->result()[0]->_view;

            		if ($_view) $status = true;
            	}
            	break;
            }
        }

        return $status;
    }

}