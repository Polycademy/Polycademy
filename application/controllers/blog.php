<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

	protected $_view_data; //only accessible in this class and any classes that extend home
	protected $_settings;
	protected $_user;
	
	public function __construct(){
		parent::__construct();
		$this->_settings = $this->config->item('polycademy');		
		$this->load->helper('form');
		$this->load->library('form_validation');
		#language files require MY_ or differentiator or else they overwrite the system ones.
		$this->lang->load('MY_form_validation_lang');
		$this->_user = $this->ion_auth->user()->row(); #get the current user row
	}
	
	public function index(){
	
		$rss_feeds = rss_process('http://pipes.yahoo.com/pipes/pipe.run?_id=24a7ee6208f281f8dff1162dbac57584&_render=rss');
		if($rss_feeds){
			$rss_feeds = array_slice($rss_feeds, 0, 4);
		}
		
		$this->_view_data = $this->_settings;
		$this->_view_data += array(
			'page_title'			=> 'Blog',
			'page_desc'				=> $this->_settings['site_desc'],
			'feeds'					=> $rss_feeds,
		);
		
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('blog_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		
	}
	
	public function notices(){
	
		$rss_feeds = rss_process('http://pipes.yahoo.com/pipes/pipe.run?_id=24a7ee6208f281f8dff1162dbac57584&_render=rss');
		if($rss_feeds){
			$rss_feeds = array_slice($rss_feeds, 0, 4);
		}
		
		$this->_view_data = $this->_settings;
		$this->_view_data += array(
			'page_title'			=> 'Blog',
			'page_desc'				=> $this->_settings['site_desc'],
			'feeds'					=> $rss_feeds,
		);
		
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('blog_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		
	}
	
	public function create(){
	
		if(!$this->ion_auth->logged_in()){
		
			redirect('auth/login');
			
		}elseif($this->ion_auth->is_admin()){
		
			$rss_feeds = rss_process('http://pipes.yahoo.com/pipes/pipe.run?_id=24a7ee6208f281f8dff1162dbac57584&_render=rss');
			if($rss_feeds){
				$rss_feeds = array_slice($rss_feeds, 0, 4);
			}
			
			$this->_view_data = $this->_settings;
			$this->_view_data += array(
				'page_title'			=> 'Create Blog',
				'page_desc'				=> $this->_settings['site_desc'],
				'feeds'					=> $rss_feeds,
				'form_destination'		=> $this->router->fetch_class() . '/' . $this->router->fetch_method(),
			);
			
			$this->form_validation->set_rules($this->_settings['form_validation']['blog_create']);
			if($this->form_validation->run() == true){
				#all input post data will get xss_cleaned from the form_validation library
				#this means the form has been ran and suceeded through validation!
				$this->_form_success();
			}else{
				$this->_form_failure();
			}
			
			#$this->firephp->log($this->_user);
			
			$this->load->view('header_view', $this->_view_data);
			$this->load->view('blog_create_view', $this->_view_data);
			$this->load->view('footer_view', $this->_view_data);
			
		}else{
			
			redirect('home');
			
		}
		
	}
	
	#should be moved to models
	protected function _form_success(){
		
		$data = array(
			'date'					=> date('Y-m-d H:i:s', now()),
			'title'					=> $this->input->post('title'),
			'tags'					=> $this->input->post('tags'),
			'author'				=> $this->_user->id,
			'content'				=> $this->input->post('content'),
		);
		
		#$this->firephp->log($data);
		
		$query = $this->db->insert('blog', $data); 
		
		if($query){
			
			#all of these messages need to be moved to the view template, and the controller should be only passing status codes/messages!
			$this->_view_data += array(
				'success_message'	=> 'It\'s done!',
			);
			
			return true;
			
		}else{
		
			$this->_view_data += array(
				'error_messages'	=> '<li>We have experienced a database error in submitting your application, please try again later or contact us directly.</li>',
			);
			
			return false;
			
		}
		
	}
	
	protected function _form_failure(){
		$this->_view_data += array(
			'error_messages'	=> validation_errors('<li>', '</li>'),
		);
	}
	
	#for the template
	public function preview(){
	
		$this->_view_data = $this->_settings;
		$this->load->view('blog_preview_view', $this->_view_data);
		
	}

}