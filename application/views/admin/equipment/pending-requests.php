<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Pending Equipment Requests
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Pending Requests</li>
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
            <?php endif; ?>

            <div class="col-xs-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Manage Equipment Requests</h3>
                    </div>

                    <div class="box-body">
                        <p class="text-gray-400 mb-4">A list of all pending equipment requests requiring your approval or rejection.</p>
                        <table class="table text-white bg-[#2C2C2C]">
                            <thead>
                                <tr>
                                    <th>Requestor</th>
                                    <th>Equipment</th>
                                    <th>Request Date</th>
                                    <th>Purpose</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($requests) && !empty($requests)) : ?>
                                    <?php foreach ($requests as $request) : ?>
                                        <tr>
                                            <td>
                                                <div class="flex items-center">
                                                    <?php if ($request['user_image']) : ?>
                                                        <img class="h-[25px] w-[25px] rounded-full" src="<?php echo base_url(); ?>uploads/profile-pic/<?php echo $request['user_image']; ?>" alt="<?php echo $request['staff_name']; ?>">
                                                    <?php endif; ?>
                                                    <div class="<?php echo $request['user_image'] ? 'ml-3' : ''; ?>">
                                                        <div class="font-medium text-white"><?php echo $request['staff_name']; ?></div>
                                                        <div class="text-gray-400 text-xs"><?php echo $request['user_department']; ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium text-white"><?php echo $request['equipment_name']; ?></div>
                                                <div class="text-gray-400 text-xs"><?php echo $request['equipment_serial']; ?></div>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($request['requested_date'])); ?></td>
                                            <td><?php echo $request['purpose']; ?></td>
                                            <td>
                                                <button onclick="showActionModal('approve', <?php echo $request['id']; ?>, '<?php echo $request['equipment_name']; ?>')" class="btn btn-success btn-sm mr-2">Approve</button>
                                                <button onclick="showActionModal('decline', <?php echo $request['id']; ?>, '<?php echo $request['equipment_name']; ?>')" class="btn btn-danger btn-sm">Decline</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-white">No pending requests found</td>
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

<!-- Action Confirmation Modal -->
<div class="modal fade bg-[#3E3E3E]" id="actionModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog bg-[#3E3E3E] modal-lg" role="document">
        <div class="modal-content bg-[#2C2C2C] text-white border-t-10 border-[#DA7F00]">
            <div class="modal-header border-0">
                <div id="modalIcon" class="mr-3"></div>
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="modalMessage" class="text-xl text-gray-400"></p>
            </div>
            <form id="actionForm" method="POST">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-footer border-0">
                    <button type="submit" id="confirmButton" class="btn btn-success">Confirm</button>
                    <button type="button" onclick="hideActionModal()" class="btn bg-[#595959] text-white" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showActionModal(action, requestId, equipmentName) {
        const modal = $('#actionModal');
        const modalIcon = document.getElementById('modalIcon');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const actionForm = document.getElementById('actionForm');
        const confirmButton = document.getElementById('confirmButton');

        if (action === 'approve') {
            modalIcon.innerHTML = '<i class="fa fa-check-circle text-green-500 fa-lg"></i>';
            modalTitle.textContent = 'Approve Equipment Request';
            modalMessage.textContent = `Are you sure you want to approve the request for ${equipmentName}?`;
            confirmButton.className = 'btn btn-success';
            actionForm.action = `<?php echo base_url('equipment/approveRequest/'); ?>${requestId}`;
        } else {
            modalIcon.innerHTML = '<i class="fa fa-times-circle text-red-500 fa-lg"></i>';
            modalTitle.textContent = 'Decline Equipment Request';
            modalMessage.textContent = `Are you sure you want to decline the request for ${equipmentName}?`;
            confirmButton.className = 'btn btn-danger';
            actionForm.action = `<?php echo base_url('equipment/declineRequest/'); ?>${requestId}`;
        }

        modal.modal('show');
    }

    function hideActionModal() {
        $('#actionModal').modal('hide');
    }
</script>