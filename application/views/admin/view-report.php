<!-- File: application/views/add_appraisal.php -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>View Report</h1>
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
                        <h3 class="box-title text-white">Viewing <?php echo $report["employee_name"] ?>'s Report</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <?php echo form_open('report/approve_report/' . $report["id"]); ?>

                        <div class="overflow-x-auto w-full">
                            <table class="table-auto border-collapse w-full mb-12">
                                <tbody>
                                    <tr>
                                        <td class="px-4 py-2 border">Employee's Name: <?php echo $report['employee_name'] ?></td>
                                        <td class="px-4 py-2 border">Title: <?php echo $report['title'] ?> </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2 border">Department: <?php echo $report['department'] ?></td>
                                        <td class="px-4 py-2 border">Status: <?php echo $report['status'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2 border">Operation Unit: <?php echo $report['operation_unit'] ?></td>
                                        <td class="px-4 py-2 border">Supervisor: <?php echo $report['supervisor'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2 border">Team Lead: <?php echo $report['team_lead'] ?></td>
                                        <td class="px-4 py-2 border">Date: <?php echo $report['date'] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table-auto border-collapse w-full mb-12">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 border">Date</th>
                                        <th class="px-4 py-2 border">Task</th>
                                        <th class="px-4 py-2 border">Total Hours</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Row 1 -->
                                    <tr>
                                        <td class="border px-4 py-2">Monday</td>
                                        <td class="border px-4 py-2">
                                            <?php echo $report['day_1_task'] ?>
                                        </td>
                                        <td class="border px-4 py-2"><?php echo $report['day_1_total_hours'] ?>hrs</td>
                                    </tr>
                                    <!-- Row 2 -->
                                    <tr>
                                        <td class="border px-4 py-2">Tuesday</td>
                                        <td class="border px-4 py-2"><?php echo $report['day_2_task'] ?></td>
                                        <td class="border px-4 py-2"><?php echo $report['day_2_total_hours'] ?>hrs</td>
                                    </tr>
                                    <!-- Row 3 -->
                                    <tr>
                                        <td class="border px-4 py-2">Wednesday</td>
                                        <td class="border px-4 py-2"><?php echo $report['day_3_task'] ?></td>
                                        <td class="border px-4 py-2"><?php echo $report['day_3_total_hours'] ?>hrs</td>
                                    </tr>
                                    <!-- Row 4 -->
                                    <tr>
                                        <td class="border px-4 py-2">Thursday</td>
                                        <td class="border px-4 py-2"><?php echo $report['day_4_task'] ?></td>
                                        <td class="border px-4 py-2"><?php echo $report['day_4_total_hours'] ?>hrs</td>
                                    </tr>
                                    <!-- Row 5 -->
                                    <tr>
                                        <td class="border px-4 py-2">Friday</td>
                                        <td class="border px-4 py-2"><?php echo $report['day_5_task'] ?></td>
                                        <td class="border px-4 py-2"><?php echo $report['day_5_total_hours'] ?>hrs</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                        <?php if (in_array($this->session->userdata('role'), array("hrm", "super"))) : ?>
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