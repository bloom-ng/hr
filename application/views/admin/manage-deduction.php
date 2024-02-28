<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Fines
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="/manage-staff">Manage Staff</a></li>
      <li class="active">Manage Fines</li>
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

      <div class="col-xs-12">
        <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
          <div class="box-header text-white">
            <h3 class="box-title">Fines for <?php echo $staff['staff_name'] ?></h3>
            <div class="d-flex mt-3">
				<?php if (in_array($this->session->userdata('role'), ["finance", "super"])) : ?>
              <button type="button" class="btn btn-primary bg-[#DA7F00] hover:bg-[#DA7F00] border-0" data-toggle="modal" data-target="#staticBackdrop">
                Add
              </button>
				<?php endif; ?>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <table id="example1" class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Amount (&#8358;)</th>
                    <th>Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (isset($deductions)) :
                    $i = 1;
                    foreach ($deductions as $cnt) :
                  ?>
                      <tr>
                        <td><?php echo $i; ?></td>

                        <td><?php echo $cnt['amount']; ?></td>
                        <td><?php echo date('d-m-Y', strtotime($cnt['date'])); ?></td>
                        <td><?php echo $cnt['reason']; ?></td>
                        <td><?php echo  $this->Deduction_model->getStatusMapping()[$cnt['status']]; ?></td>
                        <td>
							<?php if (in_array($this->session->userdata('role'), ["finance", "super"])) : ?>
							  <a href="<?php echo base_url(); ?>staff/edit-deductions/<?php echo $cnt['id']; ?>" class="btn bg-[#DA7F00] hover:bg-[#DA7F00] border-0 btn-success">Edit</a>
							  <a href="<?php echo base_url(); ?>staff/delete-deductions/<?php echo $cnt['id']; ?>" class="btn  bg-[#595959] hover:bg-[#595959] border-0 btn-danger">Delete</a>
							<?php endif; ?>
						</td>
                      </tr>
                  <?php
                      $i++;
                    endforeach;
                  endif;
                  ?>

                </tbody>
              </table>
            </div>
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
<!-- /.content-wrapper -->

<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade bg-[#3E3E3E]" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog bg-[#3E3E3E]" role="document">
    <div class="modal-content bg-[#3E3E3E]">
      <div class="modal-header bg-[#3E3E3E]">
        <h5 class="modal-title" id="addDeduction">Add Staff Fine</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" action="<?php echo base_url(); ?>staff/insert-deductions" method="POST">
          <div class="box-body">

            <input name="staff_id" value="<?php echo $staff['id']; ?>" type="hidden">
            <div class="col-md-12">
              <div class="form-group">
                <label for="amount">Amount (&#8358;)</label>
                <input min="0" required type="number" name="amount" class="form-control" placeholder="Amount">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="date">Date</label>
                <input required type="date" name="date" class="form-control" placeholder="Date">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="reason">Reason</label>
                <textarea required name="reason" class="form-control" placeholder="Reason"></textarea>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label for="reason">Status</label>
                <select name="status" class="form-control">
                  <option value="<?php echo $this->Deduction_model::DEDUCTION_PENDING ?>">Pending</option>
                  <option value="<?php echo $this->Deduction_model::DEDUCTION_PAID ?>">Paid</option>
                </select>
              </div>
            </div>

          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary bg-[#DA7F00] hover:bg-[#DA7F00] border-0" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success  bg-[#595959] hover:bg-[#595959] border-0">Submit</button>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
