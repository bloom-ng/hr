<div class="content-wrapper bg-[#3E3E3E]">
    <section class="content-header">
        <h1>
            Polls
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Vote</li>
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
                        <h3 class="box-title text-white">Open Polls</h3>
                        <?php if (!empty($is_manager)) : ?>
                            <div class="box-tools">
                                <a href="<?php echo base_url('vote/manage'); ?>" class="btn btn-primary btn-sm hover:border-[#DA7F00] border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]"><i class="fa fa-cog"></i> Manage Polls</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Closes</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($polls)) : ?>
                                    <?php foreach ($polls as $poll) : ?>
                                        <?php
                                        $is_open = $poll['status'] === 'open'
                                            && time() >= strtotime($poll['start_date'])
                                            && time() <= strtotime($poll['end_date']);
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo html_escape($poll['title']); ?>
                                                <?php if (!empty($poll['description'])) : ?>
                                                    <br><small class="text-muted"><?php echo html_escape($poll['description']); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('Y-m-d H:i', strtotime($poll['end_date'])); ?></td>
                                            <td>
                                                <?php if ($poll['has_voted']) : ?>
                                                    <span class="label label-success"><i class="fa fa-check"></i> Voted</span>
                                                <?php elseif ($is_open) : ?>
                                                    <span class="label label-warning">Awaiting your vote</span>
                                                <?php else : ?>
                                                    <span class="label label-default">Closed</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url('vote/cast/' . $poll['id']); ?>" class="btn btn-xs <?php echo ($is_open && !$poll['has_voted']) ? 'btn-primary' : 'btn-default'; ?>">
                                                    <?php echo ($is_open && !$poll['has_voted']) ? 'Vote now' : 'View'; ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="4" class="text-center">There are no polls for you right now.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
