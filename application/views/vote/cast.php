<div class="content-wrapper bg-[#3E3E3E]">
    <section class="content-header">
        <h1>
            <?php echo html_escape($poll['title']); ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url('vote'); ?>">Vote</a></li>
            <li class="active">Ballot</li>
        </ol>
    </section>

    <section class="content">
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

        <div class="row">
            <div class="col-md-8">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">
                            <?php if ($has_voted) : ?>
                                Your vote has been recorded
                            <?php elseif (!$accepting_votes) : ?>
                                This poll is not open for voting
                            <?php else : ?>
                                Choose one candidate
                            <?php endif; ?>
                        </h3>
                    </div>

                    <div class="box-body">
                        <?php if (!empty($poll['description'])) : ?>
                            <p><?php echo html_escape($poll['description']); ?></p>
                        <?php endif; ?>
                        <p class="text-muted">
                            Voting closes <?php echo date('D, d M Y H:i', strtotime($poll['end_date'])); ?>.
                        </p>

                        <?php if ($has_voted) : ?>
                            <div class="callout callout-success">
                                <h4><i class="fa fa-check"></i> Thank you</h4>
                                <p>
                                    Your vote on this poll has been recorded and cannot be changed.
                                    Results are only visible to HR.
                                </p>
                            </div>
                            <a href="<?php echo base_url('vote'); ?>" class="btn btn-default">Back to polls</a>

                        <?php elseif (!$accepting_votes) : ?>
                            <div class="callout callout-warning">
                                <h4><i class="fa fa-clock-o"></i> Voting is not open</h4>
                                <p>
                                    This poll is either not yet open or has already closed, and you did
                                    not cast a vote.
                                </p>
                            </div>
                            <a href="<?php echo base_url('vote'); ?>" class="btn btn-default">Back to polls</a>

                        <?php elseif (empty($candidates)) : ?>
                            <div class="callout callout-warning">
                                <h4><i class="fa fa-info-circle"></i> No candidates available to you</h4>
                                <p>
                                    Every candidate on this poll is either you or a member of your own
                                    department, so there is no one you are eligible to vote for.
                                </p>
                            </div>
                            <a href="<?php echo base_url('vote'); ?>" class="btn btn-default">Back to polls</a>

                        <?php else : ?>
                            <form role="form" action="<?php echo base_url('vote/submit/' . $poll['id']); ?>" method="post" onsubmit="return confirm('Submit your vote? This cannot be changed afterwards.');">
                                <?php foreach ($candidates as $candidate) : ?>
                                    <div class="radio" style="padding: 8px 0; border-bottom: 1px solid #444;">
                                        <label>
                                            <input type="radio" name="candidate_id" value="<?php echo $candidate['staff_id']; ?>" required>
                                            <strong><?php echo html_escape($candidate['staff_name']); ?></strong>
                                            <small class="text-muted">
                                                &mdash; <?php echo html_escape(!empty($candidate['department_name']) ? $candidate['department_name'] : 'Unassigned'); ?>
                                            </small>
                                        </label>
                                    </div>
                                <?php endforeach; ?>

                                <div style="margin-top: 20px;">
                                    <a href="<?php echo base_url('vote'); ?>" class="btn btn-default">Cancel</a>
                                    <button type="submit" class="btn btn-primary pull-right hover:border-[#DA7F00] border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]">Submit my vote</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Voting rules</h3>
                    </div>
                    <div class="box-body">
                        <ul style="padding-left: 18px;">
                            <li>You get one vote on this poll, and it cannot be changed.</li>
                            <li>You cannot vote for yourself.</li>
                            <li>
                                You cannot vote for anyone in your own department
                                <?php if (!empty($staff['department_id'])) : ?>
                                    &mdash; candidates from your department are not shown.
                                <?php endif; ?>
                            </li>
                            <li>Results are visible to HR only.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
