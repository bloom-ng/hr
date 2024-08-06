 <footer class="main-footer bg-[#2C2C2C] text-white">
     <div class="pull-right hidden-xs">
         <div class="flex text-black">
             <a href="https://www.instagram.com/bloom_digitalmedia?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank"><img src="<?php echo base_url(); ?>assets/dist/img/Instagram.svg" alt="Instagram Link" /></a>
             <a href="https://x.com/bloomdigitmedia?s=20" target="_blank"><img src="<?php echo base_url(); ?>assets/dist/img/Twitter.svg" alt="X Link" /></a>
             <a href="https://www.linkedin.com/company/bloom-digital-media-nigeria/" target="_blank"><img src="<?php echo base_url(); ?>assets/dist/img/LinkedIn.svg" alt="LinkedIn Link" /></a>
             <a href="https://www.facebook.com/bloomdigitmedia/" target="_blank"><img src="<?php echo base_url(); ?>assets/dist/img/Facebook.svg" alt="Facebook Link" /></a>
             <a href="https://www.tiktok.com/@bloomdigitmedia?_t=8j71WPhCotx&_r=1" target="_blank"><img src="<?php echo base_url(); ?>assets/dist/img/TikTok.svg" alt="TikTok Link" /></a>
             <a href="https://www.youtube.com/@Bloom_DigitalMedia" target="_blank"><img src="<?php echo base_url(); ?>assets/dist/img/YouTube.svg" alt="Youtube Link" /></a>
         </div>
     </div>
     <strong>&copy; <?php echo date("Y"); ?></strong> Bloom Digital Media Ltd.
 </footer>

 </div>
 <!-- ./wrapper -->


 <!-- Bootstrap 3.3.7 -->
 <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
 <!-- Slimscroll -->
 <script src="<?php echo base_url(); ?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
 <!-- bootstrap datepicker -->
 <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js">
 </script>
 <!-- FastClick -->
 <script src="<?php echo base_url(); ?>assets/bower_components/fastclick/lib/fastclick.js"></script>
 <!-- AdminLTE App -->
 <script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
 <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
 <script src="<?php echo base_url(); ?>assets/dist/js/pages/dashboard.js"></script>
 <!-- AdminLTE for demo purposes -->
 <script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>
 <!-- DataTables -->
 <script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
 <script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js">
 </script>

 <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
 <script>
     $.widget.bridge('uibutton', $.ui.button);
 </script>

 <!-- Date Picker -->
 <script>
     $('#datepicker').datepicker({
         autoclose: true
     })
 </script>

 <!-- Datatable -->
 <script>
     $(function() {
         $('#example1').DataTable()

         $('#example2').DataTable({
             'paging': true,
             'lengthChange': false,
             'searching': false,
             'ordering': true,
             'info': true,
             'autoWidth': false
         })
     })
 </script>
 </body>

 </html>