		<footer>
			<div class="container">
				<article class="row footer_grid">
					<section class="blog_panel span4">
						<h3><a href="<?php echo base_url() ?>blog">Blog</a></h3>
						<p>• No blog post yet</p>
					</section>
					<section class="fbtwitter_panel span4">
						<h3><a href="<?=$facebook_page?>">FB</a>/<a href="<?=$twitter_page?>">Twitter</a></h3>
						<p>
						<?php
							foreach($feeds as $feed){
						?>
								• <em class="footer_dates">(<?=$feed['date']?>)</em> - <a href="<?=$feed['link']?>"><?=$feed['title']?></a><br /><br />
						<?php
							}
						?>
						</p>
					</section>
					<section class="notices_panel span4">
						<h3><a href="<?php echo base_url() ?>blog/notices">Notices</a></h3>
						<p>We’re hiring teachers who would specialise in web design & web development.<br /><br />We’re interested in acquiring mentors, investors and employers who are interested to get involved.<br /><br />We’re also looking for a good location for the classroom.<br /><br />Find out more at <a href='http://www.google.com/recaptcha/mailhide/d?k=01q-bJV3WQrMYWD2quLJ7VPA==&c=FsmnfqaQraWCMzZB6tsagBZd557LPBLlxh80gaenMSo='>@polycademy.com</a></p>
					</section>
				</article>
				<p class="copyright"><?=$copyright?></p>
			</div>
		</footer>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?php echo base_url() ?>js/vendor/jquery-1.8.2.min.js"><\/script>')</script>

        <script src="<?php echo base_url() ?>js/vendor/bootstrap.min.js"></script>
		<script src="<?php echo base_url() ?>js/vendor/jquery-ui-1.8.20.custom.min.js"></script>
		<script src="<?php echo base_url() ?>js/vendor/jquery.liquid-slider-0.1.min.js"></script>

        <script src="<?php echo base_url() ?>js/plugins.js"></script>
        <script src="<?php echo base_url() ?>js/main.js"></script>

        <script>
            var _gaq=[['_setAccount','<?=$google_analytics_key?>'],['_setDomainName', '<?=$site_domain?>'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>