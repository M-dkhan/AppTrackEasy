<div class="modal fade" id="editJobModal" tabindex="-1" role="dialog" aria-labelledby="editJobLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg custom-dialog" role="document">
        <div class="modal-content custom-content">
            <div class="modal-header custom-header">
                <h5 class="modal-title custom-title" id="editJobLabel">Edit Job Details</h5>
                <button type="button" class="close custom-close modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body custom-body">
                <form id="editJobForm">
                    @csrf
                    <input type="hidden" name="job_id" >
                    <div class="form-group custom-group">
                      <label for="job_title">Job Title</label>
                      <input type="text" class="form-control custom-input" id="job_title" name="job_title" required>
                    </div>
                    <div class="form-group custom-group">
                      <label for="company_name">Company Name</label>
                      <input type="text" class="form-control custom-input" id="company_name" name="company_name" required>
                    </div>
                    <div class="form-group custom-group">
                      <label for="application_date">Application Date</label>
                      <input type="date" class="form-control custom-input" id="application_date" name="application_date" required>
                    </div>
                    <div class="form-group custom-group">
                      <label for="application_deadline">Application Deadline</label>
                      <input type="date" class="form-control custom-input" id="application_deadline" name="application_deadline" required>
                    </div>
                    <div class="form-group custom-group">
                      <label for="status">Status</label>
                      <select class="form-control custom-select" id="status" name="status">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                      </select>
                    </div>
                    <div class="form-group custom-group">
                      <label for="contact_information">Contact Information</label>
                      <input type="text" class="form-control custom-input" id="contact_information" name="contact_information">
                    </div>
                    <div class="form-group custom-group">
                      <label for="notes_or_comments">Notes and Comments</label>
                      <textarea class="form-control custom-textarea" id="notes_or_comments" name="notes_or_comments" rows="3"></textarea>
                    </div>
                  </form>
            </div>
            <div class="modal-footer custom-footer">
                <button type="button" class="btn btn-secondary custom-close-button modal-close" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary custom-save-button" id="saveJobChanges">Save Changes</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        // "Edit" button click event
        // "Edit" button click event
        $('.btn-edit-job').on('click', function() {
            var jobId = $(this).data('id');

            // Make an AJAX call to get job details for editing
            $.ajax({
                type: 'GET', // Use the appropriate HTTP method
                url: '{{ route('jobs.show', '') }}/' + jobId,
                success: function (data) {
                    console.log(data)
                    // Populate the "Edit Job" modal form fields with data
                    $('#editJobForm input[name="job_id"]').val(data.id);
                    $('#editJobForm input[name="job_title"]').val(data.job_title);
                    $('#editJobForm input[name="company_name"]').val(data.company_name);
                    $('#editJobForm input[name="application_date"]').val(data.application_date);
                    $('#editJobForm input[name="application_deadline"]').val(data.application_deadline);
                    $('#editJobForm select[name="status"]').val(data.status);
                    $('#editJobForm input[name="contact_information"]').val(data.contact_information);
                    $('#editJobForm textarea[name="notes_or_comments"]').text(data.notes_or_comments); // Use .text() for textarea

                    // Open the "Edit Job" modal
                    $('#editJobModal').modal('show');
                },
                error: function (xhr) {
                    // Handle error, display validation errors, or show an error message
                    console.log('Error:', xhr);
                    $('#validationErrors').text('An error occurred. Please try again.').show();
                }
            });
        });


        $('#editJobModal .modal-close').click(function() {
            $('#editJobModal').modal('hide');
        });
        
        $('#saveJobChanges').on('click', function() {
            console.log('saveJobChanges button is being pressed.')
            var jobId = $('#editJobForm input[name="job_id"]').val()
            // Get the edited job details from the form fields
            var editedJobData = {
                job_title: $('#editJobForm input[name="job_title"]').val(),
                company_name: $('#editJobForm input[name="company_name"]').val(),
                application_date: $('#editJobForm input[name="application_date"]').val(),
                application_deadline: $('#editJobForm input[name="application_deadline"]').val(),
                status: $('#editJobForm select[name="status"]').val(),
                contact_information: $('#editJobForm input[name="contact_information"]').val(),
                notes_or_comments: $('#editJobForm textarea[name="notes_or_comments"]').val()
            };

            // Make an AJAX call to update the job details
            $.ajax({
                type: 'PUT', // Use the appropriate HTTP method (e.g., PUT)
                url: '{{ route('jobs.update', '') }}/' + jobId,
                data: editedJobData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    // Close the "Edit Job" modal
                    $('#editJobModal').modal('hide');
                    // Optionally, display a success message
                    $('#successMessage').text(response.message).show();

                    setTimeout(function () {
                        location.reload();
                    }, 1000);

                },
                error: function (xhr) {
                    // Handle validation errors or show an error message
                    var errors = xhr.responseJSON.errors;
                    var errorHtml = '<ul>';
                    $.each(errors, function (key, value) {
                        errorHtml += '<li>' + value + '</li>';
                    });
                    errorHtml += '</ul>';
                    $('#validationErrors').html(errorHtml).show();
                }
            });
        });

    });
  </script>
    