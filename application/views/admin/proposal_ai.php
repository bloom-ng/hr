<div class="content-wrapper bg-[#3E3E3E]">
  <section class="content-header">
    <h1>
      Proposal AI
      <small>Generate Professional Proposals</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Tools</a></li>
      <li class="active">Proposal AI</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box border-[#DA7F00] bg-[#2C2C2C]">
                <div class="box-header with-border">
                    <h3 class="box-title">Create New Proposal</h3>
                </div>
                <!-- FORM -->
                <form id="proposalForm" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Client Name</label>
                                    <input type="text" class="form-control" name="client_name" placeholder="e.g. Acme Corp" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Upload Brief (PDF/DOCX/TXT)</label>
                                    <input type="file" class="form-control" name="brief_file" accept=".pdf,.docx,.txt">
                                    <p class="help-block"><small>Upload a file OR paste details below.</small></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Optional: Project Title</label>
                                    <input type="text" class="form-control" name="project_title" placeholder="Leave blank to auto-generate">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tone</label>
                                    <select class="form-control" name="tone">
                                        <option>Professional</option>
                                        <option>Persuasive</option>
                                        <option>Technical</option>
                                        <option>Friendly</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Project Details / Brief</label>
                            <textarea class="form-control" name="details" id="detailsInput" rows="4" placeholder="Paste requirements here if no file uploaded..."></textarea>
                        </div>
                    </div>
                    
                    <div class="box-footer">
                        <button type="submit" class="btn btn-warning pull-right btn-lg">
                            <i class="fa fa-magic"></i> Generate Proposal
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- PREVIEW AREA -->
            <div id="previewArea" class="box border-[#DA7F00] bg-[#2C2C2C]" style="display:none;">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-eye"></i> Proposal Preview</h3>
                </div>
                <div class="box-body" style="background: #1a1a1a; color: #ffffff; max-height: 400px; overflow-y: auto; padding: 15px;">
                    <div id="typingContainer"></div>
                </div>
                <div class="box-footer">
                     <p class="text-muted"><small>* Preview shows excerpt. Full document is formatted correctly.</small></p>
                     <a href="#" id="downloadLink" class="btn btn-success btn-lg btn-block"><i class="fa fa-download"></i> Download Complete .DOCX</a>
                </div>
            </div>
            
        </div>
    </div>
  </section>
</div>

<script>
$(document).ready(function() {
    $('#proposalForm').on('submit', function(e) {
        e.preventDefault();
        var btn = $(this).find('button[type="submit"]');
        var originalText = btn.html();
        
        // Basic Validation
        if ($('input[name="brief_file"]').val() == '' && $('#detailsInput').val().trim() == '') {
            alert('Please either upload a file or enter project details.');
            return;
        }

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Analyzing & Generating...');
        $('#previewArea').slideUp();
        $('#typingContainer').text('');

        var formData = new FormData(this);

        $.ajax({
            url: '<?php echo base_url("proposal_ai/generate"); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                btn.prop('disabled', false).html(originalText);
                
                if (response.error) {
                    alert(response.error);
                } else if (response.success) {
                    $('#previewArea').slideDown();
                    
                    // Set download link
                    $('#downloadLink').attr('href', response.download_url);
                    
                    // Typing Effect
                    var text = response.preview_text || "Proposal generated successfully.";
                    typeWriter(text, 'typingContainer');
                }
            },
            error: function() {
                btn.prop('disabled', false).html(originalText);
                alert('Server connection failed. Please check logs.');
            }
        });
    });

    function typeWriter(text, elementId) {
        var i = 0;
        var speed = 10; // ms
        var container = document.getElementById(elementId);
        container.innerHTML = ''; // Reset
        
        function type() {
            if (i < text.length) {
                // Handle newlines
                if (text.charAt(i) === '\n') {
                    container.innerHTML += '<br>';
                } else {
                    container.innerHTML += text.charAt(i);
                }
                
                // Auto scroll
                container.parentElement.scrollTop = container.parentElement.scrollHeight;
                
                i++;
                setTimeout(type, speed);
            }
        }
        type();
    }
});
</script>
