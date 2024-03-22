<!-- File: application/views/add_appraisal.php -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Add Report</h1>
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
                        <h3 class="box-title text-white">Add Report</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <?php echo form_open('report/insert'); ?>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="employee_name" value="<?php echo $staff["staff_name"]; ?>" readonly>
                            <input type="text" class="form-control" id="staff_id" name="staff_id" value="<?php echo $staff["id"]; ?>" hidden>
                        </div>
                        <div class="form-group">
                            <label for="job_title">Job Title:</label>
                            <input type="text" class="form-control" id="job_title" name="title">
                        </div>
                        <div class="form-group">
                            <label for="department_id">Department:</label>
                            <input type="text" class="form-control" id="department_name" name="department" value="<?php echo $department["department_name"]; ?>" readonly>
                            <input type="text" class="form-control" id="department_id" name="department_id" value="<?php echo $department["id"]; ?>" hidden>
                        </div>
                        <div class="form-group">
                            <label for="team_lead">Team Lead:</label>
                            <input type="text" class="form-control" id="team_lead" name="team_lead" value="Agharaye Tseyi" readonly>
                        </div>
                        <div class="form-group">
                            <label for="team_lead">Supervisor:</label>
                            <input type="text" class="form-control" id="supervisor" name="supervisor">
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" name="date" placeholder="Date">
                        </div>

                        <div class="form-group">
                            <label for="operation_unit">Operation Unit:</label>
                            <input type="text" class="form-control" id="operation_unit" name="operation_unit">
                        </div>

                        <div class="form-group">
                            <label for="day_1_task">Monday Task:</label>
                            <input type="text" class="form-control" id="day_1_task" name="day_1_task" placeholder="Monday Task">
                            <input type="number" class="form-control" id="day_1_total_hours" name="day_1_total_hours" placeholder="Work Hours">
                        </div>
                        <div class="form-group">
                            <label for="day_2_task">Tuesday Task:</label>
                            <input type="text" class="form-control" id="day_2_task" name="day_2_task" placeholder="Tuesday Task">
                            <input type="number" class="form-control" id="day_2_total_hours" name="day_2_total_hours" placeholder="Work Hours">
                        </div>
                        <div class="form-group">
                            <label for="day_3_task">Wednesday Task:</label>
                            <input type="text" class="form-control" id="day_3_task" name="day_3_task" placeholder="Wednesday Task">
                            <input type="number" class="form-control" id="day_3_total_hours" name="day_3_total_hours" placeholder="Work Hours">
                        </div>
                        <div class="form-group">
                            <label for="day_4_task">Thursday Task:</label>
                            <input type="text" class="form-control" id="day_4_task" name="day_4_task" placeholder="Thursday Task">
                            <input type="number" class="form-control" id="day_4_total_hours" name="day_4_total_hours" placeholder="Work Hours">
                        </div>
                        <div class="form-group">
                            <label for="day_5_task">Friday Task:</label>
                            <input type="text" class="form-control" id="day_5_task" name="day_5_task" placeholder="Friday Task">
                            <input type="number" class="form-control" id="day_5_total_hours" name="day_5_total_hours" placeholder="Work Hours">
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