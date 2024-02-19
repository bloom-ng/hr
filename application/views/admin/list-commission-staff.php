<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Manage Commissions</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Staff Management</a></li>
            <li class="active">Manage Commissions</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Manage Commissions</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($staff_members)): ?>
                                    <?php $i=1; foreach($staff_members as $staff): ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $staff['staff_name']; ?></td>
                                            <td>
                                                <a class="btn btn-info"  href="/commission/manage/<?php echo $staff['id'] ?>" >Commissions</a>
                                                <button class="btn btn-success" data-toggle="modal" data-target="#commissionModal<?php echo $staff['id']; ?>">Add</button>
                                            </td>
                                        </tr>

                                        <!--Add Commission Modal -->
                                        <div class="modal fade" id="commissionModal<?php echo $staff['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel<?php echo $staff['id']; ?>">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title" id="attendanceModalLabel<?php echo $staff['id']; ?>">Commission</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        
                                                        <?php echo form_open('commission/insert'); ?>
                                                            <div class="form-group">
                                                                <label for="date">Date:</label>
                                                                <input type="date" class="form-control" id="date" name="date" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="client">Client:</label>
                                                                <input type="text" class="form-control" id="client" name="client" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="total">Total (&#8358;) :</label>
                                                                <input type="number" class="form-control" id="total" name="total" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="commission">Commission (%):</label>
                                                                <input type="number" class="form-control" id="commission" name="commission" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="comments">Comments:</label>
                                                                <textarea class="form-control" id="comments" name="comments"></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="status">Status</label>
                                                                <select name="status" class="form-control" >
                                                                <option selected value="<?php echo $this->Commission_model::COMMISSION_PENDING ?>">
                                                                    Pending
                                                                </option>
                                                                <option value="<?php echo $this->Commission_model::COMMISSION_PAID ?>">Paid</option>
                                                                </select>
                                                            </div>
                                                                                                    <input type="hidden" id="staff_id" name="staff_id" value="<?php echo $staff['id']; ?>">
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    
                                        <?php $i++; endforeach; ?>
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