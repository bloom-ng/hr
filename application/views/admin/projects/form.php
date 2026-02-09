<?php
// This is a partial form that can be included in both create and edit views
$is_edit = isset($project);
$project = $is_edit ? $project : (object)[
    'priority' => 'low',
    'schedule_type' => 'monthly',
    'status' => 'pending',
    'payment_status' => 'pending'
];
$form_action = $is_edit ? site_url('projects/edit/' . $project->id) : site_url('projects/create');
$user_role = $this->session->userdata('role');
?>

<div class="box-body">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-times"></i> Error!</h4>
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= $form_action ?>" class="form-vertical">

        <style>
            /* Vertical form styling */
            .form-vertical .form-group {
                margin-bottom: 20px;
            }

            .form-vertical .control-label {
                display: block;
                margin-bottom: 8px;
                font-weight: 600;
                font-size: 14px;
            }

            .form-vertical .form-control {
                width: 100%;
                margin-bottom: 5px;
            }

            .form-vertical .help-block {
                margin-top: 5px;
                font-size: 12px;
                line-height: 1.4;
            }

            .form-vertical textarea.form-control {
                resize: vertical;
                min-height: 100px;
            }

            .form-vertical select.form-control {
                height: 40px;
            }

            .form-vertical input.form-control {
                height: 40px;
            }
        </style>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="control-label text-white">Project Name <span class="text-red">*</span></label>
                    <input type="text" class="form-control bg-[#3E3E3E] border border-[#555] text-white" id="name" name="name" required
                        value="<?= $is_edit ? html_escape($project->name) : set_value('name') ?>">
                </div>

                <!-- Department Selection (only for super/hrm) -->
                <?php if (in_array($user_role, ['finance', 'super', 'hrm'])): ?>
                    <div class="form-group">
                        <label for="department_id" class="control-label text-white">Department <span class="text-red">*</span></label>
                        <select name="department_id" id="department_id" class="form-control bg-[#3E3E3E] border border-[#555] text-white" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <?php
                                $selected = false;
                                if ($is_edit && isset($project->department_id)) {
                                    $selected = ($dept['id'] == $project->department_id);
                                } elseif (!$is_edit && $dept['id'] == $staff_department) {
                                    $selected = true;
                                }
                                ?>
                                <option value="<?= $dept['id'] ?>" <?= $selected ? 'selected' : '' ?>>
                                    <?= html_escape($dept['department_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="department_id" value="<?= $staff_department ?>">
                    <div class="form-group">
                        <label for="department_display" class="control-label text-white">Department</label>
                        <input type="text" class="form-control bg-[#3E3E3E] border border-[#555] text-white"
                            value="<?= html_escape($this->Department_model->get_department_name($staff_department)) ?>" readonly>
                    </div>
                <?php endif; ?>

                <!-- Project Manager Selection -->
                <div class="form-group">
                    <label for="manager_id" class="control-label text-white">Project Manager <span class="text-red">*</span></label>
                    <select name="manager_id" id="manager_id" class="form-control bg-[#3E3E3E] border border-[#555] text-white" required>
                        <option value="">Select Project Manager</option>
                        <?php if (isset($staff_options) && !empty($staff_options)): ?>
                            <?php foreach ($staff_options as $staff_member): ?>
                                <option value="<?= $staff_member['id'] ?>"
                                    <?= ($is_edit && isset($project->manager_id) && $project->manager_id == $staff_member['id']) ||
                                        (!$is_edit && $staff_member['id'] == $this->session->userdata('userid')) ? 'selected' : '' ?>>
                                    <?= html_escape($staff_member['staff_name']) ?>
                                    <?php if (isset($staff_member['department_name'])): ?>
                                        (<?= html_escape($staff_member['department_name']) ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="help-block text-gray-400">
                        <?php if (in_array($user_role, ['super', 'hrm'])): ?>
                            Select any staff member as project manager. When you change the department, the manager list will update automatically.
                        <?php else: ?>
                            You can select yourself or any other staff from your department as the project manager.
                        <?php endif; ?>
                    </small>
                </div>

                <div class="form-group">
                    <label for="client_email" class="control-label text-white">Client Email <span class="text-red">*</span></label>
                    <input type="email" class="form-control bg-[#3E3E3E] border border-[#555] text-white" id="client_email" name="client_email" required
                        value="<?= $is_edit ? html_escape($project->client_email) : set_value('client_email') ?>">
                </div>

                <div class="form-group">
                    <label for="client_phone" class="control-label text-white">Client Phone <span class="text-red">*</span></label>
                    <input type="text" class="form-control bg-[#3E3E3E] border border-[#555] text-white" id="client_phone" name="client_phone" required
                        value="<?= $is_edit ? html_escape($project->client_phone) : set_value('client_phone') ?>">
                </div>

                <div class="form-group">
                    <label for="priority" class="control-label text-white">Priority <span class="text-red">*</span></label>
                    <select class="form-control bg-[#3E3E3E] border border-[#555] text-white" id="priority" name="priority" required>
                        <?php
                        $priorities = [
                            'low' => 'Low',
                            'medium' => 'Medium',
                            'high' => 'High'
                        ];
                        foreach ($priorities as $value => $label):
                            if ($_POST && isset($_POST['priority'])) {
                                $selected = ($_POST['priority'] === $value);
                            } else {
                                $selected = ($is_edit && $project->priority === $value);
                            }
                            $color_class = [
                                'low' => 'bg-blue-500',
                                'medium' => 'bg-yellow-500',
                                'high' => 'bg-red-500'
                            ][$value] ?? 'bg-gray-500';
                        ?>
                            <option value="<?= $value ?>" <?= $selected ? 'selected' : '' ?> data-class="<?= $color_class ?>">
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="schedule_type" class="control-label text-white">Schedule Type <span class="text-red">*</span></label>
                    <select class="form-control bg-[#3E3E3E] border border-[#555] text-white" id="schedule_type" name="schedule_type" required>
                        <?php
                        $schedule_types = [
                            'monthly' => 'Monthly',
                            'quarterly' => 'Quarterly',
                            'annual' => 'Annual',
                            'one-off' => 'One-off'
                        ];
                        foreach ($schedule_types as $value => $label):
                            if ($_POST && isset($_POST['schedule_type'])) {
                                $selected = ($_POST['schedule_type'] === $value);
                            } else {
                                $selected = ($is_edit && $project->schedule_type === $value);
                            }
                        ?>
                            <option value="<?= $value ?>" <?= $selected ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="schedule_date" class="control-label text-white">Schedule Date</label>
                    <input type="date" class="form-control bg-[#3E3E3E] border border-[#555] text-white" id="schedule_date" name="schedule_date"
                        value="<?= $is_edit && $project->schedule_date ? date('Y-m-d', strtotime($project->schedule_date)) : set_value('schedule_date') ?>">
                </div>

                <?php if (in_array($user_role, ['finance', 'super'])): ?>
                    <div class="form-group">
                        <label for="payment_status" class="control-label text-white">Payment Status</label>
                        <select class="form-control bg-[#3E3E3E] border border-[#555] text-white" id="payment_status" name="payment_status">
                            <?php
                            $payment_statuses = [
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'refunded' => 'Refunded'
                            ];
                            foreach ($payment_statuses as $value => $label):
                                if ($_POST && isset($_POST['payment_status'])) {
                                    $selected = ($_POST['payment_status'] === $value);
                                } else {
                                    $selected = ($is_edit && isset($project->payment_status) && $project->payment_status === $value);
                                }
                            ?>
                                <option value="<?= $value ?>" <?= $selected ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="receipt_id" class="control-label text-white">Receipt ID</label>
                        <div class="input-group">
                            <input type="text" class="form-control bg-[#3E3E3E] border border-[#555] text-white" id="receipt_id" name="receipt_id"
                                value="<?= $is_edit && isset($project->receipt_id) ? html_escape($project->receipt_id) : set_value('receipt_id') ?>"
                                placeholder="Enter receipt code to validate">
                            <div class="input-group-append">
                                <button type="button" class="bg-[#DA7F00] px-6 py-2" id="validate_receipt_btn" onclick="validateReceipt()">
                                    <i class="fa fa-check-circle"></i> Validate
                                </button>
                            </div>
                        </div>
                        <div id="receipt_validation_status" class="mt-2" style="display: none;">
                            <div id="receipt_loading" class="text-yellow-400" style="display: none;">
                                <i class="fa fa-spinner fa-spin"></i> Validating receipt...
                            </div>
                            <div id="receipt_success" class="text-green-400" style="display: none;">
                                <i class="fa fa-check-circle"></i> Receipt validated successfully
                                <?php if (in_array($user_role, ['super', 'finance'])): ?>
                                    <button type="button" class="btn btn-sm btn-success ml-2" id="view_invoice_btn" onclick="viewInvoice()" style="display: none;">
                                        <i class="fa fa-external-link-alt"></i> View Invoice
                                    </button>
                                <?php endif; ?>
                            </div>
                            <div id="receipt_error" class="text-red-400" style="display: none;">
                                <i class="fa fa-times-circle"></i> <span class="" id="receipt_error_msg"></span>
                            </div>
                        </div>
                        <small class="help-block text-gray-400">
                            Enter a receipt code to validate it against our payment system
                        </small>
                    </div>
                <?php endif; ?>

                <?php if (in_array($user_role, ['super', 'hrm'])): ?>
                    <div class="form-group">
                        <label for="status" class="control-label text-white">Status</label>
                        <select class="form-control bg-[#3E3E3E] border border-[#555] text-white" id="status" name="status">
                            <?php
                            $statuses = [
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'in-progress' => 'In Progress',
                                'on-hold' => 'On Hold',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled'
                            ];
                            foreach ($statuses as $value => $label):
                                // Check if form was submitted (validation failed) - use POST value
                                // Otherwise use the project's current status
                                if ($_POST && isset($_POST['status'])) {
                                    $selected = ($_POST['status'] === $value);
                                } else {
                                    $selected = ($is_edit && isset($project->status) && $project->status === $value);
                                }
                            ?>
                                <option value="<?= $value ?>" <?= $selected ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="description_of_deliverables" class="control-label text-white">Description of Deliverables <span class="text-red">*</span></label>
            <textarea class="form-control bg-[#3E3E3E] border border-[#555] text-white" id="description_of_deliverables" name="description_of_deliverables" rows="5" required placeholder="Describe what needs to be delivered for this project..."><?= $is_edit ? html_escape($project->description_of_deliverables) : set_value('description_of_deliverables') ?></textarea>
            <small class="help-block text-gray-400">Provide a detailed description of all deliverables for this project</small>
        </div>

        <div class="form-group">
            <label for="special_request" class="control-label text-white">Special Requests</label>
            <textarea class="form-control bg-[#3E3E3E] border border-[#555] text-white" id="special_request" name="special_request" rows="3" placeholder="Any special requirements or requests for this project..."><?= $is_edit ? html_escape($project->special_request) : set_value('special_request') ?></textarea>
            <small class="help-block text-gray-400">Optional: Add any special requirements or requests</small>
        </div>

        <div class="form-group">
            <div class="text-center">
                <button type="submit" class="btn btn-primary hover:border-[#DA7F00] border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00] text-white px-6 py-2 rounded-md transition-all duration-200 hover:shadow-lg">
                    <i class="fa fa-save mr-2"></i> <?= $is_edit ? 'Update' : 'Create' ?> Project
                </button>
                <a href="<?= site_url('projects') ?>" class="btn btn-default hover:bg-[#3E3E3E] border border-[#555] text-white px-6 py-2 rounded-md transition-all duration-200 ml-3">
                    <i class="fa fa-times mr-2"></i> Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Initialize select2 for better select inputs
        if ($.fn.select2) {
            $('select').select2({
                theme: 'dark',
                width: '100%',
                placeholder: function() {
                    return $(this).attr('placeholder') || 'Please select...';
                }
            });
        }

        // Initialize datepicker
        if ($.fn.datepicker) {
            $('#schedule_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
        }

        // Update priority badge color when changed
        $('#priority').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const colorClass = selectedOption.data('class') || 'bg-gray-500';
            $(this).removeClass('bg-red-500 bg-yellow-500 bg-blue-500 bg-gray-500').addClass(colorClass);
        }).trigger('change');

        // Dynamic manager loading based on department selection (for super/hrm)
        $('#department_id').on('change', function() {
            var departmentId = $(this).val();
            var managerSelect = $('#manager_id');

            if (departmentId) {
                // Show loading state
                managerSelect.html('<option value="">Loading staff...</option>');

                // Make AJAX call to get staff by department
                $.ajax({
                    url: '<?= site_url("projects/get_staff_by_department") ?>',
                    type: 'POST',
                    data: {
                        department_id: departmentId
                    },
                    dataType: 'json',
                    success: function(response) {
                        managerSelect.html('<option value="">Select Project Manager</option>');

                        if (response.success && response.staff) {
                            $.each(response.staff, function(index, staff) {
                                var optionText = staff.staff_name;
                                if (staff.department_name) {
                                    optionText += ' (' + staff.department_name + ')';
                                }
                                managerSelect.append('<option value="' + staff.id + '">' + optionText + '</option>');
                            });
                        } else {
                            managerSelect.html('<option value="">No staff found in this department</option>');
                        }
                    },
                    error: function() {
                        managerSelect.html('<option value="">Error loading staff</option>');
                    }
                });
            } else {
                managerSelect.html('<option value="">Select Department first</option>');
            }
        });

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Form validation styling
        $('form').on('submit', function(e) {
            var isValid = true;
            $(this).find('input[required], select[required], textarea[required]').each(function() {
                if (!$(this).val()) {
                    $(this).addClass('border-red-500');
                    isValid = false;
                } else {
                    $(this).removeClass('border-red-500');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });

        // Remove error styling on input
        $('input, select, textarea').on('input change', function() {
            $(this).removeClass('border-red-500');
        });

        // Initialize receipt validation status if editing with existing receipt
        <?php if ($is_edit && isset($project->receipt_id) && !empty($project->receipt_id)): ?>
            $('#receipt_validation_status').show();
            $('#receipt_success').show();
            <?php if (in_array($user_role, ['super', 'finance'])): ?>
                $('#view_invoice_btn').show();
            <?php endif; ?>
            $('#validate_receipt_btn').prop('disabled', true).text('Validated');
        <?php endif; ?>
    });

    // Receipt validation function
    function validateReceipt() {
        var receiptId = $('#receipt_id').val().trim();

        if (!receiptId) {
            alert('Please enter a receipt ID to validate');
            return;
        }

        // Show loading state
        $('#receipt_validation_status').show();
        $('#receipt_loading').show();
        $('#receipt_success').hide();
        $('#receipt_error').hide();
        $('#view_invoice_btn').hide();
        $('#validate_receipt_btn').prop('disabled', true);

        // Make AJAX call to validate receipt
        $.ajax({
            url: '<?= site_url("projects/validate_receipt") ?>',
            type: 'POST',
            data: {
                receipt_id: receiptId
            },
            dataType: 'json',
            success: function(response) {
                $('#receipt_loading').hide();

                if (response.success) {
                    $('#receipt_success').show();
                    <?php if (in_array($user_role, ['super', 'finance'])): ?>
                        $('#view_invoice_btn').show();
                    <?php endif; ?>
                    $('#validate_receipt_btn').text('Validated').removeClass('btn-outline-warning').addClass('btn-outline-success');

                    // Store receipt data for invoice viewing
                    window.validatedReceiptId = response.data.receipt_id;
                    window.validatedReceiptData = response.data;
                } else {
                    $('#receipt_error').show();
                    $('#receipt_error_msg').text(response.message || 'Receipt validation failed');
                    $('#validate_receipt_btn').prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                $('#receipt_loading').hide();
                $('#receipt_error').show();
                $('#receipt_error_msg').text('Error validating receipt. Please try again.');
                $('#validate_receipt_btn').prop('disabled', false);

                console.error('Receipt validation error:', error);
            }
        });
    }

    // View invoice function
    function viewInvoice() {
        var receiptId = window.validatedReceiptId || $('#receipt_id').val().trim();

        if (!receiptId) {
            alert('No receipt ID available');
            return;
        }

        // Open invoice in new tab
        var invoiceUrl = 'https://invoice.bloomdigitmedia.com/receipt/' + receiptId;
        window.open(invoiceUrl, '_blank');
    }

    // Auto-validate receipt when input changes (for existing receipts)
    $('#receipt_id').on('blur', function() {
        var receiptId = $(this).val().trim();
        if (receiptId && !$('#validate_receipt_btn').prop('disabled')) {
            // Only auto-validate if it's a new receipt (not already validated)
            if (!window.validatedReceiptId || window.validatedReceiptId !== receiptId) {
                validateReceipt();
            }
        }
    });
</script>