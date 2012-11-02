<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation{

	private $_CI;
	
	public function __construct($rules = array()){
	
		parent::__construct();
		
		#this->_CI is the CI super object, it allows you to access the CI framework within the library
		if(!isset($this->_CI)){
			$this->_CI =& get_instance();
		}
		
		// applies delimiters set in config file.
		if (isset($rules['error_prefix']))
		{
			$this->_error_prefix = $rules['error_prefix'];
			unset($rules['error_prefix']);
		}
		if (isset($rules['error_suffix']))
		{
			$this->_error_suffix = $rules['error_suffix'];
			unset($rules['error_suffix']);
		}
		
		#this is copied from the parent construct, or else the config file will not be autoloaded
		// Validation rules can be stored in a config file.
		$this->_config_rules = $rules;
		
	}
	
	/*
	* Date Validation
	* With date and format and optional minimum age_limit
	* The error message is in my own lang!
	*/
	public function valid_date($date, $format){
		
		#count between 8 and 10 characters (because of separators)
		if(strlen($date) >= 8 && strlen($date) <= 10){
		
			#based on the $format (which could be DD-MM-YYYY), it removes the characters and leaves the separators (-)
			$separator_only = str_replace(array('M','D','Y'),'', $format);
			$separator = $separator_only[0];
			
			#there needs tobe a separator!, or it fails
			if($separator){
			
				#looks for the separator, and adds 1 backslash \
				$regexp = str_replace($separator, '\\' . $separator, $format);
				#replaces the MM in the format(now $regexp) with MM and so on
				$regexp = str_replace('MM', '(0[1-9]|1[0-2])', $regexp);
				$regexp = str_replace('M', '(0?[1-9]|1[0-2])', $regexp);
				$regexp = str_replace('DD', '(0[1-9]|[1-2][0-9]|3[0-1])', $regexp);
				$regexp = str_replace('D', '(0?[1-9]|[1-2][0-9]|3[0-1])', $regexp);
				$regexp = str_replace('YYYY', '\d{4}', $regexp);
				$regexp = str_replace('YY', '\d{2}', $regexp);
				#the regexp is constructed, proceed to look for match!
				if($regexp != $date && preg_match('/'.$regexp.'$/', $date)){
				
					#explode the format, explode the date, combine, and now construct new values
					foreach(array_combine(explode($separator, $format), explode($separator, $date)) as $key => $value){
					
						if ($key == 'YY') $year = '20' . $value;
						if ($key == 'YYYY') $year = $value;
						if ($key[0] == 'M') $month = $value;
						if ($key[0] == 'D') $day = $value;
					
					}
					
					#everything has been constructed, if its a valid date, then proceed to return true
					if(checkdate($month, $day, $year)) return true;
					
				}
				
			}
			
		}
		
		return false;
		
	}
	
	public function word_limit($str, $limit){
		
		if(str_word_count($str, 0) > $limit){
			return false;
		}
		
		return true;
		
	}
	
}