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
				  <a href="<?php echo base_url(); ?>my-appraisal" class="small-box-footer pull-right bg-[#D9D9D9] text-black px-16 py-2">More Info <i class="fa fa-arrow-circle-right"></i></a>
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


        <!-- ./col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
