<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?=$page_title?> - <?= ($this->router->fetch_class() == $this->router->routes['default_controller']) ? $site_desc : $site_name . ' is ' . $site_desc ?></title>
        <meta name="description" content="<?= ((empty($custom_meta_desc)) ? $meta_desc : $custom_meta_desc) ?>">
		<meta name="google-site-verification" content="Mq6Yv8R9mGJK9SrUH5oZ8SNB8Z_WJHwkdkgp49ukHYE" />
		<meta name="msvalidate.01" content="907A19AD04C8D5C2095C9DCE875FD165" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="<?php echo base_url() ?>favicon.ico">
		<link rel="apple-touch-icon" href="<?php echo base_url() ?>apple-touch-icon.png">
		<link rel="stylesheet" href="<?php echo base_url($links['css_assets']) ?>/main.css">
        <script src="<?php echo base_url($links['js_assets']) ?>/modernizr-2.6.1-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->
        <header class="navbar navbar-static-top">
			<div class="container">
				<div class="navbar-inner">
					<a class="logo" href="<?php echo site_url() ?>"><img src="<?php echo base_url($links['img_assets']) ?>/logo.png" /></a>
					<p class="slogan"><?=$site_desc?></p>
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<?php
								foreach($links['navigation'] as $name => $link){
									echo '<li>' . anchor($link, $name) . '</li>';
									end($links['navigation']);
									if($name !== key($links['navigation'])){
										echo '<li class="divider-vertical"></li>';
									}
								}
							?>
						</ul>
					</div>
				</div>
			</div>
        </header>