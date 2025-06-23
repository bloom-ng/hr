<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Equipment List</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Equipment</li>
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
                    <div class="box-header">
                        <h3 class="box-title text-white">Manage Equipment</h3>
                        <div class="mt-3">
                            <select id="statusFilter" class="form-control">
                                <option value="">All Status</option>
                                <option value="available">Available</option>
                                <option value="damaged">Damaged</option>
                                <option value="in_repair">In Repair</option>
                                <option value="missing">Missing</option>
                            </select>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table bg-[#2C2C2C]">
                            <thead>
                                <tr>
                                    <th>Equipment</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($available_equipment as $item) : ?>
                                    <tr class="equipment-row" data-status="<?php echo $item['status']; ?>">
                                        <td>
                                            <div class="flex items-center">
                                                <?php if ($item['image']) : ?>
                                                    <img class="h-[30px] w-[30px] rounded-full object-cover" src="<?php echo base_url(); ?>uploads/equipment/<?php echo $item['image']; ?>" alt="">
                                                <?php endif; ?>
                                                <div class="<?php echo $item['image'] ? 'ml-3' : ''; ?>">
                                                    <div class="font-medium text-white"><?php echo $item['name']; ?></div>
                                                    <div class="text-gray-400 text-xs"><?php echo $item['unique_id']; ?></div>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="badge <?php
                                                                switch ($item['status']) {
                                                                    case 'available':
                                                                        echo 'badge-success';
                                                                        break;
                                                                    case 'damaged':
                                                                        echo 'badge-danger';
                                                                        break;
                                                                    case 'in_repair':
                                                                        echo 'badge-warning';
                                                                        break;
                                                                    case 'missing':
                                                                        echo 'badge-secondary';
                                                                        break;
                                                                    default:
                                                                        echo 'badge-default';
                                                                } ?>">
                                                <?php echo ucwords(str_replace('_', ' ', $item['status'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button onclick="showRequestModal(<?php echo $item['id']; ?>, '<?php echo addslashes($item['name']); ?>')" class="btn btn-primary btn-sm <?php echo $item['status'] !== 'available' ? 'disabled' : ''; ?>">
                                                Request
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

<!-- Request Modal -->
<div class="modal fade bg-[#3E3E3E]" id="requestModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-[#2C2C2C] text-white border-t-10 border-[#DA7F00]">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="modalTitle">Request Equipment</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class=" text-gray-400" id="equipment-name"></p>
                <form id="requestForm" method="POST">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="equipment_id" id="equipment_id">
                    <div class="form-group">
                        <label for="purpose">Purpose *</label>
                        <textarea id="purpose" name="purpose" rows="3" required class="form-control"></textarea>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary bg-[#DA7F00] border-[#DA7F00]">Submit Request</button>
                        <button type="button" onclick="hideRequestModal()" class="btn btn-secondary bg-[#595959]">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('statusFilter').addEventListener('change', function() {
        const selectedStatus = this.value;
        document.querySelectorAll('.equipment-row').forEach(row => {
            row.style.display = (!selectedStatus || row.dataset.status === selectedStatus) ? '' : 'none';
        });
    });

    function showRequestModal(id, name) {
        document.getElementById('equipment-name').textContent = `Requesting: ${name}`;
        document.getElementById('equipment_id').value = id;
        document.getElementById('requestForm').action = `<?php echo base_url('equipment/requestEquipment/'); ?>${id}`;
        $('#requestModal').modal('show');
    }

    function hideRequestModal() {
        $('#requestModal').modal('hide');
        document.getElementById('requestForm').reset();
    }
</script>