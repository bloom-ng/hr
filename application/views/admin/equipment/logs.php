<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo isset($equipment_name) ? $equipment_name . ' - ' : ''; ?>Equipment Logs
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url('equipment'); ?>">Equipment</a></li>
            <li class="active">Logs</li>
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
                        <h3 class="box-title text-white">Equipment Activity Logs</h3>
                    </div>

                    <div class="box-body">
                        <table id="equipment-logs-table" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>Equipment Name</th>
                                    <th>Status</th>
                                    <th>Staff (Holder)</th>
                                    <th>Purpose</th>
                                    <th>Requested On</th>
                                    <th>Returned On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($logs) && !empty($logs)) : ?>
                                    <?php $i = 1;
                                    foreach ($logs as $log) : ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td>
                                                <?php if ($log['status'] === 'in_use') : ?>
                                                    <form action="<?php echo base_url('equipment/markAsReturned/' . $log['id']); ?>" method="post" style="display: inline;">
                                                        <button type="submit" class="btn btn-success btn-sm">Mark as Returned</button>
                                                    </form>
                                                <?php else : ?>
                                                    <span class="text-gray-400">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="font-medium text-white"><?php echo $log['equipment_name']; ?></div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo $log['status']; ?>
                                                </span>
                                            </td>
                                            <td class="text-white"><?php echo $log['staff_name']; ?></td>
                                            <td class="text-white"><?php echo $log['purpose']; ?></td>
                                            <td><?php echo date('M d, Y H:i:s', strtotime($log['created_at'])); ?></td>
                                            <td>
                                                <?php
                                                if (!empty($log['returned_date'])) {
                                                    echo date('M d, Y H:i:s', strtotime($log['returned_date']));
                                                } else {
                                                    echo 'N/A';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-white">No logs found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <?php if (isset($pagination)) : ?>
                            <div class="text-center">
                                <?php echo $pagination; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>