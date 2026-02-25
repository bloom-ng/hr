<div class="content-wrapper bg-[#3E3E3E]">
    <div class="content-header">
        <h1 class="m-0 text-dark">Edit Appraisal (2026)</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item active">Edit Appraisal</li>
        </ol>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if ($this->session->flashdata('success')) : ?>
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> Success!</h4>
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    </div>
                <?php elseif ($this->session->flashdata('error')) : ?>
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> Failed!</h4>
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-md-12">
                    <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                        <div class="box-header">
                            <h3 class="box-title">Employee Information</h3>
                        </div>
                        <form role="form" action="<?php echo base_url('appraisal_new/update/'.$appraisal['id']); ?>" method="post">
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
                                            <input type="text" class="form-control" name="position" value="<?php echo $appraisal['position']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Month Under Review</label>
                                            <input type="month" class="form-control" name="month_under_review" value="<?php echo $appraisal['month_under_review']; ?>" required>
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
                                            <input type="number" class="form-control" name="rating_<?php echo $cat['key']; ?>" value="<?php echo $appraisal['rating_'.$cat['key']]; ?>" min="1" max="10" required>
                                        </div>
                                        <div class="col-md-10">
                                            <label>Comments</label>
                                            <textarea class="form-control" name="comment_<?php echo $cat['key']; ?>" rows="2"><?php echo $appraisal['comment_'.$cat['key']]; ?></textarea>
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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="kpa-tbody">
                                        <?php 
                                        $kpas = isset($appraisal['kpas']) ? $appraisal['kpas'] : [];
                                        $count = max(4, count($kpas));
                                        for($i=0; $i<$count; $i++): 
                                            $kpa = isset($kpas[$i]) ? $kpas[$i] : null;
                                        ?>
                                        <tr>
                                            <td><input type="text" class="form-control" name="kpa_category[]" value="<?php echo $kpa ? htmlspecialchars($kpa['category']) : ''; ?>"></td>
                                            <td><input type="text" class="form-control" name="kpa_description[]" value="<?php echo $kpa ? htmlspecialchars($kpa['description']) : ''; ?>"></td>
                                            <td><input type="text" class="form-control" name="kpa_expected[]" value="<?php echo $kpa ? htmlspecialchars($kpa['expected_output']) : ''; ?>"></td>
                                            <td><input type="text" class="form-control" name="kpa_actual[]" value="<?php echo $kpa ? htmlspecialchars($kpa['actual_output']) : ''; ?>"></td>
                                            <td><input type="number" class="form-control" name="kpa_rating[]" min="1" max="10" value="<?php echo $kpa ? $kpa['rating'] : ''; ?>"></td>
                                            <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove();">X</button></td>
                                        </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-sm btn-secondary mb-4" onclick="addKpaRow()">+ Add KPA Row</button>

                                <h4 class="mt-4 mb-3">SECTION 2: TASK TRACKING</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Number of Tasks Assigned</label>
                                            <input type="number" class="form-control" name="tasks_assigned" id="tasks_assigned" value="<?php echo $appraisal['tasks_assigned']; ?>" oninput="calculateCompletion()">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Number of Tasks Completed</label>
                                            <input type="number" class="form-control" name="tasks_completed" id="tasks_completed" value="<?php echo $appraisal['tasks_completed']; ?>" oninput="calculateCompletion()">
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
                                            <select class="form-control" name="accuracy_rate">
                                                <option value="Excellent" <?php echo ($appraisal['accuracy_rate'] == 'Excellent') ? 'selected' : ''; ?>>Excellent</option>
                                                <option value="Good" <?php echo ($appraisal['accuracy_rate'] == 'Good') ? 'selected' : ''; ?>>Good</option>
                                                <option value="Fair" <?php echo ($appraisal['accuracy_rate'] == 'Fair') ? 'selected' : ''; ?>>Fair</option>
                                                <option value="Needs Improvement" <?php echo ($appraisal['accuracy_rate'] == 'Needs Improvement') ? 'selected' : ''; ?>>Needs Improvement</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="mt-4 mb-3">SECTION 3: PERFORMANCE SUMMARY</h4>
                                <?php 
                                    $raw_strengths = json_decode($appraisal['strengths'], true) ?: [];
                                    $strengths = isset($raw_strengths['selections']) ? $raw_strengths['selections'] : ((is_array($raw_strengths) && !isset($raw_strengths['selections']) && !isset($raw_strengths['comment'])) ? $raw_strengths : []);
                                    $strength_comment = isset($raw_strengths['comment']) ? $raw_strengths['comment'] : '';

                                    $raw_weaknesses = json_decode($appraisal['weaknesses'], true) ?: [];
                                    $weaknesses = isset($raw_weaknesses['selections']) ? $raw_weaknesses['selections'] : ((is_array($raw_weaknesses) && !isset($raw_weaknesses['selections']) && !isset($raw_weaknesses['comment'])) ? $raw_weaknesses : []);
                                    $weakness_comment = isset($raw_weaknesses['comment']) ? $raw_weaknesses['comment'] : '';

                                    $raw_training = json_decode($appraisal['training_needs'], true) ?: [];
                                    $training_needs = isset($raw_training['selections']) ? $raw_training['selections'] : ((is_array($raw_training) && !isset($raw_training['selections']) && !isset($raw_training['comment'])) ? $raw_training : []);
                                    $training_comment = isset($raw_training['comment']) ? $raw_training['comment'] : '';

                                    $goals = json_decode($appraisal['next_month_goals'], true) ?: [];
                                ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Employee Strengths</label>
                                        <div id="strengths-container">
                                            <?php 
                                            $count = max(3, count($strengths));
                                            for($i=0; $i<$count; $i++): 
                                                $val = isset($strengths[$i]) ? $strengths[$i] : '';
                                            ?>
                                            <select class="form-control mb-2" name="strengths[]">
                                                <option value="">Select Strength</option>
                                                <option value="Effective Communication" <?php echo ($val == 'Effective Communication') ? 'selected' : ''; ?>>Effective Communication</option>
                                                <option value="Outstanding knowledge of the job" <?php echo ($val == 'Outstanding knowledge of the job') ? 'selected' : ''; ?>>Outstanding knowledge of the job</option>
                                                <option value="Strong Team Player" <?php echo ($val == 'Strong Team Player') ? 'selected' : ''; ?>>Strong Team Player</option>
                                                <option value="Innovative Thinking" <?php echo ($val == 'Innovative Thinking') ? 'selected' : ''; ?>>Innovative Thinking</option>
                                                <option value="Adaptable to Change" <?php echo ($val == 'Adaptable to Change') ? 'selected' : ''; ?>>Adaptable to Change</option>
                                                <option value="Willingness to learn & improve" <?php echo ($val == 'Willingness to learn & improve') ? 'selected' : ''; ?>>Willingness to learn & improve</option>
                                                <?php if($val && !in_array($val, ['Effective Communication', 'Outstanding knowledge of the job', 'Strong Team Player', 'Innovative Thinking', 'Adaptable to Change', 'Willingness to learn & improve'])): ?>
                                                    <option value="<?php echo htmlspecialchars($val); ?>" selected><?php echo htmlspecialchars($val); ?></option>
                                                <?php endif; ?>
                                            </select>
                                            <?php endfor; ?>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addDropdown('strengths-container', 'strengths[]', getStrengthOptions())">+ Add Strength</button>
                                        <textarea class="form-control mb-3" name="strength_comment" placeholder="Further comments on strengths..." rows="2"><?php echo htmlspecialchars($strength_comment); ?></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Weaknesses / Areas for Improvement</label>
                                        <div id="weaknesses-container">
                                            <?php 
                                            $count = max(3, count($weaknesses));
                                            for($i=0; $i<$count; $i++): 
                                                $val = isset($weaknesses[$i]) ? $weaknesses[$i] : '';
                                            ?>
                                            <select class="form-control mb-2" name="weaknesses[]">
                                                <option value="">Select Weakness</option>
                                                <option value="Time Management" <?php echo ($val == 'Time Management') ? 'selected' : ''; ?>>Time Management</option>
                                                <option value="Conflict Resolution" <?php echo ($val == 'Conflict Resolution') ? 'selected' : ''; ?>>Conflict Resolution</option>
                                                <option value="Technical Skills Enhancement" <?php echo ($val == 'Technical Skills Enhancement') ? 'selected' : ''; ?>>Technical Skills Enhancement</option>
                                                <option value="Goal Setting and Achievement" <?php echo ($val == 'Goal Setting and Achievement') ? 'selected' : ''; ?>>Goal Setting and Achievement</option>
                                                <option value="Communication with Team Members" <?php echo ($val == 'Communication with Team Members') ? 'selected' : ''; ?>>Communication with Team Members</option>
                                                <?php if($val && !in_array($val, ['Time Management', 'Conflict Resolution', 'Technical Skills Enhancement', 'Goal Setting and Achievement', 'Communication with Team Members'])): ?>
                                                    <option value="<?php echo htmlspecialchars($val); ?>" selected><?php echo htmlspecialchars($val); ?></option>
                                                <?php endif; ?>
                                            </select>
                                            <?php endfor; ?>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addDropdown('weaknesses-container', 'weaknesses[]', getWeaknessOptions())">+ Add Weakness</button>
                                        <textarea class="form-control mb-3" name="weakness_comment" placeholder="Further comments on weaknesses..." rows="2"><?php echo htmlspecialchars($weakness_comment); ?></textarea>
                                    </div>
                                </div>

                                <h4 class="mt-4 mb-3">SECTION 4: TRAINING & DEVELOPMENT NEEDS</h4>
                                <div id="training-container">
                                    <?php 
                                    $count = max(3, count($training_needs));
                                    for($i=0; $i<$count; $i++): 
                                        $val = isset($training_needs[$i]) ? $training_needs[$i] : '';
                                    ?>
                                    <select class="form-control mb-2" name="training_needs[]">
                                        <option value="">Select Training Need</option>
                                        <option value="Leadership Training" <?php echo ($val == 'Leadership Training') ? 'selected' : ''; ?>>Leadership Training</option>
                                        <option value="Technical Skills Training" <?php echo ($val == 'Technical Skills Training') ? 'selected' : ''; ?>>Technical Skills Training</option>
                                        <option value="Communication Skills Workshop" <?php echo ($val == 'Communication Skills Workshop') ? 'selected' : ''; ?>>Communication Skills Workshop</option>
                                        <option value="Project Management Training" <?php echo ($val == 'Project Management Training') ? 'selected' : ''; ?>>Project Management Training</option>
                                        <?php if($val && !in_array($val, ['Leadership Training', 'Technical Skills Training', 'Communication Skills Workshop', 'Project Management Training'])): ?>
                                            <option value="<?php echo htmlspecialchars($val); ?>" selected><?php echo htmlspecialchars($val); ?></option>
                                        <?php endif; ?>
                                    </select>
                                    <?php endfor; ?>
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addDropdown('training-container', 'training_needs[]', getTrainingOptions())">+ Add Training Need</button>
                                <textarea class="form-control mb-3" name="training_comment" placeholder="Further comments on training needs..." rows="2"><?php echo htmlspecialchars($training_comment); ?></textarea>

                                <h4 class="mt-4 mb-3">SECTION 5: Goals & Objectives for Next Month</h4>
                                <div id="goals-container">
                                    <?php 
                                    $count = max(3, count($goals));
                                    for($i=0; $i<$count; $i++): 
                                        $val = isset($goals[$i]) ? $goals[$i] : '';
                                    ?>
                                        <input type="text" class="form-control mb-2" name="next_month_goals[]" placeholder="Goal <?php echo $i+1; ?>" value="<?php echo htmlspecialchars($val); ?>">
                                    <?php endfor; ?>
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addInput('goals-container', 'next_month_goals[]', 'Goal')">+ Add Goal</button>

                                <h4 class="mt-4 mb-3">SECTION 6: REMARKS</h4>
                                <div class="form-group">
                                    <label>HoD’s Comment (Overall appraisal, concerns, commendations, expectations)</label>
                                    <textarea class="form-control" name="hod_remarks" rows="3"><?php echo $appraisal['hod_remarks']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="employee_remarks">Employee/SurBodinate Remarks</label>
                                    <textarea class="form-control" name="employee_remarks" id="employee_remarks" rows="3" readonly><?php echo $appraisal['employee_remarks']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="hr_remarks">HR Approval/Remarks</label>
                                    <textarea class="form-control" name="hr_remarks" id="hr_remarks" rows="3" readonly="<?php in_array($this->session->userdata('role'), ['hrm', 'super']); ?>"><?php echo $appraisal['hr_remarks']; ?></textarea>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update Appraisal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function calculateCompletion() {
    var assigned = document.getElementById('tasks_assigned').value;
    var completed = document.getElementById('tasks_completed').value;
    if(assigned && completed && assigned > 0) {
        var rate = (completed / assigned) * 100;
        document.getElementById('completion_rate').value = rate.toFixed(2);
    }
}

