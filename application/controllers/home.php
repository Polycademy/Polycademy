<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	protected $_view_data; //only accessible in this class and any classes that extend home
	protected $_settings;
	
	public function __construct(){
		parent::__construct();
		$this->_settings = $this->config->item('polycademy');
		$this->_view_data = $this->_settings;
		#construct for Home class set some default values, or run a default process when your class is instantiated
		#this stuff is executed regardless of what page is loaded, good for when index()is not launched
	}
	
	public function index(){
	
		$rss_feeds = rss_process('http://pipes.yahoo.com/pipes/pipe.run?_id=24a7ee6208f281f8dff1162dbac57584&_render=rss');
		if($rss_feeds){
			$rss_feeds = array_slice($rss_feeds, 0, 4);
		}
		
		#$this->firephp->log($this->ion_auth->is_admin(), 'Yes I am an admin');
		#$this->firephp->log($this->ion_auth->logged_in(), 'Yes I am logged in');
		#$this->firephp->log($this->ion_auth->in_group('admin'), 'Yes I am part of the admin group');
		#$this->firephp->log($this->ion_auth->in_group('staff'), 'Yes I am also part of the members group');
		/*
		if($this->ion_auth->in_group('guests')){
			$this->firephp->log('yes I am part of staff');
		}else{
			$this->firephp->log('I am not part of the guests');
		}
		*/
		
		$this->_view_data += array(
			'page_title'			=> $this->_settings['site_name'],
			'page_desc'				=> $this->_settings['site_desc'],
			'feeds'					=> $rss_feeds,
		);
		
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('home_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		
	}
	
}