<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Equipment Management
        </h1>
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
                        <h3 class="box-title text-white">Manage Equipment</h3>
                        <div class="d-flex mt-3">
                            <a href="<?php echo base_url('equipment/add'); ?>" class="btn btn-primary hover:border-[#DA7F00] border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]">
                                Add Equipment
                            </a>
                        </div>
                    </div>

                    <div class="box-body">
                        <table id="equipment-table" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Equipment</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($equipment) && !empty($equipment)) : ?>
                                    <?php $i = 1;
                                    foreach ($equipment as $item) : ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td>
                                                <div class="flex items-center">
                                                    <?php if ($item['image']) : ?>
                                                        <div class="h-[30px] w-[30px]">
                                                            <img class="h-[30px] w-[30px] rounded-lg" 
                                                                src="<?php echo $item['image'] 
                                                                    ? base_url('uploads/equipment/' . $item['image']) 
                                                                    : 'https://ui-avatars.com/api/?name=' . urlencode($item['name']); ?>" 
                                                                alt="<?php echo htmlspecialchars($item['name']); ?>" />
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="ml-3">
                                                        <div class="font-medium text-white"><?php echo $item['name']; ?></div>
                                                        <div class="text-white opacity-75"><?php echo $item['unique_id']; ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge 
                          <?php echo $item['status'] === 'Available' ? 'bg-success' : ($item['status'] === 'In Use' ? 'bg-info' : 'bg-warning'); ?>">
                                                    <?php echo $item['status']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($item['updated_at'])); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary text-white hover:border-[#DA7F00] border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]" data-toggle="modal" data-target="#viewEquipment<?php echo $item['id'] ?>">
                                                    View
                                                </button>
                                                <a href="<?php echo base_url('equipment/logs/' . $item['id']); ?>" class="btn btn-primary">Show Log</a>
                                                <a href="<?php echo base_url('equipment/edit/' . $item['id']); ?>" class="btn btn-success">Edit</a>
                                                <a href="javascript:void(0);" onclick="deleteEquipment(<?php echo $item['id']; ?>)" class="btn btn-danger">Delete</a>
                                            </td>
                                        </tr>

                                        <!-- View Modal -->
                                        <div class="modal fade" id="viewEquipment<?php echo $item['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
                                                <div class="modal-content bg-[#3E3E3E] text-white">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title"><?php echo $item['name']; ?></h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-lg">
                                                        <p><strong>Serial Number:</strong> <?php echo $item['unique_id']; ?></p>
                                                        <p><strong>Status:</strong> <?php echo $item['status']; ?></p>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn bg-[#595959] hover:bg-[#595959]" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-white">No equipment found.</td>
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

<!-- JS Delete Modal Handling -->
<script>
    function deleteEquipment(id) {
        if (confirm("Are you sure you want to delete this equipment? This action cannot be undone.")) {
            window.location.href = "<?php echo base_url('equipment/delete/'); ?>" + id;
        }
    }
</script>