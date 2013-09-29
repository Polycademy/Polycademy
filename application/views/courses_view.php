<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		<div class="blue_container">
			<div class="container">
				<article class="course_demo">
					<h1 class="course_title">11 Weeks Express</h1>
					<div class="course_grid">
						<div class="row-fluid">
							<section class="course_details span6">
								<h4>What is it?</h4>
								<p>The 11 weeks express involves of <strong>15 hours per week instruction time plus 6 hours per week free time</strong>. That equals 231 hours of value. The class size is limited to 15 students and we recommend you to get into a team of 2 or 3. During after hours, the classroom will be open for you to chill out and meet the other students.</p>
								<p>Our three philosophies at Polycademy is <strong>Minimum Viable Product (MVP)</strong>, <strong>Agile Development</strong> and <strong>Flipped Classroom</strong>.</p>
								<ul>
									<li><strong>MVP</strong> means designing your product so it is minimum enough to launch quickly to get market testing, and viable enough to make your product distinct from competitors.</li>
									<li><strong>Agile Development</strong> is about rapid and flexible incremental iteration to code. Rather than memorising the whole corpus of programming in 11 weeks, each week is a milestone in which parts of your product will be deployed.</li>
									<li><strong>Flipped Classroom</strong> means that before class an online ecosystem of materials will be provided to you. During class we'll keep it practical by spending time discussing models, cutting code, and deploying applications to the cloud.</li>
								</ul>
								<p>We'll be arranging your team with a mentor who will volunteer their time to coach your team on the entrepenurial or development process.</p>
								<p>At the end of 11 weeks, a careers/pitching fair will be opened. Tech employers and investors will be invited to check out your work.</p>
								<p>If you are from overseas, an <?php echo anchor('http://www.immi.gov.au/visitors/tourist/', 'Australian tourist visa') ?> will give you enough time to stay in Canberra to complete the 11 week course. The student visa is not suitable for Polycademy.</p>
							</section>
							<section class="course_details span6">
								<h4>What will you learn?</h4>
								<p>The following details may be adjusted after launch.</p>
								<ul class="detailed_list">
									<li>Theory</li>
									<li>Agile Development Method</li>
									<li>MVP Design</li>
									<li>MoSCoW Features</li>
									<li>Object Oriented Programming</li>
									<li>Asynchronous Programming</li>
								</ul>
								<ul class="detailed_list">
									<li>Environment</li>
									<li>Vagrant Deployment</li>
									<li>Browsers</li>
									<li>Git & Github</li>
									<li>Command Line</li>
									<li>AMP, NodeJS, Ruby</li>
									<li>Build Automation using Grunt</li>
								</ul>
								<ul class="detailed_list">
									<li>Design</li>
									<li>User Experience Design</li>
									<li>Adobe Photoshop Mockups</li>
									<li>HTML with H5BP and Bootstrap</li>
									<li>CSS with LESS.css</li>
								</ul>
								<ul class="detailed_list">
									<li>Programming</li>
									<li>PHP with Codeigniter</li>
									<li>Algorithms and Design Patterns</li>
									<li>Database CRUD using SQL, Redis and NoSQL</li>
									<li>Javascript with AngularJS</li>
									<li>AJAX and API architecture</li>
									<li>Sockets & Network Programming</li>
								</ul>
								<ul class="detailed_list">
									<li>Performance</li>
									<li>Browser & File Caching</li>
									<li>CDN & Reverse Proxy Caching</li>
									<li>Concurrent Programming</li>
									<li>Opcode Caching</li>
									<li>Message Queues</li>
									<li>Compression</li>
								</ul>
								<ul class="detailed_list">
									<li>Deployment</li>
									<li>Domain Operations</li>
									<li>Cloud Hosting & PAAS</li>
									<li>Search Engine Optimisation</li>
									<li>Web Analytics and Tracking</li>
									<li>Entrepreneurship</li>
								</ul>
							</section>
						</div>
						<div class="row-fluid">
							<section class="course_details span6">
								<h4>What's the Schedule Like?</h4>
								<p>Check the application form for proposed dates. A typical weekly timeline is <strong>3 hours per day for 5 days a week for 11 weeks. These hours start at 5pm and end at 8pm.</strong> However these times are flexible and change according to student needs.</p>
							</section>
							<section class="course_details span6">
								<h4>Ready to go?</h4>
								<p>Tuition (AUD) can be paid with 2 options. Should you choose to terminate your course, you won't have any contractual obligations to pay the remainder of the fee. All prices are GST inclusive, however they may be payment charges if paying by credit card.</p>
								<div class="prices row-fluid">
									<ul class="price_list span6">
										<li>Upfront</li>
										<li>$4899</li>
									</ul>
									<ul class="price_list span6">
										<li>Weekly</li>
										<li>$459 per week</li>
									</ul>
								</div>
							</section>
						</div>
					</div>
				</article>
			</div>
		</div>
		<div class="red_container">
			<div class="container">
				<article class="course_application red_text_container" id="course_application">
					<h2>Application Form</h2>
					<? if(!empty($error_messages)){	?>
						<section class="form_errors">
							<h5>There are form errors, please check below:</h5>
							<ul>
								<?= $error_messages ?>
							</ul>
						</section>
					<? }elseif(!empty($success_message)){ ?>
						<h1 class="form_success"><?= $success_message ?></h1>
					<? }else{ ?>
						<h4>Becoming a web application entrepeneur is not easy. You need to be truly passionate and committed to developing your idea into reality.</h4>
						<p>Your information here will only be recorded for application purposes. If you are accepted into the course, we may offer your information to potential investors or employers.</p>
					<? } ?>
					<?= form_open($form_destination . '#course_application', array('class' => 'course_application_form form-horizontal')) ?>
						<h3>Personal Details <small>&lang;REQUIRED&rang;</small></h3>
						<div class="control-group">
							<?= form_label('Full Name', 'form_full_name', array('class' => 'control-label required')) ?>
							<div class="controls controls-row">
								<div class="input-append">
									<?= form_input(array('name' => 'full_name[first]', 'id' => 'form_first_name', 'class' => 'span2', 'placeholder' => 'First Name', 'value' => set_value('full_name[first]'))) ?>
									<span class="add-on"><i class="icon-user"></i></span>
								</div>
								<div class="input-append">
									<?= form_input(array('name' => 'full_name[last]', 'id' => 'form_last_name', 'class' => 'span2', 'placeholder' => 'Last Name', 'value' => set_value('full_name[last]'))) ?>
									<span class="add-on"><i class="icon-user"></i></span>
								</div>
							</div>
						</div>
						<div class="control-group">
							<?= form_label('Email', 'form_email', array('class' => 'control-label required')) ?>
							<div class="controls">
								<div class="input-append">
									<?= form_input(array('name' => 'email', 'id' => 'form_email', 'class' => 'span4', 'placeholder' => 'Make sure it\'s correct!', 'value' => set_value('email'))) ?>
									<span class="add-on"><i class="icon-inbox"></i></span>
								</div>
							</div>
						</div>
						<div class="control-group">
							<?= form_label('Skype ID', 'form_skype', array('class' => 'control-label required')) ?>
							<div class="controls">
								<div class="input-append">
									<?= form_input(array('name' => 'skype', 'id' => 'form_skype', 'class' => 'span4', 'placeholder' => 'For Interviews', 'value' => set_value('skype'))) ?>
									<span class="add-on"><i class="icon-eye-open"></i></span>
								</div>
							</div>
						</div>
						<h3>Preferences <small>&lang;REQUIRED&rang;</small></h3>
						<div class="control-group">
							<?= form_label('Tick all the courses you would prefer to get into. We\'ll try to get you into your most recent preference.', 'form_course', array('class' => 'control-label required')) ?>
							<div class="controls">
								<div class="span5">
									<table class="table table-bordered table-condensed">
										<colgroup>
											<col class="course_row_title" />
											<col class="course_row_input" />
										</colgroup>
										<thead>
											<tr>
												<th scope="col"><strong>11 Weeks Express</strong></th>
												<th scope="col"><i class="icon-ok icon-white"></i></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th scope="row">Term 1 - <?= $course_dates_table['ex_t1'] ?> to <?php echo $course_dates_table['ex_t1_end'] ?></th>
												<td><input id="form_course_ex_t1" type="checkbox" value="ex_t1" name="courses[]" disabled="disabled" <?= set_checkbox('courses[]', 'ex_t1') ?> /></td>
											</tr>
											<tr>
												<th scope="row">Term 2 - <?= $course_dates_table['ex_t2'] ?> to <?php echo $course_dates_table['ex_t2_end'] ?></th>
												<td><input id="form_course_ex_t2" type="checkbox" value="ex_t2" name="courses[]" <?= set_checkbox('courses[]', 'ex_t2') ?> /></td>
											</tr>
											<tr>
												<th scope="row">Term 3 - <?= $course_dates_table['ex_t3'] ?> to <?php echo $course_dates_table['ex_t3_end'] ?></th>
												<td><input id="form_course_ex_t3" type="checkbox" value="ex_t3" name="courses[]" <?= set_checkbox('courses[]', 'ex_t3') ?> /></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="control-group">
							<?= form_label('Do you agree to the Terms of Service, Privacy Policy and Refund Policy?', 'form_agreement', array('class' => 'control-label required')) ?>
							<div class="controls">
								<label class="checkbox" for="form_agreement_yes">
									<input type="checkbox" name="agreement" id="form_agreement_yes" value="yes" <?= set_checkbox('agreement', 'yes') ?> />
									I agree!
								</label>
								<span class="help-block">Check out the <?= anchor($links['terms_of_service'], 'TOS & Privacy Policy') ?> and <?= anchor($links['refund_policy'], 'Refund Policy') ?>.</span>
							</div>
						</div>
						<div class="control-group">
							<?= form_label('What do you want to build and get out of Polycademy?', 'form_build', array('class' => 'control-label required')) ?>
							<div class="controls">
								<?= form_textarea(array('name' => 'build', 'id' => 'form_build', 'class' => 'span5', 'placeholder' => 'Your idea is safe with us! Until it goes live of course...', 'value' => set_value('build'))) ?>
								<span class="help-block">Limit 400 words.</span>
							</div>
						</div>
						<div class="control-group">
							<?= form_label('Where did you here about us?', 'form_where', array('class' => 'control-label')) ?>
							<div class="controls">
								<?= form_textarea(array('name' => 'where', 'id' => 'form_where', 'class' => 'span5', 'placeholder' => 'We would like to know where you found out about us so we can better focus our marketing!', 'value' => set_value('where'))) ?>
								<span class="help-block">Limit 400 words.</span>
							</div>
						</div>
						<div class="control-group">
							<?= form_label('Are you awesome?', 'form_awesome', array('class' => 'control-label')) ?>
							<div class="controls">
								<label class="radio" for="form_awesome_yes">
									<?= form_radio(array('name' => 'awesome', 'id' => 'form_awesome_yes', 'value' => 'Yes!', 'checked' => 'checked')) ?>
									I am awesome.
								</label>
							</div>
						</div>
						<div class="form-actions">
							<?= form_button(array('name' => 'submit', 'class' => 'btn btn-primary', 'type' => 'submit', 'value' => 'true', 'content' => 'Submit Application')) ?>
						</div>
					<?= form_close() ?>
				</article>
			</div>
		</div>