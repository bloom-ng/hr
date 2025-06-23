<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="text-white">Add Equipment</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active text-white">Add Equipment</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <?php echo validation_errors('<div class="col-md-12">
          <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-times"></i> Failed!</h4>', '</div>
        </div>'); ?>

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

            <!-- Form column -->
            <div class="col-md-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C] text-white">
                    <div class="box-header with-border">
                        <h3 class="box-title text-white">Add Equipment</h3>
                    </div>

                    <?php echo form_open_multipart('equipment/add'); ?>
                    <div class="box-body">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Equipment Name *</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter Equipment Name" required value="<?php echo set_value('name'); ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Serial Number (Unique ID) *</label>
                                <input type="text" name="unique_id" class="form-control" placeholder="Enter Serial Number" required value="<?php echo set_value('unique_id'); ?>">
                            </div>
                        </div>

                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label>Status *</label>
                                <select name="status" class="form-control" required>
                                    <option value="available" <?php echo set_select('status', 'available', true); ?>>Available</option>
                                    <option value="in_use" <?php echo set_select('status', 'in_use'); ?>>In Use</option>
                                    <option value="in_repair" <?php echo set_select('status', 'in_repair'); ?>>In Repair</option>
                                    <option value="damaged" <?php echo set_select('status', 'damaged'); ?>>Damaged</option>
                                    <option value="missing" <?php echo set_select('status', 'missing'); ?>>Missing</option>
                                </select>
                            </div>
                        </div> -->

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Upload Image</label>
                                <input type="file" name="equipment_image" class="form-control" accept="image/*">
                            </div>
                        </div>

                    </div>

                    <div class="box-footer border-0 bg-[#2C2C2C]">
                        <a href="<?php echo base_url('equipment'); ?>" class="btn bg-[#595959] text-white hover:bg-[#595959]">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-success pull-right border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]">
                            Add Equipment
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>