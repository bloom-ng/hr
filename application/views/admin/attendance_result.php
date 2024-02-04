<!-- admin/attendance_result.php -->
<!-- <div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="resultModalLabel">Attendance Result</h4>
            </div>
            <div class="modal-body">
                <?php if(isset($attendance_result) && !empty($attendance_result)): ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach($attendance_result as $attendance): ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($attendance['date'])); ?></td>
                                    <td><?php echo date('H:i:s', strtotime($attendance['time_in'])); ?></td>
                                    <td><?php echo date('H:i:s', strtotime($attendance['time_out'])); ?></td>
                                    <td><?php echo $attendance['notes']; ?></td>
                                </tr>
                            <?php $i++; endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center">No attendance records found for the specified date range.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> -->

<div class="modal-body">
                <?php if(isset($attendance_result) && !empty($attendance_result)): ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach($attendance_result as $attendance): ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($attendance['date'])); ?></td>
                                    <td><?php echo date('H:i:s', strtotime($attendance['time_in'])); ?></td>
                                    <td><?php echo date('H:i:s', strtotime($attendance['time_out'])); ?></td>
                                    <td><?php echo $attendance['notes']; ?></td>
                                </tr>
                            <?php $i++; endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center">No attendance records found for the specified date range.</p>
                <?php endif; ?>
            </div>