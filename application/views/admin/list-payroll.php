<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Payroll
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage Payroll</li>
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
            <h3 class="box-title text-white">Staff Payroll</h3>
            <div class="d-flex mt-3">
				<?php if (in_array($this->session->userdata('role'), ["finance", "super"])) : ?>
				  <button type="button" class="btn btn-primary border-0 bg-[#DA7F00] hover:bg-[#DA7F00]" data-toggle="modal" data-target="#payrollModal">
					Generate Payroll
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
                    <th>Period</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (isset($payrolls)) :
                    $i = 1;
                    foreach ($payrolls as $cnt) :
                  ?>
                      <tr>
                        <td><?php echo $i; ?></td>

                        <td><?php echo $cnt['period']; ?></td>
                        
                        <td>
							<?php if (in_array($this->session->userdata('role'), ["finance", "super"])) : ?>
                                <a href="<?php echo base_url(); ?>payroll/manage/<?php echo $cnt['period']; ?>" class="btn btn-primary text-white hover:border-[#DA7F00] border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]">
                                    Manage
                                </a>
                                <a href="<?php echo base_url(); ?>payroll/delete/<?php echo $cnt['period']; ?>" class="btn btn-danger border-0 bg-[#595959] hover:bg-[#595959]">
                                  Delete
                                </a>
							<?php endif; ?>
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

<!--Generate payroll Modal -->
<div class="modal fade bg-[#3E3E3E]" id="payrollModal" tabindex="-1" role="dialog" aria-labelledby="payrollModalLabel">
  <div class="modal-dialog bg-[#3E3E3E]" role="document">
    <div class="modal-content bg-[#3E3E3E]">
      <div class="modal-header bg-[#3E3E3E]">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" >Generate Payroll</h4>
      </div>
      <div class="modal-body">

        <?php echo form_open('payroll/generate'); ?>
        <div class="form-group">
          <label for="date">Period:</label>
          <input type="month" class="bg-gray-200 form-control" id="period" name="period" required>
        </div>
        
        <button type="submit" class="btn bg-[#DA7F00] hover:bg-[#DA7F00] border-0 btn-primary">Generate</button>
        </form>
      </div>
    </div>
  </div>
</div>
