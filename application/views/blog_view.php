<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		<div class="white_container">
			<div class="container">
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
							<?= (!empty($article['tags'])) ? '<span class="tag_meta">Tags: ' . $article['tags'] . '</span>' : '' ?>
						</div>
					</article>
				<? 
					}
				}else{
				?>
					<article>
						<h2>Sorry no blog posts to show!</h2>
					</article>
				<? }?>
				<?
					if(!empty($pagination_links)){
				?>
					<div class="pagination pagination-centered pagination-large">
						<?= $pagination_links ?>
					</div>
				<?
					}
				?>
			</div>
		</div>