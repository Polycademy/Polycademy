<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		<div class="blue_container">
			<div class="container">
				<article class="blog_create blue_text_container">
					<h2>Create a new entry in the blog!</h2>
					<? if(!empty($error_messages)){	?>
						<section class="form_errors">
							<h5>There were errors in submitting the blog, please check below:</h5>
							<ul>
								<?= $error_messages ?>
							</ul>
						</section>
					<? }elseif(!empty($success_message)){ ?>
						<h1 class="form_success"><?= $success_message ?></h1>
					<? }else{ ?>
						<h4>Type your submission below!</h4>
					<? } ?>
					<?= form_open($form_destination, array('class' => 'form-horizontal')) ?>
						<div class="control-group">
							<?= form_label('Title', 'form_title', array('class' => 'control-label required')) ?>
							<div class="controls">
								<?= form_input(array('name' => 'title', 'id' => 'form_title', 'class' => 'span5', 'value' => set_value('title'))) ?>
							</div>
						</div>
						<div class="control-group">
							<?= form_label('Tags', 'form_tags', array('class' => 'control-label required')) ?>
							<div class="controls">
								<?= form_input(array('name' => 'tags', 'id' => 'form_tags', 'class' => 'span5', 'placeholder' => 'Tag,Tag,Tag', 'value' => set_value('tags'))) ?>
							</div>
						</div>
						<div class="control-group">
							<?= form_label('Content', 'form_content', array('class' => 'control-label required')) ?>
							<div class="controls">
								<?= form_textarea(array('name' => 'content', 'id' => 'form_content', 'class' => 'span6', 'value' => set_value('content'))) ?>
							</div>
						</div>
						<div class="form-actions">
							<?= form_button(array('name' => 'submit', 'class' => 'btn btn-primary', 'type' => 'submit', 'value' => 'true', 'content' => 'Submit Application')) ?>
						</div>
					<?= form_close() ?>
				</article>
			</div>
		</div>