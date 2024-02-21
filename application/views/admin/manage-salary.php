<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Salary Management
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Salary Management</a></li>
      <li class="active">Manage Salary</li>
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
            <h3 class="box-title text-white">Manage Salary</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <table id="example1" class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Staff Name</th>
                    <th>Department</th>
                    <th>Photo</th>
                    <th>Basic Salary</th>
                    <th>Allowance</th>
                    <th>Total Amount</th>
                    <th>Paid On</th>
                    <th>Invoice</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (isset($content)) :
                    $i = 1;
                    foreach ($content as $cnt) :
                  ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $cnt['staff_name']; ?></td>
                        <td><?php echo $cnt['department_name']; ?></td>
                        <td><img src="<?php echo base_url(); ?>uploads/profile-pic/<?php echo $cnt['pic'] ?>" class="img-circle" width="50px" alt="User Image"></td>
                        <td>&#8358;<?php echo $cnt['basic_salary']; ?></td>
                        <td>&#8358;<?php echo $cnt['allowance']; ?></td>
                        <td>&#8358;<?php echo $cnt['total']; ?></td>
                        <td><?php echo date('Y-m-d', strtotime($cnt['added_on'])); ?></td>
                        <td><a href="<?php echo base_url(); ?>salary-invoice/<?php echo $cnt['id']; ?>" class="btn btn-warning">Invoice</a></td>
                        <td>
                          <a href="<?php echo base_url(); ?>delete-salary/<?php echo $cnt['id']; ?>" class="btn bg-[#595959] border border-[#595959] btn-danger">Delete</a>
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