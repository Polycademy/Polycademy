<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

	protected $_view_data; //only accessible in this class and any classes that extend home
	protected $_settings;
	protected $_user;
	protected $_tags = array();
	protected $_limit;
	protected $_offset;
	
	protected $_footer_blog = false;
	protected $_rss_feeds = false;
	
	public function __construct(){
		parent::__construct();
		$this->_settings = $this->config->item('polycademy');
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		#language files require MY_ or differentiator or else they overwrite the system ones.
		$this->lang->load('MY_form_validation_lang');
		$this->_user = $this->ion_auth->user()->row(); #get the current user row
		
		$this->_limit = $this->_settings['pagination']['blog']['limit'];
		$this->_offset = $this->_settings['pagination']['blog']['offset']; #initial offset is zero, but later calculated from the limit
		
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
		
		$this->db->select('*');
		$this->db->limit($this->_limit, $this->_offset);
		$this->db->order_by('date', 'desc');
		$this->db->from('blog');
		if(!empty($this->_tags)){
			foreach($this->_tags as $tag){
				#we need to use like to search for the tags because multiple blog posts have different tags
				#should be or like as it is not cascading, but all posts that contain any of the tags...
				$this->db->or_like('tags', $tag);
			}
		}
		
		$blog_query = $this->db->get();
		
		if($blog_query->num_rows() > 0){
		
			foreach($blog_query->result() as $row){
			
				#$this->firephp->log($row); #$row is an object
				
				#find the author's name by cross referencing the author
				$this->db->select('username')->from('users')->where('id', $row->author);
				$author_query = $this->db->get();
				
				if($author_query->num_rows() > 0){
				
					$author = $author_query->row()->username;
					
					#$this->firephp->log($author);
					
				}else{
				
					$author = false;
				
				}
				
				#produce the blog array
				$blog_data[] = array(
					'id'		=> (int) $row->id,
					'title'		=> $row->title,
					'content'	=> $row->content,
					'date'		=> $row->date,
					'tags'		=> $row->tags,
					'author'	=> $author,
				);
				
				#$this->firephp->log($blog_data);
				
			}
			
		}else{
		
			#no blog posts
			$blog_data = false;
		
		}
		
		$this->_view_data += array(
			'page_title'			=> 'Blog',
			'page_desc'				=> $this->_settings['site_desc'],
			'blog_data'				=> $blog_data,
		);
		
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('blog_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		
	}
	
	public function notices(){
	
		$this->_tags[] = 'notices';
		$this->index();
		
	}
	
	public function create(){
	
		if(!$this->ion_auth->logged_in()){
		
			redirect('auth/login');
			
		}elseif($this->ion_auth->is_admin()){
			
			$this->_view_data += array(
				'page_title'			=> 'Create Blog',
				'page_desc'				=> $this->_settings['site_desc'],
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
	
	#should go into a regex_helper..., doesn't work well with nested stuff...
	#will require a full parser one day...
	protected function _code_parsing($data){
	
		#but don't escape already escaped stuff
		function escape_html($data){
			return $data[1] . htmlspecialchars($data[2], ENT_COMPAT|ENT_HTML5, 'UTF-8', false) . $data[3];
		}
		
		#$this->firephp->log($data);

		
		$data = preg_replace_callback('/(<code[^>]*>)([\s\S]*?)(<\/code>)/m', "escape_html", $data);
		
		#$this->firephp->log($data);
		
		
		return $data;
		
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