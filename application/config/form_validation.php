<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
	'application_form' => array(
		array(
			'field'		=> 'fullname[first]',
			'label'		=> 'First Name',
			'rules'		=> 'required|alpha|min_length[2]|max_length[16]|trim|xss_clean',
		),
		array(
			'field'		=> 'fullname[last]',
			'label'		=> 'Last Name',
			'rules'		=> 'required|alpha|min_length[2]|max_length[16]|trim|xss_clean',
		),
		array(
			'field'		=> 'email',
			'label'		=> 'Email',
			'rules'		=> 'required|valid_email|min_length[5]|max_length[30]|trim',
		),
		array(
			'field'		=> 'birthday',
			'label'		=> 'Date of Birth',
			'rules'		=> 'required|trim|xss_clean|valid_date[DD-MM-YYYY]',
		),
		array(
			'field'		=> 'location',
			'label'		=> 'Location',
			'rules'		=> 'required|max_length[60]|trim|xss_clean',
		),
		array(
			'field'		=> 'skype',
			'label'		=> 'Skype ID',
			'rules'		=> 'required|max_length[25]|trim|xss_clean',
		),
		array(
			'field'		=> 'courses[]',
			'label'		=> 'Courses',
			'rules'		=> 'required',
		),
		array(
			'field'		=> 'payment_options',
			'label'		=> 'Payment Options',
			'rules'		=> 'required',
		),
		array(
			'field'		=> 'agreement',
			'label'		=> 'Agreement to TOS, Privacy Policy and Refund Policy',
			'rules'		=> 'required',
		),
		array(
			'field'		=> 'education_employment',
			'label'		=> 'Education & Employment',
			'rules'		=> 'required|trim|xss_clean|htmlspecialchars|word_limit[100]',
		),
		array(
			'field'		=> 'work_study',
			'label'		=> 'Work & Study Commitments',
			'rules'		=> 'required|trim|xss_clean|word_limit[30]',
		),
		array(
			'field'		=> 'experience',
			'label'		=> 'Technical Experience',
			'rules'		=> 'required|trim|xss_clean|htmlspecialchars|word_limit[200]',
		),
		array(
			'field'		=> 'build',
			'label'		=> 'What do you want to build?',
			'rules'		=> 'required|trim|xss_clean|htmlspecialchars|word_limit[400]',
		),
		array(
			'field'		=> 'video',
			'label'		=> 'Video',
			'rules'		=> 'trim|xss_clean|max_length[50]|htmlspecialchars|prep_url',
		),
		array(
			'field'		=> 'feedback',
			'label'		=> 'Feedback',
			'rules'		=> 'trim|xss_clean|htmlspecialchars|word_limit[200]',
		),
	),
);

?>