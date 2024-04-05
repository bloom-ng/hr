<!-- File: application/views/add_appraisal.php -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Edit Report</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Reports</a></li>
            <li class="active">Edit Report</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Edit Report</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <?php echo form_open('report/update/' . $report['id']); ?>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="employee_name" value="<?php echo $report["employee_name"]; ?>" readonly>
                            <input type="text" class="form-control" id="staff_id" name="staff_id" value="<?php echo $report["staff_id"]; ?>" hidden>
                        </div>
                        <div class="form-group">
                            <label for="job_title">Job Title:</label>
                            <input type="text" class="form-control" id="job_title" name="title" value="<?php echo $report["title"]; ?>">
                        </div>
                        <div class="form-group">
                            <label for="department_id">Department:</label>
                            <input type="text" class="form-control" id="department_name" name="department" value="<?php echo $report["department"]; ?>" readonly>
                            <input type="text" class="form-control" id="department_id" name="department_id" value="<?php echo $report["department_id"]; ?>" hidden>
                        </div>
                        <div class="form-group">
                            <label for="team_lead">Team Lead:</label>
                            <input type="text" class="form-control" id="team_lead" name="team_lead" value="<?php echo $report["team_lead"]; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="team_lead">Supervisor:</label>
                            <input type="text" class="form-control" id="supervisor" name="supervisor" value="<?php echo $report["supervisor"]; ?>">
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="week" class="form-control" id="date" name="date" placeholder="Date" value="<?php echo $report["date"]; ?>">
                        </div>

                        <div class="form-group">
                            <label for="operation_unit">Operation Unit:</label>
                            <input type="text" class="form-control" id="operation_unit" name="operation_unit" value="<?php echo $report["operation_unit"]; ?>">
                        </div>

                        <div class="form-group">
                            <label for="day_1_task">Monday Task:</label>
                            <input type="text" class="form-control" id="day_1_task" name="day_1_task" placeholder="Monday Task" value="<?php echo $report["day_1_task"]; ?>">
                            <input type="number" class="form-control" id="day_1_total_hours" name="day_1_total_hours" placeholder="Work Hours" value="<?php echo $report["day_1_total_hours"]; ?>">
                        </div>
                        <div class="form-group">
                            <label for="day_2_task">Tuesday Task:</label>
                            <input type="text" class="form-control" id="day_2_task" name="day_2_task" placeholder="Tuesday Task" value="<?php echo $report["day_2_task"]; ?>">
                            <input type="number" class="form-control" id="day_2_total_hours" name="day_2_total_hours" placeholder="Work Hours" value="<?php echo $report["day_2_total_hours"]; ?>">
                        </div>
                        <div class="form-group">
                            <label for="day_3_task">Wednesday Task:</label>
                            <input type="text" class="form-control" id="day_3_task" name="day_3_task" placeholder="Wednesday Task" value="<?php echo $report["day_3_task"]; ?>">
                            <input type="number" class="form-control" id="day_3_total_hours" name="day_3_total_hours" placeholder="Work Hours" value="<?php echo $report["day_3_total_hours"]; ?>">
                        </div>
                        <div class="form-group">
                            <label for="day_4_task">Thursday Task:</label>
                            <input type="text" class="form-control" id="day_4_task" name="day_4_task" placeholder="Thursday Task" value="<?php echo $report["day_4_task"]; ?>">
                            <input type="number" class="form-control" id="day_4_total_hours" name="day_4_total_hours" placeholder="Work Hours" value="<?php echo $report["day_4_total_hours"]; ?>">
                        </div>
                        <div class="form-group">
                            <label for="day_5_task">Friday Task:</label>
                            <input type="text" class="form-control" id="day_5_task" name="day_5_task" placeholder="Friday Task" value="<?php echo $report["day_5_task"]; ?>">
                            <input type="number" class="form-control" id="day_5_total_hours" name="day_5_total_hours" placeholder="Work Hours" value="<?php echo $report["day_5_total_hours"]; ?>">
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