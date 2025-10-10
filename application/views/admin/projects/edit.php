<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="text-white">
            Edit Project: <?= html_escape($project->name) ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('admin/dashboard'); ?>" class="text-[#DA7F00] hover:text-[#DA7F00]"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('projects'); ?>" class="text-[#DA7F00] hover:text-[#DA7F00]">Projects</a></li>
            <li><a href="<?php echo site_url('projects/view/' . $project->id); ?>" class="text-[#DA7F00] hover:text-[#DA7F00]">View Project</a></li>
            <li class="text-white">Edit</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Edit Project Details</h3>
                        <div class="box-tools">
                            <a href="<?php echo site_url('projects/view/' . $project->id); ?>" class="btn btn-info btn-sm">
                                <i class="fa fa-eye"></i> View Project
                            </a>
                            <a href="<?php echo site_url('projects'); ?>" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-left"></i> Back to Projects
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if (validation_errors()): ?>
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-times"></i> Validation Error!</h4>
                                <?= validation_errors() ?>
                            </div>
                        <?php endif; ?>

                        <?php $this->load->view('admin/projects/form'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>