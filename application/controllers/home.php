<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	protected $_view_data; //only accessible in this class and any classes that extend home
	protected $_settings;
	
	public function __construct(){
		parent::__construct();
		$this->_settings = $this->config->item('polycademy');
		#construct for Home class set some default values, or run a default process when your class is instantiated
		#this stuff is executed regardless of what page is loaded, good for when index()is not launched
	}
	
	public function index(){
	
		$this->_view_data = $this->_settings;
		$this->_view_data += array(
			'page_title'			=> $this->_settings['site_name'],
			'page_desc'				=> $this->_settings['site_desc'],
		);
		
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('home_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		
	}
}