<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Studio Income
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage Studio Income</li>
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
            <h3 class="box-title text-white">Studio Income </h3>
            <div class="d-flex mt-3">
				<?php if (in_array($this->session->userdata('role'), ["finance", "super"])) : ?>
				  <button type="button" class="btn btn-primary border-0 bg-[#DA7F00] hover:bg-[#DA7F00]" data-toggle="modal" data-target="#commissionModal">
					Add
				  </button>
				<?php endif; ?>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <table id="example1" class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Description</th>
                    <th>Invoice Amount (&#8358;)</th>
                    <th>Amount Paid (&#8358;)</th>
                    <th>Balance (&#8358;)</th>
                    <th>Date</th>
                    <th>Paid (%)</th>
                    <th>Completion (%)</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (isset($incomes)) :
                    $i = 1;
                    foreach ($incomes as $cnt) :
                     $balance =  $cnt['invoice_amount'] - $cnt['amount_paid']
                  ?>
                      <tr>
                        <td><?php echo $i; ?></td>

                        <td><?php echo $cnt['client']; ?></td>
                        <td><?php echo $cnt['description']; ?></td>
                        <td><?php echo $cnt['invoice_amount']; ?></td>
                        <td><?php echo $cnt['amount_paid']; ?></td>
                        <td><?php echo $balance; ?></td>
                        <td><?php echo date('d-m-Y', strtotime($cnt['date'])); ?></td>
                        <td><?php echo ($cnt['amount_paid'] / $cnt['invoice_amount']) * 100; ?></td>
                        <td><?php echo ($balance  / $cnt['invoice_amount']) * 100 ; ?></td>
                        <td><?php echo  $this->Studio_Income_model->getStatusMapping()[$cnt['status']]; ?></td>
                        <td>
							<?php if (in_array($this->session->userdata('role'), ["finance", "super"])) : ?>
                          <button type="button" data-toggle="modal" data-target="#commissionModal<?php echo $cnt['id'] ?>" class="btn btn-success border-0 bg-[#DA7F00] hover:bg-[#DA7F00]">Edit</button>
                          <a href="<?php echo base_url(); ?>manage-studio-income/delete/<?php echo $cnt['id']; ?>" class="btn btn-danger border-0 bg-[#595959] hover:bg-[#595959]">
                            Delete
                          </a>
							<?php endif; ?>
                        </td>
                      </tr>

                      <div class="modal fade bg-[#3E3E3E]" id="commissionModal<?php echo $cnt['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="commissionModalLabel<?php echo $cnt['id']; ?>">
                        <div class="modal-dialog bg-[#3E3E3E]" role="document">
                          <div class="modal-content bg-[#3E3E3E]">
                            <div class="modal-header bg-[#3E3E3E]">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              <h4 class="modal-title" id="commissionModalLabel<?php echo $cnt['id']; ?>">
                                Studio Income
                              </h4>
                            </div>
                            <div class="modal-body">

                              <?php echo form_open("manage-studio-income/update/{$cnt['id']}"); ?>
                  <!--     "id", "client", "description", "invoice_amount", "amount_paid", "date", "status" -->

                              <div class="form-group">
                                <label for="date">Date:</label>
                                <input type="date" class="bg-gray-200 form-control" id="date" value="<?php echo $cnt['date'] ?>" name="date" required>
                              </div>
                              <div class="form-group">
                                <label for="client">Client:</label>
                                <input type="text" class="bg-gray-200 form-control" id="client" value="<?php echo $cnt['client'] ?>" name="client" required>
                              </div>
                              <div class="form-group">
                                <label for="total">Invoice Amount (&#8358;) :</label>
                                <input type="number" class="bg-gray-200 form-control" id="invoice_amount" name="invoice_amount" value="<?php echo $cnt['invoice_amount'] ?>" required>
                              </div>
                              <div class="form-group">
                                <label for="total">Amount Paid (&#8358;) :</label>
                                <input type="number" class="bg-gray-200 form-control" id="amount_paid" name="amount_paid" value="<?php echo $cnt['amount_paid'] ?>" required>
                              </div>
                              <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="bg-gray-200 form-control" id="description" name="description"><?php echo $cnt['description'] ?></textarea>
                              </div>

                              <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" class="bg-gray-200 form-control">
                                  <option 
                                    value="<?php echo $this->Studio_Income_model::PAYMENT_PENDING ?>"
                                    <?php echo $cnt['status'] == $this->Studio_Income_model::PAYMENT_PENDING ? "selected" : "" ?> >
                                    Pending
                                  </option>
                                  <option 
                                    value="<?php echo $this->Studio_Income_model::PAYMENT_COMPLETE ?>"
                                    <?php echo $cnt['status'] == $this->Studio_Income_model::PAYMENT_COMPLETE ? "selected" : "" ?> >
                                    Completed
                                  </option>
                                </select>
                              </div>

                              
                              <button type="submit" class="btn  bg-[#DA7F00] hover:bg-[#DA7F00] border-0 btn-primary">Submit</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
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

<!-- Button trigger modal -->

<!--Add Income Modal -->
<div class="modal fade bg-[#3E3E3E]" id="commissionModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel">
  <div class="modal-dialog bg-[#3E3E3E]" role="document">
    <div class="modal-content bg-[#3E3E3E]">
      <div class="modal-header bg-[#3E3E3E]">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="commissionModalLabel">Studio Income</h4>
      </div>
      <div class="modal-body">
        <?php echo form_open('manage-studio-income/insert'); ?>
        <div class="form-group">
          <label for="date">Date:</label>
          <input type="date" class="bg-gray-200 form-control" id="date" name="date" required>
        </div>
        <div class="form-group">
          <label for="client">Client:</label>
          <input type="text" class="bg-gray-200 form-control" id="client" name="client" required>
        </div>
        <div class="form-group">
          <label for="invoice_amount">Invoice Amount (&#8358;) :</label>
          <input type="number" class="bg-gray-200 form-control" id="invoice_amount" name="invoice_amount" required>
        </div>
        <div class="form-group">
          <label for="total">Amount Paid (&#8358;) :</label>
          <input type="number" class="bg-gray-200 form-control" id="amount_paid" name="amount_paid" required>
        </div>
       
        <div class="form-group">
          <label for="comments">Description:</label>
          <textarea class="bg-gray-200 form-control" id="description" name="description"></textarea>
        </div>
        <div class="form-group">
          <label for="status">Status</label>
          <select name="status" class="bg-gray-200 form-control">
            <option selected value="<?php echo $this->Studio_Income_model::PAYMENT_PENDING ?>">
              Pending
            </option>
            <option value="<?php echo $this->Studio_Income_model::PAYMENT_COMPLETE ?>">Completed</option>
          </select>
        </div>
        <button type="submit" class="btn bg-[#DA7F00] hover:bg-[#DA7F00] border-0 btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
