let base_url = document.getElementById('app').getAttribute('data-url');

$('#mcqs_create_form').submit(function (e) { 
    e.preventDefault();          
    let formData = new FormData();
    
    formData.append('question_text', $('#question_text').val());
    formData.append('option_a', $('#option_a').val());
    formData.append('option_b', $('#option_b').val());
    formData.append('option_c', $('#option_c').val());
    formData.append('option_d', $('#option_d').val());
    formData.append('mcq_category_id', $('#mcq_category_id').val());
    formData.append('correct_answer', $('#correct_answer').val());
    formData.append('difficulty_level', $('#difficulty_level').val());
    
    // formData.append('images', $('#formFile')[0].files[0]);
    $.ajax({
        type: "POST",
        url: base_url + "/mcqs",
        data: formData,  
        processData: false,     
        contentType: false, 
        headers: {
            'X-CSRF-TOKEN': $("input[name='_token']").val() 
        },
        success: function (response) {
            $('#mcqs_create_form')[0].reset(); 
            $('.error-message').remove();
            updateToastBackground("bg-success", response.message);
            setTimeout(() => {
                window.location.href = base_url + "/mcqs";
            }, 1200);                        
        },
        error: function (xhr) {
            $('.error-message').remove(); 
            let errors = xhr.responseJSON.errors;
            handleValidationErrors(errors);
        }
    });
    
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

   
/**
 * Custom method to handle validation errors from the server
 * @param {*} errors 
 */
function handleValidationErrors(errors) {
    $.each(errors, function (field, messages) {
        var fieldElement = $(`[name="${field}"]`);
        if (fieldElement.length > 0) {
            var errorMessage = $('<span class="error-message text-danger"></span>')
                    .text(messages[0])
                    .insertAfter(fieldElement);
        }
    });
}

/**
 * this function is for view question detials
 */
const mcqView = document.getElementById('mcqView')
if (mcqView) {
    mcqView.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const question_id = button.getAttribute('data-bs-question_id')

        fetch(base_url+'/mcqs/'+question_id, {
            method: 'GET',         
        })          
        .then(response => response.json())        
        .then(data => {
            let mcq_data = data.data;
            let modelBody = document.querySelector('#mcqView .modal-body');
            var badgeClass = '';
            var badgeText = '';

            if (mcq_data.difficulty_level == 'Easy') {
            badgeClass = 'badge bg-success';  // Green for Easy
            badgeText = 'Easy';
            } else if (mcq_data.difficulty_level == 'Medium') {
            badgeClass = 'badge bg-warning';  // Yellow for Medium
            badgeText = 'Medium';
            } else if (mcq_data.difficulty_level == 'Hard') {
            badgeClass = 'badge bg-danger';   // Red for Hard
            badgeText = 'Hard';
            }
            modelBody.innerHTML = `
            <div class="card-body">

                <label class="form-label">Question:</label>
                <p class="card-text">${mcq_data.question_text}</p>

                <dl class="row mb-0">
                    <dt class="col-sm-4">Option A: </dt>
                    <dd class="col-sm-8">${mcq_data.option_a}</dd>

                    <dt class="col-sm-4">Option B: </dt>
                    <dd class="col-sm-8">${mcq_data.option_b}</dd>

                    <dt class="col-sm-4">Option C: </dt>
                    <dd class="col-sm-8">${mcq_data.option_c}</dd>

                    <dt class="col-sm-4">Option D: </dt>
                    <dd class="col-sm-8">${mcq_data.option_d}</dd>   
                    
                    <dt class="col-sm-4">Correct Answer: </dt>
                    <dd class="col-sm-8">${mcq_data.correct_answer}</dd>        
                    
                    <dt class="col-sm-4">Category: </dt>
                    <dd class="col-sm-8">${mcq_data.category.category_name}</dd>      
                </dl>
            </div>`;                       
        })        
        .catch(error => console.log(error));
        
    })
}

/**
 * mcq edit form submit
 */
$('#mcqs_edit_form').submit(function (e) { 
    e.preventDefault();      
    var mcq_id = $('#mcq_id').val();    
    let formData = new FormData();
    formData.append('question_text', $('#question_text').val());
    formData.append('option_a', $('#option_a').val());
    formData.append('option_b', $('#option_b').val());
    formData.append('option_c', $('#option_c').val());
    formData.append('option_d', $('#option_d').val());
    formData.append('correct_answer', $('#correct_answer').val());
    formData.append('mcq_category_id', $('#mcq_category_id').val());
    formData.append('difficulty_level', $('#difficulty_level').val());

    $.ajax({
        type: "POST",
        url: base_url + "/mcqs/"+mcq_id,
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
                window.location.href = base_url + "/mcqs";
            }, 1200);                        
        },
        error: function (xhr) {
            $('.error-message').remove(); 
            let errors = xhr.responseJSON.errors;
            handleValidationErrors(errors);
        }
    });
    
});

/**
 * delete function for delete mcqs
 */
$(document).on('click', '.deleteMcqForm button', function (e) {
    
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
            var jobId = $(this).data('mcq-id');
            $.ajax({
                url: base_url+'/mcqs/' + jobId, 
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $("input[name='_token']").val(),
                },
                success: function(response) {
                    updateToastBackground("bg-success", response.message);
                    setTimeout(() => {
                        window.location.href = base_url + "/mcqs";
                    }, 1200);   
                },
                error: function(xhr, status, error) {
                    Swal.fire("An error occurred while deleting the job.");
                }
            });
        }
    });    
});

