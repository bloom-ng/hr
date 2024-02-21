<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Admins
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage Admins</li>
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
            <h3 class="box-title"></h3>
            <div class="d-flex mt-3">
              <button type="button" class="btn btn-primary border-0 bg-[#DA7F00] hover:bg-[#DA7F00]" data-toggle="modal" data-target="#adminModal">
                Add Admin
              </button>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <table id="example1" class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (isset($admins)) :
                    $i = 1;
                    foreach ($admins as $cnt) :
                  ?>
                      <tr>
                        <td><?php echo $i; ?></td>

                        <td><?php echo $cnt['username']; ?></td>
                        <td><?php echo $cnt['role']; ?></td>
                        <td>
                          <button type="button" data-toggle="modal" data-target="#adminModal<?php echo $cnt['id'] ?>" class="btn btn-success border-0 hover:bg-[#FF9501] bg-[#FF9501]">Edit</button>
                          <a href="<?php echo base_url(); ?>admin/delete/<?php echo $cnt['id']; ?>" class="btn btn-danger border-0 bg-[#595959] hover:bg-[#595959] ">
                            Delete
                          </a>
                        </td>
                      </tr>

                      <div class="modal fade bg-[#3E3E3E]" id="adminModal<?php echo $cnt['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="adminModalLabel<?php echo $cnt['id']; ?>">
                        <div class="modal-dialog bg-[#3E3E3E]" role="document">
                          <div class="modal-content bg-[#3E3E3E]">
                            <div class="modal-header bg-[#3E3E3E]">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              <h4 class="modal-title" id="adminModalLabel<?php echo $cnt['id']; ?>">
                                Edit Admin
                              </h4>
                            </div>
                            <div class="modal-body">

                              <?php echo form_open("admin/update/{$cnt['id']}"); ?>

                              <div class="form-group">
                                <label for="username">Username/email:</label>
                                <input type="text" class="bg-gray-200 form-control" id="username" value="<?php echo $cnt['username'] ?>" name="username" required>
                              </div>
                              <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="bg-gray-200 form-control" id="password" name="password">
                              </div>

                              <div class="form-group">
                                <label for="role">Role:</label>
                                <select name="role" class="bg-gray-200 form-control">
                                  <option value="super"> Super Admin</option>
                                  <option value="hrm"> HR Admin</option>
                                  <option value="finance"> Finance Admin</option>
                                </select>

                              </div>

                              <button type="submit" class="btn btn-primary">Submit</button>
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

<!--Add Commission Modal -->
<div class="modal fade bg-[#3E3E3E]" id="adminModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel">
  <div class="modal-dialog bg-[#3E3E3E]" role="document">
    <div class="modal-content bg-[#3E3E3E]">
      <div class="modal-header bg-[#3E3E3E]">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="adminModalLabel">Add Admin</h4>
      </div>
      <div class="modal-body">

        <?php echo form_open('admin/insert'); ?>
        <div class="form-group">
          <label for="username">Username/email:</label>
          <input type="text" class="form-control bg-gray-200" id="username" name="username" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" class="form-control bg-gray-200" id="password" name="password" required>
        </div>

        <div class="form-group">
          <label for="role">Role:</label>
          <select name="role" class="form-control bg-gray-200">
            <option value="super"> Super Admin</option>
            <option value="hrm"> HR Admin</option>
            <option value="finance"> Finance Admin</option>
          </select>

        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>