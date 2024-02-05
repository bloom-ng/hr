<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Deduction
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="/manage-staff">Manage Staff</a></li>
        <li class="active">Manage Deduction</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <?php if($this->session->flashdata('success')): ?>
          <div class="col-md-12">
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $this->session->flashdata('success'); ?>
            </div>
          </div>
        <?php elseif($this->session->flashdata('error')):?>
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Failed!</h4>
                  <?php echo $this->session->flashdata('error'); ?>
            </div>
          </div>
        <?php endif;?>

        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Deductions for <?php echo $staff['staff_name'] ?></h3>
              <div class="d-flex mt-3">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">
                Add
              </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table id="table" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Amount (&#8358;)</th>
                    <th>Date</th>
                    <th>Reason</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                    if(isset($deductions)):
                    $i=1; 
                    foreach($deductions as $cnt): 
                  ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        
                        <td><?php echo $cnt['amount']; ?></td>
                        <td><?php echo date('d-m-Y', strtotime($cnt['date'])); ?></td>
                        <td><?php echo $cnt['reason']; ?></td>
                        <td>
                          <a href="<?php echo base_url(); ?>staff/edit-deductions/<?php echo $cnt['id']; ?>" class="btn btn-success">Edit</a>
                          <a href="<?php echo base_url(); ?>staff/delete-deductions/<?php echo $cnt['id']; ?>" class="btn btn-danger">Delete</a>
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

  <!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addDeduction">Add Staff Deduction</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form role="form" action="<?php echo base_url(); ?>staff/insert-deductions" method="POST">
              <div class="box-body">
        
                <input name="staff_id" value="<?php echo $staff['id']; ?>" type="hidden" >
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="amount">Amount (&#8358;)</label>
                    <input min="0" required type="number" name="amount" class="form-control" placeholder="Amount">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="date">Date</label>
                    <input required type="date" name="date" class="form-control" placeholder="Date">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="reason">Reason</label>
                    <textarea required name="reason" class="form-control" placeholder="Reason"></textarea>
                  </div>
                </div>
                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Submit</button>
              </div>
              </div>
            </form>
      </div>
      
    </div>
  </div>
</div>


    