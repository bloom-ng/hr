  <style>
    .floatybox {
      display: inline-block;
      width: 123px;
    }
  </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Staff Management
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Staff Management</a></li>
        <li class="active">Edit Staff</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <?php echo validation_errors('<div class="col-md-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Failed!</h4>', '</div>
          </div>'); ?>

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
              <h3 class="box-title text-white">Edit Staff</h3>
            </div>
            <!-- /.box-header -->

            <?php if (isset($content)) : ?>
              <?php foreach ($content as $cnt) : ?>
                <!-- form start -->
                <?php echo form_open_multipart('Staff/update'); ?>
                <div class="box-body">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Full Name</label>
                      <input type="hidden" name="txtid" value="<?php echo $cnt['id'] ?>" class="form-control bg-gray-200" placeholder="Full Name">
                      <input type="text" name="txtname" value="<?php echo $cnt['staff_name'] ?>" class="form-control bg-gray-200" placeholder="Full Name">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Staff ID Number: BDM-</label>
                      <input type="text" name="txtstaff_number" value="<?php echo $cnt['staff_number'] ?>" class="form-control bg-gray-200" placeholder="Full Name">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Department</label>
                      <select class="form-control bg-gray-200" name="slcdepartment">
                        <option value="">Select</option>
                        <?php
                        if (isset($department)) {
                          foreach ($department as $cnt1) {
                            if ($cnt1['id'] == $cnt['department_id']) {
                              print "<option value='" . $cnt1['id'] . "' selected>" . $cnt1['department_name'] . "</option>";
                            } else {
                              print "<option value='" . $cnt1['id'] . "'>" . $cnt1['department_name'] . "</option>";
                            }
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Gender</label>
                      <select class="form-control bg-gray-200" name="slcgender">
                        <option value="">Select</option>
                        <option value="Male" <?php echo $cnt['gender'] == 'Male' ? 'selected' : '' ?>>
                          Male
                        </option>
                        <option value="Female" <?php echo $cnt['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Email</label>
                      <input type="text" name="txtemail" value="<?php echo $cnt['email'] ?>" class="form-control bg-gray-200" placeholder="Email" readonly>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Mobile</label>
                      <input type="text" name="txtmobile" value="<?php echo $cnt['mobile'] ?>" class="form-control bg-gray-200" placeholder="Mobile">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Photo</label>
                      <input type="file" name="filephoto" value="<?php echo base_url(); ?>uploads/profile-pic/<?php echo $cnt['pic'] ?>" class="form-control bg-gray-200">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Date of Birth</label>
                      <input type="date" name="txtdob" value="<?php echo $cnt['dob'] ?>" class="form-control bg-gray-200" placeholder="DOB">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Date of Employment</label>
                      <input type="date" name="txtdoj" value="<?php echo $cnt['doj'] ?>" class="form-control bg-gray-200" placeholder="DOJ">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Address</label>
                      <textarea class="form-control bg-gray-200" name="txtaddress"><?php echo $cnt['address'] ?></textarea>
                    </div>
                  </div>

                </div>
                <!-- /.box-body -->
                <div class="box-footer bg-[#2C2C2C] border-0">
                  <button type="submit" class="btn btn-success hover:bg-[#DA7F00] bg-[#DA7F00] border-0 pull-right">Submit</button>
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