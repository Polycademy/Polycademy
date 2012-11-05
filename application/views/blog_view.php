<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		<div class="white_container">
			<div class="container">
				<?
				if(!empty($blog_data)){
					foreach($blog_data as $article){
				?>
					<article class="white_text_container blog_article">
						<h1><?= $article['title'] ?></h1>
						<div class="blog_content">
							<?= $article['content'] ?>
						</div>
						<div class="meta_box">
							<span class="author_meta">Posted by <?= (!empty($article['author'])) ? $article['author'] : 'Unknown' ?> on <?= $article['date'] ?></span>
							<?= (!empty($article['tags'])) ? '<span class="tag_meta">Tags: ' . $article['tags'] . '</span>' : '' ?>
						</div>
						<!---<p><?= $article['id'] ?></p>-->
					</article>
				<? 
					}
				}else{
				?>
					<article>
						<h2>Sorry no blog posts to show!</h2>
					</article>
				<? }?>
			</div>
		</div>