let base_url = document.getElementById('app').getAttribute('data-url');
editOnLoad();

/**
 * 
 */
$(document).ready(function() {
    ['#category_ids', '#mcqs_questions', '#edit_category_ids', '#edit_mcqs_questions'].forEach(function(selector) {
        $(selector).select2({
            placeholder: selector.includes('category') ? "Select Categories" : "Select Questions", 
            allowClear: true  
        });
    });
});


/**
 * 
 * @param {*} selectElement 
 */
function getQuestion(selectElement) {
    $('#mcqs_questions').html(`<option value="">select</option>`);
    const category_ids = $(selectElement).val();
    if (category_ids && category_ids.length > 0) {
        $.ajax({
            url: base_url + '/mcqs-question', 
            method: 'GET',
            data: { category_ids: category_ids }, 
            success: function(response) {
                let output = `<option value="">select</option>`;
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(question) {
                        output += `<option value="${question.id}">${question.question_text}</option>`;
                    });
                } else {               
                    output += `<option value="">No questions available</option>`;
                }                
                $('#mcqs_questions').html(output);
            },
            error: function(error) {
                updateToastBackground("bg-danger", 'Error fetching questions');
            }
        });
    } else {
        $('#mcqs_questions').html(`<option value="">select</option>`);
    }
}


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
 * 
 */
$('#mcqs_sets_create_form').submit(function (e) { 
    e.preventDefault();          
    let formData = new FormData();
    
    formData.append('set_title', $('#set_title').val());
    formData.append('category_ids', $('#category_ids').val());
    formData.append('mcqs_questions', $('#mcqs_questions').val());
    $.ajax({
        type: "POST",
        url: base_url + "/mcqs-sets",
        data: formData,  
        processData: false,     
        contentType: false, 
        headers: {
            'X-CSRF-TOKEN': $("input[name='_token']").val() 
        },
        success: function (response) {
            $('#mcqs_sets_create_form')[0].reset(); 
            $('.error-message').remove();
            updateToastBackground("bg-success", response.message);
            setTimeout(() => {
                window.location.href = base_url + "/mcqs-sets";
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
 * Custom method to handle validation errors from the server
 * @param {*} errors 
 */
function handleValidationErrors(errors) {
    $.each(errors, function (field, messages) {
        var fieldElement = $(`[name="${field}"]`);
        if (fieldElement.length > 0) {
            $('<span class="error-message text-danger"></span>')
            .text(messages[0])
            .insertAfter(fieldElement);
        }
    });
}

/**
 * 
 * @param {*} selectElement 
 */
function getEditQuestion(selectElement) {
   $('#edit_mcqs_questions').html(``);
    
    const category_ids = $(selectElement).val();
    if (category_ids && category_ids.length > 0) {
        $.ajax({
            url: base_url + '/mcqs-question', 
            method: 'GET',
            data: { category_ids: category_ids }, 
            success: function(response) {
                let output = `<option value="">select</option>`;
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(question) {
                        output += `<option value="${question.id}">${question.question_text}</option>`;
                    });
                } else {               
                    output += `<option value="">No questions available</option>`;
                }                
                $('#edit_mcqs_questions').html(output);
            },
            error: function(error) {
                updateToastBackground("bg-danger", 'Error fetching questions');
            }
        });
    } else {
        $('#mcqs_questions').html(`<option value="">select</option>`);
    }
}

/**
 * 
 */
function editOnLoad(){
    let oldQuestion = $('#old_question').val(); // response: "19,20,21,30,37"
    console.log(oldQuestion); // Verify the old question IDs

    // Clear previous options in the #edit_mcqs_questions dropdown
    $('#edit_mcqs_questions').html(`<option value="">select</option>`);

    const category_ids = $('#edit_category_ids').val(); // Assuming this selects category IDs
    if (category_ids && category_ids.length > 0) {
        $.ajax({
            url: base_url + '/mcqs-question', 
            method: 'GET',
            data: { category_ids: category_ids }, 
            success: function(response) {
                let output = `<option value="">select</option>`;
                let selectedQuestions = oldQuestion.split(','); // Convert the comma-separated string to an array

                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(question) {
                        // Check if the current question ID is in the selectedQuestions array
                        let selected = selectedQuestions.includes(String(question.id)) ? 'selected' : '';

                        output += `<option value="${question.id}" ${selected}>${question.question_text}</option>`;
                    });
                } else {               
                    output += `<option value="">No questions available</option>`;
                }

                // Set the options in the dropdown
                $('#edit_mcqs_questions').html(output);
            },
            error: function(error) {
                updateToastBackground("bg-danger", 'Error fetching questions');
            }
        });
    } else {
        $('#mcqs_questions').html(`<option value="">select</option>`);
    }
}

/**
 * mcq edit form submit
 */
$('#mcqs_sets_edit_form').submit(function (e) { 
    e.preventDefault();      
    var set_id = $('#set_id').val();    
    let formData = new FormData();
    formData.append('set_title', $('#set_title').val());
    formData.append('category_ids', $('#edit_category_ids').val());
    formData.append('mcqs_questions', $('#edit_mcqs_questions').val());

    $.ajax({
        type: "POST",
        url: base_url + "/mcqs-sets/"+set_id,
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
                window.location.href = base_url + "/mcqs-sets";
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
 * delete function for delete job
 */
$(document).on('click', '.deleteMcqSetForm button', function (e) {
    
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
            var setId = $(this).data('set-id');
            $.ajax({
                url: base_url+'/mcqs-sets/' + setId, 
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $("input[name='_token']").val(),
                },
                success: function(response) {
                    updateToastBackground("bg-success", response.message);
                    setTimeout(() => {
                        window.location.href = base_url + "/mcqs-sets";
                    }, 1200);   
                },
                error: function(xhr, status, error) {
                    Swal.fire("An error occurred while deleting the job.");
                }
            });
        }
    });
    

    
});