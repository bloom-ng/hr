<div class="content-wrapper bg-[#3E3E3E]">
  <section class="content-header">
    <h1>
      Manage Budget: <?php echo $budget_info['department_name']; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo base_url(); ?>budget">Budgets</a></li>
      <li class="active">Manage</li>
    </ol>
  </section>

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

    <div class="row">
        <!-- Budget Info -->
        <div class="col-md-4">
            <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                <div class="box-header with-border">
                    <h3 class="box-title text-white">Budget Summary</h3>
                </div>
                <div class="box-body">
                    <p><strong>Total Budget:</strong> <?php echo number_format($budget_info['budget_amount'], 2); ?></p>
                    <p><strong>Spent (Approved):</strong> <?php echo number_format($budget_info['spent_amount'], 2); ?></p>
                    <p><strong>Pending:</strong> <?php echo number_format($budget_info['pending_amount'], 2); ?></p>
                    <hr>
                    <?php 
                        $balance = $budget_info['balance_amount'];
                        $color = $balance >= 0 ? 'green' : 'red';
                    ?>
                    <h3 class="text-<?php echo $color; ?>">Balance: <?php echo number_format($balance, 2); ?></h3>
                </div>
                
                <?php if(in_array($role, ['finance', 'super'])): ?>
                <div class="box-footer">
                    <form action="<?php echo base_url(); ?>budget/save_budget" method="post" class="bg-[#2C2C2C]">
                        <div class="input-group">
                            <input type="hidden" name="department_id" value="<?php echo $budget_info['department_id']; ?>">
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="Update Budget Amount" value="<?php echo $budget_info['budget_amount']; ?>" required>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-success btn-flat">Set Budget</button>
                            </span>
                        </div>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if($role == 'finance'): ?>
        <div class="col-md-8">
            <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                <div class="box-header with-border">
                    <h3 class="box-title text-white">Add Expense</h3>
                </div>
                <div class="box-body">
                    <form action="<?php echo base_url(); ?>budget/add_expense" method="post" class="bg-[#2C2C2C]">
                        <input type="hidden" name="department_id" value="<?php echo $budget_info['department_id']; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Item</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="number" step="0.01" name="amount" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-flat">Add Expense</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Logs -->
    <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
        <div class="box-header">
            <h3 class="box-title text-white">Spending Logs</h3>
        </div>
        <div class="box-body table-responsive">
            <table id="example1" class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <?php if($role == 'super'): ?>
                        <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($logs as $log): ?>
                    <tr>
                        <td><?php echo date('d M Y', strtotime($log['date'])); ?></td>
                        <td><?php echo $log['title']; ?></td>
                        <td><?php echo $log['description']; ?></td>
                        <td><?php echo number_format($log['amount'], 2); ?></td>
                        <td>
                            <?php 
                                $label = 'label-warning';
                                if($log['status'] == 'approved') $label = 'label-success';
                                elseif($log['status'] == 'rejected') $label = 'label-danger';
                            ?>
                            <span class="label <?php echo $label; ?>"><?php echo ucfirst($log['status']); ?></span>
                        </td>
                        <td><?php echo $log['creator_name']; ?></td>
                        <?php if($role == 'super'): ?>
                        <td>
                            <?php if($log['status'] == 'pending'): ?>
                                <a href="<?php echo base_url(); ?>budget/update_status/<?php echo $log['id']; ?>/approved" class="btn btn-success btn-xs" onclick="return confirm('Approve this expense? This will deduct from the budget.');">Approve</a>
                                <a href="<?php echo base_url(); ?>budget/update_status/<?php echo $log['id']; ?>/rejected" class="btn btn-danger btn-xs" onclick="return confirm('Reject this expense?');">Reject</a>
                            <?php else: ?>
                                <span class="text-muted">No Action</span>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

  </section>
</div>
