<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<div class="blue_container">
		<div class="container">
			<article class="blue_text_container error_page">
				<h1><?=$error_title?> <span>:(</span></h1>
				<p><?=$error_message?></p>
				<script>
					var GOOG_FIXURL_LANG = (navigator.language || '').slice(0,2),GOOG_FIXURL_SITE = location.host;
				</script>
				<script src="http://linkhelp.clients.google.com/tbproxy/lh/wm/fixurl.js"></script>
			</article>
		</div>
	</div>