<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		<div class="blue_container">
			<div class="container">
				<?
				if(!empty($blog_data)){
					foreach($blog_data as $article){
				?>
					<article class="white_text_container blog_article">
						<h1><?= $article['title'] ?></h1>
						<p><?= (!empty($article['author'])) ? $article['author'] : '' ?></p>
						<p><?= $article['date'] ?></p>
						<div class="blog_content">
							<?= $article['content'] ?>
						</div>
						<p><?= $article['tags'] ?></p>
						<p><?= $article['id'] ?></p>
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