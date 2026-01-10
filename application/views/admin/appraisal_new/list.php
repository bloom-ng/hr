<div class="content-wrapper bg-[#3E3E3E]">
    <div class="content-header">
        <h1 class="m-0 text-dark">
            Appraisals (2026) 
            <?php if(isset($staff_name)) echo " - " . $staff_name; ?>
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
                            <h3 class="box-title text-white">List of Appraisals</h3>
                            <?php if (isset($hod)) : ?>
                                <?php if ($hod || in_array($this->session->userdata('role'), ["hrm", "super"])) : ?>
                                    <div class="box-tools">
                                        <a href="<?php echo base_url('appraisal_new/create/'.$staff_id); ?>" class="btn btn-success btn-sm">Create New Appraisal</a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <div class="box-body p-0">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Period</th>
                                        <th>Completion Rate</th>
                                        <th>Accuracy</th>
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
                                                <td><?php echo $appraisal['completion_rate']; ?>%</td>
                                                <td><?php echo $appraisal['accuracy_rate']; ?></td>
                                                <td>
                                                    <?php 
                                                    $statusClasses = [
                                                        'pending' => 'warning',
                                                        'hr_approved' => 'info',
                                                        'staff_replied' => 'primary',
                                                        'final' => 'success'
                                                    ];
                                                    $statusLabel = [
                                                        'pending' => 'Pending HR',
                                                        'hr_approved' => 'HR Approved',
                                                        'staff_replied' => 'Staff Replied',
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
                                                    
                                                    <?php 
                                                    $role = $this->session->userdata('role');
                                                    $is_hod_admin = $hod ?? false;
                                                    $is_hr = in_array($role, ['hrm', 'super']);
                                                    $is_super = ($role == 'super');
                                                    ?>

                                                    <?php if($appraisal['status'] == 'draft' && $is_hod_admin): ?>
                                                        <a href="<?php echo base_url('appraisal_new/edit/'.$appraisal['id']); ?>" class="btn btn-primary btn-sm">Edit</a>
                                                        <a href="<?php echo base_url('appraisal_new/submit_to_hr/'.$appraisal['id']); ?>" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to submit this to HR?');">Send to HR</a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if($appraisal['status'] == 'pending' && $is_hr): ?>
                                                        <a href="<?php echo base_url('appraisal_new/hr_approve/'.$appraisal['id']); ?>" class="btn btn-success btn-sm" onclick="return confirm('Approve and send to staff?');">Send to Staff</a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if($appraisal['status'] == 'staff_replied' && $is_super): ?>
                                                        <a href="<?php echo base_url('appraisal_new/super_approve/'.$appraisal['id']); ?>" class="btn btn-success btn-sm" onclick="return confirm('Finalize this appraisal?');">Final Approve</a>
                                                    <?php endif; ?>
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
