<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Payslip | Bloom EMS</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
  <style>
      .invoice { margin: 40px auto; max-width: 800px; padding: 40px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0,0,0,0.1); background: #fff; }
      @media print {
          .no-print { display: none !important; }
          .invoice { border: none; box-shadow: none; margin: 0; padding: 0; }
      }
      .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
          border: 1px solid #ddd;
      }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini bg-gray">
<div class="wrapper" style="background: #ecf0f5;">

  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <img src="<?php echo base_url(); ?>assets/dist/img/bloom.png" alt="Bloom Digital Media Ltd." style="height: 50px;">
        <h2 class="page-header" style="border-bottom: 2px solid #DA7F00; padding-bottom: 10px;">
          <span class="text-[#DA7F00]">Bloom</span> Digital Media Ltd.
          <small class="pull-right">Date: <?php echo date('d/m/Y'); ?></small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        From
        <address>
          <strong>Bloom Digital Media Ltd.</strong><br>
          S03 Pathfield Mall, <br>
          4th Avenue, Gwarimpa, <br>
          Abuja.<br>
          Email: info@bloomdigitmedia.com
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        To
        <address>
          <strong><?php echo $payslip['staff_name']; ?></strong><br>
          Department: <?php echo $payslip['department_name']; ?><br>
          Phone: <?php echo $payslip['mobile']; ?><br>
          Email: <?php echo $payslip['email']; ?>
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>Payslip #<?php echo str_pad($payslip['id'], 6, '0', STR_PAD_LEFT); ?></b><br>
        <br>
        <b>Period:</b> <?php echo $payslip['period']; ?><br>
        <b>Generated On:</b> <?php echo date('d/m/Y', strtotime($payslip['date'])); ?>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <?php
        $gross = $payslip['salary'] + $payslip['housing'] + $payslip['transport'] + $payslip['utility'] + $payslip['wardrobe'] + $payslip['medical'] + $payslip['meal_subsidy'];
        $additions = $payslip['addition_advance_salary'] + $payslip['addition_loans'] + $payslip['addition_commission'] + $payslip['addition_others'];
        $deductions = $payslip['deduction_advance_salary'] + $payslip['deduction_loans'] + $payslip['deduction_commission'] + $payslip['deduction_others'];
        $net = $gross + $additions - $deductions;
    ?>

    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table table-bordered">
          <thead>
          <tr style="background-color: #f9f9f9;">
            <th style="width: 50%">Earnings</th>
            <th style="width: 20%" class="text-right">Amount (&#8358;)</th>
            <th style="width: 30%">Deductions</th>
            <th style="width: 20%" class="text-right">Amount (&#8358;)</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>Basic Salary</td>
            <td class="text-right"><?php echo number_format($payslip['salary'], 2); ?></td>
            <td>Advance Salary</td>
            <td class="text-right"><?php echo number_format($payslip['deduction_advance_salary'], 2); ?></td>
          </tr>
          <tr>
            <td>Housing Allowance</td>
            <td class="text-right"><?php echo number_format($payslip['housing'], 2); ?></td>
            <td>Loans</td>
            <td class="text-right"><?php echo number_format($payslip['deduction_loans'], 2); ?></td>
          </tr>
          <tr>
            <td>Transport Allowance</td>
            <td class="text-right"><?php echo number_format($payslip['transport'], 2); ?></td>
            <td>Commission Deduction</td>
            <td class="text-right"><?php echo number_format($payslip['deduction_commission'], 2); ?></td>
          </tr>
          <tr>
            <td>Utility Allowance</td>
            <td class="text-right"><?php echo number_format($payslip['utility'], 2); ?></td>
            <td>Other Deductions</td>
            <td class="text-right"><?php echo number_format($payslip['deduction_others'], 2); ?></td>
          </tr>
          <tr>
            <td>Wardrobe Allowance</td>
            <td class="text-right"><?php echo number_format($payslip['wardrobe'], 2); ?></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td>Medical Allowance</td>
            <td class="text-right"><?php echo number_format($payslip['medical'], 2); ?></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td>Meal Subsidy</td>
            <td class="text-right"><?php echo number_format($payslip['meal_subsidy'], 2); ?></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="4" style="background-color: #f0f0f0; font-weight: bold;">Additions</td>
          </tr>
          <tr>
            <td>Advance Salary Addition</td>
            <td class="text-right"><?php echo number_format($payslip['addition_advance_salary'], 2); ?></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td>Loans Addition</td>
            <td class="text-right"><?php echo number_format($payslip['addition_loans'], 2); ?></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td>Commission</td>
            <td class="text-right"><?php echo number_format($payslip['addition_commission'], 2); ?></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td>Other Additions</td>
            <td class="text-right"><?php echo number_format($payslip['addition_others'], 2); ?></td>
            <td></td>
            <td></td>
          </tr>
          </tbody>
          <tfoot>
            <tr style="font-weight: bold; background-color: #DA7F00; color: white;">
                <td>Total Gross Earnings</td>
                <td class="text-right">&#8358; <?php echo number_format($gross + $additions, 2); ?></td>
                <td>Total Deductions</td>
                <td class="text-right">&#8358; <?php echo number_format($deductions, 2); ?></td>
            </tr>
            <tr style="font-size: 1.2em; font-weight: bold;">
                <td colspan="2"></td>
                <td class="text-right">NET PAY</td>
                <td class="text-right" style="border-bottom: 3px double #000;">&#8358; <?php echo number_format($net, 2); ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-xs-12">
        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
          This is a computer-generated document and does not require a signature.
        </p>
      </div>
    </div>
    <!-- /.row -->

    <!-- this row will not appear when printing -->
    <div class="row no-print">
      <div class="col-xs-12">
        <button onclick="window.print();" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
        <!-- <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
          <i class="fa fa-download"></i> Generate PDF
        </button> -->
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
