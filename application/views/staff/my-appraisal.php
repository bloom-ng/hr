<!-- File: application/views/add_appraisal.php -->
<div class="content-wrapper bg-neutral-800">
	<!-- Content Header (Page header) -->
	<section class="content-header bg-neutral-800">
		<h1>Edit Appraisal</h1>
		<ol class="breadcrumb text-white">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Appraisals</a></li>
			<li class="active">Edit Appraisal</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-info bg-neutral-800">
					<div class="box-header">
						<h3 class="box-title text-white">Edit Appraisal</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<?php echo form_open('appraisal/self_appraisal/' . $appraisal['id']); ?>
						<div class="form-group">
							<label for="name">Name:</label>
							<input type="text" class="form-control" id="name" name="name" value="<?php echo $appraisal["name"]; ?>" readonly>
							<input type="text" class="form-control" id="staff_id" name="staff_id" value="<?php echo $appraisal["staff_id"]; ?>" hidden>
							<input type="text" class="form-control" id="created_by" name="created_by" value="<?php echo $appraisal['created_by'] ?>" hidden>
						</div>
						<div class="form-group">
							<label for="job_title">Job Title:</label>
							<input type="text" class="form-control" id="job_title" name="job_title" value="<?php echo $appraisal["job_title"]; ?>" readonly>
						</div>
						<div class="form-group">
							<label for="department_id">Department:</label>
							<input type="text" class="form-control" id="department_name" name="department_name" value="<?php echo $appraisal["department_name"]; ?>" readonly>
							<input type="text" class="form-control" id="department_id" name="department_id" value="<?php echo $appraisal["department_id"]; ?>" hidden>
						</div>
						<div class="form-group">
							<label for="date">Date (Month/Year):</label>
							<input type="month" class="form-control" id="date" name="date" value="<?php echo date('Y-m', strtotime($appraisal['date'])); ?>" readonly>
						</div>

						<!-- Overall Performance Rating -->
						<h3>I. Overall Performance Rating</h3>
						<!-- Include radio buttons for overall performance rating here -->

						<!-- Key Performance Areas -->
						<h3>II. Key Performance Areas</h3>
						<h4>A. Job Knowledge and Skills:</h4>
						<div class="form-group">
							<label for="job_knowledge">Tick a box and leave a remark:</label><br>
							<input type="radio" id="job_knowledge1" name="job_knowledge" value="1" <?php echo ($appraisal['job_knowledge'] == '1') ? 'checked' : ''; ?>>
							<label for="job_knowledge1">Exceptional</label><br>
							<input type="radio" id="job_knowledge2" name="job_knowledge" value="2" <?php echo ($appraisal['job_knowledge'] == '2') ? 'checked' : ''; ?>>
							<label for="job_knowledge2">Proficient</label><br>
							<input type="radio" id="job_knowledge3" name="job_knowledge" value="3" <?php echo ($appraisal['job_knowledge'] == '3') ? 'checked' : ''; ?>>
							<label for="job_knowledge3">Needs Improvement</label><br>
							<textarea id="job_knowledge_comment" name="job_knowledge_comment" class="form-control" placeholder="Remark"><?php echo $appraisal['job_knowledge_comment']; ?></textarea>
						</div>

						<!-- Quality of Work -->
						<h4>B. Quality of Work:</h4>
						<div class="form-group">
							<label for="quality_of_work">Tick a box and leave a remark:</label><br>
							<input type="radio" id="quality_of_work1" name="quality_of_work" value="1" <?php echo ($appraisal['quality_of_work'] == '1') ? 'checked' : ''; ?>>
							<label for="quality_of_work1">Outstanding</label><br>
							<input type="radio" id="quality_of_work2" name="quality_of_work" value="2" <?php echo ($appraisal['quality_of_work'] == '2') ? 'checked' : ''; ?>>
							<label for="quality_of_work2">Satisfactory</label><br>
							<input type="radio" id="quality_of_work3" name="quality_of_work" value="3" <?php echo ($appraisal['quality_of_work'] == '3') ? 'checked' : ''; ?>>
							<label for="quality_of_work3">Unsatisfactory</label><br>
							<textarea id="quality_of_work_comment" name="quality_of_work_comment" class="form-control" placeholder="Remark"><?php echo $appraisal['quality_of_work_comment']; ?></textarea>
						</div>

						<!-- Communication Skills -->
						<h4>C. Communication Skills:</h4>
						<div class="form-group">
							<label for="communication_skills">Tick a box and leave a remark:</label><br>
							<input type="radio" id="communication_skills1" name="communication_skills" value="1" <?php echo ($appraisal['communication_skills'] == '1') ? 'checked' : ''; ?>>
							<label for="communication_skills1">Excellent</label><br>
							<input type="radio" id="communication_skills2" name="communication_skills" value="2" <?php echo ($appraisal['communication_skills'] == '2') ? 'checked' : ''; ?>>
							<label for="communication_skills2">Good</label><br>
							<input type="radio" id="communication_skills3" name="communication_skills" value="3" <?php echo ($appraisal['communication_skills'] == '3') ? 'checked' : ''; ?>>
							<label for="communication_skills3">Needs Improvement</label><br>
							<textarea id="communication_skills_comment" name="communication_skills_comment" class="form-control" placeholder="Remark"><?php echo $appraisal['communication_skills_comment']; ?></textarea>
						</div>

						<!-- Teamwork and Collaboration -->
						<h4>D. Teamwork and Collaboration:</h4>
						<div class="form-group">
							<label for="teamwork_collaboration">Tick a box and leave a remark:</label><br>
							<input type="radio" id="teamwork_collaboration1" name="teamwork_collaboration" value="1" <?php echo ($appraisal['teamwork_collaboration'] == '1') ? 'checked' : ''; ?>>
							<label for="teamwork_collaboration1">Strong</label><br>
							<input type="radio" id="teamwork_collaboration2" name="teamwork_collaboration" value="2" <?php echo ($appraisal['teamwork_collaboration'] == '2') ? 'checked' : ''; ?>>
							<label for="teamwork_collaboration2">Average</label><br>
							<input type="radio" id="teamwork_collaboration3" name="teamwork_collaboration" value="3" <?php echo ($appraisal['teamwork_collaboration'] == '3') ? 'checked' : ''; ?>>
							<label for="teamwork_collaboration3">Weak/Needs Improvement</label><br>
							<textarea id="teamwork_collaboration_comment" name="teamwork_collaboration_comment" class="form-control" placeholder="Remark"><?php echo $appraisal['teamwork_collaboration_comment']; ?></textarea>
						</div>


						<!-- Goals and Objectives -->
						<h3>III. Goals and Objectives</h3>
						<<!-- Achievement of Goals -->
						<div class="form-group">
							<label>A. Achievement of Goals:</label><br>
							<label class="radio-inline">
								<input type="radio" name="achievement_of_goals" value="1" <?php echo ($appraisal['achievement_of_goals'] == '1') ? 'checked' : ''; ?>> Exceeded Expectations
							</label>
							<label class="radio-inline">
								<input type="radio" name="achievement_of_goals" value="2" <?php echo ($appraisal['achievement_of_goals'] == '2') ? 'checked' : ''; ?>> Met Expectations
							</label>
							<label class="radio-inline">
								<input type="radio" name="achievement_of_goals" value="3" <?php echo ($appraisal['achievement_of_goals'] == '3') ? 'checked' : ''; ?>> Partially Met Expectations
							</label>
							<label class="radio-inline">
								<input type="radio" name="achievement_of_goals" value="4" <?php echo ($appraisal['achievement_of_goals'] == '4') ? 'checked' : ''; ?>> Did not meet Expectations
							</label>
							<label>Why?:</label>
							<input type="text" class="form-control" name="achievement_of_goals_reason" value="<?php echo $appraisal['achievement_of_goals_reason']; ?>">
						</div>

						<!-- Completion of Projects/Tasks -->
						<div class="form-group">
							<label>B. Completion of Projects/Tasks:</label><br>
							<label>How many were assigned:</label>
							<input type="text" class="form-control" name="assigned_projects_count" value="<?php echo $appraisal['assigned_projects_count']; ?>"><br>
							<label>How many were completed:</label>
							<input type="text" class="form-control" name="completed_projects_count" value="<?php echo $appraisal['completed_projects_count']; ?>"><br>
							<label>Outcome:</label><br>
							<label class="radio-inline">
								<input type="radio" name="completion_of_projects_outcome" value="1" <?php echo ($appraisal['completion_of_projects_outcome'] == '1') ? 'checked' : ''; ?>> Successful
							</label>
							<label class="radio-inline">
								<input type="radio" name="completion_of_projects_outcome" value="2" <?php echo ($appraisal['completion_of_projects_outcome'] == '2') ? 'checked' : ''; ?>> Partial Success
							</label>
							<label class="radio-inline">
								<input type="radio" name="completion_of_projects_outcome" value="3" <?php echo ($appraisal['completion_of_projects_outcome'] == '3') ? 'checked' : ''; ?>> Unsuccessful
							</label>
							<label>State why?:</label>
							<input type="text" class="form-control" name="completion_of_projects_reason" value="<?php echo $appraisal['completion_of_projects_reason']; ?>">
						</div>

						<!-- Strengths -->
						<h3>IV. Strengths</h3>
						<div class="form-group">
							<label>Strengths:</label><br>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="outstanding_job_knowledge" value="true" <?php echo ($appraisal['outstanding_job_knowledge'] == 'true') ? 'checked' : ''; ?>> Outstanding Job Knowledge
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="effective_communication" value="true" <?php echo ($appraisal['effective_communication'] == 'true') ? 'checked' : ''; ?>> Effective Communication
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="strong_team_player" value="true" <?php echo ($appraisal['strong_team_player'] == 'true') ? 'checked' : ''; ?>> Strong Team Player
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="innovative_thinking" value="true" <?php echo ($appraisal['innovative_thinking'] == 'true') ? 'checked' : ''; ?>> Innovative Thinking
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="adaptable_to_change" value="true" <?php echo ($appraisal['adaptable_to_change'] == 'true') ? 'checked' : ''; ?>> Adaptable to Change
								</label>
							</div>
						</div>


						<!-- Areas for Improvement -->
						<h3>V. Areas for Improvement</h3>
						<div class="form-group">
							<label>Areas for Improvement:</label><br>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="time_management" value="true" <?php echo ($appraisal['time_management'] == 'true') ? 'checked' : ''; ?>> Time Management
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="conflict_resolution" value="true" <?php echo ($appraisal['conflict_resolution'] == 'true') ? 'checked' : ''; ?>> Conflict Resolution
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="technical_skills_enhancement" value="true" <?php echo ($appraisal['technical_skills_enhancement'] == 'true') ? 'checked' : ''; ?>> Technical Skills Enhancement
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="goal_setting_and_achievement" value="true" <?php echo ($appraisal['goal_setting_and_achievement'] == 'true') ? 'checked' : ''; ?>> Goal Setting and Achievement
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="communication_with_team_members" value="true" <?php echo ($appraisal['communication_with_team_members'] == 'true') ? 'checked' : ''; ?>> Communication with Team Members
								</label>
							</div>
						</div>


						<!-- Training and Development Needs -->
						<h3>VI. Training and Development Needs</h3>
						<div class="form-group">
							<label>Training and Development Needs:</label><br>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="leadership_training" value="true" <?php echo ($appraisal['leadership_training'] == 'true') ? 'checked' : ''; ?>> Leadership Training
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="technical_skills_training" value="true" <?php echo ($appraisal['technical_skills_training'] == 'true') ? 'checked' : ''; ?>> Technical Skills Training
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="communication_skills_workshop" value="true" <?php echo ($appraisal['communication_skills_workshop'] == 'true') ? 'checked' : ''; ?>> Communication Skills Workshop
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="project_management_training" value="true" <?php echo ($appraisal['project_management_training'] == 'true') ? 'checked' : ''; ?>> Project Management Training
								</label>
							</div>
						</div>


						<!-- Additional Comments -->
						<div class="form-group">
							<label for="additional_comments">VII. Additional Comments:</label>
							<textarea id="additional_comments" name="additional_comments" class="form-control" placeholder="Provide specific comments and feedback."><?php echo $appraisal['additional_comments']; ?></textarea>
						</div>

						<!-- Employee's Self-Assessment -->
						<div class="form-group">
							<label for="employee_self_assessment">VIII. Employee's Self-Assessment:</label>
							<textarea id="employee_self_assessment" name="employee_self_assessment" class="form-control" placeholder="Provide space for the employee to share their self-assessment."><?php echo $appraisal['employee_self_assessment']; ?></textarea>
						</div>

						<!-- Manager's Comments -->
						<div class="form-group">
							<label for="manager_comments">IX. Manager's Comments:</label>
							<textarea id="manager_comments" name="manager_comments" class="form-control" placeholder="Provide space for the manager to provide overall comments and feedback."><?php echo $appraisal['manager_comments']; ?></textarea>
						</div>

						<!-- Action Plan for Improvement -->
						<div class="form-group">
							<label for="action_plan_for_improvement">X. Action Plan for Improvement:</label>
							<textarea id="action_plan_for_improvement" name="action_plan_for_improvement" class="form-control" placeholder="Outline specific steps and goals for the employee's improvement." value="<?php echo $appraisal['action_plan_for_improvement']; ?>"></textarea>
						</div>

						<!-- Follow-Up Meeting Schedule -->
						<div class="form-group">
							<label for="follow_up_meeting_schedule">XI. Follow-Up Meeting Schedule:</label>
							<input type="date" class="form-control" id="follow_up_meeting_schedule" name="follow_up_meeting_schedule" placeholder="Specify dates for follow-up meetings to track progress." value="<?php echo $appraisal['follow_up_meeting_schedule']; ?>">
						</div>
						<?php if ($appraisal['status'] == 'approved' && $appraisal['staff_id'] == $this->session->userdata('userid')) :?>
							<button type="submit" class="btn btn-primary text-blue-500">Submit</button>
						<?php endif; ?>

						<?php echo form_close(); ?>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper --
