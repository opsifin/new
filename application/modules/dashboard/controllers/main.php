<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
        
    function __construct() {
        parent::__construct();
        $username = $this->session->userdata('username');
        if (empty($username)){
            redirect(site_url('main/index'), 'refresh');
        };
        $this->load->model('user_mdl');
        // create content page fo dp supplier
        $this->twiggy->title('OPSIFIN')->prepend('Login');;
        $this->twiggy->meta('keywords', 'twiggy, twig, template, layout, codeigniter');
        $this->twiggy->meta('description', 'Twiggy is an implementation of Twig template engine for CI');
        $this->twiggy->set('BREADCRUMBS_TITLE', 'Dashboard');
    }
    
    function index()
    {   
        $this->load->library('menu');
        $menu = $this->menu->set_menu();
		
        $this->twiggy->set('menu_navigasi', $menu);      
        
        $data = array();
        $content = $this->twiggy->template('breadcrumbs')->render();
        $content .= $this->twiggy->template('dashboard_layout')->render();                
        $this->twiggy->set('content_page', $content);
        
        $output = $this->twiggy->template('dashboard')->render();
        $this->output->set_output($output);
    }
}    