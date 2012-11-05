<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		<footer>
			<div class="container">
				<article class="row footer_grid">
					<section class="blog_panel span4">
						<h3>
							<?php echo anchor($links['navigation']['blog'], 'Blog') ?>
						</h3>
						<p>
							<?
							if(!empty($footer_blog_data)){
								foreach($footer_blog_data as $article){
							?>
									• <em class="footer_dates">(<?= $article['date'] ?>)</em> - <?= $article['title'] ?><br /><br />
							<? 
								}
							}else{
							?>
									• No blog post yet
							<? }?>
						</p>
					</section>
					<section class="fbtwitter_panel span4">
						<h3><a href="<?=$facebook_page?>">FB</a>.<a href="<?=$twitter_page?>">Twitter</a></h3>
						<p>
						<?php
							if(is_array($feeds)){
								foreach($feeds as $feed){
						?>
									&#8226; <em class="footer_dates">(<?=$feed['date']?>)</em> - <?php echo anchor($feed['link'], $feed['title']) ?><br /><br />
						<?php
								}
							}
						?>
						</p>
					</section>
					<section class="notices_panel span4">
						<h3>
							<?php echo anchor($links['notices'], array_search($links['notices'], $links)) ?>
						</h3>
						<p>We’re hiring teachers who would specialise in web design & web development.<br /><br />We’re interested in acquiring mentors, investors and employers who are interested to get involved.<br /><br />We’re also looking for a good location for the classroom.<br /><br />Find out more at <a href='http://www.google.com/recaptcha/mailhide/d?k=01q-bJV3WQrMYWD2quLJ7VPA==&c=FsmnfqaQraWCMzZB6tsagBZd557LPBLlxh80gaenMSo='>@polycademy.com</a></p>
					</section>
				</article>
				<ul class="footer_links">
					<li><?php echo anchor($links['terms_of_service'], 'Terms of Service & Privacy Policy') ?></li>
					<li><?php echo anchor($links['refund_policy'], 'Refund Policy') ?></li>
				</ul>
				<p class="copyright"><?=$copyright?></p>
			</div>
		</footer>
		
        <!--This passes any PHP/Codeigniter Variables to JS in case we need it-->
		<script>
			var ci_base_url = "<?= base_url() ?>";
			var ci_preview_path = "~<?= $links['preview_template'] ?>";
		</script>
		
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?php echo base_url($links['js_assets']) ?>/jquery-1.8.2.min.js"><\/script>')</script>

        <script src="<?php echo base_url($links['js_assets']) ?>/bootstrap.min.js"></script>
		<script src="<?php echo base_url($links['js_assets']) ?>/jquery-ui-1.8.20.custom.min.js"></script>
		<script src="<?php echo base_url($links['js_assets']) ?>/jquery.liquid-slider-0.1.min.js"></script>

        <script src="<?php echo base_url($links['js_assets']) ?>/main.js"></script>

        <script>
            var _gaq=[['_setAccount','<?=$google_analytics_key?>'],['_setDomainName', '<?=$site_domain?>'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>