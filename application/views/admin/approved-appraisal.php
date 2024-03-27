<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Approved/Done Appraisals</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Staff Management</a></li>
            <li class="active">Approved/Done Appraisals</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Approved/Done Appraisals</h3>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Name </th>
                                    <th>Status </th>
                                    <th>Actions</th>
                                    <th style="display: none;">Department</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($appraisals)) : ?>
                                    <?php $i = 1;
                                    foreach ($appraisals as $appraisal) : ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $appraisal['date']; ?></td>
                                            <td><?php echo $appraisal['name']; ?></td>
                                            <td><?php echo $appraisal['status']; ?></td>
                                            <td>
                                                <?php if ($appraisal['status'] == 'done' && in_array($this->session->userdata('role'), array("hrm", "super"))) : ?>
                                                    <a href="<?php echo base_url(); ?>view-appraisal/<?php echo $appraisal['id']; ?>" class="btn btn-success hover:bg-[#DA7F00] border-0">Check</a>
                                                <?php endif; ?>
                                            </td>
                                            <td style="display: none;"><?php echo $appraisal['department_id']; ?></td>
                                        </tr>
                                    <?php $i++;
                                    endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
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