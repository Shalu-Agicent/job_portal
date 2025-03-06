let base_url = document.getElementById('app').getAttribute('data-url');
editOnLoad();

/**
 * 
 */
$(document).ready(function() {
    ['#category_ids',  '#edit_category_ids'].forEach(function(selector) {
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

let previousSelectedValues = [];
function getQuestion(selectElement) {   
    
    const selectedOptions = Array.from(selectElement.selectedOptions).map(option => option.value);
    const addedValues = selectedOptions.filter(value => !previousSelectedValues.includes(value));
    const removedValues = previousSelectedValues.filter(value => !selectedOptions.includes(value));
    previousSelectedValues = selectedOptions;

    var added_value = addedValues[0] !== undefined ? addedValues[0] : 0;
    var removed_value = removedValues[0] !== undefined ? removedValues[0] : 0;

 
    if (added_value > 0) {
        $.ajax({
            url: base_url + '/mcqs-question', 
            method: 'GET',
            data: { category_ids: addedValues }, 
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let questionsHtml = ``;
                    
                    response.data.forEach(question => {
                    questionsHtml += `
                        <div class="row mb-3 category_${question.mcq_category_id}">
                            <div class="col-lg-12">      
                                <input type="checkbox" class="me-2 question-checkbox" name="selected_questions[]" value="${question.id}">                       
                                <span>${question.question_text}</span>
                            </div>
                        </div>`;
                    });

                    $('#question_div').append(questionsHtml);

                    // Attach event listener for "Select All"
                    $('#select_all').on('change', function() {
                        $('.question-checkbox').prop('checked', this.checked);
                    });

                    // Uncheck "Select All" if any checkbox is manually unchecked
                    $('.question-checkbox').on('change', function() {
                        if (!$('.question-checkbox:checked').length) {
                            $('#select_all').prop('checked', false);
                        } else if ($('.question-checkbox:checked').length === $('.question-checkbox').length) {
                            $('#select_all').prop('checked', true);
                        }
                    });

                } else {
                    $('#question_div').html(`<p class="text-danger">No questions found for the selected category.</p>`);
                }
            },
            error: function(error) {
                updateToastBackground("bg-danger", 'Error fetching questions');
            }
        });
    } else if (removedValues.length > 0) {
        // Ensure we are only removing specific rows related to this category
        $(`.category_${removed_value}`).each(function () {
            $(this).remove();
        });
    } else {
        $('#question_div').html(`<p class="text-warning">Please select a category.</p>`);
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
 * for save action
 */
$('#mcqs_sets_create_form').submit(function (e) { 
    e.preventDefault();          
    let formData = new FormData();

    let selectedValues = [];
    document.querySelectorAll('.question-checkbox:checked').forEach((checkbox) => {
        selectedValues.push(checkbox.value);
    });

    formData.append('set_title', $('#set_title').val());
    formData.append('category_ids', $('#category_ids').val());
    formData.append('mcqs_questions', selectedValues);
    
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
 * for edit page select question
 */
let editPreviousSelectedValues = [];
function getEditQuestion(selectElement) {   
    
    const selectedOptions = Array.from(selectElement.selectedOptions).map(option => option.value);
    const addedValues = selectedOptions.filter(value => !editPreviousSelectedValues.includes(value));
    const removedValues = editPreviousSelectedValues.filter(value => !selectedOptions.includes(value));
    editPreviousSelectedValues = selectedOptions;

    var added_value = addedValues[0] !== undefined ? addedValues[0] : 0;
    var removed_value = removedValues[0] !== undefined ? removedValues[0] : 0;

 
    if (added_value > 0) {
        $.ajax({
            url: base_url + '/mcqs-question', 
            method: 'GET',
            data: { category_ids: addedValues }, 
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let questionsHtml = ``;
                    
                    response.data.forEach(question => {
                    questionsHtml += `
                        <div class="row mb-3 category_${question.mcq_category_id}">
                            <div class="col-lg-12">      
                                <input type="checkbox" class="me-2 edit-question-checkbox" id="edit_mcqs_questions" name="selected_questions[]" value="${question.id}">                       
                                <span>${question.question_text}</span>
                            </div>
                        </div>`;
                    });

                    $('#edit_question_div').append(questionsHtml);

                    // Attach event listener for "Select All"
                    $('#select_all').on('change', function() {
                        $('.edit-question-checkbox').prop('checked', this.checked);
                    });

                    // Uncheck "Select All" if any checkbox is manually unchecked
                    $('.edit-question-checkbox').on('change', function() {
                        if (!$('.edit-question-checkbox:checked').length) {
                            $('#select_all').prop('checked', false);
                        } else if ($('.edit-question-checkbox:checked').length === $('.edit-question-checkbox').length) {
                            $('#select_all').prop('checked', true);
                        }
                    });

                } else {
                    $('#edit_question_div').html(`<p class="text-danger">No questions found for the selected category.</p>`);
                }
            },
            error: function(error) {
                updateToastBackground("bg-danger", 'Error fetching questions');
            }
        });
    } else if (removedValues.length > 0) {
        // Ensure we are only removing specific rows related to this category
        $(`.category_${removed_value}`).each(function () {
            $(this).remove();
        });
    } else {
        $('#question_div').html(`<p class="text-warning">Please select a category.</p>`);
    }
}

/**
 * edit page on load select qduestion
 */
function editOnLoad(){

    var old_question = $('#old_question').val();   
    const set_id = $('#set_id').val();
    if (set_id && set_id.length > 0) {
        $.ajax({
            url: base_url + '/set-mcqs-question', 
            method: 'GET',
            data: { set_id: set_id }, 
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let questionsHtml = ``;
                    let oldQuestions = $('#old_question').val().split(','); // Convert string to array
                
                    response.data.forEach(question => {
                        let isChecked = oldQuestions.includes(question.id.toString()) ? 'checked' : ''; // Check if ID exists in oldQuestions
                        
                        questionsHtml += `
                            <div class="row mb-3 category_${question.mcq_category_id}">
                                <div class="col-lg-12">      
                                    <input type="checkbox" class="me-2 edit-question-checkbox" name="selected_questions[]" value="${question.id}" ${isChecked}>                       
                                    <span>${question.question_text}</span>
                                </div>
                            </div>`;
                    });
                
                    $('#edit_question_div').append(questionsHtml);
                
                    // Attach event listener for "Select All"
                    $('#edit_select_all').on('change', function() {
                        $('.edit-question-checkbox').prop('checked', this.checked);
                    });
                
                    // Uncheck "Select All" if any checkbox is manually unchecked
                    $('.edit-question-checkbox').on('change', function() {
                        if (!$('.edit-question-checkbox:checked').length) {
                            $('#edit_select_all').prop('checked', false);
                        } else if ($('.edit-question-checkbox:checked').length === $('.edit-question-checkbox').length) {
                            $('#edit_select_all').prop('checked', true);
                        }
                    });
                
                    // If all checkboxes are already checked, check the "Select All" checkbox
                    if ($('.edit-question-checkbox:checked').length === $('.edit-question-checkbox').length) {
                        $('#edit_select_all').prop('checked', true);
                    }
                
                } else {
                    $('#question_div').html(`<p class="text-danger">No questions found for the selected category.</p>`);
                }
                
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

    let edit_mcqs_questions = [];
    document.querySelectorAll('.edit-question-checkbox:checked').forEach((checkbox) => {
        edit_mcqs_questions.push(checkbox.value);
    });

    var set_id = $('#set_id').val();    
    let formData = new FormData();
    formData.append('set_title', $('#set_title').val());
    formData.append('category_ids', $('#edit_category_ids').val());
    formData.append('mcqs_questions', edit_mcqs_questions);

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