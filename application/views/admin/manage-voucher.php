<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Voucher
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Voucher</li>
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
                        <h3 class="box-title  text-white">Manage Voucher</h3>
                        <div class="d-flex mt-3">
                            <?php if (in_array($this->session->userdata('role'), ["finance", "super"])) : ?>
                                <a type="button" href="<?php echo base_url(); ?>voucher/add" class="btn btn-primary hover:border-[#DA7F00] border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]">
                                    Add
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($vouchers)) :
                                    $i = 1;
                                    foreach ($vouchers as $cnt) :
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $cnt['title']; ?></td>
                                            <td><?php echo $cnt['date']; ?></td>
                                            <td>
                                                <?php if (in_array($this->session->userdata('role'), ["finance", "super"])) : ?>
                                                    <a href="<?php echo base_url(); ?>voucher/edit/<?php echo $cnt['id']; ?>" class="btn btn-info">view</a>
                                                    <a href="<?php echo base_url(); ?>voucher/edit/<?php echo $cnt['id']; ?>" class="btn btn-success">Edit</a>
                                                    <a href="<?php echo base_url(); ?>voucher/delete/<?php echo $cnt['id']; ?>" class="btn btn-danger">Delete</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                <?php
                                        $i++;
                                    endforeach;
                                endif;
                                ?>

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