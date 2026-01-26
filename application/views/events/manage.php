
<div class="content-wrapper bg-[#3E3E3E]">
    <section class="content-header">
        <h1>
            Manage Events
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Events</a></li>
            <li class="active">Manage</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Events List</h3>
                        <div class="box-tools">
                            <a href="<?php echo base_url('events/create'); ?>" class="btn btn-primary hover:border-[#DA7F00] border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]"><i class="fa fa-plus"></i> Add Event</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($events)) : ?>
                                    <?php foreach ($events as $event) : ?>
                                        <tr>
                                            <td><?php echo $event['id']; ?></td>
                                            <td><?php echo $event['title']; ?></td>
                                            <td><?php echo date('Y-m-d H:i', strtotime($event['start_date'])); ?></td>
                                            <td><?php echo date('Y-m-d H:i', strtotime($event['end_date'])); ?></td>
                                            <td><?php echo substr($event['description'], 0, 50) . '...'; ?></td>
                                            <td>
                                                <a href="<?php echo base_url('events/edit/' . $event['id']); ?>" class="btn btn-warning btn-xs">Edit</a>
                                                <a href="<?php echo base_url('events/delete/' . $event['id']); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No events found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div>
