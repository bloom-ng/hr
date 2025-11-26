<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Payslip Management
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Payslip Management</li>
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
            <h3 class="box-title text-white">Payroll Periods</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <table id="example1" class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Period</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (isset($periods)) :
                    $i = 1;
                    foreach ($periods as $period) :
                  ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $period['period']; ?></td>
                        <td>
                            <?php if($period['payslip_status'] == 1): ?>
                                <span class="label label-success">Generated</span>
                            <?php else: ?>
                                <span class="label label-warning">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($period['payslip_status'] == 0): ?>
                                <a href="<?php echo base_url(); ?>payslip/generate/<?php echo $period['period']; ?>" class="btn btn-primary btn-xs" onclick="return confirm('Are you sure you want to generate/publish payslips for this period?');">Generate</a>
                            <?php endif; ?>
                            <a href="<?php echo base_url(); ?>payslip/manage/<?php echo $period['period']; ?>" class="btn btn-info btn-xs">View</a>
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
