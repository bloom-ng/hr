<div class="content-wrapper bg-[#3E3E3E]">
	<div class="content-header">
		<h1 class="m-0 text-dark">
			Performance - <?php echo (isset($quarter) && $quarter !== NULL) ? 'Quarter ' . (int)$quarter : 'Year ' . (int)$year; ?>
		</h1>
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="<?php echo base_url('performance'); ?>">Home</a></li>
			<li class="breadcrumb-item"><a href="<?php echo base_url('performance/manage/' . (int)$year . (isset($quarter) && $quarter !== NULL ? '/' . (int)$quarter : '')); ?>">Performance</a></li>
			<li class="breadcrumb-item active">Department</li>
		</ol>
	</div>

	<section class="content">
		<div class="container-fluid">
			<?php if ($this->session->flashdata('error')) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger">
						<?php echo $this->session->flashdata('error'); ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="row">
				<div class="col-md-12">
					<div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
						<div class="box-header">
							<h3 class="box-title text-white">
								Department: <?php echo htmlspecialchars((string)$department_name); ?>
							</h3>
						</div>

						<div class="box-body">
							<?php
							$isQuarter = isset($quarter) && $quarter !== NULL;
							$periodLabel = $isQuarter ? ('Year ' . (int)$year . ' - Q' . (int)$quarter) : ('Year ' . (int)$year);
							?>
							<p class="text-white mb-3">Period: <?php echo $periodLabel; ?></p>

							<?php if (!empty($staff_summaries)): ?>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Staff</th>
											<th>Avg Score</th>
											<th>Rating</th>
											<th>Months Count</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($staff_summaries as $row): ?>
											<tr>
												<td><?php echo htmlspecialchars((string)$row['staff_name']); ?></td>
												<td><?php echo (float)$row['avg_score']; ?></td>
												<td><?php echo htmlspecialchars((string)$row['rating_band']); ?></td>
												<td><?php echo (int)$row['month_count']; ?></td>
												<td>
													<?php if ($isQuarter): ?>
														<a class="btn btn-info btn-sm" href="<?php echo base_url('performance/department/' . (int)$department_id . '/' . (int)$year . '/' . (int)$quarter . '/staff/' . (int)$row['staff_id']); ?>">
															View Detail
														</a>
													<?php else: ?>
														<a class="btn btn-info btn-sm" href="<?php echo base_url('performance/department/' . (int)$department_id . '/' . (int)$year . '/staff/' . (int)$row['staff_id']); ?>">
															View Detail
														</a>
													<?php endif; ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							<?php else: ?>
								<p class="text-white mb-3">No final appraisals found for this department/period.</p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

