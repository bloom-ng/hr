<div class="content-wrapper bg-[#3E3E3E]">
  <section class="content-header">
    <h1>Transaction Journals - Categories</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Categories</li>
    </ol>
  </section>

  <section class="content">
    <?php if($this->session->flashdata('success')): ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Success!</h4>
        <?php echo $this->session->flashdata('success'); ?>
      </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-ban"></i> Error!</h4>
        <?php echo $this->session->flashdata('error'); ?>
      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-md-5">
        <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
          <div class="box-header with-border">
            <h3 class="box-title text-white">Add Category</h3>
          </div>
          <div class="box-body">
            <form action="<?php echo base_url(); ?>transaction-journals/categories/insert" method="post" class="bg-[#2C2C2C]">
              <div class="form-group text-white">
                <label>Category name</label>
                <input type="text" name="category_name" class="form-control" placeholder="e.g. Petty cash" required>
              </div>
              <button type="submit" class="btn btn-success btn-flat">Save</button>
              <a class="btn btn-info btn-flat" href="<?php echo base_url(); ?>transaction-journals/registery">Back to Registery</a>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-7">
        <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
          <div class="box-header">
            <h3 class="box-title text-white">Categories</h3>
          </div>
          <div class="box-body table-responsive">
            <table id="example1" class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($categories as $c): ?>
                  <tr>
                    <td><?php echo (int)$c['id']; ?></td>
                    <td><?php echo $c['category_name']; ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

