<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="text-white">
            Project Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('admin/dashboard'); ?>" class="text-[#DA7F00] hover:text-[#DA7F00]"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="text-white">Projects</li>
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
                        <h4><i class="icon fa fa-times"></i> Failed!</h4>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-xs-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Manage Projects</h3>
                        <div class="d-flex mt-3">
                            <?php
                            $user_role = $this->session->userdata('role');
                            $user_id = $this->session->userdata('userid');
                            $is_admin = in_array($user_role, ['super', 'hrm', 'finance']);
                            $is_hod = false;

                            // Check if user is HOD
                            if ($user_role === 'staff') {
                                $is_hod = $this->Department_model->is_head_of_department($user_id);
                            }

                            if ($is_admin || $is_hod) : ?>
                                <a href="<?php echo site_url('projects/create'); ?>" class="btn btn-primary hover:border-[#DA7F00] border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]">
                                    <i class="fa fa-plus"></i> Add New Project
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="box-body">
                        <table id="projectsTable" class="table">
                            <thead>
                                <tr class="text-white">
                                    <th>#</th>
                                    <th>Project Name</th>
                                    <th>Department</th>
                                    <th>Manager</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($projects)) : ?>
                                    <?php $i = 1;
                                    foreach ($projects as $project) : ?>
                                        <tr class="text-white">
                                            <td><?php echo $i++; ?></td>
                                            <td>
                                                <div class="font-medium"><?php echo html_escape($project->name); ?></div>
                                            </td>
                                            <td>
                                                <?php
                                                echo html_escape($this->Department_model->get_department_name($project->department_id));
                                                ?>
                                            </td>
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
                                            <td>
                                                <span class="px-4 py-2 font-semibold rounded-full <?php
                                                                                                    echo $project->priority === 'high' ? 'bg-red-500' : ($project->priority === 'medium' ? 'bg-yellow-500' : 'bg-blue-500');
                                                                                                    ?>">
                                                    <?php echo ucfirst($project->priority); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $status_colors = [
                                                    'delivered' => 'bg-green-500',
                                                    'in-progress' => 'bg-blue-500',
                                                    'approved' => 'bg-blue-400',
                                                    'pending' => 'bg-yellow-500',
                                                    'on-hold' => 'bg-orange-500',
                                                    'cancelled' => 'bg-red-500'
                                                ];
                                                $status_class = $status_colors[$project->status] ?? 'bg-gray-500';
                                                ?>
                                                <span class="px-4 py-2 font-semibold rounded-full <?= $status_class ?>">
                                                    <?php echo ucfirst(str_replace('-', ' ', $project->status)); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <!-- View Button - Available to all users who can see the project -->
                                                    <a href="<?php echo site_url('projects/view/' . $project->id); ?>"
                                                        class="btn btn-sm btn-info text-white"
                                                        title="View Project">
                                                        <i class="fa fa-eye"></i>
                                                    </a>

                                                    <?php
                                                    // Check edit permissions based on controller logic
                                                    $current_user_role = $this->session->userdata('role');
                                                    $current_user_id = $this->session->userdata('userid');

                                                    // Get current user's staff info for department check
                                                    $current_staff = $this->Staff_model->select_staff_byID($current_user_id);
                                                    $current_user_department = $current_staff ? $current_staff[0]['department_id'] : null;

                                                    $is_manager = ($project->manager_id == $current_user_id);
                                                    $is_same_department = ($project->department_id == $current_user_department);
                                                    $can_edit = in_array($current_user_role, ['super', 'hrm', 'finance']) || $is_manager;

                                                    if ($can_edit) : ?>
                                                        <!-- Edit Button -->
                                                        <a href="<?php echo site_url('projects/edit/' . $project->id); ?>"
                                                            class="btn btn-sm btn-warning text-white"
                                                            title="Edit Project">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if ($current_user_role === 'super') : ?>
                                                        <!-- Delete Button - Only Super Admin -->
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger text-white"
                                                            title="Delete Project"
                                                            onclick="deleteProject(<?php echo $project->id; ?>)">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-white py-4">No projects found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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

<!-- DataTables -->
<script src="<?php echo base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>

<style>
    /* Custom styling for projects table */
    #projectsTable {
        background-color: #2C2C2C;
        color: white;
    }

    #projectsTable thead th {
        background-color: #3E3E3E !important;
        color: white !important;
        border-bottom: 1px solid #555 !important;
        font-weight: 600;
    }

    #projectsTable tbody tr {
        background-color: #2C2C2C;
        color: white;
        border-bottom: 1px solid #444;
    }

    #projectsTable tbody tr:hover {
        background-color: #3E3E3E !important;
    }

    #projectsTable tbody td {
        border-top: 1px solid #444;
        padding: 12px 8px;
        vertical-align: middle;
    }

    /* DataTables controls styling */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_processing,
    .dataTables_wrapper .dataTables_paginate {
        color: white;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        color: white !important;
        border: 1px solid #555;
        background: #3E3E3E;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #DA7F00 !important;
        border-color: #DA7F00;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #DA7F00 !important;
        border-color: #DA7F00;
    }

    /* Button group styling */
    .btn-group .btn {
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    /* Priority and Status badges */
    .badge {
        font-size: 0.75em;
        padding: 0.4em 0.8em;
        border-radius: 0.5rem;
    }
</style>

<script>
    $(document).ready(function() {
        // Initialize DataTable with dark theme
        $('#projectsTable').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': false,
            'pageLength': 25,
            'language': {
                'search': '_INPUT_',
                'searchPlaceholder': 'Search projects...',
                'paginate': {
                    'next': '<i class="fa fa-chevron-right"></i>',
                    'previous': '<i class="fa fa-chevron-left"></i>'
                },
                'emptyTable': 'No projects found',
                'info': 'Showing _START_ to _END_ of _TOTAL_ projects',
                'infoEmpty': 'No projects available',
                'infoFiltered': '(filtered from _MAX_ total projects)',
                'lengthMenu': 'Show _MENU_ projects per page'
            },
            'drawCallback': function() {
                $('.dataTables_filter input')
                    .addClass('form-control bg-[#3E3E3E] border border-[#555] text-white')
                    .attr('placeholder', 'Search projects...');
                $('.dataTables_length select')
                    .addClass('form-control bg-[#3E3E3E] border border-[#555] text-white');

                // Style pagination buttons
                $('.dataTables_paginate .paginate_button').addClass('text-white');
                $('.dataTables_paginate .paginate_button.current').addClass('bg-[#DA7F00]');

                // Style info text
                $('.dataTables_info').addClass('text-gray-400');
            },
            'dom': '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
                '<"row"<"col-sm-12"tr>>' +
                '<"row"<"col-sm-5"i><"col-sm-7"p>>'
        });

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Style the table header
        $('#projectsTable thead th').addClass('bg-[#3E3E3E] text-white border-b border-[#555]');

        // Style table rows
        $('#projectsTable tbody tr').addClass('border-b border-[#444] hover:bg-[#3E3E3E]');
    });

    function deleteProject(id) {
        $('#deleteModal').modal('show');
        $('#confirmDelete').attr('href', '<?php echo site_url('projects/delete/'); ?>' + id);
    }
</script>