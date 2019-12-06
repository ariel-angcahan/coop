<?php //if( ! defined('BASEPATH') ) exit('NO direct script access allowed');
require_once APPPATH.'libraries/TCPDF/tcpdf.php';

class Pdf_Library extends TCPDF{

	
	public function __construct()
	{
		parent::__construct();
	}
}