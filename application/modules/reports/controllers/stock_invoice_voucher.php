<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class stock_invoice_voucher extends CI_Controller {	
	public function __construct()
	{
		parent::__construct();
	//	$this->load->model('Authentication');
	}
	
	public function home(){
		

		$log = $this->session->all_userdata();
		$userLogged = $this->session->userdata('userLogged');
		
		if ($userLogged) {
			$content = array (
				"log" => $log,
				"base_url" => base_url(),
			);
			$this->twig->display("stock_invoice_voucher", $content);
		}
	}
}