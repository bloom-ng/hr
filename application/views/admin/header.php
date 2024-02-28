<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Bloom HR</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/output.css">
  <link rel="shortcut icon" href="<?php echo base_url(); ?>uploads/profile-pic/favicon.png" type="image/x-icon">


  <!-- jQuery 3 -->
  <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition bg-[#2C2C2C] text-white sidebar-mini">
  <div class="wrapper">

    <header class="main-header bg-[#2C2C2C]">
      <!-- Logo -->

      <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>HR</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="uppercase font-black text-[#FF9501]"><b>Bloom</b></span><span class="uppercase font-meduim text-white">&nbsp; HR</span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Notifications: style can be found in dropdown.less -->

            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu bg-[#2C2C2C] border-0">
              <a href="#" class="dropdown-toggle bg-[#2C2C2C]" data-toggle="dropdown">
                <img src="https://ui-avatars.com/api/?name=<?php echo $this->session->userdata('username') ?>" class="user-image" alt="User Image">
                <span class="hidden-xs"><?php echo $this->session->userdata('username') ?></span>
              </a>
              <ul class="dropdown-menu bg-[#2C2C2C]">
                <!-- User image -->
                <li class="user-header bg-[#2C2C2C]">
                  <img src="https://ui-avatars.com/api/?name=<?php echo $this->session->userdata('username') ?>" class="img-circle" alt="User Image">
                  <small class="text-white"><?php echo $this->session->userdata('username') ?></small>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer bg-[#2C2C2C]">
                  <div class="pull-right">
                    <a href="<?php echo base_url(); ?>logout" class="btn btn-default bg-[#595959] hover:bg-[#595959] border-0 text-white btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <!-- <img src="https://ui-avatars.com/api/?name=<?php echo $this->session->userdata('username') ?>" class="img-circle" alt="User Image"> -->
          </div>
          <div class="pull-left info">
            <!-- <small> <?php echo $this->session->userdata('username') ?></small> -->
            <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
          </div>
        </div>
        <!-- search form -->
        <!-- <form action="#" method="get" class="sidebar-form">
          <div class="ml-4 input-group">
            <input type="text" name="q" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
          </div>
        </form> -->
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
          <li class="header">MAIN NAVIGATION</li>

          <li class="active"><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>

          <li class="treeview">
            <a href="#">
              <i class="fa fa-wrench"></i> <span>Tools</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a target="_blank" href="https://webmail.bloomdigitmedia.com"><i class="fa fa-circle-o"></i>Webmail</a></li>
              <li><a target="_blank" href="https://pm.bloomdigitmedia.com"><i class="fa fa-circle-o"></i>Project management</a></li>
              <li><a target="_blank" href="https://invoice.bloomdigitmedia.com"><i class="fa fa-circle-o"></i>Invoice</a></li>
              <li><a target="_blank" href="https://inventory.bloomdigitmedia.com"><i class="fa fa-circle-o"></i>Asset Management</a></li>
              <li><a target="_blank" href="https://ticket.bloomdigitmedia.com"><i class="fa fa-circle-o"></i>Support/Ticket</a></li>
            </ul>
          </li>


          <li class="treeview">
            <a href="#">
              <i class="fa fa-sticky-note"></i> <span>Memo</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?php echo base_url(); ?>memos"><i class="fa fa-circle-o"></i> Memos</a></li>
            </ul>
          </li>

          <!-- APPRAISAL-->
          <li class="treeview">
            <a href="#">
              <i class="fa fa-thumbs-up"></i> <span>Appraisals</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?php echo base_url(); ?>manage-appraisal"><i class="fa fa-circle-o"></i>Manage Appraisal</a></li>
              <?php if (in_array($this->session->userdata('role'), (array)"staff")) : ?>
                <li><a href="<?php echo base_url(); ?>my-appraisal"><i class="fa fa-circle-o"></i> My Appraisal</a></li>
              <?php endif; ?>
            </ul>
          </li>
          <!-- APPRAISAL-->

          <?php if (in_array($this->session->userdata('role'), ["finance", "super"])) : ?>
            <!-- FINANCE -->
            <li class="treeview">
              <a href="#">
                <i class="fa fa-calendar"></i> <span>Commission</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>commission-staff"><i class="fa fa-circle-o"></i>Manage Commissions</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-money"></i> <span>Salary</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>add-salary"><i class="fa fa-circle-o"></i> Add Salary</a></li>
                <li><a href="<?php echo base_url(); ?>manage-salary"><i class="fa fa-circle-o"></i> Manage Salary</a></li>
              </ul>
            </li>

            <!-- BONUS -->
            <li class="treeview">
              <a href="#">
                <i class="fa fa-money"></i> <span>Bonus</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>bonus-staff"><i class="fa fa-circle-o"></i>Manage Bonus</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-users"></i> <span>Staff</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>manage-staff"><i class="fa fa-circle-o"></i> Manage Staff</a></li>
              </ul>
            </li>
          <?php endif; ?>

          <?php if (in_array($this->session->userdata('role'), ["hrm", "super"])) : ?>
            <!-- HR ADMIN -->
            <li class="treeview">
              <a href="#">
                <i class="fa fa-share"></i> <span>Leave</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>approve-leave"><i class="fa fa-circle-o"></i> Manage Staff's Leave</a></li>
                <li><a href="<?php echo base_url(); ?>leave-history"><i class="fa fa-circle-o"></i> Leave History</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-users"></i> <span>Staff</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>add-staff"><i class="fa fa-circle-o"></i> Add Staff</a></li>
                <li><a href="<?php echo base_url(); ?>manage-staff"><i class="fa fa-circle-o"></i> Manage Staff</a></li>
              </ul>
            </li>


            <li class="treeview">
              <a href="#">
                <i class="fa fa-calendar"></i> <span>Attendance</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>attendance-manage"><i class="fa fa-circle-o"></i>Manage Attendance</a></li>
                <li><a href="<?php echo base_url(); ?>attendance-export"><i class="fa fa-circle-o"></i> Show Attendance</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-th-large"></i> <span>Department</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>add-department"><i class="fa fa-circle-o"></i> Add Department</a></li>
                <li><a href="<?php echo base_url(); ?>manage-department"><i class="fa fa-circle-o"></i> Manage Department</a></li>
              </ul>
            </li>

          <?php endif; ?>


          <?php if (in_array($this->session->userdata('role'), ["super"])) : ?>
            <li class="active"><a href="<?php echo base_url(); ?>admins"><i class="fa fa-users"></i>
                <span>
                  Manage Admins
                </span></a>
            </li>
          <?php endif; ?>


          <?php if (in_array($this->session->userdata('role'), ["staff"])) : ?>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-money"></i> <span>Salary</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>view-salary"><i class="fa fa-circle-o"></i> View Salary</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-share"></i> <span>Leave</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>apply-leave"><i class="fa fa-circle-o"></i> Apply Leave</a></li>
                <li><a href="<?php echo base_url(); ?>view-leave"><i class="fa fa-circle-o"></i> My Leave History</a></li>
              </ul>
            </li>

            <!-- BONUS -->
            <li class="treeview">
              <a href="#">
                <i class="fa fa-money"></i> <span>Bonus</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>bonus/manage/<?php echo $this->session->userdata('staff_id') ?>"><i class="fa fa-circle-o"></i>My Bonus</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-money"></i> <span>Commission</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>commission/manage/<?php echo $this->session->userdata('staff_id') ?>"><i class="fa fa-circle-o"></i>My Commissions</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-money"></i> <span>Fines</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>staff/manage-deductions/<?php echo $this->session->userdata('staff_id') ?>"><i class="fa fa-circle-o"></i>My Deductions</a></li>
              </ul>
            </li>


          <?php endif; ?>






        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>

    <?php
    // if ($this->session->userdata('usertype')!=1)
    // { 
    //   redirect('login');
    // }
    ?>
