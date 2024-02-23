  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Deductions
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Deductions</a></li>
        <li class="active">Edit Deduction</li>
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
              <h4><i class="icon fa fa-check"></i> Failed!</h4>
              <?php echo $this->session->flashdata('error'); ?>
            </div>
          </div>
        <?php endif; ?>

        <!-- column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
            <div class="box-header text-white">
              <h3 class="box-title">Edit Staff deduction</h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <form role="form" action="<?php echo base_url(); ?>staff/update-deductions/<?php echo $deduction['id'] ?>" method="POST">
              <div class="box-body">

                <input name="staff_id" value="<?php echo $deduction['staff_id']; ?>" type="hidden">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="amount"> Amount (&#8358;)</label>
                    <input min="0" required type="number" value="<?php echo $deduction['amount']; ?>" name="amount" class="form-control" placeholder="Amount">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="date">Date</label>
                    <input required type="date" value="<?php echo $deduction['date']; ?>" name="date" class="form-control" placeholder="Date">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="reason">Reason</label>
                    <textarea required name="reason" class="form-control" placeholder="Reason"><?php echo $deduction['reason']; ?></textarea>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label for="reason">Status</label>
                    <select name="status" class="form-control">
                      <option <?php echo $deduction['status'] == $this->Deduction_model::DEDUCTION_PENDING ? "selected" : "" ?> value="<?php echo $this->Deduction_model::DEDUCTION_PENDING ?>">Pending</option>
                      <option <?php echo $deduction['status'] == $this->Deduction_model::DEDUCTION_PAID ? "selected" : "" ?> value="<?php echo $this->Deduction_model::DEDUCTION_PAID ?>">Paid</option>
                    </select>
                  </div>
                </div>

              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-success bg-[#DA7F00] hover:bg-[#DA7F00] border-0">Update</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (left) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->