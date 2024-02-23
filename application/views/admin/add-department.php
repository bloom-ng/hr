  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Departments
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Departments</a></li>
        <li class="active">Add Department</li>
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
              <h3 class="box-title text-white">Add Department</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="bg-[#2C2C2C]" role="form" action="<?php echo base_url(); ?>insert-department" method="POST">
              <div class="box-body border-10 border-[#DA7F00]">

                <div class="col-md-12">
                  <div class="form-group text-white">
                    <label for="exampleInputPassword1">Department Name</label>
                    <input type="text" name="txtdepartment" class="text-white bg-transparent border border-white form-control" placeholder="Department Name">
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