<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="text-white">
            Project Details: <?= html_escape($project->name) ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('admin/dashboard'); ?>" class="text-[#DA7F00] hover:text-[#DA7F00]"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('projects'); ?>" class="text-[#DA7F00] hover:text-[#DA7F00]">Projects</a></li>
            <li class="text-white">View Project</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php elseif ($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-times"></i> Error!</h4>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Project Information</h3>
                        <div class="box-tools">
                            <?php
                            $current_user_role = $this->session->userdata('role');
                            $current_user_id = $this->session->userdata('userid');
                            $is_manager = ($project->manager_id == $current_user_id);

                            // Get current user's department for permission check
                            $current_staff = $this->Staff_model->select_staff_byID($current_user_id);
                            $current_user_department = $current_staff ? $current_staff[0]['department_id'] : null;
                            $is_same_department = ($project->department_id == $current_user_department);
                            $can_edit = in_array($current_user_role, ['super', 'hrm', 'finance']) || $is_manager;

                            if ($can_edit) : ?>
                                <a href="<?= site_url('projects/edit/' . $project->id) ?>" class="btn btn-warning btn-sm">
                                    <i class="fa fa-edit"></i> Edit Project
                                </a>
                            <?php endif; ?>
                            <a href="<?= site_url('projects') ?>" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-left"></i> Back to Projects
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless text-white">
                                    <tr>
                                        <td class="text-[#DA7F00] font-weight-bold">Project Name:</td>
                                        <td><?= html_escape($project->name) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-[#DA7F00] font-weight-bold">Status:</td>
                                        <td>
                                            <?php
                                            $status_class = [
                                                'pending' => 'bg-yellow-500',
                                                'approved' => 'bg-blue-500',
                                                'in_progress' => 'bg-blue-600',
                                                'on_hold' => 'bg-orange-500',
                                                'completed' => 'bg-green-500',
                                                'cancelled' => 'bg-red-500'
                                            ][$project->status] ?? 'bg-gray-500';
                                            ?>
                                            <span class="px-3 py-1 font-semibold rounded-full <?= $status_class ?> text-white">
                                                <?= ucfirst(str_replace('_', ' ', $project->status)) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-[#DA7F00] font-weight-bold">Priority:</td>
                                        <td>
                                            <?php
                                            $priority_class = [
                                                'low' => 'bg-blue-500',
                                                'medium' => 'bg-yellow-500',
                                                'high' => 'bg-red-500'
                                            ][$project->priority] ?? 'bg-gray-500';
                                            ?>
                                            <span class="px-3 py-1 font-semibold rounded-full <?= $priority_class ?> text-white">
                                                <?= ucfirst($project->priority) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-[#DA7F00] font-weight-bold">Payment Status:</td>
                                        <td>
                                            <?php
                                            $payment_class = [
                                                'pending' => 'bg-yellow-500',
                                                'paid' => 'bg-green-500',
                                                'refunded' => 'bg-red-500'
                                            ][$project->payment_status] ?? 'bg-gray-500';
                                            ?>
                                            <span class="px-3 py-1 font-semibold rounded-full <?= $payment_class ?> text-white">
                                                <?= ucfirst($project->payment_status) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php if ($project->receipt_id): ?>
                                        <tr>
                                            <td class="text-[#DA7F00] font-weight-bold">Receipt ID:</td>
                                            <td><?= html_escape($project->receipt_id) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless text-white">
                                    <tr>
                                        <td class="text-[#DA7F00] font-weight-bold">Client Email:</td>
                                        <td><a href="mailto:<?= html_escape($project->client_email) ?>" class="text-[#DA7F00]"><?= html_escape($project->client_email) ?></a></td>
                                    </tr>
                                    <tr>
                                        <td class="text-[#DA7F00] font-weight-bold">Client Phone:</td>
                                        <td><a href="tel:<?= html_escape($project->client_phone) ?>" class="text-[#DA7F00]"><?= html_escape($project->client_phone) ?></a></td>
                                    </tr>
                                    <tr>
                                        <td class="text-[#DA7F00] font-weight-bold">Schedule Type:</td>
                                        <td><?= ucfirst(str_replace('_', ' ', $project->schedule_type)) ?></td>
                                    </tr>
                                    <?php if ($project->schedule_date): ?>
                                        <tr>
                                            <td class="text-[#DA7F00] font-weight-bold">Schedule Date:</td>
                                            <td><?= date('M d, Y', strtotime($project->schedule_date)) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td class="text-[#DA7F00] font-weight-bold">Department:</td>
                                        <td><?= html_escape($this->Department_model->get_department_name($project->department_id)) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-[#DA7F00] font-weight-bold">Manager:</td>
                                        <td>
                                            <?php
                                            $manager = $this->Staff_model->select_staff_byID($project->manager_id);
                                            if ($manager) {
                                                echo html_escape($manager[0]['staff_name']);
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-[#DA7F00] font-weight-bold">Created:</td>
                                        <td><?= date('M d, Y H:i', strtotime($project->created_at)) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr class="border-[#555]">

                        <div class="mb-4">
                            <h5 class="text-white mb-3"><i class="fa fa-list-alt text-[#DA7F00]"></i> Description of Deliverables</h5>
                            <div class="bg-[#3E3E3E] border border-[#555] p-4 rounded">
                                <p class="text-white mb-0"><?= nl2br(html_escape($project->description_of_deliverables)) ?></p>
                            </div>
                        </div>

                        <?php if (!empty($project->special_request)): ?>
                            <div class="mb-4">
                                <h5 class="text-white mb-3"><i class="fa fa-star text-[#DA7F00]"></i> Special Requests</h5>
                                <div class="bg-[#3E3E3E] border border-[#555] p-4 rounded">
                                    <p class="text-white mb-0"><?= nl2br(html_escape($project->special_request)) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Project Actions</h3>
                    </div>
                    <div class="box-body">
                        <?php if ($current_user_role === 'super' && $project->status === 'pending'): ?>
                            <a href="<?= site_url('projects/approve/' . $project->id) ?>"
                                class="btn btn-success btn-block mb-3"
                                onclick="return confirm('Are you sure you want to approve this project?')">
                                <i class="fa fa-check"></i> Approve Project
                            </a>
                        <?php endif; ?>

                        <?php if ($can_edit) : ?>
                            <a href="<?= site_url('projects/edit/' . $project->id) ?>" class="btn btn-warning btn-block mb-3">
                                <i class="fa fa-edit"></i> Edit Project
                            </a>
                        <?php endif; ?>

                        <?php if ($current_user_role === 'super') : ?>
                            <button type="button" class="btn btn-danger btn-block mb-3"
                                onclick="deleteProject(<?= $project->id ?>)">
                                <i class="fa fa-trash"></i> Delete Project
                            </button>
                        <?php endif; ?>

                        <div class="mt-4">
                            <h5 class="text-white mb-3">Project Progress</h5>
                            <div class="progress" style="height: 25px;">
                                <?php
                                $progress = [
                                    'pending' => 25,
                                    'approved' => 50,
                                    'in_progress' => 75,
                                    'on_hold' => 60,
                                    'completed' => 100,
                                    'cancelled' => 0
                                ][$project->status] ?? 10;

                                $progress_class = [
                                    'pending' => 'bg-yellow-500',
                                    'approved' => 'bg-blue-500',
                                    'in_progress' => 'bg-blue-600',
                                    'on_hold' => 'bg-orange-500',
                                    'completed' => 'bg-green-500',
                                    'cancelled' => 'bg-red-500'
                                ][$project->status] ?? 'bg-gray-500';
                                ?>
                                <div class="progress-bar <?= $progress_class ?> progress-bar-striped progress-bar-animated"
                                    role="progressbar"
                                    style="width: <?= $progress ?>%"
                                    aria-valuenow="<?= $progress ?>"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                    <?= $progress ?>%
                                </div>
                            </div>
                            <p class="text-center mt-2 text-gray-400">
                                <small><?= ucfirst(str_replace('_', ' ', $project->status)) ?></small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-[#2C2C2C] text-white">
            <div class="modal-header bg-[#DA7F00] border-b border-[#444]">
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteModalLabel">Confirm Delete</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this project? This action cannot be undone.</p>
            </div>
            <div class="modal-footer bg-[#3E3E3E] border-t border-[#444]">
                <button type="button" class="btn bg-[#595959] hover:bg-[#595959]" data-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDelete" class="btn btn-danger hover:bg-red-700">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteProject(id) {
        $('#deleteModal').modal('show');
        $('#confirmDelete').attr('href', '<?php echo site_url('projects/delete/'); ?>' + id);
    }
</script>