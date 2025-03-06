let base_url = document.getElementById('app').getAttribute('data-url');
/**
 * create job form submit
 */
$('#job_create_form').submit(function (e) { 
    e.preventDefault();          
    let formData = new FormData();
    
    formData.append('title', $('#title').val());
    formData.append('employment_type', $('#employment_type').val());
    formData.append('location', $('#location').val());
    formData.append('status', $('#job_status').val());
    formData.append('posted_at', $('#posted_at').val());
    formData.append('assessment', $('#assessment').val());
    formData.append('salary_range', $('#salary_range').val());
    formData.append('description', $('#ckeditor-classic').val());
    formData.append('requirements', $('#ckeditor-classic-other').val());

    // formData.append('images', $('#formFile')[0].files[0]);
    $.ajax({
        type: "POST",
        url: base_url + "/jobs",
        data: formData,  
        processData: false,     
        contentType: false, 
        headers: {
            'X-CSRF-TOKEN': $("input[name='_token']").val()  // Get CSRF token from the hidden input
        },
        success: function (response) {
            $('#job_create_form')[0].reset(); 
            $('.error-message').remove();
            updateToastBackground("bg-success", response.message);
            
            setTimeout(() => {
                window.location.href = base_url + "/jobs";
            }, 1200);                        
        },
        error: function (xhr) {
            $('.error-message').remove(); 
            let errors = xhr.responseJSON.errors;
            $.each(errors, function(field, messages) {
                let fieldElement = $(`[name="${field}"]`);
                if (fieldElement.length > 0) {
                    let errorMessage = $('<span class="error-message text-danger"></span>')
                    .text(messages[0])  
                    .insertAfter(fieldElement); 
                }
            });
        }
    });
    
});

/**
 * edit job form submit
 */
$('#job_edit_form').submit(function (e) { 
    e.preventDefault();      
    var jobid = $('#job_id').val();    
    let formData = new FormData();
    formData.append('title', $('#title').val());
    formData.append('employment_type', $('#employment_type').val());
    formData.append('location', $('#location').val());
    formData.append('status', $('#job_status').val());
    formData.append('posted_at', $('#posted_at').val());
    formData.append('salary_range', $('#salary_range').val());
    formData.append('assessment', $('#assessment').val());
    formData.append('description', $('#ckeditor-classic').val());
    formData.append('requirements', $('#ckeditor-classic-other').val());

    // formData.append('images', $('#formFile')[0].files[0]);
    $.ajax({
        type: "POST",
        url: base_url + "/jobs/"+jobid,
        data: formData,  
        processData: false,     
        contentType: false, 
        headers: {
            'X-CSRF-TOKEN': $("input[name='_token']").val(),
            'X-HTTP-Method-Override': 'PUT',
        },
        success: function (response) {          
            $('.error-message').remove();
            updateToastBackground("bg-success", response.message); 
            setTimeout(() => {
                window.location.href = base_url + "/jobs";
            }, 1200);                        
        },
        error: function (xhr) {
            $('.error-message').remove(); 
            let errors = xhr.responseJSON.errors;
            $.each(errors, function(field, messages) {
                let fieldElement = $(`[name="${field}"]`);
                if (fieldElement.length > 0) {
                    let errorMessage = $('<span class="error-message text-danger"></span>')
                    .text(messages[0])  
                    .insertAfter(fieldElement); 
                }
            });
        }
    });
    
});

/**
 * delete function for delete job
 */
$(document).on('click', '.deleteJobForm button', function (e) {
    
    e.preventDefault();
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            var jobId = $(this).data('job-id');
            $.ajax({
                url: base_url+'/jobs/' + jobId, 
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $("input[name='_token']").val(),
                },
                success: function(response) {
                    updateToastBackground("bg-success", response.message);
                    setTimeout(() => {
                        window.location.href = base_url + "/jobs";
                    }, 1200);   
                },
                error: function(xhr, status, error) {
                    Swal.fire("An error occurred while deleting the job.");
                }
            });
        }
    });
    

    
});

/**
 * show applicant information in model
 */
var singleApplicantModal = document.getElementById('singleApplicant')
singleApplicantModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;  
    var postid = button.getAttribute('data-bs-postid')
    
    fetch(base_url+'/get-applicant/' + postid, {
    method: 'GET',
    headers: {          
        'Content-Type': 'application/json',      
    },          
    })          
    .then(response => response.json())        
    .then(data => {
        if (data.success) {
            let post_data = data.data; // Assuming the response is a single applicant
            let modelBody = document.querySelector('#singleApplicant .modal-body');
            modelBody.innerHTML = `
                <div class="card-body">
                    <div class="text-center mb-3">
                        <!-- Display applicant's resume link -->
                        <a href="${base_url}/storage/app/public/${post_data.resume}" class="btn btn-primary" target="_blank" download>
                            <i class="bx bxs-download"></i> Download Resume
                        </a>
                    </div>

                    <!-- Applicant Name -->
                    <h5 class="card-title mb-2">Name: ${post_data.user_name}</h5>

                    <!-- Applicant Email -->
                    <p class="card-text"><strong>Email:</strong> ${post_data.email}</p>

                    <!-- Applicant Phone Number -->
                    <p class="card-text"><strong>Phone Number:</strong> ${post_data.phone_number}</p>
                    

                    <!-- Status of the Application -->
                    <p class="card-text"><strong>Status:</strong> ${post_data.status}</p>

                    <!-- Application Submission Date -->
                    <p class="card-text"><strong>Applied On:</strong> ${new Date(post_data.created_at).toLocaleDateString()}</p>
                
                    <!-- Applicant Cover Letter -->
                    <p class="card-text"><strong>Cover Letter:</strong></p>
                    <p class="card-text">${post_data.cover_letter}</p>

                    </div>`;
        } else {
            alert('Applicant not found');
        }                      
    })        
    .catch(error => console.log(error));
});

/**
 * method for show toast message 
 * @param {*} addClassName 
 * @param {*} addMessage 
 */
function updateToastBackground(addClassName, addMessage){
    var toastElement = document.getElementById('borderedToast1');
    if (toastElement) {
        var toast = new bootstrap.Toast(toastElement);
        $("#toast_color").removeClass(function (index, className) {
            return (className.match(/(^|\s)bg-\S+/g) || []).join(' ');
        });
        $("#toast_color").addClass(addClassName);
        $('.toast-body').text(addMessage);
        toast.show();
    }
}