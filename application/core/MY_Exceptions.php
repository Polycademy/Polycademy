<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Exceptions extends CI_Exceptions{

	private $_CI;
	private $_routes;
	
	public function __construct($rules = array()){
	
		parent::__construct();
		
		#this->_CI is the CI super object, it allows you to access the CI framework within the library
		if(!isset($this->_CI)){
			$this->_CI =& get_instance();
		}
		
		$this->_CI->load->library('session');
		$this->_routes = $this->_CI->router->routes;
		
	}
	
	public function show_404($page = '', $log_error = TRUE){
	
		$heading = '404 Page Not Found';
		$message = 'Sorry we didn\'t find what you wanted, try a search or check out other links.';

		// By default we log this, but allow a dev to skip it
		if ($log_error){
			log_message('error', '404 Page Not Found --> '.$page);
		}

		echo $this->show_error($heading, $message, 'error_404', 404);
		
		exit;
		
	}
	
	/**
	 * General Error Page
	 *
	 * Takes an error message as input (either as a string or an array)
	 * and displays it using the specified template.
	 *
	 * @param	string		$heading	Page heading
	 * @param	string|string[]	$message	Error message
	 * @param	string		$template	Template name
	 * @param 	int		$status_code	(default: 500)
	 *
	 * @return	string	Error page output
	 */
	public function show_error($heading, $message, $template = 'error_general', $status_code = 500){
	
		set_status_header($status_code);

		$message = implode('</p><p>', is_array($message) ? $message : array($message));

		if (ob_get_level() > $this->ob_level + 1){
			ob_end_flush();
		}
		
		if(!empty($this->_routes['error_page'])){
		
			$this->_CI->session->set_flashdata('message', $message);
			$this->_CI->session->set_flashdata('status_code', $status_code);
			header('Location: ' . base_url() . $this->_routes['error_page'] . $status_code);
		
		}else{
		
			ob_start();
			include(VIEWPATH.'errors/'.$template.'.php');
			$buffer = ob_get_contents();
			ob_end_clean();
			return $buffer;
		
		}
	
	}
	
}