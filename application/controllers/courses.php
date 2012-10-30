<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Courses extends CI_Controller {

	protected $_view_data; //only accessible in this class and any classes that extend home
	protected $_settings;
	
	public function __construct(){
		parent::__construct();
		$this->_settings = $this->config->item('polycademy');
	}
	
	public function index(){
	
		$rss_feeds = rss_process('http://pipes.yahoo.com/pipes/pipe.run?_id=24a7ee6208f281f8dff1162dbac57584&_render=rss');
		if($rss_feeds){
			$rss_feeds = array_slice($rss_feeds, 0, 4);
		}
		
		$this->_view_data = $this->_settings;
		$this->_view_data += array(
			'page_title'			=> 'Courses',
			'page_desc'				=> $this->_settings['site_desc'],
			'course_dates'			=> $this->_course_dates(),
			'course_times'			=> $this->_course_times(),
			'feeds'					=> $rss_feeds,
		);
		
		$this->load->view('header_view', $this->_view_data);
		$this->load->view('courses_view', $this->_view_data);
		$this->load->view('footer_view', $this->_view_data);
		
	}
	
	protected function _course_dates(){
	
		#THIS NEEDS TO BE CHANGED EACH YEAR, start each on a MONDAY!, remember cohort 2 starts a little bit later
		$course_dates = array(
			#standard means the 21 week course (1-2 weeks break in between)
			'first_standard_cohort1'			=> '4th Feb 2013',
			'first_standard_cohort2'			=> '7th Feb 2013',
			'second_standard_cohort1'			=> '15th Jul 2013',
			'second_standard_cohort2'			=> '18th Jul 2013',
			#express means the 11 week course (1-2 weeks break in between)
			'first_express'		=> '4th Feb 2013',
			'second_express'	=> '6th May 2013',
			'third_express'		=> '5th August 2013',
		);
		
		foreach($course_dates as $course => $start_date){
		
			$course_dates[$course] = date('F jS l Y', strtotime($start_date));
			
			#if six_months are at the start, it will return 0, we need to make sure 0 is not false
			if(strpos($course, 'standard') !== false){
			
				$course_dates[$course . '_end'] = $this->_course_end_date($start_date, 147, 'F jS l Y');
				
			}elseif(strpos($course, 'express') !== false){
			
				$course_dates[$course . '_end'] = $this->_course_end_date($start_date, 77, 'F jS l Y');
				
			}
		}
		
		$this->firephp->log($course_dates);
		
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