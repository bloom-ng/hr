<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 class="text-white">Create Fund Request</h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('admin/dashboard'); ?>" class="text-[#DA7F00] hover:text-[#DA7F00]"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo base_url('fund-requests'); ?>" class="text-[#DA7F00] hover:text-[#DA7F00]">Fund Requests</a></li>
      <li class="text-white">Create</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
          <div class="box-header">
            <h3 class="box-title text-white">Request Details</h3>
            <div class="box-tools">
              <a href="<?php echo base_url('fund-requests'); ?>" class="btn btn-default hover:border-[#DA7F00] border-[#DA7F00] bg-[#3E3E3E] hover:bg-[#DA7F00] text-white">
                <i class="fa fa-arrow-left"></i> Back to Fund Requests
              </a>
            </div>
          </div>
          <div class="box-body">
            <?php if (validation_errors()) : ?>
              <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-times"></i> Validation Error!</h4>
                <?= validation_errors() ?>
              </div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('fund-requests/create'); ?>">
              <div class="form-group">
                <label for="amount" class="text-white">Amount</label>
                <input type="number" step="0.01" min="0" class="form-control bg-[#3E3E3E] border border-[#555] text-white" id="amount" name="amount" placeholder="Enter amount" required>
              </div>
              <div class="form-group">
                <label for="message" class="text-white">Message</label>
                <textarea class="form-control bg-[#3E3E3E] border border-[#555] text-white" id="message" name="message" rows="4" placeholder="Request Message" required></textarea>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary hover:border-[#DA7F00] border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]">Submit Request</button>
                <a href="<?= base_url('fund-requests'); ?>" class="btn btn-default bg-[#3E3E3E] hover:bg-[#DA7F00] border-[#DA7F00] text-white">Cancel</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
