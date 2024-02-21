<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Manage Appraisals</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Staff Management</a></li>
			<li class="active">Manage Appraisals</li>
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
						<h3 class="box-title text-white">Manage Appraisals</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<!-- Department Filter Dropdown -->
						<div class="form-group">
							<label for="department_filter">Filter by Department:</label>
							<select class="form-control" id="department_filter">
								<option value="">Select Department</option>
								<?php if (isset($departments)) {
									foreach ($departments as $department) : ?>
										<option value="<?php echo $department['id']; ?>"><?php echo $department['department_name']; ?></option>
								<?php endforeach;
								} ?>
							</select>
						</div>

						<table id="example1" class="table">
							<thead>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>Actions</th>
									<th style="display: none;">Department</th> <!-- Hidden column for department ID -->
								</tr>
							</thead>
							<tbody>
								<?php if (isset($staff_members)) : ?>
									<?php $i = 1;
									foreach ($staff_members as $staff) : ?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $staff['staff_name']; ?></td>
											<td>
												<a href="<?php echo base_url(); ?>list-appraisal/<?php echo $staff['id']; ?>" class="btn btn-infohover:border-[#DA7F00] border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]">Appraisals</a>
												<!--											--><?php
																									//											// Assuming $departments is the array of departments
																									//											$loggedInUserId = $this->session->userdata('userid');
																									//											if (isset($departments)) {
																									//												foreach ($departments as $department) {
																									//													if ($loggedInUserId == $department['staff_id'] || in_array($this->session->userdata('role'), array("hrm", "super"))) {
																									//														
																									?>
												<!--														<a href="--><?php //echo base_url(); 
																														?><!--add-appraisal/--><?php //echo $staff['id']; 
																																				?><!--" class="btn btn-success">Add</a>-->
												<!--														--><?php
																												//														break; // Exit the loop after finding a matching department
																												//													}
																												//												}
																												//											}
																												//											
																												?>


											</td>
											<td style="display: none;"><?php echo $staff['department_id']; ?></td> <!-- Hidden column for department ID -->
										</tr>
									<?php $i++;
									endforeach; ?>
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

<script>
	$(document).ready(function() {
		$('#department_filter').change(function() {
			var selectedDepartment = $(this).val();

			if (selectedDepartment !== '') {
				$('tbody tr').each(function() {
					var departmentId = $(this).find('td:eq(3)').text(); // Get department ID from the hidden td

					if (departmentId !== selectedDepartment) {
						$(this).hide();
					} else {
						$(this).show();
					}
				});
			} else {
				$('tbody tr').show();
			}
		});
	});
</script>