<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	protected $_view_data; //only accessible in this class and any classes that extend home
	
	public function __construct(){
		parent::__construct();
		#construct for Home class set some default values, or run a default process when your class is instantiated
		#this stuff is executed regardless of what page is loaded, good for when index()is not launched
	}
	
	public function index(){
	
		/*
		$this->_view_data = array(
			'page'				=> 'index',
			'page_view'			=> 'index_body_view',
			'page_view_data'	=> array(
				'topic_posts'				=> $topic_posts,
				'pagination'				=> $pagination,
				'topic_error_message'		=> $topic_error_message,
			),
			'footer_poll'				=> $footer_poll,
			'footer_poll_error_message'	=> $footer_poll_error_message,
		);
		
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('body_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		*/
	}
}