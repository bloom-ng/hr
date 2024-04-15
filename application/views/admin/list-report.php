<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>List Report</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Staff Management</a></li>
            <li class="active">List Report</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
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
            <div class="col-xs-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">List Report</h3>
                    </div>

                    <?php if ($this->session->userdata('staff_id') == $user) : ?>
                        <div class="box-header">
                            <h3 class="box-title"></h3>
                            <div class="d-flex mt-3">
                                <a href="<?php echo base_url(); ?>add-report/<?php echo $user; ?>" class="btn btn-info border-[#DA7F00] hover:border-[#DA7F00] hover:bg-[#DA7F00] bg-[#DA7F00]">Add Report</a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name </th>
                                    <th>Date</th>
                                    <th>Status </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($reports)) : ?>
                                    <?php $i = 1;
                                    foreach ($reports as $report) : ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $report['employee_name']; ?></td>
                                            <td><?php echo $report['date']; ?></td>
                                            <td><?php echo $report['status']; ?></td>
                                            <td>
                                                <?php if ($report['status']  == 'pending' && $this->session->userdata('staff_id') == $report['staff_id']) : ?>
                                                    <a href="<?php echo base_url(); ?>review-report/<?php echo $report['id']; ?>" class="btn btn-info hover:bg-[#DA7F00] bg-[#DA7F00] border-0">Send For Review</a>
                                                    <a href="<?php echo base_url(); ?>edit-report/<?php echo $report['id']; ?>" class="btn btn-success hover:bg-[#595959] bg-[#595959] border-0">Edit</a>
                                                <?php endif; ?>

                                                <?php if ($report['status'] !== 'pending' && in_array($this->session->userdata('role'), array("hrm", "super"))) : ?>
                                                    <a href="<?php echo base_url(); ?>view-report/<?php echo $report['id']; ?>" class="btn btn-success hover:bg-[#DA7F00] border-0">Check</a>
                                                <?php endif; ?>

                                                <?php if ($report['status']  !== 'pending' && $this->session->userdata('staff_id') == $report['staff_id']) : ?>

                                                    <a href="<?php echo base_url(); ?>view-report/<?php echo $report['id']; ?>" class="btn btn-success hover:bg-[#DA7F00] border-0">Preview</a>
                                                <?php endif; ?>

                                                <?php if (in_array($this->session->userdata('role'), ["hrm", "super"])) : ?>
                                                    <a href="<?php echo base_url(); ?>report/delete/<?php echo $cnt['id']; ?>" class="btn btn-danger border-0 bg-[#595959] hover:bg-[#595959] btn-danger">
                                                        Delete
                                                    </a>
                                                <?php endif; ?>
                                            </td>
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