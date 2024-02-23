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
        <li class="active">Edit Department</li>
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
          <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
            <div class="box-header">
              <h3 class="box-title  text-white">Edit Department</h3>
            </div>
            <!-- /.box-header -->

            <?php if (isset($content)) : ?>
              <?php foreach ($content as $cnt) : ?>
                <!-- form start -->
                <form role="form" action="<?php echo base_url(); ?>update-department" method="POST">
                  <div class="box-body">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Department Name</label>
                        <input type="hidden" name="txtid" value="<?php echo $cnt['id']; ?>" class="form-control bg-gray-200">
                        <input type="text" name="txtdepartment" value="<?php echo $cnt['department_name']; ?>" class="form-control bg-gray-200" placeholder="Department Name">
                      </div>

                      <div class="form-group">
                        <label>HOD</label>
                        <select name="hod" class="form-control bg-gray-200">
                          <option> </option>
                          <?php foreach ($staffs as $staff) : ?>
                            <option value="<?php echo $staff['id'] ?>" <?php echo $cnt['staff_id'] ==  $staff['id'] ? "selected" : ""  ?>>
                              <?php echo $staff['staff_name'] ?>
                            </option>

                          <?php endforeach; ?>

                        </select>
                      </div>
                    </div>

                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer bg-[#2C2C2C] border-0">
                    <button type="submit" class="btn btn-success bg-[#DA7F00] hover:bg-[#DA7F00] border-0 pull-right">Update</button>
                  </div>
                </form>
              <?php endforeach; ?>
            <?php endif; ?>
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