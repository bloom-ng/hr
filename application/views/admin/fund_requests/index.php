<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 class="text-white">Fund Requests</h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('admin/dashboard'); ?>" class="text-[#DA7F00] hover:text-[#DA7F00]"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="text-white">Fund Requests</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <?php if ($this->session->flashdata('success')) : ?>
        <div class="col-md-12">
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        </div>
      <?php elseif ($this->session->flashdata('error')) : ?>
        <div class="col-md-12">
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-times"></i> Failed!</h4>
            <?php echo $this->session->flashdata('error'); ?>
          </div>
        </div>
      <?php endif; ?>

      <div class="col-xs-12">
        <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
          <div class="box-header">
            <h3 class="box-title text-white">Manage Fund Requests</h3>
            <div class="d-flex mt-3">
              <?php if (!empty($can_create)) : ?>
                <a href="<?= base_url('fund-requests/create'); ?>" class="btn btn-primary hover:border-[#DA7F00] border-[#DA7F00] bg-[#DA7F00] hover:bg-[#DA7F00]">
                  <i class="fa fa-plus"></i> Create Request
                </a>
              <?php endif; ?>
            </div>
          </div>

          <div class="box-body">
            <table id="fundRequestsTable" class="table">
              <thead>
                <tr class="text-white">
                  <th>#</th>
                  <th>Department</th>
                  <th>Staff</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Payment</th>
                  <th>Created</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($requests)) : ?>
                  <?php foreach ($requests as $req) : ?>
                    <tr class="text-white">
                      <td><a class="text-[#DA7F00]" href="<?= base_url('fund-requests/view/'.$req['id']); ?>"><?= (int)$req['id']; ?></a></td>
                      <td><?= html_escape($this->Department_model->get_department_name($req['department_id'])); ?></td>
                      <td>
                        <?php $staff = $this->Staff_model->select_staff_byID($req['staff_id']); echo !empty($staff) ? html_escape($staff[0]['staff_name']) : 'N/A'; ?>
                      </td>
                      <td>₦<?= number_format($req['amount'], 2); ?></td>
                      <td>
                        <span class="px-4 py-2 font-semibold rounded-full <?= $req['status']==='Approved' ? 'bg-green-500' : ($req['status']==='Pending' ? 'bg-yellow-500' : 'bg-red-500'); ?>">
                          <?= html_escape($req['status']); ?>
                        </span>
                      </td>
                      <td>
                        <span class="px-4 py-2 font-semibold rounded-full <?= $req['payment_status']==='Paid' ? 'bg-green-500' : ($req['payment_status']==='Pending' ? 'bg-yellow-500' : 'bg-red-500'); ?>">
                          <?= html_escape($req['payment_status']); ?>
                        </span>
                      </td>
                      <td><?= date('Y-m-d H:i', strtotime($req['created_at'])); ?></td>
                      <td>
                        <?php $role = $this->session->userdata('role'); ?>
                        <div class="btn-group" role="group">
                          <a href="<?= base_url('fund-requests/view/'.$req['id']); ?>" class="btn btn-sm btn-info text-white" title="View"><i class="fa fa-eye"></i></a>
                          <?php if ($role === 'super' && $req['status'] === 'Pending') : ?>
                            <a href="<?= base_url('fund-requests/approve/'.$req['id']); ?>" class="btn btn-sm btn-success text-white" title="Approve"><i class="fa fa-check"></i></a>
                            <a href="<?= base_url('fund-requests/decline/'.$req['id']); ?>" class="btn btn-sm btn-danger text-white" title="Decline"><i class="fa fa-times"></i></a>
                          <?php endif; ?>
                          <?php if ($role === 'finance') : ?>
                            <form method="post" action="<?= base_url('fund-requests/update-payment/'.$req['id']); ?>" class="form-inline" style="display:inline-block">
                              <select name="payment_status" class="form-control input-sm bg-[#3E3E3E] border border-[#555] text-white">
                                <option <?= $req['payment_status']==='Pending'?'selected':''; ?>>Pending</option>
                                <option <?= $req['payment_status']==='Paid'?'selected':''; ?>>Paid</option>
                                <option <?= $req['payment_status']==='Declined'?'selected':''; ?>>Declined</option>
                              </select>
                              <button type="submit" class="btn btn-sm btn-primary bg-[#DA7F00] border-[#DA7F00] hover:bg-[#DA7F00]">Update</button>
                            </form>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else : ?>
                  <tr>
                    <td colspan="8" class="text-center text-white py-4">No fund requests found.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- DataTables -->
<script src="<?php echo base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>

<style>
  #fundRequestsTable { background-color: #2C2C2C; color: white; }
  #fundRequestsTable thead th { background-color: #3E3E3E !important; color: white !important; border-bottom: 1px solid #555 !important; font-weight: 600; }
  #fundRequestsTable tbody tr { background-color: #2C2C2C; color: white; border-bottom: 1px solid #444; }
  #fundRequestsTable tbody tr:hover { background-color: #3E3E3E !important; }
  #fundRequestsTable tbody td { border-top: 1px solid #444; padding: 12px 8px; vertical-align: middle; }
  .dataTables_wrapper .dataTables_length,
  .dataTables_wrapper .dataTables_filter,
  .dataTables_wrapper .dataTables_info,
  .dataTables_wrapper .dataTables_processing,
  .dataTables_wrapper .dataTables_paginate { color: white; }
  .dataTables_wrapper .dataTables_paginate .paginate_button { color: white !important; border: 1px solid #555; background: #3E3E3E; }
  .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #DA7F00 !important; border-color: #DA7F00; }
  .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #DA7F00 !important; border-color: #DA7F00; }
  .btn-group .btn { margin-right: 2px; }
  .btn-group .btn:last-child { margin-right: 0; }
</style>

<script>
  $(document).ready(function() {
    $('#fundRequestsTable').DataTable({
      paging: true,
      lengthChange: true,
      searching: true,
      ordering: true,
      info: true,
      autoWidth: false,
      pageLength: 25,
      language: {
        search: '_INPUT_',
        searchPlaceholder: 'Search fund requests...',
        paginate: { next: '<i class="fa fa-chevron-right"></i>', previous: '<i class="fa fa-chevron-left"></i>' },
        emptyTable: 'No fund requests found',
        info: 'Showing _START_ to _END_ of _TOTAL_ requests',
        infoEmpty: 'No requests available',
        infoFiltered: '(filtered from _MAX_ total requests)',
        lengthMenu: 'Show _MENU_ requests per page'
      },
      dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
           '<"row"<"col-sm-12"tr>>' +
           '<"row"<"col-sm-5"i><"col-sm-7"p>>'
    });

    // Style the table header and rows
    $('#fundRequestsTable thead th').addClass('bg-[#3E3E3E] text-white border-b border-[#555]');
    $('#fundRequestsTable tbody tr').addClass('border-b border-[#444] hover:bg-[#3E3E3E]');
  });
</script>
