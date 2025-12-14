<div class="content-wrapper bg-[#3E3E3E]">
  <section class="content-header">
    <h1>
      Attendance AI
      <small>Analyze Logs & Ask Questions</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Tools</a></li>
      <li class="active">Attendance AI</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <!-- UPLOAD COLUMN -->
      <div class="col-md-4">
        <div class="box border-[#DA7F00] bg-[#2C2C2C]">
          <div class="box-header with-border">
            <h3 class="box-title">1. Upload Log</h3>
          </div>
          <div class="box-body">
            <form id="uploadForm" enctype="multipart/form-data">
              <div class="form-group">
                <label for="file">Biometric/Excel CSV Log</label>
                <input type="file" name="attendance_file" id="attendance_file" class="form-control" accept=".csv,.txt" required>
                <p class="help-block">Supported Format: CSV/Tab-delimited. Required columns: Name, Time (or Datetime).</p>
              </div>
              <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-upload"></i> Analyze</button>
            </form>
          </div>
        </div>

        <!-- SUMMARY STATS -->
        <div class="info-box bg-red" id="statLateBox" style="display:none;">
          <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Late Entries</span>
            <span class="info-box-number" id="totalLateVal">0</span>
          </div>
        </div>
      </div>

      <!-- RESULTS COLUMN -->
      <div class="col-md-8">
        <div class="box border-[#DA7F00] bg-[#2C2C2C]" id="resultBox" style="display:none;">
          <div class="box-header with-border">
            <h3 class="box-title">Analysis Report</h3>
          </div>
          <div class="box-body">
             <!-- TABS -->
             <div class="nav-tabs-custom bg-[#2C2C2C] text-white">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_all" data-toggle="tab" aria-expanded="false">All Staff Stats</a></li>
                    <li class=""><a href="#tab_punctual" data-toggle="tab" aria-expanded="false">Top Punctual</a></li>
                    <li class=""><a href="#tab_late" data-toggle="tab" aria-expanded="true">Top Late</a></li>
                </ul>
                <div class="tab-content" style="background:#2C2C2C; padding: 10px;">
                  
                  <!-- TAB LATE -->
                  <div class="tab-pane" id="tab_late">
                    <h4><i class="fa fa-thumbs-down text-red"></i> Top Late Staff</h4>
                    <ul class="list-group" id="topLateList"></ul>
                  </div>
                  
                  <!-- TAB PUNCTUAL -->
                  <div class="tab-pane" id="tab_punctual">
                    <h4><i class="fa fa-thumbs-up text-green"></i> Top Punctual Staff</h4>
                    <ul class="list-group" id="topPunctualList"></ul>
                  </div>

                  <!-- TAB ALL -->
                  <div class="tab-pane active" id="tab_all">
                      <div class="clearfix" style="margin-bottom:10px;">
                          <h4 class="pull-left">Full Staff List</h4>
                          <button class="btn btn-success btn-sm pull-right" id="downloadCsvBtn"><i class="fa fa-download"></i> Download CSV</button>
                      </div>
                      <div class="table-responsive">
                          <table class="table table-bordered text-white" id="allStaffTable">
                              <thead>
                                  <tr>
                                      <th>Name</th>
                                      <th>Present</th>
                                      <th>Late</th>
                                      <th>Rate</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <!-- AJax Content -->
                              </tbody>
                          </table>
                      </div>
                  </div>

                </div>
             </div>
          </div>
          <!-- CHAT SECTION INSIDE RESULT BOX -->
          <div class="box-footer">
              <h4><i class="fa fa-comments-o"></i> Ask about this data</h4>
              <div id="chatResponse" style="margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; min-height: 50px; border-radius: 4px; display:none;"></div>
              <div class="input-group">
                  <input type="text" id="chatInput" class="form-control" placeholder="E.g. Who came late most often?">
                  <span class="input-group-btn">
                      <button type="button" id="chatBtn" class="btn btn-warning btn-flat">Ask AI</button>
                  </span>
              </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
$(document).ready(function() {
    // Handle File Upload
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Analyzing...');

        $.ajax({
            url: '<?php echo base_url("attendance_ai/analyze"); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                btn.prop('disabled', false).html('<i class="fa fa-upload"></i> Analyze');
                
                if (response.error) {
                    alert(response.error);
                    return;
                }

                if (response.success) {
                    // Update UI
                    $('#statLateBox').show();
                    $('#totalLateVal').text(response.summary.total_late);
                    
                    $('#resultBox').show();
                    
                    // Render Lists
                    var lateHtml = '';
                    response.top_late.forEach(function(item) {
                       lateHtml += '<li class="list-group-item bg-[#2C2C2C]">' + item.name + 
                                   '<span class="pull-right badge bg-red">' + item.late + ' times</span></li>';
                    });
                    $('#topLateList').html(lateHtml);

                    var punctHtml = '';
                    response.top_punctual.forEach(function(item) {
                       punctHtml += '<li class="list-group-item bg-[#2C2C2C]">' + item.name + 
                                   '<span class="pull-right badge bg-green">' + item.late + ' times late</span></li>';
                    });
                    $('#topPunctualList').html(punctHtml);

                    // Render All Staff Table
                    var allHtml = '';
                    window.allStaffData = response.all_staff || []; // Parse for CSV later
                    window.allStaffData.forEach(function(item) {
                        allHtml += '<tr>' +
                            '<td>' + item.name + '</td>' +
                            '<td>' + item.present + '</td>' +
                            '<td>' + item.late + '</td>' +
                            '<td>' + item.lateness_rate + '%</td>' +
                            '</tr>';
                    });
                    $('#allStaffTable tbody').html(allHtml);

                    $('#chatResponse').hide().text('');
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fa fa-upload"></i> Analyze');
                alert('An error occurred during verification/upload.');
            }
        });
    });

    // Handle CSV Download
    $('#downloadCsvBtn').click(function() {
        if (!window.allStaffData || window.allStaffData.length === 0) {
            alert('No data to download.');
            return;
        }
        
        var csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Name,Times Present,Times Late,Lateness Rate (%)\n"; // Headers
        
        window.allStaffData.forEach(function(item) {
            var row = [
                '"' + item.name.replace(/"/g, '""') + '"', // Escape quotes
                item.present,
                item.late,
                item.lateness_rate
            ].join(",");
            csvContent += row + "\n";
        });
        
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "attendance_stats_" + new Date().toISOString().slice(0,10) + ".csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    // Handle Chat
    $('#chatBtn').click(function() {
        var q = $('#chatInput').val().trim();
        if(!q) return;

        var btn = $(this);
        btn.prop('disabled', true).text('...');
        
        $.ajax({
            url: '<?php echo base_url("attendance_ai/ask"); ?>',
            type: 'POST',
            data: {question: q},
            dataType: 'json',
            success: function(res) {
                 btn.prop('disabled', false).text('Ask AI');
                 $('#chatResponse').show().html('<b>AI:</b> ' + res.answer.replace(/\n/g, '<br>'));
            },
            error: function() {
                 btn.prop('disabled', false).text('Ask AI');
                 alert("Failed to contact AI.");
            }
        });
    });
});
</script>
