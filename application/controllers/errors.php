<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends CI_Controller {

	protected $_view_data; //only accessible in this class and any classes that extend home
	protected $_settings;
	protected $_footer_blog = false;
	protected $_rss_feeds = false;
	
	public function __construct(){
		parent::__construct();
		$this->_settings = $this->config->item('polycademy');
		
		if($this->_rss_feeds = rss_process('http://pipes.yahoo.com/pipes/pipe.run?_id=24a7ee6208f281f8dff1162dbac57584&_render=rss')){
			$this->_rss_feeds = array_slice($this->_rss_feeds, 0, 4);
		}
		if($this->_footer_blogs = $this->Footer_model->footer_get_blog()){
			foreach($this->_footer_blogs as $num => $row){
				$this->_footer_blogs[$num]['date'] = (string) mdate('%Y/%m/%d', strtotime($row['date']));
			}
		}
		
		$this->_view_data = $this->_settings;
		$this->_view_data += array(
			'feeds'					=> $this->_rss_feeds,
			'footer_blog_data'		=> $this->_footer_blogs,
		);
		
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
			'error_title'			=> 'Error 404',
			'error_message'			=> 'This is an error page! Since you haven\'t received an error, then you should check out the other links!',
		);
	
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('errors_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		$this->output->set_status_header('404');
	
	}
	
}