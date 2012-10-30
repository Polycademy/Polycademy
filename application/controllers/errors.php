<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Errors extends CI_Controller {

	protected $_view_data; //only accessible in this class and any classes that extend home
	protected $_settings;
	protected $_rss_feeds;
	
	public function __construct(){
		parent::__construct();
		$this->_settings = $this->config->item('polycademy');
		$this->_view_data = $this->_settings;
		
		$this->_rss_feeds = rss_process('http://pipes.yahoo.com/pipes/pipe.run?_id=24a7ee6208f281f8dff1162dbac57584&_render=rss');
		if($this->_rss_feeds){
			$this->_rss_feeds = array_slice($this->_rss_feeds, 0, 4);
		}
		
	}
	
	/* Remapped from the routes.php (need to expand to include other errors) */
	#error_general -> index
	#error_404
	#error_php
	#error_db
	public function _remap($method){
		if($method == 'error_404'){
			$this->$method();
		}else{
			$this->index();
		}
	}
	
	public function index(){
	
		$this->_view_data += array(
			'page_title'			=> 'Error!',
			'page_desc'				=> $this->_settings['site_name'],
			'feeds'					=> $this->_rss_feeds,
			'error_title'			=> 'Error Page',
			'error_message'			=> 'This is an error page! Since you haven\'t received an error, then you should check out the other links!',
		);
		
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('errors_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		
	}
	
	public function error_404(){
	
		$this->_view_data += array(
			'page_title'			=> 'Error 404',
			'page_desc'				=> $this->_settings['site_name'],
			'feeds'					=> $this->_rss_feeds,
			'error_title'			=> 'Error 404',
			'error_message'			=> 'This is an error page! Since you haven\'t received an error, then you should check out the other links!',
		);
	
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('errors_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		$this->output->set_status_header('404');
	
	}
	
}