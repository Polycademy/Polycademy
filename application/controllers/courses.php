<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends CI_Controller {

	protected $_view_data; //only accessible in this class and any classes that extend home
	protected $_settings;
	
	public function __construct(){
		
		parent::__construct();
		
		$this->_settings = $this->config->item('polycademy');
		$this->_view_data = $this->_settings;
		
		$this->load->helper('form');
		
		$this->load->library('form_validation');
		
		#language files require MY_ or differentiator or else they overwrite the system ones.
		$this->lang->load('MY_form_validation_lang');
	
	}
	
	public function index(){
	
		$rss_feeds = rss_process('http://pipes.yahoo.com/pipes/pipe.run?_id=24a7ee6208f281f8dff1162dbac57584&_render=rss');
		if($rss_feeds){
			$rss_feeds = array_slice($rss_feeds, 0, 4);
		}
		
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
		
		$format = array(
			'start'	=> 'F jS l Y',
			'end'	=> 'F jS l Y',
		);
		
		$format_for_table = array(
			'start'	=> 'D jS F',
			'end'	=> 'D jS F',
		);
		
		$course_dates = $this->_course_dates($course_dates, $duration, $format);
		$course_dates_table = $this->_course_dates($course_dates, $duration, $format_for_table);
		
		$this->_view_data += array(
			'page_title'			=> 'Courses',
			'page_desc'				=> $this->_settings['site_desc'],
			'feeds'					=> $rss_feeds,
			'course_dates'			=> $course_dates,
			'course_dates_table'	=> $course_dates_table,
			'course_times'			=> $this->_course_times(),
			'form_destination'		=> $this->router->fetch_class(),
		);
		
		$this->firephp->log($this->input->post('feedback'));

		
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
			'video'					=> $this->input->post('video'),
			'tech'					=> $this->input->post('tech'),
			'feedback'				=> $this->input->post('feedback'),
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
			'express' => '9am - 12pm & 2pm - 4pm on Monday, Tuesday and Thursday',
		);
		
		return $course_times;
	
	}
	
}