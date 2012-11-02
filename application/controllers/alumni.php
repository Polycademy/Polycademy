<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Alumni extends CI_Controller {

	protected $_view_data; //only accessible in this class and any classes that extend home
	protected $_settings;
	
	public function __construct(){
		parent::__construct();
		$this->_settings = $this->config->item('polycademy');
		$this->load->library('firephp');
		#construct for Home class set some default values, or run a default process when your class is instantiated
		#this stuff is executed regardless of what page is loaded, good for when index()is not launched
	}
	
	public function index(){
	
		$rss_feeds = rss_process('http://pipes.yahoo.com/pipes/pipe.run?_id=24a7ee6208f281f8dff1162dbac57584&_render=rss');
		if($rss_feeds){
			$rss_feeds = array_slice($rss_feeds, 0, 4);
		}
		
		$this->_view_data = $this->_settings;
		$this->_view_data += array(
			'page_title'			=> 'Alumni',
			'page_desc'				=> $this->_settings['site_desc'],
			'feeds'					=> $rss_feeds,
		);
		
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('alumni_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		
	}
	
}