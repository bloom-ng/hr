<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-neutral-800">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>List Appraisals</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Staff Management</a></li>
			<li class="active">List Appraisals</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content bg-neutral-800">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-info bg-neutral-800">
					<div class="box-header">
						<h3 class="box-title">List Appraisals</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<!-- Department Filter Dropdown -->
<!--						<div class="form-group">-->
<!--							<label for="department_filter">Filter by Department:</label>-->
<!--							<select class="form-control" id="department_filter">-->
<!--								<option value="">Select Department</option>-->
<!--								--><?php //if (isset($departments)) {
//									foreach ($departments as $department): ?>
<!--										<option value="--><?php //echo $department['id']; ?><!--">--><?php //echo $department['department_name']; ?><!--</option>-->
<!--									--><?php //endforeach;
//								} ?>
<!--							</select>-->
<!--						</div>-->

						<table id="example1" class="table table-bordered">
							<thead>
							<tr>
								<th>#</th>
								<th>Date</th>
								<th>Name </th>
								<th>Status </th>
								<th>Actions</th>
								<th style="display: none;">Department</th>
							</tr>
							</thead>
							<tbody>
							<?php if(isset($appraisals)): ?>
								<?php $i=1; foreach($appraisals as $appraisal): ?>
									<tr>
										<td><?php echo $i; ?></td>
										<td><?php echo $appraisal['date']; ?></td>
										<td><?php echo $appraisal['name']; ?></td>
										<td><?php echo $appraisal['status']; ?></td>
										<td>
											<?php if($appraisal['status']  == 'pending' && $this->session->userdata('userid') == $appraisal['created_by']): ?>
												<a href="<?php echo base_url(); ?>review-appraisal/<?php echo $appraisal['id']; ?>" class="btn btn-info" >Send For Review</a>
											<?php endif; ?>

											<?php if ($appraisal['status'] == 'review' && in_array($this->session->userdata('role'), array("hrm", "super"))) : ?>
												<a href="<?php echo base_url(); ?>edit-appraisal/<?php echo $appraisal['id']; ?>" class="btn btn-success">Check</a>
											<?php endif; ?>

											<?php if ($appraisal['status']  == 'pending' && $this->session->userdata('userid') == $appraisal['created_by']) : ?>
												<a href="<?php echo base_url(); ?>edit-appraisal/<?php echo $appraisal['id']; ?>" class="btn btn-success">Edit</a>
											<?php endif; ?>
										</td>
										<td style="display: none;"><?php echo $appraisal['department_id']; ?></td>
									</tr>
									<?php $i++; endforeach; ?>
							<?php endif; ?>
							</tbody>
						</table>
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
