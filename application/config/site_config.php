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
	'meta_desc'					=> 'Polycademy is a brick & mortar school in Canberra that teaches web application development from a top-down framework. Its customers will be entrepreneurial highschool students or adults who want to reskill. Students are placed in teams and are taught web tech by starting with envisioning their own project to coding and deployment. Students finish the course not with a shiny paper, but an actual us
able application. The development experience integrates industry practices such as using revision control systems and agile development methodologies, and also includes workshops in deploying it to the market.',
	'copyright'					=> '&copy; Polycademy & Code for Australia 2012',
	'google_analytics_key'		=> 'UA-35507004-1',
	'recaptcha_personal_email'	=> '<a href=\'http://www.google.com/recaptcha/mailhide/d?k=01q-bJV3WQrMYWD2quLJ7VPA==&c=FsmnfqaQraWCMzZB6tsagBZd557LPBLlxh80gaenMSo=\'>@polycademy.com</a>',
	'twitter_page'				=> 'https://twitter.com/Polycademy',
	'facebook_page'				=> 'https://www.facebook.com/Polycademy',
	'links'						=> array( #this is in order of navigation from left to right 'name of link' => directory/controller name/method name
		#header links
		'navigation'	=> array(
			'home'			=> '',
			'courses'		=> 'courses',
			'alumni'		=> 'alumni',
			'blog'			=> 'blog',
			'get involved'	=> 'get_involved',
			'about'			=> 'about',
			'devhub'		=> 'devhub',
		),
		#other links
		'notices'		=> 'blog/notices',
		'codex'			=> 'wiki',
		'forum'			=> 'forum',
		#resources for base_url()
		'js_assets'		=> 'js',
		'css_assets'	=> 'css',
		'img_assets'	=> 'img',
		'font_assets'	=> 'fonts',
	),
);

$config['codeforaustralia'] = array();

?>