<div class="modal fade" id="archiveJobModal" tabindex="-1" role="dialog" aria-labelledby="archiveJobLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveJobLabel">Archive Job</h5>
                <button type="button" class="close custom-close modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to archive this job?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal-close" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmArchive">Archive</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
    
    var jobId;
    
    $('.btn-archive-job').on('click', function() {
        jobId = $(this).data('id');
        $('#archiveJobModal').modal('show');
    });

    $('#archiveJobModal .modal-close').click(function() {
        $('#archiveJobModal').modal('hide');
    });
        

    $('#confirmArchive').on('click', function() {
        // Make an AJAX call to archive the job
        console.log(jobId)
        $.ajax({
            type: 'DELETE',
            url: '/jobs/' + jobId, // Replace with your route
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // Close the modal
                $('#archiveJobModal').modal('hide');
                // Optionally, display a success message
                $('#successMessage').text(response.message).show();

                setTimeout(function () {
                        location.reload();
                    }, 1000);
            },
            error: function (xhr) {
                // Handle error or show an error message
                console.log('Error:', xhr);
            }
        });
    });
});

</script>