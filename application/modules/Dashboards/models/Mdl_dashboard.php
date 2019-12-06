<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class mdl_dashboard extends CI_Model{
	
	function __construct() {
		parent::__construct();
		$this->load->database();
	}
}	