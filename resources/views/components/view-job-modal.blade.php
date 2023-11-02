
<!-- Add a div to display validation errors -->
<div class="alert alert-danger" id="validationErrors" style="display: none;"></div>

<!-- Add a div to display success messages -->
<div class="alert alert-success" id="successMessage" style="display: none;"></div>

<!-- "View Job" modal -->
<div class="modal fade" id="viewJobModal" tabindex="-1" role="dialog" aria-labelledby="viewJobLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg custom-dialog" role="document">
        <div class="modal-content custom-content">
            <div class="modal-header custom-header">
                <h5 class="modal-title custom-title" id="viewJobLabel">View Job Details</h5>
                <button type="button" class="close custom-close modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body custom-body">
                <form id="viewJobForm" class="form">
                    <div class="job-details">
                        <div class="form-group">
                            <label for="viewJobTitle">Job Title</label>
                            <input type="text" class="form-control" id="viewJobTitle" name="viewJobTitle" readonly>
                        </div>
                        <div class="form-group">
                            <label for="viewCompanyName">Company Name</label>
                            <input type="text" class="form-control" id="viewCompanyName" name="viewCompanyName" readonly>
                        </div>
                        <div class="form-group">
                            <label for="viewApplicationDate">Application Date</label>
                            <input type="date" class="form-control" id="viewApplicationDate" name="viewApplicationDate" readonly>
                        </div>
                        <div class="form-group">
                            <label for="viewApplicationDeadline">Application Deadline</label>
                            <input type="date" class="form-control" id="viewApplicationDeadline" name="viewApplicationDeadline" readonly>
                        </div>
                        <div class="form-group">
                            <label for="viewStatus">Status</label>
                            <input type="text" class="form-control" id="viewStatus" name="viewStatus" readonly>
                        </div>
                        <div class="form-group">
                            <label for="viewContactInformation">Contact Information</label>
                            <input type="text" class="form-control" id="viewContactInformation" name="viewContactInformation" readonly>
                        </div>
                        <div class="form-group">
                            <label for="viewNotesOrComments">Notes and Comments</label>
                            <textarea class="form-control" id="viewNotesOrComments" name="viewNotesOrComments" rows="3" readonly></textarea>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer custom-footer">
                <button type="button" class="btn btn-secondary custom-close-button modal-close" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // "View" button click event
        // "View" button click event
        $('.btn-view-job').on('click', function() {
            var jobId = $(this).data('id');

            // Make an AJAX call to get job details by jobId
            $.ajax({
                type: 'GET', // Use the appropriate HTTP method
                url: '{{ route('jobs.show', '') }}/' + jobId,
                success: function (data) {
                    // Populate the form fields with data
                    $('#viewJobTitle').val(data.job_title);
                    $('#viewCompanyName').val(data.company_name);
                    $('#viewApplicationDate').val(data.application_date);
                    $('#viewApplicationDeadline').val(data.application_deadline);
                    $('#viewStatus').val(data.status);
                    $('#viewContactInformation').val(data.contact_information);
                    $('#viewNotesOrComments').val(data.notes_or_comments);

                    // Open the "View Job" modal
                    $('#viewJobModal').modal('show');
                },
                error: function (xhr) {
                    // Handle error, display validation errors, or show an error message
                    console.log('Error:', xhr);
                    $('#validationErrors').text('An error occurred. Please try again.').show();
                }
            });
        });


        // Modal close button click event
        $('#viewJobModal .modal-close').click(function() {
            $('#viewJobModal').modal('hide');
        });
    });
</script>
