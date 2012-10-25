<?php

# WHAT IS THE ENVIRONMENT? Change to production when in production
define('ENVIRONMENT','development');

// ----------------------------------------
// 	MySQL + Paths

$domain_name = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] : "http://" . $_SERVER['SERVER_NAME'];

if(ENVIRONMENT == 'production'){
	
	$services = getenv('VCAP_SERVICES');
	$services_json = json_decode($services, true);
	$mysql_config = $services_json["mysql-5.1"][0]["credentials"];
	
	define('MYSQL_HOST', $mysql_config['hostname']);
	define('MYSQL_DB', $mysql_config['name']);
	define('MYSQL_USER', $mysql_config['user']);
	define('MYSQL_PASS', $mysql_config['password']);
	
	#edit these paths when you change the wiki or admin directory
	$base_directory = '/wiki'; #for the wiki itself
	$admin_directory = $base_directory . '/admin'; #for the admin folder in the wiki
	
}else{

	define('MYSQL_HOST','localhost');
	define('MYSQL_DB','polycademy');
	define('MYSQL_USER','root');
	define('MYSQL_PASS','');
	
	#edit these pathes when you are in development
	$base_directory = '/polycademy/wiki';
	$admin_directory = $base_directory . '/admin';
	
}

#database options
define('TABLE_PREFIX','bd_');
define('ADMIN_FOLDER','admin');

#these shouldn't need to be edited
define('PATH', dirname(__FILE__));
define('COMPANY_URL', $domain_name);
define('URLHOLD', COMPANY_URL . $base_directory);
define('ADMIN_URL', COMPANY_URL . $admin_directory);

#salts, which need to match the database salts
define('SITE_SALT','7001');
define('SALT1','508947001f290d6f1821fc0aaf930f738af8c0bf27e10ec3f02c3f47663f');
define('SALT2','139388454310630715831160694882103101019512668904101188564012');

// ----------------------------------------
//	PHP Configuration

// ini_set('display_errors', 1); 
// ini_set('error_log', PATH . '/generated/error_log.txt'); 
define('PERFORMANCE_TESTS','0');


// ----------------------------------------
// 	Let's start dancing...

define('LANGUAGE','english');
require PATH . '/addons/languages/' . LANGUAGE . '.php';
require PATH . '/start_load.php';

?>