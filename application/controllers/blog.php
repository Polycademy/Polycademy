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
		
		#settings
		$this->_settings = $this->config->item('polycademy');
		
		#helpers
		$this->load->helper('form');
		$this->load->helper('pagination_helper');
		#libraries
		$this->load->library('form_validation');
		$this->load->library('pagination');
		#lang
		$this->lang->load('MY_form_validation_lang');
		#user
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
	
	public function index($offset = false){
	
		if(is_numeric($offset) AND $offset > 1){
			$this->_offset = $offset;
		}
		
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
				
				#find the author's name by cross referencing the author
				$this->db->select('username')->from('users')->where('id', $row->author);
				$author_query = $this->db->get();
				
				if($author_query->num_rows() > 0){
				
					$author = $author_query->row()->username;
					
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
					'link'		=> $row->link,
				);
				
			}
			
			$pagination_links = create_pagination(
				site_url('blog'),
				$this->db->count_all('blog'),
				$this->_limit,
				array(
					'full_tag_div'	=> array(
						'class'		=> 'pagination pagination-centered pagination-large',
					),
					'cur_tag'		=> array(
						'class'		=> 'active',
					),
				)
			);
			
		}else{
		
			#no blog posts
			$blog_data = false;
			$pagination_links =  false;
		
		}
		
		$this->_view_data += array(
			'page_title'			=> 'Blog',
			'blog_data'				=> $blog_data,
			'pagination_links'		=> $pagination_links,
		);
		
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('blog_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		
	}
	
	public function id($id = false, $url_title = false){
	
		if(!is_numeric($id)){
		
			show_404();
			
		}else{
		
			$this->db->select('blog.*, users.username');
			$this->db->where('blog.id', $id);
			$this->db->from('blog');
			$this->db->join('users', 'users.id = blog.author');
			
			$blog_query = $this->db->get();
			
			if($blog_query->num_rows() > 0){
			
				$blog_query = $blog_query->row();
				
				#if the link doesn't match the url_title, then redirect to the link
				if($blog_query->link != $url_title){
					redirect('blog/id/' . $id . '/' . $blog_query->link, 'location', 301);
				}
				
				#produce the blog array it is still an array because we are using the same view
				$blog_data[] = array(
					'id'		=> (int) $blog_query->id,
					'title'		=> $blog_query->title,
					'content'	=> $blog_query->content,
					'date'		=> $blog_query->date,
					'tags'		=> $blog_query->tags,
					'author'	=> $blog_query->username,
					'link'		=> $blog_query->link,
				);
				
				#NEXT BLOG ARTICLE
				$this->db->select('id, link');
				$this->db->from('blog');
				$this->db->where('date >', $blog_query->date);
				$this->db->order_by('date', 'asc');
				$this->db->limit(1);
				
				$next_blog_query = $this->db->get();
				
				if($next_blog_query->num_rows() > 0){
				
					$next_blog = $next_blog_query->row();
					$pager['next_link'] = site_url('blog/id/' . $next_blog->id . '/' . $next_blog->link);
				
				}
				
				#PREVIOUS BLOG ARTICLE
				$this->db->select('id, link');
				$this->db->from('blog');
				$this->db->where('date <', $blog_query->date);
				$this->db->order_by('date', 'desc');
				$this->db->limit(1);
				
				$prev_blog_query = $this->db->get();
				
				if($prev_blog_query->num_rows() > 0){
				
					$prev_blog = $prev_blog_query->row();
					$pager['prev_link'] = site_url('blog/id/' . $prev_blog->id . '/' . $prev_blog->link);
				
				}
				
			}else{
			
				show_404();
				
			}
			
		}
		
		$this->_view_data += array(
			'page_title'			=> $blog_data[0]['title'],
			'blog_data'				=> $blog_data,
			'pager'					=> $pager,
			'single_page'			=> true,
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
			'link'					=> url_title($this->input->post('title'), '_', true),
		);
		
		#$this->firephp->log($data);
		
		$query = $this->db->insert('blog', $data); 
		
		if($query){
			
			#all of these messages need to be moved to the view template, and the controller should be only passing status codes/messages!
			$this->_view_data += array(
				'success_message'	=> 'It\'s done! Check it out <a href="' . site_url('blog/id/' . $this->db->insert_id() . '/' . url_title($this->input->post('title'), '_', true)) . '">here!</a>',
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
	
	#for the template ajax
	public function preview(){
	
		$this->_view_data = $this->_settings;
		$this->load->view('blog_preview_view', $this->_view_data);
		
	}

}