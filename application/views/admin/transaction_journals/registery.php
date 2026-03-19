<div class="content-wrapper bg-[#3E3E3E]">
  <section class="content-header">
    <h1>Transaction Journals - Registery</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Registery</li>
    </ol>
  </section>

  <section class="content">
    <?php if ($this->session->flashdata('success')): ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Success!</h4>
        <?php echo $this->session->flashdata('success'); ?>
      </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-ban"></i> Error!</h4>
        <?php echo $this->session->flashdata('error'); ?>
      </div>
    <?php endif; ?>

    <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
      <div class="box-header with-border">
        <h3 class="box-title text-white">Filter</h3>
        <div class="box-tools">
          <a class="btn btn-success btn-sm" href="<?php echo base_url(); ?>transaction-journals/add">Add Entry</a>
          <a class="btn btn-info btn-sm" href="<?php echo base_url(); ?>transaction-journals/categories">Categories</a>
        </div>
      </div>
      <div class="box-body">
        <form method="get" action="<?php echo base_url(); ?>transaction-journals/registery" class="bg-[#2C2C2C]">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group text-white">
                <label>Registery (Category)</label>
                <select name="category_id" class="form-control" required>
                  <?php foreach ($categories as $c): ?>
                    <option value="<?php echo $c['id']; ?>" <?php echo ((int)$category_id === (int)$c['id']) ? 'selected' : ''; ?>>
                      <?php echo $c['category_name']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group text-white">
                <label>From</label>
                <input type="date" name="from_date" class="form-control" value="<?php echo $from_date; ?>" required>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group text-white">
                <label>To</label>
                <input type="date" name="to_date" class="form-control" value="<?php echo $to_date; ?>" required>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label class="text-white">&nbsp;</label>
                <button class="btn btn-primary btn-flat form-control" type="submit">Apply</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
      <div class="box-header">
        <h3 class="box-title text-white">Registery</h3>
      </div>
      <div class="box-body table-responsive">
        <?php
        $ending_balance = (float)$starting_balance;
        if (!empty($rows)) {
          $last = end($rows);
          if (isset($last['balance_on'])) {
            $ending_balance = (float)$last['balance_on'];
          }
          reset($rows);
        }
        ?>
        <table id="example1" class="table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Description</th>
              <th>Payment</th>
              <th>Deposit</th>
              <th>Balance On</th>
              <th>Category</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($rows)): ?>
              <?php foreach ($rows as $r): ?>
                <tr>
                  <td><?php echo date('d M Y', strtotime($r['payment_date'])); ?></td>
                  <td><?php echo $r['payed_to']; ?></td>
                  <td><?php echo $r['payment_value'] !== null ? number_format((float)$r['payment_value'], 2) : ''; ?></td>
                  <td><?php echo $r['deposit_value'] !== null ? number_format((float)$r['deposit_value'], 2) : ''; ?></td>
                  <td><?php echo number_format((float)$r['balance_on'], 2); ?></td>
                  <td><?php echo $r['category_name'] ?? ''; ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-muted">No transactions found for this period.</td>
              </tr>
            <?php endif; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="4" class="text-left">Current balance (<?php echo date('d M Y', strtotime($from_date)); ?> - <?php echo date('d M Y', strtotime($to_date)); ?>)</th>
              <th class="text-green-400"><?php echo number_format($ending_balance, 2); ?></th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </section>
</div>