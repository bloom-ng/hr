<div class="content-wrapper bg-[#3E3E3E]">
    <div class="content-header">
        <h1 class="m-0 text-dark">View Appraisal (2026)</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item active">View Appraisal</li>
        </ol>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                        <div class="box-header">
                            <h3 class="box-title">Employee Information</h3>
                        </div>
                        <?php 
                            $role = $this->session->userdata('role');
                            $is_staff_replied = ($appraisal['status'] == 'staff_replied');
                            $is_super = ($role == 'super');
                            $is_hr = ($role == 'hrm');
                            $is_super_or_hr = in_array($role, ['hrm', 'super']);
                            $is_pending = ($appraisal['status'] == 'pending');
                            
                            // Determine form action
                            $form_action = '#';
                            if ($is_super_or_hr && $is_pending) {
                                $form_action = base_url('appraisal_new/hr_approve/'.$appraisal['id']);
                            }

                            if($appraisal['status'] == 'staff_replied' && $this->session->userdata('role') == 'super') {
                                $form_action = base_url('appraisal_new/super_approve/'.$appraisal['id']);
                            }
                        ?>
                        <form role="form" action="<?php echo $form_action; ?>" method="post">
                            <div class="box-body">
                                <div class="row">
                                    <input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
                                    <input type="hidden" name="department_id" value="<?php echo $department['id']; ?>">
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Employee Name</label>
                                            <input type="text" class="form-control" value="<?php echo $staff['staff_name']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Department</label>
                                            <input type="text" class="form-control" value="<?php echo $department['department_name']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Position</label>
                                            <input type="text" class="form-control" name="position" value="<?php echo $appraisal['position']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Month Under Review</label>
                                            <input type="month" class="form-control" name="month_under_review" value="<?php echo $appraisal['month_under_review']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <h4 class="mt-4 mb-3">SECTION 1: PERFORMANCE CATEGORIES</h4>
                                
                                <?php 
                                $categories = [
                                    ['key' => 'teamwork', 'label' => '1. Teamwork & Collaboration', 'desc' => 'Consider: cooperation, conflict resolution, willingness to assist, reliability as a team member.'],
                                    ['key' => 'communication', 'label' => '2. Communication Skills', 'desc' => 'Consider: responsiveness, tone, client communication, reporting accuracy, proactive updates.'],
                                    ['key' => 'quality', 'label' => '3. Quality of Work', 'desc' => 'Consider: attention to detail, level of revisions needed, alignment with Bloom quality standards.'],
                                    ['key' => 'timeliness', 'label' => '5. Time Management & Timeliness', 'desc' => 'Evaluate punctuality, task delivery speed, response times, and ability to meet deadlines.'], 
                                    ['key' => 'innovation', 'label' => '6. Innovation, Initiative & Growth', 'desc' => 'Assess creativity, problem-solving ability, willingness to introduce new ideas, commitment to personal development.'],
                                    ['key' => 'professionalism', 'label' => '7. Professionalism & Values Fit', 'desc' => 'Evaluate adherence to Bloom’s culture, policies, conduct, dress code, attitude, accountability.']
                                ];
                                ?>
                                
                                <?php foreach($categories as $cat): ?>
                                <div class="form-group border p-3 rounded">
                                    <label><strong><?php echo $cat['label']; ?></strong></label>
                                    <p class="text-muted small"><?php echo $cat['desc']; ?></p>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>Rating (1-10)</label>
                                            <input type="number" class="form-control" name="rating_<?php echo $cat['key']; ?>" value="<?php echo $appraisal['rating_'.$cat['key']]; ?>" min="1" max="10" readonly>
                                        </div>
                                        <div class="col-md-10">
                                            <label>Comments</label>
                                            <textarea class="form-control" name="comment_<?php echo $cat['key']; ?>" rows="2" readonly><?php echo $appraisal['comment_'.$cat['key']]; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>

                                <h4 class="mt-4 mb-3">4. Key Performance Areas (KPAs)</h4>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>KPI / Task Category</th>
                                            <th>Description</th>
                                            <th>Expected Output</th>
                                            <th>Actual Output</th>
                                            <th>Rating (1-10)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $kpas = isset($appraisal['kpas']) ? $appraisal['kpas'] : [];
                                        $count = max(4, count($kpas));
                                        for($i=0; $i<$count; $i++): 
                                            $kpa = isset($kpas[$i]) ? $kpas[$i] : null;
                                        ?>
                                        <tr>
                                            <td><input type="text" class="form-control" name="kpa_category[]" value="<?php echo $kpa ? $kpa['category'] : ''; ?>" readonly></td>
                                            <td><input type="text" class="form-control" name="kpa_description[]" value="<?php echo $kpa ? $kpa['description'] : ''; ?>" readonly></td>
                                            <td><input type="text" class="form-control" name="kpa_expected[]" value="<?php echo $kpa ? $kpa['expected_output'] : ''; ?>" readonly></td>
                                            <td><input type="text" class="form-control" name="kpa_actual[]" value="<?php echo $kpa ? $kpa['actual_output'] : ''; ?>" readonly></td>
                                            <td><input type="number" class="form-control" name="kpa_rating[]" min="1" max="10" value="<?php echo $kpa ? $kpa['rating'] : ''; ?>" readonly></td>
                                        </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>

                                <h4 class="mt-4 mb-3">SECTION 2: TASK TRACKING</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Number of Tasks Assigned</label>
                                            <input type="number" class="form-control" name="tasks_assigned" id="tasks_assigned" value="<?php echo $appraisal['tasks_assigned']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Number of Tasks Completed</label>
                                            <input type="number" class="form-control" name="tasks_completed" id="tasks_completed" value="<?php echo $appraisal['tasks_completed']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Completion Rate (%)</label>
                                            <input type="text" class="form-control" name="completion_rate" id="completion_rate" value="<?php echo $appraisal['completion_rate']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Accuracy/Revision Rate</label>
                                            <input type="text" class="form-control" value="<?php echo $appraisal['accuracy_rate']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="mt-4 mb-3">SECTION 3: PERFORMANCE SUMMARY</h4>
                                <?php 
                                    $strengths = json_decode($appraisal['strengths'], true) ?: [];
                                    $weaknesses = json_decode($appraisal['weaknesses'], true) ?: [];
                                    $training_needs = json_decode($appraisal['training_needs'], true) ?: [];
                                    $goals = json_decode($appraisal['next_month_goals'], true) ?: [];
                                ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Employee Strengths</label>
                                        <?php for($i=0; $i<3; $i++): ?>
                                            <input type="text" class="form-control mb-2" name="strengths[]" placeholder="Strength <?php echo $i+1; ?>" value="<?php echo isset($strengths[$i]) ? $strengths[$i] : ''; ?>" readonly>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Weaknesses / Areas for Improvement</label>
                                        <?php for($i=0; $i<3; $i++): ?>
                                            <input type="text" class="form-control mb-2" name="weaknesses[]" placeholder="Weakness <?php echo $i+1; ?>" value="<?php echo isset($weaknesses[$i]) ? $weaknesses[$i] : ''; ?>" readonly>
                                        <?php endfor; ?>
                                    </div>
                                </div>

                                <h4 class="mt-4 mb-3">SECTION 4: TRAINING & DEVELOPMENT NEEDS</h4>
                                <?php for($i=0; $i<3; $i++): ?>
                                    <input type="text" class="form-control mb-2" name="training_needs[]" placeholder="Training Need <?php echo $i+1; ?>" value="<?php echo isset($training_needs[$i]) ? $training_needs[$i] : ''; ?>" readonly>
                                <?php endfor; ?>

                                <h4 class="mt-4 mb-3">SECTION 5: Goals & Objectives for Next Month</h4>
                                <?php for($i=0; $i<3; $i++): ?>
                                    <input type="text" class="form-control mb-2" name="next_month_goals[]" placeholder="Goal <?php echo $i+1; ?>" value="<?php echo isset($goals[$i]) ? $goals[$i] : ''; ?>" readonly>
                                <?php endfor; ?>

                                <h4 class="mt-4 mb-3">SECTION 6: REMARKS</h4>
                                <div class="form-group">
                                    <label>HoD’s Comment (Overall appraisal, concerns, commendations, expectations)</label>
                                    <textarea class="form-control" name="hod_remarks" rows="3" readonly><?php echo $appraisal['hod_remarks']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="employee_remarks">Employee/Subordinate Remarks</label>
                                    <textarea class="form-control" name="employee_remarks" id="employee_remarks" rows="3" readonly><?php echo $appraisal['employee_remarks']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="hr_remarks">HR Approval/Remarks</label>
                                    <textarea class="form-control" name="hr_remarks" id="hr_remarks" rows="3" <?php echo $is_super_or_hr ? '' : 'readonly'; ?>><?php echo $appraisal['hr_remarks']; ?></textarea>
                                </div>

                            </div>
                            <div class="card-footer px-4 py-2">
                                <?php if ($is_super_or_hr && $is_pending): ?>
                                    <button type="submit" class="btn btn-success">Approve</button>
                                <?php endif; ?>
                                <?php if ($is_super && $is_staff_replied): ?>
                                    <button type="submit" class="btn btn-success">Finalize</button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
