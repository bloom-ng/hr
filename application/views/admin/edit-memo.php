  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
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
        <li class="active">Edit Memo</li>
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

        <!-- column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Memo</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" 
                    action="<?php echo base_url(); ?>memo/update/<?php echo $memo['id'] ?>"
                    method="POST">
              <div class="box-body">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" value="<?php echo $memo['title']; ?>"
                        name="title" class="form-control" placeholder="Title">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>Date</label>
                    <input type="date" value="<?php echo $memo['date']; ?>" name="date" class="form-control">
                  </div>
                </div>
               
                <div class="col-md-12">
                    <div id="editor">
                        <?php echo $memo['body']; ?>
                    </div>
                </div>
                <input type="hidden" id="body" name="body">
                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right">Submit</button>
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
