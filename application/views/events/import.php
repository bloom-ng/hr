
<div class="content-wrapper bg-[#3E3E3E]">
    <section class="content-header">
        <h1>
            Import Events
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url('events/manage'); ?>">Events</a></li>
            <li class="active">Import</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header with-border">
                        <h3 class="box-title text-white">Upload CSV File</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="<?php echo base_url('events/upload_csv'); ?>" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('error')) : ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                    <?php echo $this->session->flashdata('error'); ?>
                                </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="csv_file" class="text-white">Choose CSV File</label>
                                <input type="file" name="csv_file" id="csv_file" class="form-control" required accept=".csv">
                                <p class="help-block text-gray-400">Please upload a valid CSV file.</p>
                            </div>
                            
                            <div class="form-group">
                                <a href="<?php echo base_url('events/download_sample'); ?>" class="btn btn-info"><i class="fa fa-download"></i> Download Sample CSV</a>
                            </div>

                             <div class="callout callout-info bg-[#333] border-l-4 border-info">
                                <h4>Instructions:</h4>
                                <ul class="list-disc pl-5">
                                    <li>Use the sample CSV to understand the format.</li>
                                    <li>Data columns: <strong>Title, Start Date, End Date, Description</strong>.</li>
                                    <li>Date format should be <strong>YYYY-MM-DD HH:MM</strong>.</li>
                                    <li>Duplicate events (same title, start date, and end date) will be skipped.</li>
                                </ul>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary bg-[#DA7F00] border-[#DA7F00] hover:bg-[#DA7F00] hover:border-[#DA7F00]">Upload</button>
                            <a href="<?php echo base_url('events/manage'); ?>" class="btn btn-default">Back</a>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div>
