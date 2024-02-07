<!-- File: application/views/add_appraisal.php -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Add Appraisal</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Appraisals</a></li>
			<li class="active">Add Appraisal</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">Add Appraisal</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<?php echo form_open('appraisal/save'); ?>
						<div class="form-group">
							<label for="name">Name:</label>
							<input type="text" class="form-control" id="name" name="name" value="<?php echo $staff['staff_name']; ?>" readonly>
						</div>
						<div class="form-group">
							<label for="job_title">Job Title:</label>
							<input type="text" class="form-control" id="job_title" name="job_title" value="<?php echo $job_title; ?>" readonly>
						</div>
						<div class="form-group">
							<label for="department_id">Department ID:</label>
							<input type="text" class="form-control" id="department_id" name="department_id" value="<?php echo $staff['department_id']; ?>" readonly>
						</div>
						<div class="form-group">
							<label for="date">Date (Month/Year):</label>
							<input type="month" class="form-control" id="date" name="date" required>
						</div>
						<!-- Add other input fields for appraisal details -->
						<button type="submit" class="btn btn-primary">Submit</button>
						<?php echo form_close(); ?>
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
