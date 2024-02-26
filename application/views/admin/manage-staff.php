<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Staff
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Staff Management</a></li>
      <li class="active">Manage Staff</li>
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
          <div class="box-header">
            <h3 class="box-title text-white">Manage Staff</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <table id="example1" class="table">
                <thead>
                  <tr>
                    <th>Actions</th>

                    <th>#</th>
                    <th>Name</th>
                    <th>Photo</th>
                    <th>Department</th>
                    <th>Gender</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>DOB</th>
                    <th>Joined On</th>
                    <th>Address</th>

                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (isset($content)) :
                    $i = 1;
                    foreach ($content as $cnt) :
                  ?>
                      <tr>
                        <td>
                          <a href="<?php echo base_url(); ?>staff/manage-deductions/<?php echo $cnt['id']; ?>" class="btn bg-transparent text-blue-500">Deductions</a>
                          <a href="<?php echo base_url(); ?>edit-staff/<?php echo $cnt['id']; ?>" class="btn bg-transparent text-green-500">Edit</a>
                          <a href="<?php echo base_url(); ?>delete-staff/<?php echo $cnt['id']; ?>" class="btn bg-transparent text-red-500">Delete</a>
                        </td>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $cnt['staff_name']; ?></td>
                        <td><img src="<?php echo base_url(); ?>uploads/profile-pic/<?php echo $cnt['pic'] ?>" class="img-circle" width="50px" alt="User Image"></td>
                        <td><?php echo $cnt['department_name']; ?></td>
                        <td><?php echo $cnt['gender']; ?></td>
                        <td><?php echo $cnt['mobile']; ?></td>
                        <td><?php echo $cnt['email']; ?></td>
                        <td><?php echo date('d-m-Y', strtotime($cnt['dob'])); ?></td>
                        <td><?php echo date('d-m-Y', strtotime($cnt['doj'])); ?></td>
                        <td><?php echo $cnt['address']; ?></td>


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
<!-- Attendance Modal -->
<?php if (isset($content)) : ?>
<?php foreach ($content as $cnt) : ?>
  <div class="modal fade" id="attendanceModal<?php echo $cnt['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel<?php echo $cnt['id']; ?>">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="attendanceModalLabel<?php echo $cnt['id']; ?>">Attendance</h4>
        </div>
        <div class="modal-body">
          <?php echo form_open('Staff/attendance'); ?>
          <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" id="date" name="date" max="<?php echo date('Y-m-d'); ?>" required>
          </div>
          <div class="form-group">
            <label for="time_in">Time In:</label>
            <input type="time" class="form-control" id="time_in" name="time_in">
          </div>
          <div class="form-group">
            <label for="time_out">Time Out:</label>
            <input type="time" class="form-control" id="time_out" name="time_out">
          </div>
          <div class="form-group">
            <label for="notes">Notes:</label>
            <textarea class="form-control" id="notes" name="notes"></textarea>
          </div>
          <input type="hidden" name="staff_id" value="<?php echo $cnt['id']; ?>">
          <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>
<?php endif; ?>