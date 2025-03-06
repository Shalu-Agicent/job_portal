let base_url = document.getElementById('app').getAttribute('data-url');
// editOnLoad();

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
            url: base_url + '/coding-question', 
            method: 'GET',
            data: { category_ids: addedValues }, 
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let questionsHtml = ``;
                    
                    response.data.forEach(question => {
                    questionsHtml += `
                        <div class="row category_${question.programming_cat_id}">
                            <div class="col-lg-12">
                                <div class="d-inline-flex align-items-center"> 
                                    <input type="checkbox" class="me-2 question-checkbox" name="selected_questions[]" value="${question.id}">                       
                                    <div class="mt-3">${question.question_text}</div>
                                </div>    
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