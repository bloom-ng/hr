<!-- File: application/views/add_appraisal.php -->
<div class="content-wrapper bg-[#3E3E3E]">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- Include the Quill library -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>View Anonymouns</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Anonymous</a></li>
            <li class="active">View Anonymous</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">View Anonymous</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body flex items-start justify-center flex-col gap-12">

                        <div class="col-md-12">
                            <h1 class="text-4xl font-bold">Subject: <?php echo $anonymous["subject"]; ?></h1>
                        </div>

                        <div class="col-md-12">
                            <h3 class="text-4xl font-medium">Message: <span class="font-light leading-loose"><?php echo $anonymous["message"]; ?></span></h3>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <?php if (isset($prev_id) && $prev_id): ?>
                                    <a href="<?php echo base_url('anonymous/view/' . $prev_id); ?>" class="btn btn-primary">Previous</a>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 text-right">
                                <?php if (isset($next_id) && $next_id): ?>
                                    <a href="<?php echo base_url('anonymous/view/' . $next_id); ?>" class="btn btn-primary">Next</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
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