
<div class="content-wrapper bg-[#3E3E3E]">
    <section class="content-header">
        <h1>
            <?php echo isset($event) ? 'Edit Event' : 'Add New Event'; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Events</a></li>
            <li class="active"><?php echo isset($event) ? 'Edit' : 'Add'; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white"><?php echo isset($event) ? 'Edit Event Details' : 'Event Details'; ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="<?php echo isset($event) ? base_url('events/update/' . $event['id']) : base_url('events/store'); ?>" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" value="<?php echo isset($event) ? $event['title'] : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="<?php echo isset($event) ? date('Y-m-d\TH:i', strtotime($event['start_date'])) : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="<?php echo isset($event) ? date('Y-m-d\TH:i', strtotime($event['end_date'])) : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter description"><?php echo isset($event) ? $event['description'] : ''; ?></textarea>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <a href="<?php echo base_url('events/manage'); ?>" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-primary pull-right"><?php echo isset($event) ? 'Update' : 'Save'; ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
