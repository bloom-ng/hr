<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>My Equipment Requests</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Equipment Requests</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <?php if ($this->session->flashdata('success')) : ?>
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Success!</h4>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-xs-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header d-flex justify-content-between align-items-center">
                        <h3 class="box-title text-white">My Equipment Requests</h3>
                        <a href="<?php echo base_url('equipment/requestEquipment'); ?>" class="btn btn-primary hover:border-[#DA7F00] border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]">
                            New Request
                        </a>
                    </div>

                    <div class="box-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Equipment</th>
                                    <th>Request Date</th>
                                    <th>Purpose</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($requests) && !empty($requests)): ?>
                                    <?php foreach ($requests as $request): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if ($request['equipment_image']): ?>
                                                        <img class="rounded-lg mr-2" width="80" height="80" src="<?php echo base_url(); ?>uploads/equipment/<?php echo $request['equipment_image']; ?>" alt="<?php echo $request['equipment_name']; ?>">
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="text-white font-weight-bold"><?php echo $request['equipment_name']; ?></div>
                                                        <small class="text-muted"><?php echo $request['equipment_serial']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($request['requested_date'])); ?></td>
                                            <td><?php echo $request['purpose']; ?></td>
                                            <td>
                                                <span class="badge <?php
                                                                    switch ($request['request_status']) {
                                                                        case 'pending':
                                                                            echo 'badge-warning';
                                                                            break;
                                                                        case 'approved':
                                                                            echo 'badge-success';
                                                                            break;
                                                                        case 'declined':
                                                                            echo 'badge-danger';
                                                                            break;
                                                                        case 'cancelled':
                                                                            echo 'badge-secondary';
                                                                            break;
                                                                        default:
                                                                            echo 'badge-dark';
                                                                    }
                                                                    ?>">
                                                    <?php echo ucfirst($request['request_status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($request['request_status'] === 'pending'): ?>
                                                    <button onclick="showCancelModal(<?php echo $request['id']; ?>, '<?php echo addslashes($request['equipment_name']); ?>')" class="btn btn-danger btn-sm">
                                                        Cancel
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-white">No requests found</td>
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

<!-- Cancel Confirmation Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-[#2C2C2C] text-white">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Request</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="cancel-message"></p>
            </div>
            <div class="modal-footer border-0">
                <form id="cancelForm" method="POST">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button type="submit" class="btn btn-warning">Cancel Request</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keep Request</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showCancelModal(requestId, equipmentName) {
        const message = document.getElementById('cancel-message');
        const form = document.getElementById('cancelForm');

        message.textContent = `Are you sure you want to cancel your request for ${equipmentName}?`;
        form.action = `<?php echo base_url('equipment/cancelRequest/'); ?>${requestId}`;

        $('#cancelModal').modal('show');
    }
</script>