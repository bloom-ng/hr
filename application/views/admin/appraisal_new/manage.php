<div class="content-wrapper bg-[#3E3E3E]">
    <section class="content-header">
        <h1 class="m-0 text-dark">Manage Appraisals (2026)</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item active">Manage Appraisals</li>
        </ol>
    </section>

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
                            <h3 class="box-title text-white">Select Staff to Manage Appraisals</h3>
                        </div>
                        <div class="box-body">
                            <!-- Department Filter Dropdown -->
                            <div class="form-group">
                                <label for="department_filter">Filter by Department:</label>
                                <select class="form-control" id="department_filter">
                                    <option value="">Select Department</option>
                                    <?php if (isset($departments)) {
                                        foreach ($departments as $department) : ?>
                                            <option value="<?php echo $department['id']; ?>"><?php echo $department['department_name']; ?></option>
                                    <?php endforeach;
                                    } ?>
                                </select>
                            </div>

                            <table id="example1" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Actions</th>
                                        <th style="display: none;">Dept ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($staff_members)) : ?>
                                        <?php $i = 1;
                                        foreach ($staff_members as $staff) : ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $staff['staff_name']; ?></td>
                                                <td><?php echo isset($department_names[$staff['department_id']]) ? $department_names[$staff['department_id']] : 'N/A'; ?></td>
                                                <td>
                                                    <a href="<?php echo base_url('appraisal_new/list_staff_appraisals/'.$staff['id']); ?>" class="btn btn-warning">Appraisals</a>
                                                </td>
                                                <td style="display: none;"><?php echo $staff['department_id']; ?></td>
                                            </tr>
                                        <?php $i++;
                                        endforeach; ?>
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

<script>
    $(document).ready(function() {
        $('#department_filter').change(function() {
            var selectedDepartment = $(this).val();

            if (selectedDepartment !== '') {
                $('tbody tr').each(function() {
                    var departmentId = $(this).find('td:eq(4)').text(); // Get department ID from the hidden td

                    if (departmentId !== selectedDepartment) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            } else {
                $('tbody tr').show();
            }
        });
    });
</script>
