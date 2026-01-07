<div class="content-wrapper bg-[#3E3E3E]">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Department Budgets (<?php echo $year; ?>)
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Department Budgets</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <?php if($this->session->flashdata('success')): ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Success!</h4>
        <?php echo $this->session->flashdata('success'); ?>
      </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-ban"></i> Error!</h4>
        <?php echo $this->session->flashdata('error'); ?>
      </div>
    <?php endif; ?>

    <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
      <div class="box-header">
        <h3 class="box-title text-white">Budget Overview</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table id="example1" class="table">
          <thead>
          <tr>
            <th>Department</th>
            <th>Total Budget</th>
            <th>Spent (Approved)</th>
            <th>Pending</th>
            <th>Balance</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach($budgets as $b): ?>
          <tr>
            <td><?php echo $b['department_name']; ?></td>
            <td><?php echo number_format($b['budget_amount'], 2); ?></td>
            <td><?php echo number_format($b['spent_amount'], 2); ?></td>
            <td><?php echo number_format($b['pending_amount'], 2); ?></td>
            <td>
                <?php 
                    $balance_class = 'text-green';
                    if($b['balance_amount'] < 0) $balance_class = 'text-red';
                ?>
                <span class="<?php echo $balance_class; ?>">
                    <?php echo number_format($b['balance_amount'], 2); ?>
                </span>
            </td>
            <td>
                <a href="<?php echo base_url(); ?>budget/manage/<?php echo $b['department_id']; ?>" class="btn btn-info btn-flat btn-sm">Manage</a>
            </td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->

  </section>
  <!-- /.content -->
</div>
