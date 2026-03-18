<div class="content-wrapper bg-[#3E3E3E]">
	<div class="content-header">
		<h1 class="m-0 text-dark">Performance Summary</h1>
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
			<li class="breadcrumb-item active">Performance</li>
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
							<h3 class="box-title text-white">Quarterly Performance (Year <?php echo (int)$year; ?>)</h3>
						</div>
						<div class="box-body">
							<?php
							$quartersToRender = [];
							if ($quarter_filter !== NULL) {
								$quartersToRender = [(int)$quarter_filter];
							} else {
								$quartersToRender = [1, 2, 3, 4];
							}
							?>

							<?php foreach ($quartersToRender as $q): ?>
								<h4 class="mt-3 mb-2 text-white">Quarter Q<?php echo (int)$q; ?></h4>

								<?php $rows = $quarters_map[$q] ?? []; ?>
								<?php if (!empty($rows)): ?>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Department</th>
												<th>Avg Score</th>
												<th>Rating</th>
												<th>Staff Count</th>
												<th>Distribution</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($rows as $row): ?>
												<?php $dist = $row['rating_distribution'] ?? []; ?>
												<tr>
													<td><?php echo $row['department_name']; ?></td>
													<td><?php echo (float)$row['avg_score']; ?></td>
													<td><?php echo $row['rating_band']; ?></td>
													<td><?php echo (int)$row['staff_count']; ?></td>
													<td>
														<?php echo 'Outstanding: ' . ($dist['Outstanding'] ?? 0); ?>
														<?php echo ', Excellent: ' . ($dist['Excellent'] ?? 0); ?>
														<?php echo ', Good: ' . ($dist['Good'] ?? 0); ?>
														<?php echo ', Fair: ' . ($dist['Fair'] ?? 0); ?>
														<?php echo ', Poor: ' . ($dist['Poor'] ?? 0); ?>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								<?php else: ?>
									<p class="text-white mb-3">No final appraisals found for this quarter.</p>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C] mt-4">
						<div class="box-header">
							<h3 class="box-title text-white">Yearly Performance (Year <?php echo (int)$year; ?>)</h3>
						</div>
						<div class="box-body">
							<?php if (!empty($year_summaries)): ?>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Department</th>
											<th>Avg Score</th>
											<th>Rating</th>
											<th>Staff Count</th>
											<th>Distribution</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($year_summaries as $row): ?>
											<?php $dist = $row['rating_distribution'] ?? []; ?>
											<tr>
												<td><?php echo $row['department_name']; ?></td>
												<td><?php echo (float)$row['avg_score']; ?></td>
												<td><?php echo $row['rating_band']; ?></td>
												<td><?php echo (int)$row['staff_count']; ?></td>
												<td>
													<?php echo 'Outstanding: ' . ($dist['Outstanding'] ?? 0); ?>
													<?php echo ', Excellent: ' . ($dist['Excellent'] ?? 0); ?>
													<?php echo ', Good: ' . ($dist['Good'] ?? 0); ?>
													<?php echo ', Fair: ' . ($dist['Fair'] ?? 0); ?>
													<?php echo ', Poor: ' . ($dist['Poor'] ?? 0); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							<?php else: ?>
								<p class="text-white mb-3">No final appraisals found for this year.</p>
							<?php endif; ?>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>
</div>

