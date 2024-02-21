  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper bg-[#3E3E3E]">
    <!-- Include Quill stylesheet -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- Include the Quill library -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Memo
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Add Memo</li>
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
          <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C] text-white">
            <div class=" box-header with-border">
              <h3 class="box-title text-white">Add Memo</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <?php echo form_open_multipart('memos/insert'); ?>
            <div class="box-body">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Title</label>
                  <input type="text" name="title" class="form-control" placeholder="Title">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>Date</label>
                  <input type="date" name="date" class="form-control">
                </div>
              </div>

              <div class="col-md-12 ">
                <div id="editor" class="">
                </div>
              </div>
              <input type="hidden" id="body" name="body">

            </div>
            <!-- /.box-body -->
            <div class="box-footer border-0 bg-[#2C2C2C]">
              <button type="submit" class="btn btn-success pull-right hover:border-[#DA7F00] border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]">Submit</button>
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
    // Initialize Quill
    var quill = new Quill('#editor', {
      theme: 'snow'
    });

    // Listen for the 'text-change' event
    quill.on('text-change', function(delta, oldDelta, source) {
      // Update the value of the hidden input with the editor's HTML content
      document.getElementById('body').value = quill.root.innerHTML;
    });
  </script>