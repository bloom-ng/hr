  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
			<div class="small-box bg-green">
				<div class="inner">
					<h3><?php
						if (isset($leave)) {
							echo sizeof($leave);
						} else {
							echo 0;
						}
						?></h3>

					<p>Leaves</p>
				</div>
				<div class="icon">
					<i class="ionicons ion-log-out"></i>
				</div>
				<a href="<?php echo base_url(); ?>view-leave" class="small-box-footer pull-right bg-[#D9D9D9] text-black px-16 py-2">More Info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
        </div>

		  <div class="col-lg-3 col-xs-6">
			  <!-- small box -->
			  <div class="small-box bg-primary">
				  <div class="inner">
					  <h3><?php
						  if (isset($appraisal)) {
							  echo sizeof($appraisal);
						  } else {
							  echo 0;
						  }
						  ?></h3>

					  <p>Available Appraisal</p>
				  </div>
				  <div class="icon">
					  <i class="fa fa-thumbs-up"></i>
				  </div>
				  <a href="<?php echo base_url(); ?>Appraisal_new/my_appraisals" class="small-box-footer pull-right bg-[#D9D9D9] text-black px-16 py-2">More Info <i class="fa fa-arrow-circle-right"></i></a>
			  </div>
		  </div>

		  <div class="col-lg-3 col-xs-6">
			  <!-- small box -->
			  <div class="small-box bg-purple">
				  <div class="inner">
					  <h3>&#8358;<?php
						  if (isset($bonus)) {
							  echo $bonus;
						  } else {
							  echo 0;
						  }
						  ?></h3>

					  <p>Unpaid Bonus</p>
				  </div>
				  <div class="icon">
					  <i class="fa fa-money"></i>
				  </div>
				  <a href="<?php echo base_url(); ?>bonus/manage/<?php echo $this->session->userdata('staff_id') ?>" class="small-box-footer pull-right bg-[#D9D9D9] text-black px-16 py-2">More Info <i class="fa fa-arrow-circle-right"></i></a>
			  </div>
		  </div>

		  <div class="col-lg-3 col-xs-6">
			  <!-- small box -->
			  <div class="small-box bg-orange">
				  <div class="inner">
					  <h3>&#8358;<?php
						  if (isset($commission)) {
							  echo $commission;
						  } else {
							  echo 0;
						  }
						  ?></h3>

					  <p>Unpaid Commissions</p>
				  </div>
				  <div class="icon">
					  <i class="fa fa-money"></i>
				  </div>
				  <a href="<?php echo base_url(); ?>commission/manage/<?php echo $this->session->userdata('staff_id') ?>" class="small-box-footer pull-right bg-[#D9D9D9] text-black px-16 py-2">More Info <i class="fa fa-arrow-circle-right"></i></a>
			  </div>
		  </div>
		  <div class="col-lg-3 col-xs-6">
			  <!-- small box -->
			  <div class="small-box bg-orange">
				  <div class="inner">
					  <h3>&#8358;<?php
						  if (isset($unpaidFine)) {
							  echo $unpaidFine;
						  } else {
							  echo 0;
						  }
						  ?></h3>

					  <p>Unpaid Fines</p>
				  </div>
				  <div class="icon">
					  <i class="fa fa-money"></i>
				  </div>
				  <a href="<?php echo base_url(); ?>staff/manage-deductions/<?php echo $this->session->userdata('staff_id') ?>" class="small-box-footer pull-right bg-[#D9D9D9] text-black px-16 py-2">More Info <i class="fa fa-arrow-circle-right"></i></a>
			  </div>
		  </div>


        <!-- ./col -->
      </div>
      <!-- /.row -->

      <!-- My Performance -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
            <div class="box-header with-border">
              <h3 class="box-title text-white">
                <i class="fa fa-line-chart"></i> My Performance
                <small class="text-[#D9D9D9]">&mdash; Year <?php echo isset($performance_year) ? (int) $performance_year : (int) date('Y'); ?></small>
              </h3>
            </div>

            <div class="box-body">
              <?php if (!empty($performance) && !empty($performance['monthly'])): ?>
                <?php
                  $overallScore = (float) $performance['overall_score'];
                  $overallBand = (string) $performance['overall_rating_band'];
                  // Colour the band so the staff gets quick visual feedback.
                  $bandClasses = [
                    'Outstanding' => 'bg-green',
                    'Excellent'   => 'bg-aqua',
                    'Good'        => 'bg-primary',
                    'Fair'        => 'bg-yellow',
                    'Poor'        => 'bg-red',
                  ];
                  $bandClass = $bandClasses[$overallBand] ?? 'bg-primary';
                ?>
                <div class="row">
                  <div class="col-md-4">
                    <div class="small-box <?php echo $bandClass; ?>">
                      <div class="inner">
                        <h3><?php echo $overallScore; ?>%</h3>
                        <p>Overall Score &mdash; <?php echo htmlspecialchars($overallBand); ?></p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-star"></i>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <p class="text-white mb-2">
                      Based on <?php echo sizeof($performance['monthly']); ?> finalized monthly appraisal(s) this year.
                    </p>
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Month</th>
                            <th>Job Perf %</th>
                            <th>Learning %</th>
                            <th>Score</th>
                            <th>Rating</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($performance['monthly'] as $m): ?>
                            <tr>
                              <td><?php echo htmlspecialchars((string) $m['month_under_review']); ?></td>
                              <td><?php echo round((float) $m['computed']['breakdown']['job_performance_percent'], 2); ?></td>
                              <td><?php echo round((float) $m['computed']['breakdown']['learning_growth_percent'], 2); ?></td>
                              <td><?php echo (float) $m['computed']['score']; ?></td>
                              <td><?php echo htmlspecialchars((string) $m['computed']['rating_band']); ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              <?php else: ?>
                <p class="text-white">No finalized appraisals yet for this year. Your performance will appear here once your appraisals are finalized.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