function getStrengthOptions() {
    return `
        <option value="">Select Strength</option>
        <option value="Effective Communication">Effective Communication</option>
        <option value="Outstanding knowledge of the job">Outstanding knowledge of the job</option>
        <option value="Strong Team Player">Strong Team Player</option>
        <option value="Innovative Thinking">Innovative Thinking</option>
        <option value="Adaptable to Change">Adaptable to Change</option>
        <option value="Willingness to learn & improve">Willingness to learn & improve</option>
    `;
}

function getWeaknessOptions() {
    return `
        <option value="">Select Weakness</option>
        <option value="Time Management">Time Management</option>
        <option value="Conflict Resolution">Conflict Resolution</option>
        <option value="Technical Skills Enhancement">Technical Skills Enhancement</option>
        <option value="Goal Setting and Achievement">Goal Setting and Achievement</option>
        <option value="Communication with Team Members">Communication with Team Members</option>
    `;
}

function getTrainingOptions() {
    return `
        <option value="">Select Training Need</option>
        <option value="Leadership Training">Leadership Training</option>
        <option value="Technical Skills Training">Technical Skills Training</option>
        <option value="Communication Skills Workshop">Communication Skills Workshop</option>
        <option value="Project Management Training">Project Management Training</option>
    `;
}

function addDropdown(containerId, inputName, optionsHtml) {
    var container = document.getElementById(containerId);
    var select = document.createElement('select');
    select.className = 'form-control mb-2';
    select.name = inputName;
    select.innerHTML = optionsHtml;
    container.appendChild(select);
}

function addInput(containerId, inputName, placeholderPrefix) {
    var container = document.getElementById(containerId);
    var count = container.querySelectorAll('input').length + 1;
    var input = document.createElement('input');
    input.type = 'text';
    input.className = 'form-control mb-2';
    input.name = inputName;
    input.placeholder = placeholderPrefix + ' ' + count;
    container.appendChild(input);
}

function addKpaRow() {
    var tbody = document.getElementById('kpa-tbody');
    var tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" class="form-control" name="kpa_category[]"></td>
        <td><input type="text" class="form-control" name="kpa_description[]"></td>
        <td><input type="text" class="form-control" name="kpa_expected[]"></td>
        <td><input type="text" class="form-control" name="kpa_actual[]"></td>
        <td><input type="number" class="form-control" name="kpa_rating[]" min="1" max="10"></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove();">X</button></td>
    `;
    tbody.appendChild(tr);
}
</script>
