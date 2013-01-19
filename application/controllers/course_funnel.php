<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Course_funnel extends CI_Controller {

	protected $_view_data;
	protected $_settings;
	protected $_footer_blog = false;
	protected $_rss_feeds = false;
	protected $_offset = 0;
	protected $_limit = 50;
	protected $_filters;
	
	public function __construct(){
		
		parent::__construct();
		
		$this->_settings = $this->config->item('polycademy');
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->lang->load('MY_form_validation_lang');
		
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
		
		if(!$this->ion_auth->logged_in() OR !$this->ion_auth->is_admin()){
			redirect('auth/login');
		}
	
	}
	
	public function index(){
	
		$this->db->select('id, app_date, name, email, courses, accepted, paid, finished');
		$this->db->from('application_form');
		$this->db->order_by('app_date', 'desc');
		#$this->db->limit($this->_offset, $this->_limit);
		
		if(!empty($this->_filters)){
			foreach($this->_filters as $key => $value){
				switch($key){
					case 'year':
						$this->db->where('DATE_FORMAT(app_date, \'%Y\') =', $value);
					break;
				}
			}
		}else{
			$this->_filters = false;
		}
		
		$query = $this->db->get();
		
		$this->firephp->log($this->db->last_query());
		
		if(!$query->num_rows()){
			
			$this->_view_data += array(
				'error_message'	=> 'There are no applications currently.',
			);
		
		}else{
			
			#$application_fieldnames = $this->db->list_fields('application_form');
			$applications = $query->result_array();
			
			foreach($applications as $key => $value){
				$applications[$key]['courses'] = explode(',', $applications[$key]['courses']);
			}
			
			$this->_view_data += array(
				'applications'				=> $applications,
			);
		
		}
		
		$this->_view_data += array(
			'page_title'			=> 'Course Funnel',
			'filters'				=> $this->_filters,
		);
		
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('course_funnel_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		
	}
	
	public function filter($year = false){
	
		$this->_filters = array(
			'year'	=> $year,
		);
		$this->index();
		
	}
	
	public function id($id = false){
		
		if(!$id) show_404();
		
		$course_dates = array(
			#standard means the 21 week course (1-2 weeks break in between)
			'st_s1_c1'	=> '4th Feb 2013',
			'st_s1_c2'	=> '7th Feb 2013',
			'st_s2_c1'	=> '15th Jul 2013',
			'st_s2_c2'	=> '18th Jul 2013',
			#express means the 11 week course (1-2 weeks break in between)
			'ex_t1'		=> '4th Feb 2013',
			'ex_t2'		=> '6th May 2013',
			'ex_t3'		=> '5th August 2013',
		);
		
		$duration = array(
			'st'	=> 147,
			'ex'	=> 77,
		);
		
		$format_for_table = array(
			'start'	=> 'D jS F',
			'end'	=> 'D jS F',
		);
		
		$course_dates_table = $this->_course_dates($course_dates, $duration, $format_for_table);
		
		$this->db->select();
		$this->db->from('application_form');
		$this->db->where('id', $id);
		
		$query = $this->db->get();
		
		//$this->firephp->log($this->db->last_query());
		
		if(!$query->num_rows()){
			
			$this->_view_data += array(
				'no_data'	=> 'There is no application with that ID.',
			);
		
		}else{
			
			#$application_fieldnames = $this->db->list_fields('application_form');
			$application = $query->row_array();
			//$this->firephp->log($application['courses']);
			$application['courses'] = explode(',', $application['courses']);
			
			$this->_view_data += array(
				'application'				=> $application,
			);
		
		}
		
		$this->_view_data += array(
			'page_title'			=> 'ID #' . $id . ' Course Funnel',
			'id'					=> $id,
			'form_destination'		=> site_url('course_funnel/id/' . $id),
			'course_dates_table'	=> $course_dates_table,
		);
		
		$this->form_validation->set_rules($this->_settings['form_validation']['application_update']); 
		if($this->form_validation->run() == true){
			#all input post data will get xss_cleaned from the form_validation library
			#this means the form has been ran and suceeded through validation!
			$this->_form_success($id);
		}else{
			$this->_form_failure();
		}
		
		if($this->session->flashdata('success_message')){
			$this->_view_data += array(
				'success_message'			=> $this->session->flashdata('success_message'),
			);
		}
		
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('course_funnel_edit_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		
	}
	
	#$course_dates are passed as an associative array (only including the start
	#$duration is an array with standard and express
	#$format is an array with start and end
	protected function _course_dates($course_dates, $duration, $format){
		
		foreach($course_dates as $course => $start_date){
		
			$course_dates[$course] = date($format['start'], strtotime($start_date));
			
			#if six_months are at the start, it will return 0, we need to make sure 0 is not false
			if(strpos($course, 'st') !== false){
			
				$course_dates[$course . '_end'] = $this->_course_end_date($start_date, $duration['st'], $format['end']);
				
			}elseif(strpos($course, 'ex') !== false){
			
				$course_dates[$course . '_end'] = $this->_course_end_date($start_date, $duration['ex'], $format['end']);
				
			}
			
		}
		
		#$this->firephp->log($course_dates);
		
		return $course_dates;
		
	}
	
	/**
	* Given a date in d m Y, add on the length in days, then return it (default is
	*
	* @param string $start_date			Start Date of Course (format of Day Month year
	* @param string $length_in_days		Length of Course in Days
	* @param string $format				Format of the end date, defaults to 'February 1st Mon 2000'
	*
	* @return string					Formatted End Date
	*/
	protected function _course_end_date($start_date, $length_in_days, $format = 'F jS D Y'){
		
		$date = new DateTime($start_date);
		#P is an ISO8601 notation for "PERIOD" meaning duration.
		$date->add(new DateInterval('P'. $length_in_days .'D'));
		$end_date = $date->format($format);
		
		return $end_date;
		
	}
	
	#should be moved to models
	protected function _form_success($id){
		
		$data = array(
			'accepted'				=> ($this->input->post('accepted') == 'yes') ? 1 : 0,
			'paid'					=> ($this->input->post('paid') == 'yes') ? 1 : 0,
			'finished'				=> ($this->input->post('finished') == 'yes') ? 1 : 0,
			'name'					=> $this->input->post('full_name'),
			'email'					=> $this->input->post('email'),
			'phone'					=> $this->input->post('phone'),
			'birthday'				=> $this->input->post('birthday'),
			'location'				=> $this->input->post('location'),
			'skype'					=> $this->input->post('skype'),
			'cfa'					=> ($this->input->post('cfa') == 'yes') ? 1 : 0,
			'courses'				=> implode(',', $this->input->post('courses')),
			'payment_options'		=> $this->input->post('payment_options'),
			'education_employment'	=> $this->input->post('education_employment'),
			'work_study'			=> $this->input->post('work_study'),
			'experience'			=> $this->input->post('experience'),
			'build'					=> $this->input->post('build'),
			'where'					=> $this->input->post('where'),
			'video'					=> $this->input->post('video'),
		);
		
		#$query = $this->db->insert('application_form', $data); 
		$this->db->where('id', $id);
		$this->db->update('application_form', $data);

		if($this->db->affected_rows() >= 0){
		
			$this->session->set_flashdata('success_message', 'Application Updated!');
			redirect(current_url(), 'location');
			
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
	
}