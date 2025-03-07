let base_url = document.getElementById('app').getAttribute('data-url');
editOnLoad();

/**
 * for select 2
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
function handleValidationErrors(errors,divBox) {
    // Remove previous error messages
    $('.error-message').remove();
    $('.is-invalid').removeClass('is-invalid'); 
    $.each(errors, function (field, messages) {
        var fieldElement = $(`[name="${field}"], [name="${field}[]"]`);

        if (fieldElement.length > 0) {
            var errorMessage = $('<span class="error-message text-danger"></span>').text(messages[0]);
            if (fieldElement.is(':checkbox')) {
                var checkboxGroup = fieldElement.closest("#"+divBox); 
                if (checkboxGroup.length) {
                    errorMessage.appendTo(checkboxGroup);
                } else {
                    errorMessage.insertAfter(fieldElement.last());
                }
            } 
            // Handle other input types
            else if (fieldElement.is('input[type="text"], input[type="password"], textarea')) {
                errorMessage.insertAfter(fieldElement);
            } 
            else if (fieldElement.is('select')) {
                if (fieldElement.attr('multiple')) {
                    errorMessage.insertAfter(fieldElement.parent());
                } else {
                    errorMessage.insertAfter(fieldElement);
                }
            } 
            else {
                errorMessage.insertAfter(fieldElement);
            }
            fieldElement.addClass('is-invalid');
        }
    });
    if ($('input[name="selected_questions[]"]:checked').length === 0) {
        var checkboxError = $('<span class="error-message text-danger"></span>').text("Please select at least one question.");
        $("#"+divBox).append(checkboxError);
    }
}

/**
 * on change function for select box
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
            url: base_url + '/coding-question', 
            method: 'GET',
            data: { category_ids: addedValues }, 
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let questionsHtml = ``;
                    
                    response.data.forEach(question => {
                    questionsHtml += `
                        <div class="category_${question.programming_cat_id}">
                            <div class="d-inline-flex align-items-center"> 
                                <input type="checkbox" class="me-2 question-checkbox" name="selected_questions[]" value="${question.id}">                       
                                <div class="mt-3">${question.question_text}</div>
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
 * for save action
 */
$('#programming_sets_create_form').submit(function (e) { 
    e.preventDefault();          
    let formData = new FormData();

    let selectedValues = [];
    document.querySelectorAll('.question-checkbox:checked').forEach((checkbox) => {
        selectedValues.push(checkbox.value);
    });

    formData.append('set_title', $('#set_title').val());
    formData.append('category_ids', $('#category_ids').val());
    formData.append('progrmming_questions', selectedValues);
    
    $.ajax({
        type: "POST",
        url: base_url + "/programming-sets",
        data: formData,  
        processData: false,     
        contentType: false, 
        headers: {
            'X-CSRF-TOKEN': $("input[name='_token']").val() 
        },
        success: function (response) {
            $('#programming_sets_create_form')[0].reset(); 
            $('.error-message').remove();
            updateToastBackground("bg-success", response.message);
            setTimeout(() => {
                window.location.href = base_url + "/programming-sets";
            }, 1200);                        
        },
        error: function (xhr) {
            $('.error-message').remove(); 
            let errors = xhr.responseJSON.errors;
            handleValidationErrors(errors,'question_div');
        }
    });
    
});


/**
 * edit page on load select qduestion
 */
function editOnLoad(){

    var old_question = $('#old_question').val();   
    const set_id = $('#set_id').val();
    if (set_id && set_id.length > 0) {
        $.ajax({
            url: base_url + '/set-progrmming-question', 
            method: 'GET',
            data: { set_id: set_id }, 
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let questionsHtml = ``;
                    let oldQuestions = $('#old_question').val().split(','); // Convert string to array
                    response.data.forEach(question => {
                        let isChecked = oldQuestions.includes(question.id.toString()) ? 'checked' : ''; // Check if ID exists in oldQuestions
                        questionsHtml += `
                        <div class="category_${question.programming_cat_id}">
                            <div class="d-inline-flex align-items-center"> 
                                <input type="checkbox" class="me-2 edit-question-checkbox" name="selected_questions[]" value="${question.id}" ${isChecked}>                       
                                <div class="mt-3">${question.question_text}</div>
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
            url: base_url + '/coding-question', 
            method: 'GET',
            data: { category_ids: addedValues }, 
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let questionsHtml = ``;                    
                        response.data.forEach(question => {
                            questionsHtml += `
                            <div class="category_${question.programming_cat_id}">
                                <div class="d-inline-flex align-items-center"> 
                                    <input type="checkbox" class="me-2 edit-question-checkbox" name="selected_questions[]" value="${question.id}">                       
                                    <div class="mt-3">${question.question_text}</div>
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
 * question set edit submit
 */
$('#progrmming_sets_edit_form').submit(function (e) { 
    e.preventDefault();      

    let programming_questions = [];
    document.querySelectorAll('.edit-question-checkbox:checked').forEach((checkbox) => {
        programming_questions.push(checkbox.value);
    });

    var set_id = $('#set_id').val();    
    let formData = new FormData();
    formData.append('set_title', $('#set_title').val());
    formData.append('category_ids', $('#edit_category_ids').val());
    formData.append('progrmming_questions', programming_questions);

    $.ajax({
        type: "POST",
        url: base_url + "/programming-sets/"+set_id,
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
                window.location.href = base_url + "/programming-sets";
            }, 1200);                        
        },
        error: function (xhr) {
            $('.error-message').remove(); 
            let errors = xhr.responseJSON.errors;
            handleValidationErrors(errors,'edit_question_div');
        }
    });
    
});
