<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header -->
    <section class="content-header">
        <h1>Edit Equipment</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Edit Equipment</li>
        </ol>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <?php if (validation_errors()) : ?>
                    <div class="alert alert-danger alert-dismissible bg-red-500 text-white">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <h4><i class="icon fa fa-warning"></i> Please fix the errors below:</h4>
                        <?php echo validation_errors(); ?>
                    </div>
                <?php endif; ?>

                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header with-border">
                        <h3 class="box-title text-white">Edit Equipment</h3>
                    </div>

                    <form action="<?php echo base_url('equipment/edit/' . $equipment['id']); ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                        <div class="box-body text-white">
                            <!-- Image Preview -->
                            <div class="form-group">
                                <label>Equipment Image</label>
                                <?php if (!empty($equipment['image'])) : ?>
                                    <div class="mb-3">
                                        <img src="<?php echo base_url(); ?>uploads/equipment/<?php echo $equipment['image']; ?>" alt="<?php echo $equipment['name']; ?>" class="w-32 h-32 object-cover rounded-lg">
                                        <p class="mt-1 text-sm opacity-75">Current image</p>
                                    </div>
                                <?php endif; ?>
                                <div class="mt-2">
                                    <input type="file" name="equipment_image" id="image" class="form-control text-white bg-[#3E3E3E] border-gray-600 rounded">
                                    <p class="help-block text-sm opacity-75">Upload a new image (PNG, JPG, GIF)</p>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="form-group">
                                <label for="name">Equipment Name *</label>
                                <input type="text" name="name" id="name" class="form-control text-white bg-[#3E3E3E] border-gray-600 rounded" required value="<?php echo set_value('name', $equipment['name']); ?>">
                            </div>

                            <!-- Serial Number -->
                            <div class="form-group">
                                <label for="serial_number">Serial Number (Unique ID) *</label>
                                <input type="text" name="unique_id" id="unique_id" class="form-control text-white bg-[#3E3E3E] border-gray-600 rounded" required value="<?php echo set_value('unique_id', $equipment['unique_id']); ?>">
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label for="status">Status *</label>
                                <select id="status" name="status" class="form-control bg-[#3E3E3E] border-gray-600 text-white rounded" required>
                                    <option value="available" <?php echo set_select('status', 'Available', ($equipment['status'] == 'available')); ?>>Available</option>
                                    <option value="in_use" <?php echo set_select('status', 'In Use', ($equipment['status'] == 'in_use')); ?>>In Use</option>
                                    <option value="in_repair" <?php echo set_select('status', 'In Repair', ($equipment['status'] == 'in_repair')); ?>>In Repair</option>
                                    <option value="damaged" <?php echo set_select('status', 'Damaged', ($equipment['status'] == 'damaged')); ?>>Damaged</option>
                                    <option value="missing" <?php echo set_select('status', 'Missing', ($equipment['status'] == 'missing')); ?>>Missing</option>
                                </select>
                            </div>
                        </div>

                        <div class="box-footer bg-[#2C2C2C] text-right border-0">
                            <a href="<?php echo base_url('equipment'); ?>" class="btn bg-[#595959] hover:bg-[#595959] text-white border-0">Cancel</a>
                            <button type="submit" class="btn ml-3 border border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00] text-white">
                                Update Equipment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Optional JS for Image Preview -->
<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'mt-3 w-32 h-32 object-cover rounded-lg';

                const previewContainer = document.querySelector('.form-group img');
                if (previewContainer) previewContainer.replaceWith(img);
            };
            reader.readAsDataURL(file);
        }
    });
</script>