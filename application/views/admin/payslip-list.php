<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Payslips for <?php echo $period; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo base_url(); ?>payslip">Payslip Management</a></li>
      <li class="active">List</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
          <div class="box-header">
            <h3 class="box-title text-white">Staff Payslips</h3>
            <a href="<?php echo base_url(); ?>payslip" class="btn btn-primary pull-right bg-[#DA7F00] border-0">Back</a>
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
                    <th>Net Salary</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (isset($payslips)) :
                    $i = 1;
                    foreach ($payslips as $payslip) :
                        $gross = $payslip['salary'] + $payslip['housing'] + $payslip['transport'] + $payslip['utility'] + $payslip['wardrobe'] + $payslip['medical'] + $payslip['meal_subsidy'];
                        $additions = $payslip['addition_advance_salary'] + $payslip['addition_loans'] + $payslip['addition_commission'] + $payslip['addition_others'];
                        $deductions = $payslip['deduction_advance_salary'] + $payslip['deduction_loans'] + $payslip['deduction_commission'] + $payslip['deduction_others'];
                        $net = $gross + $additions - $deductions;
                  ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $payslip['staff_name']; ?></td>
                        <td><?php echo $payslip['department_name']; ?></td>
                        <td>&#8358; <?php echo number_format($net, 2); ?></td>
                        <td>
                            <a href="<?php echo base_url(); ?>payslip/view/<?php echo $payslip['id']; ?>" class="btn btn-success btn-xs" target="_blank">View Payslip</a>
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
