<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Footer_model extends CI_Model{

	protected $_settings;
	protected $_user;
	protected $_tags = array();
	protected $_limit;
	protected $_offset;
	
	public function __construct(){
	
		parent::__construct();
		$this->_settings = $this->config->item('polycademy');
		
	}
	
	public function footer_get_blog($tags = array()){
	
		$this->_limit = $this->_settings['pagination']['footer_blog']['limit'];
		$this->_offset = $this->_settings['pagination']['footer_blog']['offset'];
		
		$this->db->select('*');
		$this->db->limit($this->_limit, $this->_offset);
		$this->db->order_by('date', 'desc');
		$this->db->from('blog');
		
		if(!empty($tags)){
			$this->_tags = $tags;
			foreach($this->_tags as $tag){
				$this->db->or_like('tags', $tag);
			}
		}
		
		$blog_query = $this->db->get();
		
		if($blog_query->num_rows() > 0){
		
			foreach($blog_query->result() as $row){
				
				$blog_data[] = array(
					'id'		=> (int) $row->id,
					'title'		=> word_limiter($row->title, 12),
					'content'	=> $row->content,
					'date'		=> $row->date,
					'tags'		=> $row->tags,
					'link'		=> $row->link,
				);
				
			}
			
		}else{
		
			$blog_data = false;
		
		}
		
		return $blog_data;
		
	}

}
