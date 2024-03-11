  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper bg-[#3E3E3E]">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
              Voucher
          </h1>
          <ol class="breadcrumb">
              <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
              <li><a href="#">Voucher</a></li>
              <li class="active">Add Voucher</li>
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

              <!-- column -->
              <div class="col-md-12">
                  <!-- general form elements -->
                  <div class="bg-[#2C2C2C] box border-t-10 border-[#DA7F00]">
                      <div class=" box-header">
                          <h3 class="box-title text-white">Add Voucher</h3>
                      </div>
                      <!-- /.box-header -->
                      <!-- form start -->
                      <form class="bg-[#2C2C2C]" role="form" action="<?php echo base_url(); ?>insert-voucher" method="POST">
                          <div class="box-body border-10 border-[#DA7F00]">

                              <div class="col-md-12">
                                  <div class="form-group text-white">
                                      <label for="place">Place</label>
                                      <input type="text" name="place" class="text-white bg-transparent border border-white form-control" placeholder="Place">
                                  </div>
                              </div>

                              <div class="col-md-12">
                                  <div class="form-group text-white">
                                      <label for="expense_head">Expense Head</label>
                                      <input type="text" name="expense_head" class="text-white bg-transparent border border-white form-control" placeholder="Expense Head">
                                  </div>
                              </div>

                              <div class="col-md-12">
                                  <div class="form-group text-white">
                                      <label for="month">Month</label>
                                      <input type="text" name="month" class="text-white bg-transparent border border-white form-control" placeholder="Month">
                                  </div>
                              </div>

                              <div class="col-md-12">
                                  <div class="form-group text-white">
                                      <label for="date">Date</label>
                                      <input type="date" name="date" class="text-white bg-transparent border border-white form-control" placeholder="Date">
                                  </div>
                              </div>

                              <div class="col-md-12">
                                  <div class="form-group text-white">
                                      <label for="beneficiary">Beneficiary</label>
                                      <input type="text" name="beneficiary" class="text-white bg-transparent border border-white form-control" placeholder="Beneficiary">
                                  </div>
                              </div>

                              <div class="col-md-12">
                                  <div class="form-group text-white">
                                      <label for="amount_words">Amount (Words)</label>
                                      <input type="text" name="amount_words" class="text-white bg-transparent border border-white form-control" placeholder="Amount (Words)">
                                  </div>
                              </div>

                              <table id="line_items_table" class="table">
                                  <thead>
                                      <tr>
                                          <th>Date</th>
                                          <th>Detailed Description</th>
                                          <th>Amount Naira</th>
                                          <th>Amount Kobo</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr>
                                          <td><input type="date" name="line_item_date[]" class=""></td>
                                          <td><input type="text" name="line_item_description[]" class=""></td>
                                          <td><input type="number" name="line_item_amount_naira[]" class=""></td>
                                          <td><input type="number" name="line_item_amount_kobo[]" class=""></td>
                                      </tr>
                                  </tbody>
                              </table>

                              <button class="border-0 btn btn-info pull-right" type="button" onclick="addLineItem()">Add Line Item</button>


                              <div class="col-md-12">
                                  <div class="form-group text-white">
                                      <label for="cash_cheque_no">Cash/Cheque No</label>
                                      <input type="text" name="cash_cheque_no" class="text-white bg-transparent border border-white form-control" placeholder="Cash/Cheque No">
                                  </div>
                              </div>

                              <div class="col-md-12">
                                  <div class="form-group text-white">
                                      <label for="total">Total (₦)</label>
                                      <input type="text" name="total" class="text-white bg-transparent border border-white form-control" placeholder="Total (₦)">
                                  </div>
                              </div>

                          </div>
                          <!-- /.box-body -->
                          <div class="bg-[#2C2C2C] box-footer border-0">
                              <button type="submit" class="bg-[#DA7F00] hover:bg-[#DA7F00] border-0 btn btn-success pull-right">Submit</button>
                          </div>
                      </form>
                  </div>
                  <!-- /.box -->
              </div>
              <!--/.col (left) -->
          </div>
          <!-- /.row -->
      </section>
      <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script>
      function addLineItem() {
          var table = document.getElementById("line_items_table");
          var row = table.insertRow(-1);
          row.innerHTML = `
            <td><input type="date" name="line_item_date[]"></td>
            <td><input type="text" name="line_item_description[]"></td>
            <td><input type="number" name="line_item_amount_naira[]"></td>
            <td><input type="number" name="line_item_amount_kobo[]"></td>
        `;
      }
  </script>