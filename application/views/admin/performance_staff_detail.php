<div class="content-wrapper bg-[#3E3E3E]">
	<div class="content-header">
		<h1 class="m-0 text-dark">Staff Performance Detail</h1>
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="<?php echo base_url('performance'); ?>">Home</a></li>
			<li class="breadcrumb-item">
				<a href="<?php echo isset($quarter) && $quarter !== NULL ? base_url('performance/department/' . (int)$department_id . '/' . (int)$year . '/' . (int)$quarter) : base_url('performance/department/' . (int)$department_id . '/' . (int)$year); ?>">
					Department
				</a>
			</li>
			<li class="breadcrumb-item active">Staff</li>
		</ol>
	</div>

	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
						<div class="box-header">
							<h3 class="box-title text-white">
								<?php echo htmlspecialchars((string)$staff_name); ?>
								(<?php echo htmlspecialchars((string)$department_name); ?>)
							</h3>
						</div>

						<div class="box-body">
							<?php
							$isQuarter = isset($quarter) && $quarter !== NULL;
							$periodLabel = $isQuarter ? ('Year ' . (int)$year . ' - Q' . (int)$quarter) : ('Year ' . (int)$year);
							?>
							<p class="text-white mb-2">Period: <?php echo $periodLabel; ?></p>
							<p class="text-white mb-4">
								Overall Avg Score: <?php echo (float)$overall_score; ?> | Rating: <?php echo htmlspecialchars((string)$overall_rating_band); ?>
							</p>

							<?php if (!empty($monthly)): ?>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Month Under Review</th>
											<th>Teamwork</th>
											<th>Communication</th>
											<th>Quality</th>
											<th>Timeliness</th>
											<th>Innovation</th>
											<th>Professionalism</th>
											<th>KPA Avg</th>
											<th>Completion %</th>
											<th>Accuracy</th>
											<th>Job Perf %</th>
											<th>Learning %</th>
											<th>Total Score</th>
											<th>Rating</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($monthly as $m): ?>
											<tr>
												<td><?php echo htmlspecialchars((string)$m['month_under_review']); ?></td>
												<td><?php echo (int)$m['ratings']['teamwork']; ?></td>
												<td><?php echo (int)$m['ratings']['communication']; ?></td>
												<td><?php echo (int)$m['ratings']['quality']; ?></td>
												<td><?php echo (int)$m['ratings']['timeliness']; ?></td>
												<td><?php echo (int)$m['ratings']['innovation']; ?></td>
												<td><?php echo (int)$m['ratings']['professionalism']; ?></td>
												<td>
													<?php echo isset($m['avg_kpa_rating']) && $m['avg_kpa_rating'] !== NULL ? (float)$m['avg_kpa_rating'] : '-'; ?>
												</td>
												<td><?php echo (float)$m['completion_rate']; ?></td>
												<td><?php echo htmlspecialchars((string)$m['accuracy_rate']); ?></td>
												<td><?php echo (float)$m['computed']['breakdown']['job_performance_percent']; ?></td>
												<td><?php echo (float)$m['computed']['breakdown']['learning_growth_percent']; ?></td>
												<td><?php echo (float)$m['computed']['score']; ?></td>
												<td><?php echo htmlspecialchars((string)$m['computed']['rating_band']); ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							<?php else: ?>
								<p class="text-white mb-3">No final appraisals found for this period.</p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

