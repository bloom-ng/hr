<div class="content-wrapper bg-[#3E3E3E]">
    <section class="content-header">
        <h1>
            Manage Polls
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Vote</a></li>
            <li class="active">Manage</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <?php if ($this->session->flashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Success!</h4>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Error!</h4>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Polls</h3>
                        <div class="box-tools">
                            <a href="<?php echo base_url('vote/create'); ?>" class="btn btn-primary hover:border-[#DA7F00] border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]"><i class="fa fa-plus"></i> Create Poll</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Voting Window</th>
                                    <th>Status</th>
                                    <th>Candidates</th>
                                    <th>Votes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($polls)) : ?>
                                    <?php foreach ($polls as $poll) : ?>
                                        <tr>
                                            <td><?php echo $poll['id']; ?></td>
                                            <td><?php echo html_escape($poll['title']); ?></td>
                                            <td>
                                                <?php echo date('Y-m-d H:i', strtotime($poll['start_date'])); ?>
                                                &mdash;
                                                <?php echo date('Y-m-d H:i', strtotime($poll['end_date'])); ?>
                                            </td>
                                            <td>
                                                <?php
                                                $labels = [
                                                    'draft'  => 'label-default',
                                                    'open'   => 'label-success',
                                                    'closed' => 'label-danger',
                                                ];
                                                $label = isset($labels[$poll['status']]) ? $labels[$poll['status']] : 'label-default';
                                                ?>
                                                <span class="label <?php echo $label; ?>"><?php echo ucfirst($poll['status']); ?></span>
                                            </td>
                                            <td><?php echo $poll['candidate_count']; ?></td>
                                            <td><?php echo $poll['vote_count']; ?></td>
                                            <td>
                                                <a href="<?php echo base_url('vote/results/' . $poll['id']); ?>" class="btn btn-info btn-xs">Results</a>
                                                <a href="<?php echo base_url('vote/edit/' . $poll['id']); ?>" class="btn btn-warning btn-xs">Edit</a>
                                                <?php if ($poll['status'] === 'open') : ?>
                                                    <a href="<?php echo base_url('vote/close/' . $poll['id']); ?>" class="btn btn-default btn-xs" onclick="return confirm('Close this poll? No further votes will be accepted.');">Close</a>
                                                <?php endif; ?>
                                                <a href="<?php echo base_url('vote/delete/' . $poll['id']); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Delete this poll and all of its votes? This cannot be undone.');">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No polls found.</td>
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
