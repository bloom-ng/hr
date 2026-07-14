<?php
$is_edit = isset($poll);
$staff_list = !empty($staff) ? $staff : [];

// Group the staff picker by department so it is obvious who sits where - the
// department a candidate belongs to decides who is allowed to vote for them.
$by_department = [];
foreach ($staff_list as $member) {
    $dept = !empty($member['department_name']) ? $member['department_name'] : 'Unassigned';
    $by_department[$dept][] = $member;
}
ksort($by_department);
?>
<div class="content-wrapper bg-[#3E3E3E]">
    <section class="content-header">
        <h1>
            <?php echo $is_edit ? 'Edit Poll' : 'Create Poll'; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url('vote/manage'); ?>">Vote</a></li>
            <li class="active"><?php echo $is_edit ? 'Edit' : 'Create'; ?></li>
        </ol>
    </section>

    <section class="content">
        <?php if ($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
        <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <form role="form" action="<?php echo $is_edit ? base_url('vote/update/' . $poll['id']) : base_url('vote/store'); ?>" method="post">
            <div class="row">
                <div class="col-md-5">
                    <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                        <div class="box-header">
                            <h3 class="box-title text-white">Poll Details</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" value="<?php echo $is_edit ? html_escape($poll['title']) : set_value('title'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" placeholder="What is this poll about?"><?php echo $is_edit ? html_escape($poll['description']) : set_value('description'); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="start_date">Voting Opens</label>
                                <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="<?php echo $is_edit ? date('Y-m-d\TH:i', strtotime($poll['start_date'])) : set_value('start_date'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="end_date">Voting Closes</label>
                                <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="<?php echo $is_edit ? date('Y-m-d\TH:i', strtotime($poll['end_date'])) : set_value('end_date'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <?php $current_status = $is_edit ? $poll['status'] : 'draft'; ?>
                                    <option value="draft" <?php echo $current_status === 'draft' ? 'selected' : ''; ?>>Draft &ndash; not visible to staff</option>
                                    <option value="open" <?php echo $current_status === 'open' ? 'selected' : ''; ?>>Open &ndash; staff can vote within the window</option>
                                    <option value="closed" <?php echo $current_status === 'closed' ? 'selected' : ''; ?>>Closed &ndash; no further votes</option>
                                </select>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="<?php echo base_url('vote/manage'); ?>" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-primary pull-right"><?php echo $is_edit ? 'Update Poll' : 'Create Poll'; ?></button>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                        <div class="box-header">
                            <h3 class="box-title text-white">Candidates</h3>
                            <div class="box-tools">
                                <span class="label label-warning" id="candidate-count">0 selected</span>
                            </div>
                        </div>
                        <div class="box-body">
                            <p class="text-muted">
                                Pick the staff who will appear on the ballot. Staff cannot vote for
                                themselves or for anyone in their own department, so each candidate is
                                only votable by people outside their department.
                            </p>

                            <?php if ($is_edit) : ?>
                                <div class="callout callout-warning">
                                    Removing a candidate from a poll that already has votes will also
                                    discard the votes cast for them.
                                </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <input type="text" class="form-control" id="candidate-search" placeholder="Search staff by name...">
                            </div>

                            <?php if (empty($by_department)) : ?>
                                <p class="text-center">No active staff found.</p>
                            <?php else : ?>
                                <div style="max-height: 420px; overflow-y: auto;">
                                    <?php foreach ($by_department as $dept_name => $members) : ?>
                                        <div class="candidate-group">
                                            <h4 class="text-white" style="border-bottom: 1px solid #444; padding-bottom: 5px;">
                                                <?php echo html_escape($dept_name); ?>
                                                <small class="text-muted">(<?php echo count($members); ?>)</small>
                                                <a href="#" class="pull-right toggle-dept" style="font-size: 12px;">select all</a>
                                            </h4>
                                            <?php foreach ($members as $member) : ?>
                                                <?php $checked = in_array((int) $member['id'], array_map('intval', $selected_candidates)); ?>
                                                <div class="checkbox candidate-row" data-name="<?php echo strtolower(html_escape($member['staff_name'])); ?>">
                                                    <label>
                                                        <input type="checkbox" name="candidates[]" value="<?php echo $member['id']; ?>" <?php echo $checked ? 'checked' : ''; ?>>
                                                        <?php echo html_escape($member['staff_name']); ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>

<script>
    (function () {
        var boxes = document.querySelectorAll('input[name="candidates[]"]');
        var counter = document.getElementById('candidate-count');
        var search = document.getElementById('candidate-search');

        function updateCount() {
            var n = document.querySelectorAll('input[name="candidates[]"]:checked').length;
            counter.textContent = n + (n === 1 ? ' selected' : ' selected');
        }

        for (var i = 0; i < boxes.length; i++) {
            boxes[i].addEventListener('change', updateCount);
        }
        updateCount();

        if (search) {
            search.addEventListener('keyup', function () {
                var term = this.value.toLowerCase();
                var groups = document.querySelectorAll('.candidate-group');
                for (var g = 0; g < groups.length; g++) {
                    var rows = groups[g].querySelectorAll('.candidate-row');
                    var visible = 0;
                    for (var r = 0; r < rows.length; r++) {
                        var match = rows[r].getAttribute('data-name').indexOf(term) !== -1;
                        rows[r].style.display = match ? '' : 'none';
                        if (match) visible++;
                    }
                    groups[g].style.display = visible ? '' : 'none';
                }
            });
        }

        var toggles = document.querySelectorAll('.toggle-dept');
        for (var t = 0; t < toggles.length; t++) {
            toggles[t].addEventListener('click', function (e) {
                e.preventDefault();
                var group = this.closest('.candidate-group');
                var rows = group.querySelectorAll('input[name="candidates[]"]');
                var allChecked = true;
                for (var k = 0; k < rows.length; k++) {
                    if (!rows[k].checked) { allChecked = false; break; }
                }
                for (var j = 0; j < rows.length; j++) {
                    rows[j].checked = !allChecked;
                }
                this.textContent = allChecked ? 'select all' : 'clear';
                updateCount();
            });
        }
    })();
</script>
