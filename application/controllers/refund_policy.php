<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Refund_policy extends CI_Controller {

	protected $_view_data;
	protected $_settings;
	
	public function __construct(){
		parent::__construct();
		$this->_settings = $this->config->item('polycademy');
		$this->_view_data = $this->_settings;
	}
	
	public function index(){
	
		$rss_feeds = rss_process('http://pipes.yahoo.com/pipes/pipe.run?_id=24a7ee6208f281f8dff1162dbac57584&_render=rss');
		if($rss_feeds){
			$rss_feeds = array_slice($rss_feeds, 0, 4);
		}
		
		#$this->firephp->log(site_url());
		
		$this->_view_data += array(
			'page_title'			=> $this->_settings['site_name'],
			'page_desc'				=> $this->_settings['site_desc'],
			'feeds'					=> $rss_feeds,
		);
		
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('refund_policy_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		
	}
	
}