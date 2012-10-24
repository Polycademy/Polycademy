<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?=$page_title?> - <?=$page_desc?></title>
        <meta name="description" content="<?=$meta_desc?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="<?php echo base_url() ?>favicon.ico">
		<link rel="apple-touch-icon" href="<?php echo base_url() ?>apple-touch-icon.png">
		<link rel="stylesheet" href="<?php echo base_url() ?>css/main.css">
        <script src="<?php echo base_url() ?>js/vendor/modernizr-2.6.1-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->
		
        <header class="navbar navbar-static-top">
			<div class="container">
				<div class="navbar-inner">
						<a href="<?php echo base_url() ?>"><img class="logo" src="<?php echo base_url() ?>img/logo.png" /></a>
						<p class="slogan"><?=$site_desc?></p>
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
						<div class="nav-collapse collapse">
							<ul class="nav">
								<li><a href="<?php echo base_url() ?>">home</a></li>
								<li class="divider-vertical"></li>
								<li><a href="<?php echo base_url() ?>courses">courses</a></li>
								<li class="divider-vertical"></li>
								<li><a href="<?php echo base_url() ?>alumni">alumni</a></li>
								<li class="divider-vertical"></li>
								<li><a href="<?php echo base_url() ?>blog">blog</a></li>
								<li class="divider-vertical"></li>
								<li><a href="<?php echo base_url() ?>get_involved">get involved</a></li>
								<li class="divider-vertical"></li>
								<li><a href="<?php echo base_url() ?>about">about</a></li>
								<li class="divider-vertical"></li>
								<li><a href="<?php echo base_url() ?>devhub">devhub</a></li>
							</ul>
						</div>
				</div>
			</div>
        </header>