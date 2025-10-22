<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 class="text-white">Fund Request #<?= (int)$request['id']; ?></h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('admin/dashboard'); ?>" class="text-[#DA7F00] hover:text-[#DA7F00]"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo base_url('fund-requests'); ?>" class="text-[#DA7F00] hover:text-[#DA7F00]">Fund Requests</a></li>
      <li class="text-white">View</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <?php if ($this->session->flashdata('success')) : ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> Success!</h4>
        <?php echo $this->session->flashdata('success'); ?>
      </div>
    <?php elseif ($this->session->flashdata('error')) : ?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-times"></i> Error!</h4>
        <?php echo $this->session->flashdata('error'); ?>
      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-md-8">
        <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
          <div class="box-header">
            <h3 class="box-title text-white">Request Information</h3>
            <div class="box-tools">
              <a href="<?= base_url('fund-requests'); ?>" class="btn btn-default btn-sm">
                <i class="fa fa-arrow-left"></i> Back to List
              </a>
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-6">
                <table class="table table-borderless text-white">
                  <tr>
                    <td class="text-[#DA7F00] font-weight-bold">Department:</td>
                    <td><?= html_escape($this->Department_model->get_department_name($request['department_id'])); ?></td>
                  </tr>
                  <tr>
                    <td class="text-[#DA7F00] font-weight-bold">Requested By:</td>
                    <td>
                      <?php $staff = $this->Staff_model->select_staff_byID($request['staff_id']); echo !empty($staff) ? html_escape($staff[0]['staff_name']) : 'N/A'; ?>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-[#DA7F00] font-weight-bold">Amount:</td>
                    <td>₦<?= number_format($request['amount'], 2); ?></td>
                  </tr>
                  <tr>
                    <td class="text-[#DA7F00] font-weight-bold">Status:</td>
                    <td>
                      <?php $statusClass = $request['status']==='Approved'?'bg-green-500':($request['status']==='Pending'?'bg-yellow-500':'bg-red-500'); ?>
                      <span class="px-3 py-1 font-semibold rounded-full <?= $statusClass ?> text-white"><?= html_escape($request['status']); ?></span>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-[#DA7F00] font-weight-bold">Payment Status:</td>
                    <td>
                      <?php $payClass = $request['payment_status']==='Paid'?'bg-green-500':($request['payment_status']==='Pending'?'bg-yellow-500':'bg-red-500'); ?>
                      <span class="px-3 py-1 font-semibold rounded-full <?= $payClass ?> text-white"><?= html_escape($request['payment_status']); ?></span>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-[#DA7F00] font-weight-bold">Created:</td>
                    <td><?= date('M d, Y H:i', strtotime($request['created_at'])); ?></td>
                  </tr>
                </table>
              </div>
              <div class="col-md-6">
                <h4 class="text-white"><i class="fa fa-commenting text-[#DA7F00]"></i> Message</h4>
                <div class="bg-[#3E3E3E] border border-[#555] p-4 rounded">
                  <p class="text-white mb-0"><?= nl2br(html_escape($request['message'])); ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
          <div class="box-header">
            <h3 class="box-title text-white">Actions</h3>
          </div>
          <div class="box-body">
            <?php $role = $this->session->userdata('role'); ?>
            <?php if ($role === 'super' && $request['status'] === 'Pending'): ?>
              <a href="<?= base_url('fund-requests/approve/'.$request['id']); ?>" class="btn btn-success btn-block mb-3" onclick="return confirm('Approve this request?')">
                <i class="fa fa-check"></i> Approve Request
              </a>
              <a href="<?= base_url('fund-requests/decline/'.$request['id']); ?>" class="btn btn-danger btn-block mb-3" onclick="return confirm('Decline this request?')">
                <i class="fa fa-times"></i> Decline Request
              </a>
            <?php endif; ?>

            <?php if ($role === 'finance'): ?>
              <form method="post" action="<?= base_url('fund-requests/update-payment/'.$request['id']); ?>">
                <div class="form-group">
                  <label for="payment_status" class="text-white">Payment Status</label>
                  <select name="payment_status" id="payment_status" class="form-control bg-[#3E3E3E] border border-[#555] text-white">
                    <option <?= $request['payment_status']==='Pending'?'selected':''; ?>>Pending</option>
                    <option <?= $request['payment_status']==='Paid'?'selected':''; ?>>Paid</option>
                    <option <?= $request['payment_status']==='Declined'?'selected':''; ?>>Declined</option>
                  </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block bg-[#DA7F00] border-[#DA7F00] hover:bg-[#DA7F00]">Update Payment</button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
