<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css"> -->
  <!-- Font Awesome -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">/ -->
  <!-- Ionicons -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css"> -->
  <!-- Theme style -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css"> -->
  <!-- iCheck -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/square/blue.css"> -->

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link href="<?= base_url('./assets/output.css') ?>" rel="stylesheet">
  <link rel="shortcut icon" href="<?php echo base_url(); ?>uploads/profile-pic/favicon.png" type="image/x-icon">

</head>
<!-- hold-transition login-page -->

<body class="max-h-screen">
  <section class="max-h-screen h-screen flex lg:flex-row flex-col">
    <div class="lg:basis-1/2 bg-neutral-900">
      <div class="flex flex-col h-screen px-[11%] my-[2%] lg:my-[0.1px] xl:py-[2%]">
        <div class="pb-[7%] flex-none">
          <img src="<?= base_url('assets/dist/img/logo_white.svg') ?>" alt="Logo SVG">
        </div>
        <div class="flex flex-col justify-center flex-grow">
          <div class="flex items-center justify-center
                      sm:items-start sm:justify-start
                      text-4xl md:text-[48px] pb-[10%] uppercase leading-[57px]">
            <a href="#"><span class="text-[#FF9501]"><b>Bloom </b></span> <span class="text-white "> Employee Management System(EMS)</span></a>
          </div>
          <!-- /.login-logo -->
          <div class="">
            <!-- <p class="">Please Login To Continue..</p> -->

            <?php echo form_open('Home/login'); ?>
            <!-- form-group has-feedback -->
            <div class="flex flex-col gap-6">
              <div class="mr-[6%]">
                <input class="placeholder-gray-400 italic w-full px-4 py-4" type=" text" name="txtusername" class="form-control" placeholder="Username/Staff Email">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
              </div>
              <div class="mr-[6%]">
                <input class="placeholder-gray-400 italic w-full px-4 py-4" type="password" name="txtpassword" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              </div>
            </div>
            <div class="text-[#DA7F00]">
              <?php echo $this->session->flashdata('login_error'); ?>
            </div>
            <div class="row">
              <!-- /.col -->
              <div class="py-20">
                <button type="submit" class="text-sm md:leading-[31.2px] md:text-[26px] font-bold px-3 py-2.5 md:py-3.5 md:px-5 text-black bg-[#FF9501]">
                  Sign In
                </button>
              </div>
              <!-- /.col -->
            </div>
            </form>
          </div>

        </div>
        <div class="flex flex-none">
          <a href="https://www.instagram.com/bloom_digitalmedia?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank"><img src="assets/dist/img/Instagram.svg" alt="Instagram Link" /></a>
          <a href="https://x.com/bloomdigitmedia?s=20" target="_blank"><img src="assets/dist/img/Twitter.svg" alt="X Link" /></a>
          <a href="https://www.linkedin.com/company/bloom-digital-media-nigeria/" target="_blank"><img src="assets/dist/img/LinkedIn.svg" alt="LinkedIn Link" /></a>
          <a href="https://www.facebook.com/bloomdigitmedia/" target="_blank"><img src="assets/dist/img/Facebook.svg" alt="Facebook Link" /></a>
          <a href="https://www.tiktok.com/@bloomdigitmedia?_t=8j71WPhCotx&_r=1" target="_blank"><img src="assets/dist/img/TikTok.svg" alt="TikTok Link" /></a>
          <a href="https://www.youtube.com/@Bloom_DigitalMedia" target="_blank"><img src="assets/dist/img/YouTube.svg" alt="Youtube Link" /></a>
        </div>
        <!-- /.login-box-body -->
      </div>
    </div>
    <div class="lg:basis-1/2 lg:block hidden">
      <div class="relative">
        <div class="flex items-center justify-center">
          <img class="z-10 py-[7%] xl:pt-[6px] xl:pb-[1px] mx-32 rounded-[90px] w-[700px] xl:h-screen h-[550px]" src="uploads/profile-pic/_MG_1393.png" alt="">
        </div>

        <div class="absolute inset-0 bg-orange-300 opacity-50 h-screen">
          <div class="absolute inset-0 h-screen bg-cover bg-center" style="background-image: url('uploads/profile-pic/bloom_element_1.png');">
          </div>
        </div>

      </div>
    </div>

    <!-- <div class="absolute z-10 top-0 left-[450px] h-[118px] w-[118px] bg-[#FF9501] rounded-full -mt-14 overflow-hidden"></div>
    <div class="absolute z-10 bottom-0 left-0 h-[79px] w-[79px] bg-white rounded-full -mb-14 -ml-14 overflow-hidden"></div>
    <div class="absolute z-10 bottom-[95px] right-[28%] transform -translate-x-1/2 h-[191px] w-[191px] bg-white rounded-full"></div>
    <div class="absolute z-10 top-[150px] right-[3.52%] h-[74px] w-[74px] bg-[#FF9501] rounded-full"></div> -->
  </section>
  <!-- /.login-box -->

  <!-- jQuery 3 -->
  <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- iCheck -->
  <script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
  <script>
    $(function() {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
      });
    });
  </script>
</body>

</html>

<!-- <div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Bloom</b> HR</a>
  </div>
   /.login-logo 
  <div class="login-box-body">
    <p class="login-box-msg">Please Login To Continue..</p> -->