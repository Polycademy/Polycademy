<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends CI_Controller {

	protected $_view_data; //only accessible in this class and any classes that extend home
	protected $_settings;
	protected $_footer_blog = false;
	protected $_rss_feeds = false;
	
	public function __construct(){
		
		parent::__construct();
		
		$this->_settings = $this->config->item('polycademy');
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		#language files require MY_ or differentiator or else they overwrite the system ones.
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
	
	}
	
	public function index(){
		
		$course_dates = array(
			#express means the 11 week course (1-2 weeks break in between)
			'ex_t1'		=> '6th January 2014',
			'ex_t2'		=> '7th April 2014',
			'ex_t3'		=> '14th July 2014',
		);
		
		$duration = array(
			'st'	=> 147,
			'ex'	=> 77,
		);
		
		$format = array(
			'start'	=> 'F jS l Y',
			'end'	=> 'F jS l Y',
		);
		
		$format_for_table = array(
			'start'	=> 'D jS F Y',
			'end'	=> 'D jS F Y',
		);
		
		$course_dates_slider = $this->_course_dates($course_dates, $duration, $format);
		$course_dates_table = $this->_course_dates($course_dates, $duration, $format_for_table);
		
		#$this->firephp->log($course_dates_slider);
		#$this->firephp->log($course_dates_table);
		
		$this->_view_data += array(
			'page_title'			=> 'Courses',
			'course_dates'			=> $course_dates_slider,
			'course_dates_table'	=> $course_dates_table,
			'course_times'			=> $this->_course_times(),
			'form_destination'		=> $this->router->fetch_class(),
		);
		
		#$this->firephp->log($this->input->post('feedback'));

		
		$this->form_validation->set_rules($this->_settings['form_validation']['application_form']); 
		if($this->form_validation->run() == true){
			#all input post data will get xss_cleaned from the form_validation library
			#this means the form has been ran and suceeded through validation!
			$this->_form_success();
		}else{
			$this->_form_failure();
		}
		
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('courses_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		
	}
	
	#should be moved to models
	protected function _form_success(){
		#make sure the processing from form validation is passed to here!
		
		#need some function to say they succeeded
		#preferably a message and some details + call to action
		#NEED MESSAGE, and hook to DB model to enter in data
		#IF HAVE MESSAGE, try to prevent duplicate entries, by overwriting the old entry...? NOO COOKIE DATA? Or just BIG MESSAGE!
		
		$data = array(
			'app_date'				=> date('Y-m-d H:i:s', now()),
			'name'					=> implode(' ', $this->input->post('full_name')),
			'email'					=> $this->input->post('email'),
			'skype'					=> $this->input->post('skype'),
			'courses'				=> implode(',', $this->input->post('courses')),
			'build'					=> $this->input->post('build'),
			'where'					=> $this->input->post('where'),
		);
		
		$query = $this->db->insert('application_form', $data); 
		
		if($query){
			
			#all of these messages need to be moved to the view template, and the controller should be only passing status codes/messages!
			$this->_view_data += array(
				'success_message'	=> 'Thanks for submitting your application! We\'ll be in touch shortly. In the meanwhile check out our blog.',
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
	
	protected function _course_times(){
	
		#standard => 8 hours & expess => 15hours
		$course_times = array(
			'standard_cohort1' => '6pm - 9pm on Monday & Tuesday and 6pm - 8pm on Wednesday',
			'standard_cohort2' => '6pm - 9pm on Thursday & Friday and 6pm - 8pm on Saturday',
			'express' => '6pm - 8pm (3hrs) on Monday to Friday',
		);
		
		return $course_times;
	
	}
	
}