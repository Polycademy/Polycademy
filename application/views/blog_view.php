<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		<div class="white_container">
			<div class="container">
				<?
					if(!empty($tag_page)){
				?>
						<h4>Showing articles with the tag(s): '<?= implode(', ', $tags) ?>'</h4>
				<?
					}
				?>
				<?
					if(!empty($blog_data)){
						foreach($blog_data as $article){
				?>
							<article class="white_text_container blog_article">
								<h1 class="blog_title"><?= anchor('blog/id/' . $article['id'] . '/' . $article['link'], $article['title']) ?></h1>
								<div class="blog_content">
									<?= $article['content'] ?>
								</div>
								<div class="meta_box">
									<span class="author_meta">Posted by <?= (!empty($article['author'])) ? $article['author'] : 'Unknown' ?> on <?= $article['date'] ?></span>
									<? if(!empty($article['tags'])){ ?>
											<span class="tag_meta">Tags:
												<? foreach($article['tags'] as $tag){ ?>
													<?= (!empty($tag)) ? anchor('blog/tags/' . $tag, $tag) : '' ?>
												<? } ?>
											</span>
									<? } ?>
									<span class="comment_meta">
										<?= anchor('blog/id/' . $article['id'] . '/' . $article['link'] . '#disqus_thread', 'Want to Comment?') ?>
									</span>
									<? if(!empty($single_page)){ ?>
										<div class="addthis_toolbox addthis_default_style ">
											<a class="addthis_button_preferred_1"></a>
											<a class="addthis_button_preferred_2"></a>
											<a class="addthis_button_preferred_3"></a>
											<a class="addthis_button_preferred_4"></a>
											<a class="addthis_button_compact"></a>
											<a class="addthis_counter addthis_bubble_style"></a>
										</div>
										<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-509f8a9354e18205"></script>
									<? } ?>
									<? if(!empty($admin_user)){ ?>
										<span class="edit_meta"><?= anchor('blog/edit/' . $article['id'], 'Edit this Article') ?></span>
									<? } ?>
								</div>
							</article>
				<? 
						}
						
						if(empty($single_page)){
						
							if(!empty($pagination_links)){
								echo $pagination_links;
							}
							
						}else{
						
							if(!empty($pager)){
				?>
								<div>
									<ul class="pager">
										<? if(!empty($pager['prev_link'])){ ?>
											<li>
												<a href="<?= $pager['prev_link'] ?>">&larr; Older</a>
											</li>
										<? } ?>
										<? if(!empty($pager['next_link'])){ ?>
											<li>
												<a href="<?= $pager['next_link'] ?>">Newer &rarr;</a>
											</li>
										<? } ?>
									</ul>
								</div>
				<?
							}
				?>
							<div id="disqus_thread"></div>
							<script type="text/javascript">
								/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
								var disqus_shortname = "<?= strtolower($site_name) ?>";
								var disqus_developer = <?= (ENVIRONMENT == 'production') ? 0 : 1 ?>;
								var disqus_identifier = "<?= 'blog_' . $blog_data[0]['id'] ?>";
								var disqus_url = "<?= current_url() ?>";
								var disqus_title = "<?= $blog_data[0]['title'] ?>";

								/* * * DON'T EDIT BELOW THIS LINE * * */
								(function() {
									var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
									dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
									(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
								})();
							</script>
							<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
				<?
						}
						
					}else{
				?>
						<article>
							<h2>Sorry no blog posts to show!</h2>
						</article>
				<?
					}
				?>
			</div>
		</div>