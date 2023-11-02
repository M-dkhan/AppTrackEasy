
<div class="modal custom-fade" id="addJobModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-dialog" role="document">
      <div class="modal-content custom-content">
        <div class="modal-header custom-header">
          <h5 class="modal-title custom-title" id="exampleModalLabel">Add New Job</h5>
          <button type="button" class="modal-close custom-close" data-dismiss="modal" aria-label="Close">
            <i class="fas fa-times"></i>
          </button>          
        </div>
        <div class="modal-body custom-body">
          <div class="modal-body custom-body">
            <!-- Add your form fields for adding a new job here -->
            <form id="addJobForm">
              @csrf
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

              <div class="modal-footer custom-footer">
                <button type="button" class="modal-close btn btn-secondary custom-close-button" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary custom-save-button">Save</button>
              </div>
            </form>
          </div>
        </div>      
        
      </div>
    </div>
  </div>
  

<script>
  $(document).ready(function () {

    $('.btn-add-new').on('click', function() {
      $('#addJobModal').modal('show'); // Open the modal
    });

    $('#addJobModal .modal-close').click(function() {
      $('#addJobModal').modal('hide');
    });

    $('#addJobForm').on('submit', function (e) {
      console.log('button pressed')
      e.preventDefault(); // Prevent the default form submission

      // Serialize form data
      var formData = $(this).serialize();

      // Send an AJAX request to the Laravel route
      $.ajax({
        type: 'POST', // Use the appropriate HTTP method (e.g., POST)
        url: '/jobs', // Replace with the URL of your controller method
        data: formData,
        success: function (response) {
          // Clear any previous validation error messages
          $('#validationErrors').hide().empty();

          // Display a success message
          $('#successMessage').text(response.message).show();

          // Optionally, reset the form
          $('#addJobForm')[0].reset();
          setTimeout(function () {
                        location.reload();
                    }, 1000);
        },
        error: function (xhr) {
          // Handle validation errors
          var errors = xhr.responseJSON.errors;
          var errorHtml = '<ul>';
          $.each(errors, function (key, value) {
            errorHtml += '<li>' + value + '</li>';
          });
          errorHtml += '</ul>';
          $('#validationErrors').html(errorHtml).show();

          // Hide the success message
          $('#successMessage').hide();
        }
      });
    });
  
  });
</script>
  