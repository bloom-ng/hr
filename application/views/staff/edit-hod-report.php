<!-- File: application/views/add_appraisal.php -->
<div class="content-wrapper bg-[#3E3E3E]">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- Include the Quill library -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Add <?php echo $report['department_name'] ?> Report</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Reports</a></li>
            <li class="active">Add Report</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Add <?php echo $report['department_name'] ?> Report</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <?php echo form_open('report/update_hod_report/' . $report["id"]); ?>
                        <div class="form-group">
                            <input type="text" class="form-control" id="staff_id" name="staff_id" value="<?php echo $report["staff_id"]; ?>" hidden>
                        </div>
                        <div class="form-group">
                            <label for="department_id">Department:</label>
                            <input type="text" class="form-control" id="department_name" name="department_name" value="<?php echo $report["department_name"]; ?>" readonly>
                            <input type="text" class="form-control" id="department_id" name="department_id" value="<?php echo $report["department_id"]; ?>" hidden>
                        </div>
                        <div class="form-group">
                            <label for="team_lead">Team Lead:</label>
                            <input type="text" class="form-control" id="team_lead" name="team_lead" value="Agharaye Tseyi" readonly>
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="month" class="form-control" id="date" name="date" placeholder="MM/YYYY" value="<?php echo $report["date"]; ?>" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="activities-editor">Activities</label>
                            <div id="activities-editor" class="w-full text-white">
                                <?php echo $report["activities"]; ?>
                            </div>
                            <input type="hidden" id="activities" name="activities">
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="achievement-editor">Achievement</label>
                            <div id="achievement-editor" class="w-full text-white">
                                <?php echo $report["achievement"]; ?>
                            </div>
                            <input type="hidden" id="achievement" name="achievement">
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="growth-editor">Growth Analysis</label>
                            <div id="growth-editor" class="w-full text-white">
                                <?php echo $report["growth_analysis"]; ?>
                            </div>
                            <input type="hidden" id="growth_analysis" name="growth_analysis">
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="challenges-editor">Challenges</label>
                            <div id="challenges-editor" class="w-full text-white">
                                <?php echo $report["challenges"]; ?>
                            </div>
                            <input type="hidden" id="challenges" name="challenges">
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="target-editor">Target</label>
                            <div id="target-editor" class="w-full text-white">
                                <?php echo $report["target_for_next_month"]; ?>
                            </div>
                            <input type="hidden" id="target_for_next_month" name="target_for_next_month">
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="recommendation-editor">Recommendations</label>
                            <div id="recommendation-editor" class="w-full text-white">
                                <?php echo $report["recommendations"]; ?>
                            </div>
                            <input type="hidden" id="recommendations" name="recommendations">
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="conclusion-editor">Conclusion</label>
                            <div id="conclusion-editor" class="w-full text-white">
                                <?php echo $report["conclusion"]; ?>
                            </div>
                            <input type="hidden" id="conclusion" name="conclusion">
                        </div>

                        <button type="submit" class="btn btn-primary hover:border-[#DA7F00] text-white border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]">Done</button>


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
<!-- /.content-wrapper -->
<!-- Script for Activities editor -->
<script>
    var activitiesQuill = new Quill('#activities-editor', {
        theme: 'snow'
    });

    activitiesQuill.on('text-change', function(delta, oldDelta, source) {
        document.getElementById('activities').value = activitiesQuill.root.innerHTML;
    });
</script>

<!-- Script for Achievement editor -->
<script>
    var achievementQuill = new Quill('#achievement-editor', {
        theme: 'snow'
    });

    achievementQuill.on('text-change', function(delta, oldDelta, source) {
        document.getElementById('achievement').value = achievementQuill.root.innerHTML;
    });
</script>

<!-- Script for Growth editor -->
<script>
    var growthQuill = new Quill('#growth-editor', {
        theme: 'snow'
    });

    growthQuill.on('text-change', function(delta, oldDelta, source) {
        document.getElementById('growth_analysis').value = growthQuill.root.innerHTML;
    });
</script>

<!-- Script for Challenges editor -->
<script>
    var challengesQuill = new Quill('#challenges-editor', {
        theme: 'snow'
    });

    challengesQuill.on('text-change', function(delta, oldDelta, source) {
        document.getElementById('challenges').value = challengesQuill.root.innerHTML;
    });
</script>

<!-- Script for Target editor -->
<script>
    var targetQuill = new Quill('#target-editor', {
        theme: 'snow'
    });

    targetQuill.on('text-change', function(delta, oldDelta, source) {
        document.getElementById('target_for_next_month').value = targetQuill.root.innerHTML;
    });
</script>

<!-- Script for Recommendation editor -->
<script>
    var recommendationQuill = new Quill('#recommendation-editor', {
        theme: 'snow'
    });

    recommendationQuill.on('text-change', function(delta, oldDelta, source) {
        document.getElementById('recommendations').value = recommendationQuill.root.innerHTML;
    });
</script>

<!-- Script for Conclusion editor -->
<script>
    var conclusionQuill = new Quill('#conclusion-editor', {
        theme: 'snow'
    });

    conclusionQuill.on('text-change', function(delta, oldDelta, source) {
        document.getElementById('conclusion').value = conclusionQuill.root.innerHTML;
    });
</script>