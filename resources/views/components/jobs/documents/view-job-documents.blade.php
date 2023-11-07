<div class="alert alert-danger" id="validationErrors" style="display: none;"></div>
<div class="alert alert-success" id="successMessage" style="display: none;"></div>

<div class="modal fade" id="viewJobDocumentsModal" tabindex="-1" role="dialog" aria-labelledby="viewJobDocumentsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg custom-dialog" role="document">
        <div class="modal-content custom-content">
            <div class="modal-header custom-header">
                <h5 class="modal-title custom-title" id="viewJobDocumentsLabel">View Job Documents</h5>
                <button type="button" class="close custom-close modal-close" data-dismiss="modal" aria-label="Close">
                <i class="fa-solid fa-x"></i></button>
            </div>
            <div class="modal-body custom-body">
                <div class="document-list"></div>

                <div class="upload-document-container">
                    <form id="documentUploadForm" enctype="multipart/form-data">
                        @csrf
                        <label class="btn btn-primary">
                            Add Document    
                            <input type="file" name="file" id="file-upload" style="display: none;">
                        </label>                          
                        <input type="hidden" name="associate_with_job" value="true">
                    </form>
                </div>
            </div>

            <div class="modal-footer custom-footer">
                <button type="button" class="btn btn-secondary custom-close-button modal-close" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
    var job_id;

    $('.btn-view-job-documents').on('click', function() {
        var jobId = $(this).data('id');
        job_id = jobId;
        $.ajax({
            type: 'GET',
            url: '{{ route('job.getDocuments', '') }}/' + jobId,
            success: function (documents) {
                loadJobDocuments(documents);
                $('#viewJobDocumentsModal').modal('show');
            },
            error: function (xhr) {
                console.log('Error:', xhr);
                $('#validationErrors').text('An error occurred while loading documents.').show();
            }
        });
    });

    $('#viewJobDocumentsModal .modal-close').click(function() {
        $('#viewJobDocumentsModal').modal('hide');
    });

    function loadJobDocuments(response) {
        var documents = response.documents;
        var documentList = $('.document-list');
        documentList.empty();

        if (documents.length > 0) {
            var listGroup = $('<ul class="list-group"></ul>');

            documents.forEach(function(document) {
                var listItem = $('<li class="list-group-item d-flex justify-content-between align-items-center"></li>');
                var fileName = document.file_path;

                var fileNameDiv = $('<div class="file-name text-break"></div>');
                fileNameDiv.text(fileName);

                var documentId = document.id;
                var downloadUrl = '/download-document/' + documentId;

                var downloadButton = $('<a class="btn btn-primary mx-1" href="' + downloadUrl + '"><i class="fa-solid fa-download mr-2"></i></a>');
                var removeButton = $('<button class="btn btn-danger btn-remove-document" data-document-id="' + document.id + '"><i class="fa-solid fa-trash mr-2"></i></button'); 

                downloadButton.data('document-id', document.id);

                downloadButton.on('click', function() {
                    var documentId = $(this).data('document-id');
                });

                listItem.append(downloadButton);
                listItem.append(removeButton);

                listItem.prepend(fileNameDiv);

                var jobIdInput = $('<input type="hidden" name="job_id" value="' + document.job_id + '">');
                listItem.append(jobIdInput);

                listGroup.append(listItem);
            });

            documentList.append(listGroup);
        } else {
            documentList.append('<p class="text-center">No documents available.</p>');
        }
    }

    $('input[type="file"]').on('change', function () {
       $('#documentUploadForm').submit();
    });
    
    $('#documentUploadForm').on('submit', function (e) {
        console.log('this function is running')
        e.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this);
        formData.append('job_id', job_id); 
        $.ajax({
            type: 'POST', // Use the appropriate HTTP method (e.g., POST)
            url: '{{ route('document.upload') }}', // Replace with the URL of your controller method
            data: formData,
            processData: false, // Don't process the data (required for FormData)
            contentType: false, // Don't set content type (required for FormData)
            success: function (response) {
            // Clear any previous validation error messages
            $('#validationErrors').hide().empty();

            // Display a success message
            $('#successMessage').text(response.message).show();

            // Optionally, reset the form
            $('#documentUploadForm')[0].reset();
            // hide the modal
            $('#viewJobDocumentsModal').modal('hide');
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

    $(document).on('click', '.btn-remove-document', function() {
        var documentId = $(this).data('document-id');

        // Display a confirmation dialog
        if (confirm('Are you sure you want to delete this document?')) {
            // If the user confirms, proceed with the delete action
            $.ajax({
                type: 'DELETE',
                url: '/delete-document/' + documentId,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#successMessage').text(data.message).show();
                    $('#viewJobDocumentsModal').modal('hide');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                }
            });
        }
    });


});

</script>