<!-- File: application/views/add_appraisal.php -->
<div class="content-wrapper bg-[#3E3E3E]">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- Include the Quill library -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>View <?php echo $report['department_name'] ?> Report for <?php echo $report["date"] ?></h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Reports</a></li>
            <li class="active">View Report</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">View <?php echo $report['department_name'] ?> Report</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body flex items-center justify-center flex-col gap-12">

                        <div class="col-md-12">
                            <h1 class="text-center text-4xl font-bold">Department: <?php echo $report["department_name"]; ?></h1>
                        </div>

                        <div class="col-md-12">
                            <h1 class="text-center text-4xl font-bold">Date: <?php echo $report["date"]; ?></h1>
                        </div>

                        <div class="text-center text-4xl font-bold col-md-12">
                            <label for="activities-editor">Activities.</label>
                            <div id="activities-editor" class="w-full text-white">
                                <?php echo $report["activities"]; ?>
                            </div>
                            <input type="hidden" id="activities" name="activities">
                        </div>

                        <div class="col-md-12 text-center text-4xl font-bold">
                            <label for="achievement-editor">Achievement</label>
                            <div id="achievement-editor" class="w-full text-white">
                                <?php echo $report["achievement"]; ?>
                            </div>
                            <input type="hidden" id="achievement" name="achievement">
                        </div>

                        <div class="col-md-12 text-center text-4xl font-bold">
                            <label for="growth-editor">Growth Analysis</label>
                            <div id="growth-editor" class="w-full text-white">
                                <?php echo $report["growth_analysis"]; ?>
                            </div>
                            <input type="hidden" id="growth_analysis" name="growth_analysis">
                        </div>

                        <div class="col-md-12 text-center text-4xl font-bold">
                            <label for="challenges-editor">Challenges</label>
                            <div id="challenges-editor" class="w-full text-white">
                                <?php echo $report["challenges"]; ?>
                            </div>
                            <input type="hidden" id="challenges" name="challenges">
                        </div>

                        <div class="col-md-12 text-center text-4xl font-bold">
                            <label for="target-editor">Target</label>
                            <div id="target-editor" class="w-full text-white">
                                <?php echo $report["target_for_next_month"]; ?>
                            </div>
                            <input type="hidden" id="target_for_next_month" name="target_for_next_month">
                        </div>

                        <div class="col-md-12 text-center text-4xl font-bold">
                            <label for="recommendation-editor">Recommendations</label>
                            <div id="recommendation-editor" class="w-full text-white">
                                <?php echo $report["recommendations"]; ?>
                            </div>
                            <input type="hidden" id="recommendations" name="recommendations">
                        </div>

                        <div class="col-md-12 text-center text-4xl font-bold">
                            <label for="conclusion-editor">Conclusion</label>
                            <div id="conclusion-editor" class="w-full text-white">
                                <?php echo $report["conclusion"]; ?>
                            </div>
                            <input type="hidden" id="conclusion" name="conclusion">
                        </div>

                        <?php echo form_open('approve-hod-report/' . $report["id"]); ?>
                        <?php if ($report["status"] == "review" && in_array($this->session->userdata('role'), array("hrm", "super"))) : ?>
                            <button type="submit" class="btn btn-primary hover:border-[#DA7F00] text-white border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]">Approve</button>
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
<!-- /.content-wrapper -->