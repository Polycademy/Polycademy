<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		<div class="blue_container">
			<div class="container">
				<article class="course_application blue_text_container" id="course_application">
					<h2>Showing Application #<?= $id ?></h2>
					<? if(!empty($no_data)){	?>
						<h3><?= $no_data ?></h3>
					<? }elseif(!empty($application)){ ?>
						<? if(!empty($error_messages)){	?>
							<section class="form_errors">
								<h5>There are form errors, please check below:</h5>
								<ul>
									<?= $error_messages ?>
								</ul>
							</section>
						<? }elseif(!empty($success_message)){ ?>
							<h4 class="form_success"><?= $success_message ?></h4>
						<? } ?>
						<?= form_open($form_destination, array('class' => 'course_application_form form-horizontal')) ?>
							<h5>Applied on <?= mdate('%D %j%S %M %Y - %H:%i HOURS', strtotime($application['app_date'])) ?></h5>
							<h3>Actions</h3>
							<div class="control-group">
								<?= form_label('Actions', 'form_actions', array('class' => 'control-label required')) ?>
								<div class="controls">
									<label class="checkbox inline" for="form_actions_accepted">
										<input type="checkbox" name="accepted" id="form_actions_accepted" value="yes" <?= ((set_checkbox('accepted', 'yes')) ? set_checkbox('accepted', 'yes') : (($application['accepted']) ? 'checked="checked"' : false)) ?> />
										Accepted!
									</label>
									<label class="checkbox inline" for="form_actions_paid">
										<input type="checkbox" name="paid" id="form_actions_paid" value="yes" <?= ((set_checkbox('paid', 'yes')) ? set_checkbox('paid', 'yes') : (($application['paid']) ? 'checked="checked"' : false)) ?> />
										Paid Deposit!
									</label>
									<label class="checkbox inline" for="form_actions_finished">
										<input type="checkbox" name="finished" id="form_actions_finished" value="yes" <?= ((set_checkbox('finished', 'yes')) ? set_checkbox('finished', 'yes') : (($application['finished']) ? 'checked="checked"' : false)) ?> />
										Finished!
									</label>
								</div>
							</div>
							<h3>Personal Details <small>&lang;REQUIRED&rang;</small></h3>
							<div class="control-group">
								<?= form_label('Full Name', 'form_full_name', array('class' => 'control-label required')) ?>
								<div class="controls controls-row">
									<div class="input-append">
										<?= form_input(array('name' => 'full_name', 'id' => 'form_full_name', 'class' => 'span4', 'placeholder' => 'Full Name', 'value' => ((set_value('full_name')) ? set_value('full_name') : $application['name']))) ?>
										<span class="add-on"><i class="icon-user"></i></span>
									</div>
								</div>
							</div>
							<div class="control-group">
								<?= form_label('Email', 'form_email', array('class' => 'control-label required')) ?>
								<div class="controls">
									<div class="input-append">
										<?= form_input(array('name' => 'email', 'id' => 'form_email', 'class' => 'span4', 'placeholder' => 'Make sure it\'s correct!', 'value' => ((set_value('email')) ? set_value('email') : $application['email']))) ?>
										<span class="add-on"><i class="icon-inbox"></i></span>
									</div>
								</div>
							</div>
							<div class="control-group">
								<?= form_label('Phone', 'form_phone', array('class' => 'control-label required')) ?>
								<div class="controls">
									<div class="input-append">
										<?= form_input(array('name' => 'phone', 'id' => 'form_phone', 'class' => 'span4', 'placeholder' => '+61...', 'value' => ((set_value('phone')) ? set_value('phone') : $application['phone']))) ?>
										<span class="add-on"><i class="icon-headphones"></i></span>
									</div>
								</div>
							</div>
							<div class="control-group">
								<?= form_label('Date of Birth', 'form_birth', array('class' => 'control-label required')) ?>
								<div class="controls">
									<div class="input-append date date_picker" data-date="<?= date('d-m-Y', strtotime('-15 years',now())) ?>" data-date-format="dd-mm-yyyy">
										<?= form_input(array('name' => 'birthday', 'id' => 'form_birth', 'class' => 'span4', 'placeholder' => 'DD-MM-YYYY (Use Cal Icon >)', 'value' => ((set_value('birthday')) ? set_value('birthday') : $application['birthday']),)) ?>
										<span class="add-on"><i class="icon-calendar"></i></span>
									</div>
								</div>
							</div>
							<div class="control-group">
								<?= form_label('Location', 'form_location', array('class' => 'control-label required')) ?>
								<div class="controls">
									<div class="input-append">
										<?= form_input(array('name' => 'location', 'id' => 'form_location', 'class' => 'span4', 'placeholder' => 'City - Country', 'value' => ((set_value('location')) ? set_value('location') : $application['location']))) ?>
										<span class="add-on"><i class="icon-map-marker"></i></span>
									</div>
								</div>
							</div>
							<div class="control-group">
								<?= form_label('Skype ID', 'form_skype', array('class' => 'control-label required')) ?>
								<div class="controls">
									<div class="input-append">
										<?= form_input(array('name' => 'skype', 'id' => 'form_skype', 'class' => 'span4', 'placeholder' => 'For Interviews', 'value' => ((set_value('skype')) ? set_value('skype') : $application['skype']))) ?>
										<span class="add-on"><i class="icon-eye-open"></i></span>
									</div>
								</div>
							</div>
							<h3>Preferences <small>&lang;REQUIRED&rang;</small></h3>
							<div class="control-group">
								<?= form_label('Are you interested in applying for the Code for Australia program?', 'form_cfa', array('class' => 'control-label required')) ?>
								<div class="controls">
									<label class="radio inline" for="form_cfa_yes">
										<input type="radio" name="cfa" id="form_cfa_yes" value="yes" <?= ((set_radio('cfa', 'yes')) ? set_radio('cfa', 'yes') : (($application['cfa']) ? 'checked="checked"' : false)) ?> />
										Yes
									</label>
									<label class="radio inline" for="form_cfa_no">
										<input type="radio" name="cfa" id="form_cfa_no" value="no" <?= ((set_radio('cfa', 'no')) ? set_radio('cfa', 'no') : ((!$application['cfa']) ? 'checked="checked"' : false)) ?> />
										No
									</label>
								</div>
							</div>
							<div class="control-group">
								<?= form_label('Tick all the courses you would prefer to get into. We\'ll try to get you into your most recent preference.', 'form_course', array('class' => 'control-label required')) ?>
								<div class="controls">
									<div class="row-fluid">
										<div class="span6">
											<table class="table table-bordered table-condensed">
												<colgroup>
													<col class="course_row_title" />
													<col class="course_row_input" />
												</colgroup>
												<thead>
													<tr>
														<th scope="col"><strong>21 Weeks Standard</strong></th>
														<th scope="col"><i class="icon-ok icon-white"></i></th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<th scope="row">Semester 1 Cohort 1 - <?= $course_dates_table['st_s1_c1'] ?> to <?php echo $course_dates_table['st_s1_c1_end'] ?></th>
														<td><input id="form_course_st_s1_c1" type="checkbox" value="st_s1_c1" name="courses[]" <?= ((set_checkbox('courses[]', 'st_s1_c1')) ? set_checkbox('courses[]', 'st_s1_c1') : ((in_array('st_s1_c1', $application['courses'])) ? 'checked="checked"' : false)) ?> /></td>
													</tr>
													<tr>
														<th scope="row">Semester 1 Cohort 2 - <?= $course_dates_table['st_s1_c2'] ?> to <?php echo $course_dates_table['st_s1_c2_end'] ?></th>
														<td><input id="form_course_st_s1_c2" type="checkbox" value="st_s1_c2" name="courses[]" <?= ((set_checkbox('courses[]', 'st_s1_c2')) ? set_checkbox('courses[]', 'st_s1_c2') : ((in_array('st_s1_c2', $application['courses'])) ? 'checked="checked"' : false)) ?> /></td>
													</tr>
													<tr>
														<th scope="row">Semester 2 Cohort 1 - <?= $course_dates_table['st_s2_c1'] ?> to <?php echo $course_dates_table['st_s2_c1_end'] ?></th>
														<td><input id="form_course_st_s2_c1" type="checkbox" value="st_s2_c1" name="courses[]" <?= ((set_checkbox('courses[]', 'st_s2_c1')) ? set_checkbox('courses[]', 'st_s2_c1') : ((in_array('st_s2_c1', $application['courses'])) ? 'checked="checked"' : false)) ?> /></td>
													</tr>
													<tr>
														<th scope="row">Semester 2 Cohort 2 - <?= $course_dates_table['st_s2_c2'] ?> to <?php echo $course_dates_table['st_s2_c2_end'] ?></th>
														<td><input id="form_course_st_s2_c2" type="checkbox" value="st_s2_c2" name="courses[]" <?= ((set_checkbox('courses[]', 'st_s2_c2')) ? set_checkbox('courses[]', 'st_s2_c2') : ((in_array('st_s2_c2', $application['courses'])) ? 'checked="checked"' : false)) ?> /></td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="span6">
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
														<td><input id="form_course_ex_t1" type="checkbox" value="ex_t1" name="courses[]" <?= ((set_checkbox('courses[]', 'ex_t1')) ? set_checkbox('courses[]', 'ex_t1') : ((in_array('ex_t1', $application['courses'])) ? 'checked="checked"' : false)) ?> /></td>
													</tr>
													<tr>
														<th scope="row">Term 2 - <?= $course_dates_table['ex_t2'] ?> to <?php echo $course_dates_table['ex_t2_end'] ?></th>
														<td><input id="form_course_ex_t2" type="checkbox" value="ex_t2" name="courses[]" <?= ((set_checkbox('courses[]', 'ex_t2')) ? set_checkbox('courses[]', 'ex_t2') : ((in_array('ex_t2', $application['courses'])) ? 'checked="checked"' : false)) ?> /></td>
													</tr>
													<tr>
														<th scope="row">Term 3 - <?= $course_dates_table['ex_t3'] ?> to <?php echo $course_dates_table['ex_t3_end'] ?></th>
														<td><input id="form_course_ex_t3" type="checkbox" value="ex_t3" name="courses[]" <?= ((set_checkbox('courses[]', 'ex_t3')) ? set_checkbox('courses[]', 'ex_t3') : ((in_array('ex_t3', $application['courses'])) ? 'checked="checked"' : false)) ?> /></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<div class="control-group">
								<?= form_label('Preferred Payment Options', 'form_payment_options', array('class' => 'control-label required')) ?>
								<div class="controls">
									<label class="radio inline" for="form_payment_options_upfront">
										<input type="radio" name="payment_options" id="form_payment_options_upfront" value="upfront" <?= ((set_radio('payment_options', 'upfront')) ? set_radio('payment_options', 'upfront') : (($application['payment_options'] == 'upfront') ? 'checked="checked"' : false)) ?> />
										Upfront
									</label>
									<label class="radio inline" for="form_payment_options_weekly">
										<input type="radio" name="payment_options" id="form_payment_options_weekly" value="weekly" <?= ((set_radio('payment_options', 'weekly')) ? set_radio('payment_options', 'weekly') : (($application['payment_options'] == 'weekly') ? 'checked="checked"' : false)) ?> />
										Weekly
									</label>
								</div>
							</div>
							<h3>Help us Help you! <small>&lang;REQUIRED&rang;</small></h3>
							<div class="control-group">
								<?= form_label('Education and Employment Milestones', 'form_education_employment', array('class' => 'control-label required')) ?>
								<div class="controls">
									<?= form_textarea(array('name' => 'education_employment', 'id' => 'form_education_employment', 'class' => 'span5', 'placeholder' => 'Year - Milestone', 'value' => ((set_value('education_employment')) ? set_value('education_employment') : $application['education_employment']))) ?>
									<span class="help-block">Limit 100 words.</span>
								</div>
							</div>
							<div class="control-group">
								<?= form_label('Work or Study Commitments', 'form_work_study', array('class' => 'control-label required')) ?>
								<div class="controls">
									<div class="input-append">
										<?= form_input(array('name' => 'work_study', 'id' => 'form_work_study', 'class' => 'span4', 'placeholder' => 'Make sure you have time!', 'value' => ((set_value('work_study')) ? set_value('work_study') : $application['work_study']))) ?>
										<span class="add-on"><i class="icon-time"></i></span>
									</div>
								</div>
							</div>
							<div class="control-group">
								<?= form_label('Technical Experience in Web Apps', 'form_experience', array('class' => 'control-label required')) ?>
								<div class="controls">
									<?= form_textarea(array('name' => 'experience', 'id' => 'form_experience', 'class' => 'span5', 'placeholder' => 'Name - Link - Function (No experience is fine too!)', 'value' => ((set_value('experience')) ? set_value('experience') : $application['experience']))) ?>
									<span class="help-block">Limit 200 words.</span>
								</div>
							</div>
							<div class="control-group">
								<?= form_label('What do you want to build and get out of Polycademy?', 'form_build', array('class' => 'control-label required')) ?>
								<div class="controls">
									<?= form_textarea(array('name' => 'build', 'id' => 'form_build', 'class' => 'span5', 'placeholder' => 'Your idea is safe with us! Until it goes live of course...', 'value' => ((set_value('build')) ? set_value('build') : $application['build']))) ?>
									<span class="help-block">Limit 400 words.</span>
								</div>
							</div>
							<div class="control-group">
								<?= form_label('Where did you here about us?', 'form_where', array('class' => 'control-label')) ?>
								<div class="controls">
									<?= form_textarea(array('name' => 'where', 'id' => 'form_where', 'class' => 'span5', 'placeholder' => 'We would like to know where you found out about us so we can better focus our marketing!', 'value' => ((set_value('where')) ? set_value('where') : $application['where']))) ?>
									<span class="help-block">Limit 400 words.</span>
								</div>
							</div>
							<div class="control-group">
								<?= form_label('Link to a video about yourself, teach or show us something you\'re good at!', 'form_video', array('class' => 'control-label')) ?>
								<div class="controls">
									<div class="input-append">
										<?= form_input(array('name' => 'video', 'id' => 'form_video', 'class' => 'span4', 'placeholder' => 'Youtube?', 'value' => ((set_value('video')) ? set_value('video') : $application['video']))) ?>
										<span class="add-on"><i class="icon-film"></i></span>
									</div>
									<span class="help-block">(optional)</span>
								</div>
							</div>
							<div class="form-actions">
								<?= form_button(array('name' => 'submit', 'class' => 'btn btn-primary', 'type' => 'submit', 'value' => 'true', 'content' => 'Submit Application')) ?>
							</div>
						<?= form_close() ?>
					<? } ?>
				</article>
			</div>
		</div>