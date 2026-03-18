<div class="content-wrapper bg-[#3E3E3E]">
	<div class="content-header">
		<h1 class="m-0 text-dark">Departments Performance</h1>
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="<?php echo base_url('performance'); ?>">Performance</a></li>
			<li class="breadcrumb-item active">Departments</li>
		</ol>
	</div>

	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
						<div class="box-header">
							<h3 class="box-title text-white">Select a Department (Year <?php echo (int)$year; ?>)</h3>
						</div>

						<div class="box-body">
							<?php if (!empty($departments)): ?>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Department</th>
											<th>Yearly</th>
											<th>Quarterly</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($departments as $dept): ?>
											<tr>
												<td><?php echo htmlspecialchars((string)($dept['department_name'] ?? 'N/A')); ?></td>
												<td>
													<a class="btn btn-info btn-sm" href="<?php echo base_url('performance/department/' . (int)$dept['id'] . '/' . (int)$year); ?>">
														View Staff (Year)
													</a>
												</td>
												<td>
													<?php for ($q = 1; $q <= 4; $q++): ?>
														<a class="btn btn-default btn-sm" style="margin-right:6px; margin-bottom:6px;" href="<?php echo base_url('performance/department/' . (int)$dept['id'] . '/' . (int)$year . '/' . (int)$q); ?>">
															Q<?php echo (int)$q; ?>
														</a>
													<?php endfor; ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							<?php else: ?>
								<p class="text-white mb-3">No departments found.</p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

