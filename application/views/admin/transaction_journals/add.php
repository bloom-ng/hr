<div class="content-wrapper bg-[#3E3E3E]">
  <section class="content-header">
    <h1>Transaction Journals</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Add Entry</li>
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
        <h3 class="box-title text-white">Add Transaction Entry</h3>
        <div class="box-tools">
          <a class="btn btn-info btn-sm" href="<?php echo base_url(); ?>transaction-journals/registery">View Registery</a>
        </div>
      </div>

      <div class="box-body">
        <form action="<?php echo base_url(); ?>transaction-journals/insert" method="post" class="bg-[#2C2C2C]">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group text-white">
                <label>Type</label>
                <select name="transaction_type" id="tj_transaction_type" class="form-control" required>
                  <option value="expense" <?php echo $transaction_type === 'expense' ? 'selected' : ''; ?>>Expense (Payment)</option>
                  <option value="deposit" <?php echo $transaction_type === 'deposit' ? 'selected' : ''; ?>>Deposit</option>
                </select>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group text-white">
                <label>Category</label>
                <select name="category_id" id="tj_category_id" class="form-control" required>
                  <?php foreach ($categories as $c): ?>
                    <option value="<?php echo $c['id']; ?>" <?php echo ((int)$category_id === (int)$c['id']) ? 'selected' : ''; ?>>
                      <?php echo $c['category_name']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <div class="help-block">
                  <a href="<?php echo base_url(); ?>transaction-journals/categories">Manage Categories</a>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group text-white">
                <label>Balance (auto)</label>
                <input type="text" id="tj_balance_on" class="text-black bg-transparent border border-white form-control" value="<?php echo number_format((float)$balance_on, 2, '.', ''); ?>" disabled>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group text-white">
                <label>Amount</label>
                <input type="number" step="0.01" min="0" name="amount" class="form-control" required>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group text-white">
                <label id="tj_payed_to_label">Payed to</label>
                <input type="text" name="payed_to" class="form-control" placeholder="Enter name/description">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group text-white">
                <label>Payment date</label>
                <input type="date" name="payment_date" id="tj_payment_date" class="form-control" value="<?php echo $payment_date; ?>" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group text-white">
                <label>Payment method</label>
                <select name="payment_method" class="form-control">
                  <option value="">-- Select --</option>
                  <?php foreach ($payment_methods as $m): ?>
                    <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-success btn-flat">Save</button>
        </form>
      </div>
    </div>
  </section>
</div>

<script>
  (function() {
    function updatePayedToLabel() {
      var type = document.getElementById('tj_transaction_type').value;
      document.getElementById('tj_payed_to_label').innerText = (type === 'deposit') ? 'Received from' : 'Payed to';
    }

    function fetchBalance() {
      var categoryId = document.getElementById('tj_category_id').value;
      var paymentDate = document.getElementById('tj_payment_date').value;
      var url = '<?php echo base_url(); ?>transaction-journals/get-balance?category_id=' + encodeURIComponent(categoryId) + '&payment_date=' + encodeURIComponent(paymentDate);

      if (window.jQuery) {
        $.getJSON(url, function(res) {
          var bal = (res && typeof res.balance_on !== 'undefined') ? parseFloat(res.balance_on) : 0;
          document.getElementById('tj_balance_on').value = bal.toFixed(2);
        });
      } else {
        fetch(url).then(function(r) {
          return r.json();
        }).then(function(res) {
          var bal = (res && typeof res.balance_on !== 'undefined') ? parseFloat(res.balance_on) : 0;
          document.getElementById('tj_balance_on').value = bal.toFixed(2);
        });
      }
    }

    document.getElementById('tj_transaction_type').addEventListener('change', updatePayedToLabel);
    document.getElementById('tj_category_id').addEventListener('change', fetchBalance);
    document.getElementById('tj_payment_date').addEventListener('change', fetchBalance);

    updatePayedToLabel();
    fetchBalance(); // on open: current date balance
  })();
</script>