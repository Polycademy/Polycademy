<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Site Variables
|--------------------------------------------------------------------------
|
*/
$config['polycademy'] = array(
	'site_domain'				=> 'polycademy.com',
	'site_name'					=> 'Polycademy',
	'site_desc'					=> 'an academy for web application entrepreneurship',
	'meta_desc'					=> 'Polycademy is a school in Canberra that teaches web application development and entrepreneurship. It is catered to potential web application entrepreneurs or developer/designers who want to reskill. Students are placed in teams and are taught web tech by starting with envisioning their own project to coding and deployment. Students finish the course with an actual product. Our three philosophies are minimum viable product, agile methodology and flipped classroom. Students will gain mentors and industry contacts through our demo days.',
	'copyright'					=> '&copy; Polycademy & Code for Australia 2012',
	'google_analytics_key'		=> 'UA-36175888-1',
	'recaptcha_personal_email'	=> '<a href=\'http://www.google.com/recaptcha/mailhide/d?k=01q-bJV3WQrMYWD2quLJ7VPA==&c=FsmnfqaQraWCMzZB6tsagBZd557LPBLlxh80gaenMSo=\'>@polycademy.com</a>',
	'twitter_page'				=> 'https://twitter.com/Polycademy',
	'facebook_page'				=> 'https://www.facebook.com/Polycademy',
	'pagination'	=> array(
		'blog'	=> array(
			'limit'		=> 5,
			'offset'	=> 0,
		),
		'footer_blog'	=> array(
			'limit'		=> 5,
			'offset'	=> 0,
		),
	),
	'links'						=> array( #this is in order of navigation from left to right 'name of link' => directory/controller name/method name
		#header links
		'navigation'	=> array(
			'home'			=> '',
			'courses'		=> 'courses',
			'partners'		=> 'partners',
			'code for australia'	=> 'http://codeforaustralia.com.au',
			'php bounce'	=> 'http://phpbounce.aws.af.cm/',
			'events'		=> 'http://polycademy.eventbrite.com.au/',
			#'alumni'		=> 'alumni',
			#'devhub'		=> 'devhub',
			'blog'			=> 'blog',
		),
		#other links
		'notices'			=> 'blog/tags/notices',
		'codex'				=> 'wiki',
		'forum'				=> 'forum',
		'terms_of_service'	=> 'terms_of_service',
		'refund_policy'		=> 'refund_policy',
		'scholarship'		=> 'scholarship',
		#resources for base_url()
		'js_assets'			=> 'js',
		'css_assets'		=> 'css',
		'img_assets'		=> 'img',
		'font_assets'		=> 'fonts',
		#for markitup form
		'preview_template'	=> '/blog/preview', //controller/method for the preview
	),
	'form_validation'		=> array(
		'application_form'	=> array(
			array(
				'field'		=> 'full_name[first]',
				'label'		=> 'First Name',
				'rules'		=> 'required|alpha|min_length[2]|max_length[20]|trim|xss_clean',
			),
			array(
				'field'		=> 'full_name[last]',
				'label'		=> 'Last Name',
				'rules'		=> 'required|alpha|min_length[2]|max_length[20]|trim|xss_clean',
			),
			array(
				'field'		=> 'email',
				'label'		=> 'Email',
				'rules'		=> 'required|valid_email|min_length[5]|max_length[40]|trim',
			),
			array(
				'field'		=> 'skype',
				'label'		=> 'Skype ID',
				'rules'		=> 'required|max_length[30]|trim|xss_clean',
			),
			array(
				'field'		=> 'courses[]',
				'label'		=> 'Courses',
				'rules'		=> 'required|trim|xss_clean',
			),
			array(
				'field'		=> 'agreement',
				'label'		=> 'Agreement to TOS, Privacy Policy and Refund Policy',
				'rules'		=> 'required|trim|xss_clean',
			),
			array(
				'field'		=> 'build',
				'label'		=> 'What do you want to build?',
				'rules'		=> 'required|trim|xss_clean|htmlspecialchars|word_limit[400]',
			),
			array(
				'field'		=> 'where',
				'label'		=> 'Where did you here about us?',
				'rules'		=> 'trim|xss_clean|htmlspecialchars|word_limit[400]',
			),
		),
		'application_update'=> array(
			array(
				'field'		=> 'full_name',
				'label'		=> 'Full Name',
				'rules'		=> 'required|trim|xss_clean',
			),
			array(
				'field'		=> 'email',
				'label'		=> 'Email',
				'rules'		=> 'required|valid_email|min_length[5]|max_length[40]|trim|xss_clean',
			),
			array(
				'field'		=> 'phone',
				'label'		=> 'Phone',
				'rules'		=> 'trim|xss_clean',
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
				'rules'		=> 'required|max_length[30]|trim|xss_clean',
			),
			array(
				'field'		=> 'cfa',
				'label'		=> 'CFA',
				'rules'		=> 'trim|xss_clean',
			),
			array(
				'field'		=> 'courses[]',
				'label'		=> 'Courses',
				'rules'		=> 'required|trim|xss_clean',
			),
			array(
				'field'		=> 'payment_options',
				'label'		=> 'Payment Options',
				'rules'		=> 'required|trim|xss_clean',
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
				'field'		=> 'where',
				'label'		=> 'Where did you here about us?',
				'rules'		=> 'trim|xss_clean|htmlspecialchars|word_limit[400]',
			),
			array(
				'field'		=> 'video',
				'label'		=> 'Video',
				'rules'		=> 'trim|xss_clean|max_length[65]|htmlspecialchars|prep_url',
			),
		),
		'blog_create'		=> array(
			array(
				'field'		=> 'title',
				'label'		=> 'Title',
				'rules'		=> 'required|trim|word_limit[30]',
			),
			array(
				'field'		=> 'tags',
				'label'		=> 'Tags',
				'rules'		=> 'trim|max_length[100]',
			),
			array(
				'field'		=> 'content',
				'label'		=> 'Content',
				'rules'		=> 'required|trim',
			),
		),
	),
);

$config['codeforaustralia'] = array();

?>