<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		<div class="blue_container">
			<div class="container">
				<article class="course_funnel blue_text_container">
					<h2>Current Applications</h2>
					<? if(!empty($filters)){ ?>
						<p>
							Showing Filters for:
							<span class="filters">
								<? foreach($filters as $key => $filter){ ?>
									&lang;<?= $key . ' => ' . $filter ?>&rang;
								<? } ?>
							</span>
						</p>
					<? } ?>
					<? if(!empty($error_messages)){	?>
						<h3><?= $error_message ?></h3>
					<? }elseif(!empty($applications)){ ?>
						<table class="table table-bordered table-striped table-condensed">
							<thead>
								<tr>
									<th scope="col"><strong>ID</strong></th>
									<th scope="col"><strong>DATE</strong></th>
									<th scope="col"><strong>NAME</strong></th>
									<th scope="col"><strong>EMAIL</strong></th>
									<th scope="col"><strong>COURSES</strong></th>
									<th scope="col"><strong>ACCEPTED</strong></th>
									<th scope="col"><strong>PAID</strong></th>
									<th scope="col"><strong>FINISHED</strong></th>
								</tr>
							</thead>
							<tbody>
								<? foreach($applications as $application){ ?>
									<tr>
										<th scope="row"><?= anchor('course_funnel/id/' . $application['id'], $application['id']) ?></th>
										<td><?= mdate('%D %j%S %M %Y - %H:%i HOURS', strtotime($application['app_date'])) ?></td>
										<td><?= $application['name'] ?></td>
										<td><?= $application['email'] ?></td>
										<td>
											<? foreach($application['courses'] as $course){ ?>
												<span class="course_name"><?= $course?></span>
											<? } ?>
										</td>
										<td><?= $application['accepted'] ?></td>
										<td><?= $application['paid'] ?></td>
										<td><?= $application['finished'] ?></td>
									</tr>
								<? } ?>
							</tbody>
						</table>
					<? } ?>
				</article>
			</div>
		</div>