let base_url = document.getElementById('app').getAttribute('data-url');

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
 * create programming questions form submit
 */
$('#question_create_form').submit(function (e) { 
    e.preventDefault();          
    let formData = new FormData();
    
    formData.append('programming_cat_id', $('#programming_cat_id').val());
    formData.append('question_text', $('#ckeditor-classic').val());
    // formData.append('images', $('#formFile')[0].files[0]);
    
    $.ajax({
        type: "POST",
        url: base_url + "/programming-question",
        data: formData,  
        processData: false,     
        contentType: false, 
        headers: {
            'X-CSRF-TOKEN': $("input[name='_token']").val()  // Get CSRF token from the hidden input
        },
        success: function (response) {
            $('#question_create_form')[0].reset(); 
            $('.error-message').remove();
            updateToastBackground("bg-success", response.message);            
            setTimeout(() => {
                window.location.href = base_url + "/programming-question";
            }, 1200);                        
        },
        error: function (xhr) {
            $('.error-message').remove(); 
            let errors = xhr.responseJSON.errors;
            $.each(errors, function(field, messages) {
                let fieldElement = $(`[name="${field}"]`);
                if (fieldElement.length > 0) {
                    $('<span class="error-message text-danger"></span>')
                    .text(messages[0])  
                    .insertAfter(fieldElement); 
                }
            });
        }
    });
    
});

/**
 * edit programming questions form submit
 */
$('#question_edit_form').submit(function (e) { 
    e.preventDefault();      
    var question_id = $('#question_id').val();    
    let formData = new FormData();
    formData.append('programming_cat_id', $('#programming_cat_id').val());
    formData.append('question_text', $('#ckeditor-classic').val());

    // formData.append('images', $('#formFile')[0].files[0]);
    $.ajax({
        type: "POST",
        url: base_url + "/programming-question/"+question_id,
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
                window.location.href = base_url + "/programming-question";
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
$(document).on('click', '.deleteQuestionForm button', function (e) {
    
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
            var questionId = $(this).data('question-id');
            $.ajax({
                url: base_url+'/programming-question/' + questionId, 
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $("input[name='_token']").val(),
                },
                success: function(response) {
                    updateToastBackground("bg-success", response.message);
                    setTimeout(() => {
                        window.location.href = base_url + "/programming-question";
                    }, 1200);   
                },
                error: function(xhr, status, error) {
                    Swal.fire("An error occurred while deleting the job.");
                }
            });
        }
    });
});