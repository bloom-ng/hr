<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-neutral-800">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>My Appraisals</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Staff Management</a></li>
			<li class="active">My Appraisals</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content bg-neutral-800">
		<div class="row">

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

			<div class="col-xs-12">
				<div class="box box-info bg-neutral-800	">
					<div class="box-header">
						<h3 class="box-title">My Appraisals</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">

						<table id="example1" class="table table-bordered ">
							<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Date</th>
								<th>Actions</th>
							</tr>
							</thead>
							<tbody>
							<?php if(isset($appraisal)): ?>
								<?php $i=1; foreach($appraisal as $appraisal): ?>
									<tr>
										<td><?php echo $i; ?></td>
										<td><?php echo $appraisal['name']; ?></td>
										<td><?php echo $appraisal['date']; ?></td>
										<td>
											<a href="<?php echo base_url(); ?>list-appraisal/<?php echo $appraisal['id']; ?>" class="btn btn-info" >Check</a>
										</td>
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

<script>
	$(document).ready(function () {
		$('#department_filter').change(function () {
			var selectedDepartment = $(this).val();

			if (selectedDepartment !== '') {
				$('tbody tr').each(function () {
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
