
<!-- Add a div to display validation errors -->
<div class="alert alert-danger" id="validationErrors" style="display: none;"></div>

<!-- Add a div to display success messages -->
<div class="alert alert-success" id="successMessage" style="display: none;"></div>

<!-- "View Job" modal -->
<div class="modal fade" id="viewJobDocumentsModal" tabindex="-1" role="dialog" aria-labelledby="viewJobDocumentsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg custom-dialog" role="document">
        <div class="modal-content custom-content">
            <div class="modal-header custom-header">
                <h5 class="modal-title custom-title" id="viewJobDocumentsLabel">View Job Documents</h5>
                <button type="button" class="close custom-close modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body custom-body">
                <form id="documentUploadForm" enctype="multipart/form-data">
                    @csrf 
                    <div class="document-list">
                        <!-- Document list will be populated dynamically using JavaScript -->
                    </div>
                    <input type="file" name="file" accept=".pdf, .doc, .docx, .txt"> <!-- Specify accepted file types as needed -->
                    <input type="hidden" name="associate_with_job" value="true">
                    <input type="hidden" name="job_id" value="Hello"> <!-- Replace with the actual job ID -->
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
        var job_id;
    // "View" button click event
    $('.btn-view-job-documents').on('click', function() {
        var jobId = $(this).data('id');
        job_id = jobId ;
        // Make an AJAX call to get job documents by jobId
        $.ajax({
            type: 'GET',
            url: '{{ route('job.getDocuments', '') }}/' + jobId,
            success: function (documents) {
                // Populate the document list with data
                loadJobDocuments(documents);

                // Open the "View Job Documents" modal
                $('#viewJobDocumentsModal').modal('show');
            },
            error: function (xhr) {
                // Handle error, display validation errors, or show an error message
                console.log('Error:', xhr);
                $('#validationErrors').text('An error occurred while loading documents.').show();
            }
        });
    });

    // Modal close button click event
    $('#viewJobDocumentsModal .modal-close').click(function() {
        $('#viewJobDocumentsModal').modal('hide');
    });

    // Function to load job documents
    function loadJobDocuments(response) {
        var documents = response.documents;
        var documentList = $('.document-list');
        documentList.empty();

        if (documents.length > 0) {
            var listGroup = $('<ul class="list-group"></ul>');

            documents.forEach(function(document) {
                // Create a list item for each document as a list group item
                var listItem = $('<li class="list-group-item d-flex justify-content-between align-items-center"></li>');
                var fileName = document.file_path;

                // Create a div to hold the document file name
                var fileNameDiv = $('<div class="file-name text-break"></div>');
                fileNameDiv.text(fileName);

                // Create buttons for downloading and removing documents
                var documentId = document.id; // Assuming this variable holds the document's ID
                var downloadUrl = '/download-document/' + documentId;

                var downloadButton = $('<a class="btn btn-primary mx-1" href="' + downloadUrl + '"><i class="fa-solid fa-download mr-2"></i></a>');
                var removeButton = $('<button class="btn btn-danger"><i class="fa-solid fa-trash mr-2"></i></button>');

                // Set data attributes to store document information
                downloadButton.data('document-id', document.id);
                removeButton.data('document-id', document.id);

                // Add click events to download and remove buttons
                downloadButton.on('click', function() {
                    // Implement logic to download the document
                    var documentId = $(this).data('document-id');
                    // Trigger the download of the document
                    // You can provide a download link to the document
                });

                removeButton.on('click', function() {
                    // Implement logic to remove the document
                    var documentId = $(this).data('document-id');
                    // Trigger the removal of the document
                    // You can confirm the removal with a modal or prompt
                });

                // Append the buttons to the list item
                listItem.append(downloadButton);
                listItem.append(removeButton);

                // Append the file name and buttons to the list item
                listItem.prepend(fileNameDiv);

                // Set the job ID from the document to an input field
                var jobIdInput = $('<input type="hidden" name="job_id" value="' + document.job_id + '">');
                console.log(jobIdInput.val());

                console.log(jobIdInput)
                listItem.append(jobIdInput);

                // Append the list item to the list group
                listGroup.append(listItem);
            });

            // Append the list group to the document list
            documentList.append(listGroup);
        } else {
            // If no documents, display a message or handle it as needed
            documentList.append('<p class="text-center">No documents available.</p>');
        }
    }


   // Add a change event listener to the file input
   $('input[type="file"]').on('change', function () {
        // Trigger form submission when a file is selected
        $('#documentUploadForm').submit();
    });

    $('#documentUploadForm').on('submit', function (e) {
        e.preventDefault();

        // Update the job_id field in the form with the correct value
        console.log(job_id)
        $('input[name="job_id"]').val(job_id);

        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: '{{ route('document.upload') }}', // Replace with your actual upload route
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                // Handle the success response (e.g., update the document list)
                // You may need to reload the document list after the upload is complete.
                loadJobDocuments(data); // Update the document list as needed
            },
            error: function (xhr) {
                // Handle the error response (e.g., display validation errors)
                console.log('Error:', xhr);
            }
        });
    });



});

</script>
