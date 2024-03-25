<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Manage Staff Account</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Staff Account</a></li>
            <li class="active">Manage staff Account</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-header">
                        <h3 class="box-title text-white">Manage Staff account</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Base Salary (&#8358;)</th>
                                    <th>Account Details</th>
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
                                            <td><?php echo $staff['base_salary']; ?></td>
                                            <td><?php echo $staff['bank'] . " " . $staff['account'] ; ?></td>
                                            <td>
                                                <button class="btn btn-success bg-[#595959] hover:bg-[#595959] border-0" data-toggle="modal" data-target="#accountModal<?php echo $staff['id']; ?>">Edit</button>
                                            </td>
                                        </tr>

                                        <!--Edit account Modal -->
                                        <div class="modal fade bg-[#3E3E3E]" id="accountModal<?php echo $staff['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="accountModal<?php echo $staff['id']; ?>">
                                            <div class="modal-dialog bg-[#3E3E3E]" role="document">
                                                <div class="modal-content bg-[#3E3E3E]">
                                                    <div class="modal-header bg-[#3E3E3E]">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title" id="accountModal<?php echo $staff['id']; ?>">Account</h4>
                                                    </div>
                                                    <div class="modal-body">

                                                        <?php echo form_open("staff/accounts/update/{$staff['id']}"); ?>
                                                       
                                                        <div class="form-group">
                                                            <label for="bank">Bank:</label>
                                                            <input value="<?php echo $staff['bank'] ?>" type="text" class="bg-gray-200 form-control" id="bank" name="bank" >
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="account">Account:</label>
                                                            <input value="<?php echo $staff['account'] ?>" type="text" class="bg-gray-200 form-control" id="account" name="account" >
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="base_salary">Base Salary (&#8358;) :</label>
                                                            <input value="<?php echo $staff['base_salary'] ?>" type="number" class="bg-gray-200 form-control" id="base_salary" name="base_salary" >
                                                        </div>
                                                       
                                                        
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