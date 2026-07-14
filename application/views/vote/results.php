<div class="content-wrapper bg-[#3E3E3E]">
    <section class="content-header">
        <h1>
            Results &mdash; <?php echo html_escape($poll['title']); ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url('vote/manage'); ?>">Vote</a></li>
            <li class="active">Results</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-9">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Tally</h3>
                        <div class="box-tools">
                            <span class="label label-warning"><?php echo $total_votes; ?> total <?php echo $total_votes === 1 ? 'vote' : 'votes'; ?></span>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if (empty($results)) : ?>
                            <p class="text-center">This poll has no candidates.</p>
                        <?php else : ?>
                            <?php foreach ($results as $row) : ?>
                                <?php $pct = $total_votes > 0 ? round(($row['votes'] / $total_votes) * 100, 1) : 0; ?>
                                <div style="margin-bottom: 16px;">
                                    <div>
                                        <strong><?php echo html_escape($row['staff_name']); ?></strong>
                                        <small class="text-muted">&mdash; <?php echo html_escape(!empty($row['department_name']) ? $row['department_name'] : 'Unassigned'); ?></small>
                                        <span class="pull-right">
                                            <?php echo $row['votes']; ?> <?php echo (int) $row['votes'] === 1 ? 'vote' : 'votes'; ?>
                                            (<?php echo $pct; ?>%)
                                        </span>
                                    </div>
                                    <div class="progress progress-sm" style="margin-top: 6px;">
                                        <div class="progress-bar" role="progressbar" style="width: <?php echo $pct; ?>%; background-color: #DA7F00;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="box-footer">
                        <a href="<?php echo base_url('vote/manage'); ?>" class="btn btn-default">Back to polls</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Poll info</h3>
                    </div>
                    <div class="box-body">
                        <p>
                            <strong>Status:</strong>
                            <?php
                            $labels = ['draft' => 'label-default', 'open' => 'label-success', 'closed' => 'label-danger'];
                            $label = isset($labels[$poll['status']]) ? $labels[$poll['status']] : 'label-default';
                            ?>
                            <span class="label <?php echo $label; ?>"><?php echo ucfirst($poll['status']); ?></span>
                        </p>
                        <p><strong>Opens:</strong><br><?php echo date('D, d M Y H:i', strtotime($poll['start_date'])); ?></p>
                        <p><strong>Closes:</strong><br><?php echo date('D, d M Y H:i', strtotime($poll['end_date'])); ?></p>
                        <hr>
                        <p class="text-muted">
                            Votes are anonymous. Only the totals above are recorded for display &mdash;
                            who voted for whom is never shown.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
