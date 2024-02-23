<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Manage Attendance</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Staff Management</a></li>
            <li class="active">Manage Attendance</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Manage Attendance</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($staff_members)) : ?>
                                    <?php $i = 1;
                                    foreach ($staff_members as $staff) : ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $staff['staff_name']; ?></td>
                                            <td>
                                                <button class="btn btn-info bg-[#DA7F00] hover:bg-[#DA7F00] border-0" data-toggle="modal" data-target="#manageModal<?php echo $staff['id']; ?>">Check</button>
                                                <button class="btn btn-success  bg-[#595959] hover:bg-[#595959] border-0" data-toggle="modal" data-target="#attendanceModal<?php echo $staff['id']; ?>">Add</button>
                                            </td>
                                        </tr>

                                        <!--Add Attendance Modal -->
                                        <?php foreach ($staff_members as $cnt) : ?>
                                            <div class="modal fade bg-[#3E3E3E]" id="attendanceModal<?php echo $cnt['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel<?php echo $cnt['id']; ?>">
                                                <div class="modal-dialog bg-[#3E3E3E]" role="document">
                                                    <div class="modal-content bg-[#3E3E3E]">
                                                        <div class="modal-header bg-[#3E3E3E]">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            <h4 class="modal-title" id="attendanceModalLabel<?php echo $cnt['id']; ?>">Attendance</h4>
                                                        </div>
                                                        <div class="modal-body">

                                                            <?php echo form_open('Attendance/insert'); ?>
                                                            <div class="form-group">
                                                                <label for="date">Date:</label>
                                                                <input type="date" class="form-control bg-gray-200" id="date" name="date" max="<?php echo date('Y-m-d'); ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="time_in">Time In:</label>
                                                                <input type="time" class="form-control bg-gray-200" id="time_in" name="time_in">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="time_out">Time Out:</label>
                                                                <input type="time" class="form-control bg-gray-200" id="time_out" name="time_out">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="notes">Notes:</label>
                                                                <textarea class="form-control bg-gray-200" id="notes" name="notes"></textarea>
                                                            </div>
                                                            <input type="hidden" id="staff_id" name="staff_id" value="<?php echo $staff['id']; ?>">
                                                            <button type="submit" class="btn hover:bg-[#DA7F00] bg-[#DA7F00] border-0 btn-primary">Submit</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                        <!-- Manage Modal -->
                                        <div class="modal fade bg-[#3E3E3E]" id="manageModal<?php echo $staff['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="manageModalLabel<?php echo $staff['id']; ?>">
                                            <div class="modal-dialog bg-[#3E3E3E]" role="document">
                                                <div class="modal-content bg-[#3E3E3E]">
                                                    <div class="modal-header bg-[#3E3E3E]">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title" id="manageModalLabel<?php echo $staff['id']; ?>">Manage Attendance</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php echo form_open('attendance/check_attendance'); ?>
                                                        <div class="form-group">
                                                            <label for="from_date">From Date:</label>
                                                            <input type="date" class="form-control bg-gray-200" id="from_date" name="from_date" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="to_date">To Date:</label>
                                                            <input type="date" class="form-control bg-gray-200" id="to_date" name="to_date" max="<?php echo date('Y-m-d'); ?>" required>
                                                        </div>
                                                        <input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
                                                        <button type="submit" class="btn btn-primary hover:bg-[#DA7F00] bg-[#DA7F00] border-0">Submit</button>
                                                        <?php echo form_close(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php $i++;
                                    endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Add this modal at the end of your view, after the existing modals -->
<div class="modal fade bg-[#3E3E3E]" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel">
    <div class="modal-dialog bg-[#3E3E3E]" role="document">
        <div class="modal-content bg-[#3E3E3E]">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="resultModalLabel">Attendance Result</h4>
            </div>
            <div class="modal-body" id="resultModalBody">

            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('form').submit(function(event) {
            event.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function(html) {
                    // Close the "Check Attendance" modal
                    $('.modal').modal('hide');

                    // Update the content of the result modal
                    $('#resultModalBody').html(html);

                    // Show the result modal
                    $('#resultModal').modal('show');
                },
                error: function() {
                    alert('An error occurred while processing the request.');
                }
            });
        });

        $('#date').on('change', function() {
            // On date change, get the selected date and check for existing attendance data
            var selectedDate = $(this).val();
            var staffId = $('#staff_id').val();

            // Perform an AJAX request to check for existing attendance
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('Attendance/check_existing_attendance'); ?>',
                data: {
                    staff_id: staffId,
                    date: selectedDate
                },
                success: function(data) {
                    // Update the form fields with existing attendance data
                    var attendanceData = JSON.parse(data);
                    $('#time_in').val(attendanceData.time_in);
                    $('#time_out').val(attendanceData.time_out);
                    $('#notes').val(attendanceData.notes);
                },
                error: function() {
                    alert('An error occurred while checking existing attendance.');
                }
            });
        });
    });
</script>