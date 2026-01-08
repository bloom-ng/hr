<div class="content-wrapper bg-[#3E3E3E]">
    <div class="content-header">
        <h1 class="m-0 text-dark">New Appraisal (2026)</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item active">New Appraisal</li>
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
                        <form role="form" action="<?php echo base_url('appraisal_new/store'); ?>" method="post">
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
                                            <input type="text" class="form-control" name="position" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Month Under Review</label>
                                            <input type="month" class="form-control" name="month_under_review" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <h4 class="mt-4 mb-3">SECTION 1: PERFORMANCE CATEGORIES</h4>
                                
                                <?php 
                                $categories = [
                                    ['key' => 'teamwork', 'label' => '1. Teamwork & Collaboration', 'desc' => 'Consider: cooperation, conflict resolution, willingness to assist, reliability as a team member.'],
                                    ['key' => 'communication', 'label' => '2. Communication Skills', 'desc' => 'Consider: responsiveness, tone, client communication, reporting accuracy, proactive updates.'],
                                    ['key' => 'quality', 'label' => '3. Quality of Work', 'desc' => 'Consider: attention to detail, level of revisions needed, alignment with Bloom quality standards.'],
                                    ['key' => 'timeliness', 'label' => '5. Time Management & Timeliness', 'desc' => 'Evaluate punctuality, task delivery speed, response times, and ability to meet deadlines.'], // Note: #4 is KPAs which is separate
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
                                            <input type="number" class="form-control" name="rating_<?php echo $cat['key']; ?>" min="1" max="10" required>
                                        </div>
                                        <div class="col-md-10">
                                            <label>Comments</label>
                                            <textarea class="form-control" name="comment_<?php echo $cat['key']; ?>" rows="2"></textarea>
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
                                        <?php for($i=0; $i<4; $i++): ?>
                                        <tr>
                                            <td><input type="text" class="form-control" name="kpa_category[]"></td>
                                            <td><input type="text" class="form-control" name="kpa_description[]"></td>
                                            <td><input type="text" class="form-control" name="kpa_expected[]"></td>
                                            <td><input type="text" class="form-control" name="kpa_actual[]"></td>
                                            <td><input type="number" class="form-control" name="kpa_rating[]" min="1" max="10"></td>
                                        </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>

                                <h4 class="mt-4 mb-3">SECTION 2: TASK TRACKING</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Number of Tasks Assigned</label>
                                            <input type="number" class="form-control" name="tasks_assigned" id="tasks_assigned" oninput="calculateCompletion()">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Number of Tasks Completed</label>
                                            <input type="number" class="form-control" name="tasks_completed" id="tasks_completed" oninput="calculateCompletion()">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Completion Rate (%)</label>
                                            <input type="text" class="form-control" name="completion_rate" id="completion_rate" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Accuracy/Revision Rate</label>
                                            <select class="form-control" name="accuracy_rate">
                                                <option value="Excellent">Excellent</option>
                                                <option value="Good">Good</option>
                                                <option value="Fair">Fair</option>
                                                <option value="Needs Improvement">Needs Improvement</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="mt-4 mb-3">SECTION 3: PERFORMANCE SUMMARY</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Employee Strengths</label>
                                        <?php for($i=0; $i<3; $i++): ?>
                                            <input type="text" class="form-control mb-2" name="strengths[]" placeholder="Strength <?php echo $i+1; ?>">
                                        <?php endfor; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Weaknesses / Areas for Improvement</label>
                                        <?php for($i=0; $i<3; $i++): ?>
                                            <input type="text" class="form-control mb-2" name="weaknesses[]" placeholder="Weakness <?php echo $i+1; ?>">
                                        <?php endfor; ?>
                                    </div>
                                </div>

                                <h4 class="mt-4 mb-3">SECTION 4: TRAINING & DEVELOPMENT NEEDS</h4>
                                <?php for($i=0; $i<3; $i++): ?>
                                    <input type="text" class="form-control mb-2" name="training_needs[]" placeholder="Training Need <?php echo $i+1; ?>">
                                <?php endfor; ?>

                                <h4 class="mt-4 mb-3">SECTION 5: Goals & Objectives for Next Month</h4>
                                <?php for($i=0; $i<3; $i++): ?>
                                    <input type="text" class="form-control mb-2" name="next_month_goals[]" placeholder="Goal <?php echo $i+1; ?>">
                                <?php endfor; ?>

                                <h4 class="mt-4 mb-3">SECTION 6: REMARKS</h4>
                                <div class="form-group">
                                    <label>HoD’s Comment (Overall appraisal, concerns, commendations, expectations)</label>
                                    <textarea class="form-control" name="hod_remarks" rows="3"></textarea>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit Appraisal</button>
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
</script>
