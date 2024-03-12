<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper px-4 bg-[#3E3E3E]">
  <!-- Include Quill stylesheet -->
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

  <!-- Include the Quill library -->
  <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Profile
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Profile</li>
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
        <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C] px-4 py-4">
          <div class="box-header with-border border-0">
            <h3 class="box-title text-white border-0">Edit Profile</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?php echo form_open("admin/update/{$user['id']}"); ?>

          <div class="form-group">
            <label for="username">Username/email:</label>
            <input type="text" class="bg-gray-200 form-control" id="username" value="<?php echo $user['username'] ?>" name="username" required>
            <input type="text" class="bg-gray-200 form-control" id="role" value="<?php echo $user['role'] ?>" name="role" hidden required>
          </div>
          <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="bg-gray-200 form-control" id="password" name="password">
          </div>

          <button type="submit" class="btn bg-[#DA7F00] hover:bg-[#DA7F00] border-0 btn-primary">Submit</button>
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