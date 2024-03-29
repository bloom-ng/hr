<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Manage Bonus</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Staff Management</a></li>
            <li class="active">Manage Bonus</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Manage Bonus</h3>
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
                                                <a class="btn btn-info hover:bg-[#DA7F00] bg-[#DA7F00] border-0" href="/bonus/manage/<?php echo $staff['id'] ?>">Bonus</a>
                                                <button class="btn btn-success hover:bg-[#595959] bg-[#595959] border-0" data-toggle="modal" data-target="#commissionModal<?php echo $staff['id']; ?>">Add</button>
                                            </td>
                                        </tr>

                                        <!--Add Commission Modal -->
                                        <div class="modal fade bg-[#3E3E3E]" id="commissionModal<?php echo $staff['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel<?php echo $staff['id']; ?>">
                                            <div class="modal-dialog bg-[#3E3E3E]" role="document">
                                                <div class="modal-content bg-[#3E3E3E]">
                                                    <div class="modal-header bg-[#3E3E3E]">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title" id="attendanceModalLabel<?php echo $staff['id']; ?>">Bonuses</h4>
                                                    </div>
                                                    <div class="modal-body">

                                                        <?php echo form_open('bonus/insert'); ?>
                                                        <div class="form-group">
                                                            <label for="date">Date:</label>
                                                            <input type="date" class="bg-gray-200 form-control" id="date" name="date" required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="amount">Amount (&#8358;) :</label>
                                                            <input type="number" class="bg-gray-200 form-control" id="amount" name="amount" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="reason">Reason</label>
                                                            <input type="text" class="bg-gray-200 form-control" id="reason" name="reason" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="status">Status</label>
                                                            <select name="status" class="bg-gray-200 form-control">
                                                                <option selected value="<?php echo $this->Bonus_model::BONUS_PENDING ?>">
                                                                    Pending
                                                                </option>
                                                                <option value="<?php echo $this->Bonus_model::BONUS_PAID ?>">Paid</option>
                                                            </select>
                                                        </div>
                                                        <input type="hidden" id="staff_id" name="staff_id" value="<?php echo $staff['id']; ?>">
                                                        <button type="submit" class="btn hover:bg-[#DA7F00] bg-[#DA7F00] border-0 btn-primary">Submit</button>
                                                        </form>
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