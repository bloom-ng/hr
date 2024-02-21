<!-- application/views/export_attendance.php -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Export Attendance</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Attendance Management</a></li>
            <li class="active">Show Attendance</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Show Attendance</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- Show Attendance Button -->
                        <button class="btn text-white bg-[#DA7F00] border-0" id="showAttendanceBtn" data-toggle="modal" data-target="#exportModal">Show Attendance</button>

                        <!-- Attendance Table -->
                        <table id="attendanceTable" class="table" style="display:none;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Staff Name</th>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Notes</th>
                                    <!-- Add other columns as needed -->
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Table rows will be dynamically added here -->
                            </tbody>
                        </table>

                        <!-- Export Modal -->
                        <div class="modal fade bg-[#3E3E3E]" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel">
                            <div class="modal-dialog bg-[#3E3E3E]" role="document">
                                <div class="modal-content bg-[#3E3E3E]">
                                    <div class="modal-header bg-[#3E3E3E]">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 class="modal-title" id="exportModalLabel">View Attendance</h4>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo form_open('attendance/fetch_attendance'); ?>
                                        <div class="form-group">
                                            <label for="from_date">From Date:</label>
                                            <input type="date" class="form-control bg-gray-200" id="from_date" name="from_date" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="to_date">To Date:</label>
                                            <input type="date" class="form-control  bg-gray-200" id="to_date" name="to_date" required>
                                        </div>
                                        <button type="button" class="btn text-white bg-[#DA7F00] border-0" id="exportBtn">View</button>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
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

<script>
    $(document).ready(function() {
        // Show Attendance Button Click Event
        $('#exportBtn').click(function() {
            // Fetch attendance data using AJAX and populate the table
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('attendance/fetch_attendance'); ?>',
                data: {
                    from_date: from_date,
                    to_date: to_date
                },
                success: function(data) {
                    var attendanceData = JSON.parse(data);
                    populateAttendanceTable(attendanceData);
                },
                error: function() {
                    alert('An error occurred while fetching attendance data.');
                }
            });
        });

        // Export Button Click Event
        $('#exportBtn').click(function() {
            // Submit the export form
            $('#exportForm').submit();
        });

        // Function to populate the attendance table dynamically
        function populateAttendanceTable(attendanceData) {
            var tableBody = $('#attendanceTable tbody');
            tableBody.empty();

            $.each(attendanceData, function(index, attendance) {
                var row = '<tr>' +
                    '<td>' + (index + 1) + '</td>' +
                    '<td>' + attendance.staff_name + '</td>' +
                    '<td>' + attendance.date + '</td>' +
                    '<td>' + attendance.time_in + '</td>' +
                    '<td>' + attendance.time_out + '</td>' +
                    '<td>' + attendance.notes + '</td>' +
                    // Add other columns as needed
                    '</tr>';

                tableBody.append(row);
            });

            // Show the attendance table
            $('#attendanceTable').show();

            // Close the export modal
            $('#exportModal').modal('hide');
        }
    });
</script>