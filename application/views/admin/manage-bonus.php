<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Bonus
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="/commission-staff">Bonus </a></li>
			<li class="active">Manage Staff Bonus</li>
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
						<h3 class="box-title text-white">Bonus for <?php echo $staff['staff_name'] ?></h3>
						<div class="d-flex mt-3">
							<button type="button" class="btn btn-primary bg-[#DA7F00] hover:bg-[#DA7F00]" data-toggle="modal" data-target="#commissionModal">
								Add
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
									<th>Amount</th>
									<th>Date</th>
									<th>Reason (&#8358;)</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
								</thead>
								<tbody>
								<?php
								if (isset($commissions)) :
									$i = 1;
									foreach ($commissions as $cnt) :
										?>
										<tr>
											<td><?php echo $i; ?></td>

											<td><?php echo $cnt['client']; ?></td>
											<td><?php echo $cnt['total']; ?></td>
											<td><?php echo date('d-m-Y', strtotime($cnt['date'])); ?></td>
											<td><?php echo  $this->Commission_model->getStatusMapping()[$cnt['status']]; ?></td>
											<td><?php echo $cnt['commission']; ?></td>
											<td>
												<button type="button" data-toggle="modal" data-target="#commissionModal<?php echo $cnt['id'] ?>" class="btn btn-success">Edit</button>
												<a href="<?php echo base_url(); ?>commission/delete/<?php echo $cnt['id']; ?>" class="btn btn-danger">
													Delete
												</a>
											</td>
										</tr>

										<div class="modal fade" id="commissionModal<?php echo $cnt['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="commissionModalLabel<?php echo $cnt['id']; ?>">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
														<h4 class="modal-title" id="commissionModalLabel<?php echo $cnt['id']; ?>">
															Commission
														</h4>
													</div>
													<div class="modal-body">

														<?php echo form_open("commission/update/{$cnt['id']}"); ?>
														<div class="form-group">
															<label for="date">Date:</label>
															<input type="date" class="form-control" id="date" value="<?php echo $cnt['date'] ?>" name="date" required>
														</div>
														<div class="form-group">
															<label for="client">Client:</label>
															<input type="text" class="form-control" id="client" value="<?php echo $cnt['client'] ?>" name="client" required>
														</div>
														<div class="form-group">
															<label for="total">Total (&#8358;) :</label>
															<input type="number" class="form-control" id="total" value="<?php echo $cnt['total'] ?>" name="total" required>
														</div>
														<div class="form-group">
															<label for="commission">Commission (%):</label>
															<input type="number" class="form-control" id="commission" value="<?php echo $cnt['commission'] ?>" name="commission" required>
														</div>
														<div class="form-group">
															<label for="comments">Comments:</label>
															<textarea class="form-control" id="comments" name="comments"><?php echo $cnt['comments'] ?></textarea>
														</div>

														<div class="form-group">
															<label for="reason">Status</label>
															<select name="status" class="form-control">
																<option <?php echo $cnt['status'] == $this->Commission_model::COMMISSION_PENDING ? "selected" : "" ?> value="<?php echo $this->Commission_model::COMMISSION_PENDING ?>">Pending</option>
																<option <?php echo $cnt['status'] == $this->Commission_model::COMMISSION_PAID ? "selected" : "" ?> value="<?php echo $this->Commission_model::COMMISSION_PAID ?>">Paid</option>
															</select>
														</div>
														<input type="hidden" id="staff_id" name="staff_id" value="<?php echo $staff['id']; ?>">
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
<div class="modal fade" id="commissionModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="commissionModalLabel<?php echo $staff['id']; ?>">Commission</h4>
			</div>
			<div class="modal-body">

				<?php echo form_open('commission/insert'); ?>
				<div class="form-group">
					<label for="date">Date:</label>
					<input type="date" class="form-control" id="date" name="date" required>
				</div>
				<div class="form-group">
					<label for="client">Client:</label>
					<input type="text" class="form-control" id="client" name="client" required>
				</div>
				<div class="form-group">
					<label for="total">Total (&#8358;) :</label>
					<input type="number" class="form-control" id="total" name="total" required>
				</div>
				<div class="form-group">
					<label for="commission">Commission (%):</label>
					<input type="number" class="form-control" id="commission" name="commission" required>
				</div>
				<div class="form-group">
					<label for="comments">Comments:</label>
					<textarea class="form-control" id="comments" name="comments"></textarea>
				</div>
				<div class="form-group">
					<label for="status">Status</label>
					<select name="status" class="form-control">
						<option selected value="<?php echo $this->Commission_model::COMMISSION_PENDING ?>">
							Pending
						</option>
						<option value="<?php echo $this->Commission_model::COMMISSION_PAID ?>">Paid</option>
					</select>
				</div>
				<input type="hidden" id="staff_id" name="staff_id" value="<?php echo $staff['id']; ?>">
				<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
