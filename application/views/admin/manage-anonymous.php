<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Anonymous
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Anonymous</a></li>
            <li class="active">Manage Anonymous</li>
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
                        <h3 class="box-title text-white">Manage Departments</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table">
                            <thead>
                                <tr class="border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($anonymous)) :
                                    $i = 1;
                                    foreach ($anonymous as $cnt) :
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $cnt['subject']; ?></td>
                                            <td><?php echo substr($cnt['message'], 0, 40) . (strlen($cnt['message']) > 40 ? '...' : ''); ?></td>
                                            <td>
                                                <a href="<?php echo base_url(); ?>view-anonymous/<?php echo $cnt['id']; ?>" class="btn bg-[#DA7F00] hover:bg-[#DA7F00] border-0 btn-success">View</a>
                                                <a href="<?php echo base_url(); ?>delete-anonymous/<?php echo $cnt['id']; ?>" class="btn  bg-[#595959] hover:bg-[#595959] border-0 btn-danger">Delete</a>
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