    let base_url = document.getElementById('app').getAttribute('data-url');
    
    /**
     * Function to check password strength
     * @param {*} password 
     * @returns 
     */
    function checkPasswordStrength(password) {
        let strength = 0;

        // Check length
        if (password.length >= 8) strength += 1;
        if (password.length >= 12) strength += 1;

        // Check for lowercase and uppercase letters
        if (/[a-z]/.test(password)) strength += 1;
        if (/[A-Z]/.test(password)) strength += 1;

        // Check for numbers
        if (/\d/.test(password)) strength += 1;

        // Check for special characters
        if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 1;

        return strength;
    }
    
    /**
     * Function to update the password strength feedback
     */
    function updatePasswordStrength() {
        const password = document.getElementById('password').value;
        const strength = checkPasswordStrength(password);
        const strengthText = document.getElementById('password-strength');

        if (strength === 0) {
            strengthText.textContent = 'Please enter a password to begin.';
            strengthText.className = '';
        } else if (strength <= 2) {
            strengthText.textContent = 'Weak password. Consider using a mix of characters for better security.';
            strengthText.className = 'weak';
        } else if (strength === 3) {
            strengthText.textContent = 'Moderate strength. Adding more characters and variety will improve security.';
            strengthText.className = 'moderate';
        } else {
            strengthText.textContent = 'Strong password. Great choice for enhanced security!';
            strengthText.className = 'strong';
        }

    }

    // Add event listener to check password strength as user types
    document.getElementById('password').addEventListener('input', updatePasswordStrength);
    // Show/hide password toggle
    document.getElementById('show-password').addEventListener('change', function() {
        const passwordField = document.getElementById('password');
        if (this.checked) {
            passwordField.type = 'text'; // Show the password
        } else {
            passwordField.type = 'password'; // Hide the password
        }
    });

    // Show/hide password toggle
    document.getElementById('show-confirm').addEventListener('change', function() {
        const passwordField = document.getElementById('confirm_password');
        if (this.checked) {
            passwordField.type = 'text'; // Show the password
        } else {
            passwordField.type = 'password'; // Hide the password
        }
    });

    $(document).ready(function () {

        var completedStep = $('#completed_step').val();
        var already_registered_id = $('#already_registered_id').val();
        
        // Hide all tabs first
        $(".nav-link").removeClass("active");
        $(".tab-pane").removeClass("active show");

        // Activate the corresponding tab based on completed step
        if (completedStep == 1) {
            $('#step-two').addClass('active show');
            $('.nav-link[href="#step-two"]').addClass('active');
            getAlreadyRegisteredEmployer(already_registered_id);

        } else if (completedStep == 2) {
            $('#step-three').addClass('active show');
            $('.nav-link[href="#step-three"]').addClass('active');
            getAlreadyRegisteredEmployer(already_registered_id);

        } else if (completedStep == 3) {
            $('#step-four').addClass('active show');
            $('.nav-link[href="#step-four"]').addClass('active');
            getAlreadyRegisteredEmployer(already_registered_id);

        } else if (completedStep == 4 || completedStep == 0) {
            $('#step-one').addClass('active show');
            $('.nav-link[href="#step-one"]').addClass('active');
        }
          
        // When user clicks the "Next" button
        $("#basic_info_save").click(function (e) {
            e.preventDefault(); 
            let password = $("#password").val();
            let confirm_password = $("#confirm_password").val();

            if (password !== confirm_password) {
                var message = "Passwords do not match! Please make sure both passwords are the same.";
                updateToastBackground("bg-warning", message);
            }

            if (!validateForm()) {
                return; 
            }
            var formData = {
                employer_name: $("input[name='employer_name']").val(),
                employer_email: $("input[name='employer_email']").val(),
                employer_phone: $("input[name='employer_phone']").val(),
                phone_code: $("#phone_code").val(),
                password: $("#password").val()
            };

            $.ajax({
                url: base_url + "/save-employer", 
                method: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $("input[name='_token']").val() 
                },
                success: function (response) {
                    if (response.success) {
                        $('.error-message').remove();
                        localStorage.setItem("employerDetails", JSON.stringify(response.data));
                        localStorage.setItem("employer_type", "new");
                        updateToastBackground("bg-success", response.message);
                        goToNextTab();
                    }else{
                        updateToastBackground("bg-danger", response.message);
                    }
                },
                error: function (xhr) {
                    handleValidationErrors(xhr.responseJSON.errors);
                }
            });
        });

        // when validate opt 
        $('#check_validation').click(function (e){
            e.preventDefault(); 
            if (!validateForm()) {
                return; 
            }

            var registered_user_id = getLocalStorage();                    
            var formData = {
                email_otp: $("input[name='email_otp']").val(),
                phone_otp: $("input[name='phone_otp']").val(),
                registered_user_id: registered_user_id
            };

            $.ajax({
                url: base_url + "/verify-employer", 
                method: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $("input[name='_token']").val() 
                },
                success: function (response) {
                    if (response.success) {
                        $('.error-message').remove();
                        localStorage.setItem("employerDetails", JSON.stringify(response.data));
                        updateToastBackground("bg-success", response.message);
                        goToNextTab();
                    }else{
                        updateToastBackground("bg-danger", response.message);                                
                    }
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 400) {
                        updateToastBackground("bg-danger", xhr.responseJSON.message);
                    }                   
                    handleValidationErrors(xhr.responseJSON.errors);
                }
            });
        });

        // company info save function 
        $('#company_info_save').click(function(e) {
            e.preventDefault(); 

            var company_logo = $('#company_logo')[0].files[0];
            if (!validateForm()) {
                return; 
            }

            var registered_user_id = getLocalStorage();
            var formData = new FormData();

            // Append form fields and file to the FormData object
            formData.append('company_name', $("input[name='company_name']").val());
            formData.append('company_size', $("input[name='company_size']").val());
            formData.append('industry_id', $("#industry_id").val());
            formData.append('sub_industry_id', $("#sub_industry_id").val());            
            formData.append('company_logo', company_logo);
            formData.append('registered_user_id', registered_user_id);
            formData.append('office_address', $("#office_address").val());
            
            // formData.append('office_address', $("#office_address").val());
            // formData.append('industry', $("input[name='industry']").val());

            // Perform the AJAX request to send the form data
            $.ajax({
                url: base_url + "/save-company-info", 
                method: "POST",
                data: formData,
                processData: false,  // Prevent jQuery from automatically transforming the data into a query string
                contentType: false,  // Set the content type to false for file uploads
                headers: {
                    'X-CSRF-TOKEN': $("input[name='_token']").val() 
                },
                success: function(response) {
                    if (response.success) {
                        $('.error-message').remove();
                        localStorage.setItem("employerDetails", JSON.stringify(response.data));
                        updateToastBackground("bg-success", response.message);
                        goToNextTab();
                    } else {
                        updateToastBackground("bg-danger", response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 400) {
                        updateToastBackground("bg-danger", xhr.responseJSON.message);
                    }   
                    handleValidationErrors(xhr.responseJSON.errors);
                }
            });
        });
        
    });

    /**
     * Custom method to validate the form fields
     * @returns 
     */
    function validateForm() {
        var isValid = true;
        $('.error-message').remove(); 
        $("input, textarea").each(function () {
            var $this = $(this);
            var value = $this.val().trim();
            if ($this.prop('required') && value === '') {
                var errorMessage = $('<span class="error-message text-danger"></span>')
                    .text($this.attr('placeholder') + " is required.")
                    .insertAfter($this);
                isValid = false;
            }
        });

        return isValid;
    }

    
    /**
     * Custom method to handle validation errors from the server
     * @param {*} errors 
     */
    function handleValidationErrors(errors) {
        $.each(errors, function (field, messages) {
            var fieldElement = $(`[name="${field}"]`);
            if (fieldElement.length > 0) {
                if(field=='password'){
                    $('<span class="error-message text-danger"></span>')
                        .text(messages[0])
                        .insertAfter('.input-group');
                }else{
                    var errorMessage = $('<span class="error-message text-danger"></span>')
                        .text(messages[0])
                        .insertAfter(fieldElement);
                }
            }
        });
    }

    /**
     * Custom method to go to the next tab
     */
    function goToNextTab() {
        // Find the current active navigation link
        var currentNav = $('.nav-link.active');  
        // Find the next navigation link
        var nextNav = currentNav.parent().next('.nav-item').find('.nav-link');
        
        // Find the current active tab content
        var currentTab = $('.tab-pane.active');                    
        // Find the next tab content
        var nextTab = currentTab.next('.tab-pane');

        if (nextTab.length > 0) {
            // If there is a next tab, show it
            currentTab.removeClass('active show');
            nextTab.addClass('active show');
            
            // Remove active class from the current navigation link
            currentNav.removeClass('active');
            // Add active class to the next navigation link
            nextNav.addClass('active');
        } else {
            // If no next tab, stay in the current tab or handle accordingly
            console.log('This is the last tab.');
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
     * function for get local storage value 
     * @returns 
     */
    function getLocalStorage(){
        const employerDetails = localStorage.getItem("employerDetails");
        return employerDetails ? JSON.parse(employerDetails)?.id || 0 : 0;
    }

    /**
     * function for choose subscription plan using plan id
     * @param {*} plan_id 
     */
    function selectSubscriptionsPlan(plan_id) {
        var registered_user_id = getLocalStorage(); 
        var url = base_url +'/plan-process/' + plan_id + '/' + registered_user_id;
        window.location.replace(url);  
    }

    /**
     * get employer detials using id
     * @param {*} employer_id 
     */
    function getAlreadyRegisteredEmployer(employer_id){
        if(employer_id>0){
            $.ajax({
                url: base_url + "/get-employer/"+employer_id, 
                method: "GET",
                success: function (response) {
                    console.log(response);
                    
                    if (response.success) {
                        localStorage.setItem("employerDetails", JSON.stringify(response.data));
                    }
                },
                error: function (xhr) {
                    handleValidationErrors(xhr.responseJSON.errors);
                }
            });
        }        
    }

    /**
     * get sub industry using industry id
     * @param {*} industry_id 
     */
    function getSubIndustry(industry_id){
        var fill_ele = `<option value="">select</option>`;
        $.ajax({
            url: base_url + "/sub-industry/"+industry_id, 
            method: "GET",
            success: function (response) {
                if(response.success === true){
                    response.data.forEach(function(item) {
                        fill_ele += `<option value="${item.id}">${item.sub_industry_name}</option>`;
                    });
                }
                $('#sub_industry_id').html(fill_ele);
            },
            error: function (xhr) {
               // handleValidationErrors(xhr.responseJSON.errors);
            }
        });
    }