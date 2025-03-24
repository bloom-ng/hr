<div class="content-wrapper bg-[#3E3E3E]">
    <section class="content-header">
        <h1 class="text-white">
            Push Notifications
            <small class="text-white">Send notifications to mobile apps</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Notifications</li>
        </ol>
    </section>

    <section class="content">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('info')): ?>
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Information</h4>
                <?php echo $this->session->flashdata('info'); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Social Media Notification Box -->
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title text-black">Social Media Post Notifications</h3>
                    </div>
                    <div class="box-body">
                        <form action="<?php echo base_url('notify/send_social_notification'); ?>" method="POST">
                            <div class="form-group">
                                <label for="platform" class="text-black">Platform</label>
                                <select name="platform" class="form-control" required>
                                    <option value="linkedin">LinkedIn</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="twitter">Twitter</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="youtube">YouTube</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="link" class="text-black">Post Link (optional)</label>
                                <input type="text" class="form-control" name="link" placeholder="New Post link">
                            </div>

                            <div class="form-group">
                                <label for="message" class="text-black">Message (optional)</label>
                                <textarea class="form-control" name="message" rows="3" placeholder="Check out our latest post on Instagram!"></textarea>
                            </div>
                            <button type="submit" class="btn bg-[#DA7F00] hover:bg-[#DA7F00] border-0 btn-primary">Send Notification</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- General Notification Box -->
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title text-black">General Notifications</h3>
                    </div>
                    <div class="box-body">
                        <form action="<?php echo base_url('notify/send_general_notification'); ?>" method="POST">
                            <div class="form-group">
                                <label for="title" class="text-black">Notification Title</label>
                                <input type="text" class="form-control" name="title" placeholder="Title" required>
                            </div>
                            <div class="form-group">
                                <label for="notification_message" class="text-black">Notification Message</label>
                                <textarea class="form-control" name="notification_message" rows="3" placeholder="Enter Message" required></textarea>
                            </div>

                            <button type="submit" class="btn bg-[#DA7F00] hover:bg-[#DA7F00] border-0 btn-primary">Send General Notification</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Meeting Notification Box -->
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title text-black">Meeting Notifications</h3>
                    </div>
                    <div class="box-body">
                        <form action="<?php echo base_url('notify/send_meeting_notification'); ?>" method="POST">
                            <div class="form-group">
                                <label for="title" class="text-black">Notification Title</label>
                                <input type="text" class="form-control" name="title" placeholder="Title" required>
                            </div>
                            <div class="form-group">
                                <label for="time" class="text-black">Notification Message</label>
                                <input type="time" class="form-control" name="time" required>
                            </div>
                            <div class="form-group">
                                <label for="link" class="text-black">Meeting Link</label>
                                <input type="text" class="form-control" name="link" placeholder="Link" required>
                            </div>

                            <button type="submit" class="btn bg-[#DA7F00] hover:bg-[#DA7F00] border-0 btn-primary">Send Meeting Notification</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receipt Checking Button -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title text-black">Push Receipt Management</h3>
                    </div>
                    <div class="box-body">
                        <p class="text-black">Check delivery status for previously sent notifications (recommended to run every 15 minutes).</p>
                        <a href="<?php echo base_url('admin/notify/check_receipts'); ?>" class="btn btn-info">
                            <i class="fa fa-refresh"></i> Check Receipts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>