<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Get_involved extends CI_Controller {

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
	
	public function index(){
		
		$this->_view_data += array(
			'page_title'			=> 'Get Involved',
		);
		
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('get_involved_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		
	}
	
}