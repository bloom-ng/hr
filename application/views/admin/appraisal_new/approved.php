<div class="content-wrapper bg-[#3E3E3E]">
    <div class="content-header">
        <h1 class="m-0 text-dark">
            Appraisals (2026) 
        </h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('appraisal_new/manage'); ?>">Manage</a></li>
            <li class="breadcrumb-item active">Appraisals</li>
        </ol>
    </div>

    <section class="content">
        <div class="container-fluid">
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
                <div class="col-md-12">
                     <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                        <div class="box-header">
                            <h3 class="box-title text-white">List of Approved Appraisals</h3>
                        </div>
                        <div class="box-body p-0">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Period</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($appraisals)): ?>
                                        <?php foreach($appraisals as $appraisal): ?>
                                            <tr>
                                                <td><?php echo $appraisal['id']; ?></td>
                                                <td><?php echo $appraisal['month_under_review']; ?></td>
                                                <td><?php echo $appraisal['name']; ?></td>
                                                <td><?php echo $appraisal['department']; ?></td>
                                                <td>
                                                    <?php 
                                                    $statusClasses = [
                                                        'final' => 'success'
                                                    ];
                                                    $statusLabel = [
                                                        'final' => 'Completed'
                                                    ];
                                                    $s = $appraisal['status'];
                                                    ?>
                                                    <span class="badge badge-<?php echo isset($statusClasses[$s]) ? $statusClasses[$s] : 'secondary'; ?>">
                                                        <?php echo isset($statusLabel[$s]) ? $statusLabel[$s] : ucfirst($s); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url('appraisal_new/view/'.$appraisal['id']); ?>" class="btn btn-info btn-sm">View</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No appraisals found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
