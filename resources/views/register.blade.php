<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Register | Job Portal</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{url('public')}}/assets/images/favicon.ico">

        <!-- preloader css -->
        <link rel="stylesheet" href="{{url('public')}}/assets/css/preloader.min.css" type="text/css" />
         <!-- twitter-bootstrap-wizard css -->
         <link rel="stylesheet" href="{{url('public')}}/assets/libs/twitter-bootstrap-wizard/prettify.css">
        <!-- Bootstrap Css -->
        <link href="{{url('public')}}/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{url('public')}}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{url('public')}}/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        <style>
            /* Styling for the password input */
            
            /* Password strength feedback area */
            #password-strength {
                font-size: 14px;
                height: 20px;
                padding: 5px;
            }

            /* Strength levels */
            .weak {
                color: red;
            }

            .moderate {
                color: orange;
            }

            .strong {
                color: green;
            }
        </style>
    </head>

    <body>  
        <div id="app" data-url="{{ url('/') }}"></div>
        <input type="hidden" value="{{ isset($completed_step) ? $completed_step : 0}}" id="completed_step">
        <input type="hidden" value="{{ isset($already_registered_id) ? $already_registered_id : 0 }}" id="already_registered_id">

        <!-- <body data-layout="horizontal"> -->
        <div class="auth-page">
            <div class="container p-0">
                {{-- Toast Message Start --}}
                <div class="position-fixed top-0 end-0 p-3" style="z-index: 11;">
                    <div id="borderedToast1" class="toast overflow-hidden mt-3" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="align-items-center text-white bg-primary border-0" id="toast_color">
                            <div class="d-flex">
                                <div class="toast-body"></div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                </div> 
                {{-- Toast Message End --}}
                <div class="mb-4 mb-md-5 mt-4 text-center">
                    <a href="index.html" class="d-block auth-logo">
                        <img src="{{url('public')}}/assets/images/logo-sm.svg" alt="" height="28"> <span class="logo-txt">Minia</span>
                    </a>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Registration</h4>
                            </div>
                            <div class="card-body">
                                <div id="basic-pills-wizard" class="twitter-bs-wizard">
                                    <ul class="twitter-bs-wizard-nav">
                                        <li class="nav-item">
                                            <a href="#step-one" class="nav-link" data-toggle="tab">
                                                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Basic Account Details">
                                                    <i class="bx bx-list-ul"></i>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#step-two" class="nav-link" data-toggle="tab">
                                                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="OTP Verification">
                                                    <i class="bx bx-message-alt-dots"></i>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#step-three" class="nav-link" data-toggle="tab">
                                                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Company Information">
                                                    <i class="bx bx-info-circle"></i>
                                                </div>
                                            </a>
                                        </li>
                                        
                                        <li class="nav-item">
                                            <a href="#step-four" class="nav-link" data-toggle="tab">
                                                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Subscription Plan Selection">
                                                    <i class="bx bx-badge-check"></i>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    <!-- wizard-nav -->

                                    <div class="tab-content twitter-bs-wizard-tab-content">
                                        <div class="tab-pane" id="step-one">
                                            <div class="text-center mb-4">
                                                <h5>Basic Account Details</h5>
                                                <p class="card-title-desc">Fill all information below</p>
                                            </div>
                                            <form>
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Full name</label>
                                                            <input type="text" class="form-control" value="{{old('employer_name')}}" name="employer_name" placeholder="Enter full name">  
                                                           </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Email</label>
                                                            <input type="text" class="form-control" value="{{old('employer_email')}}" name="employer_email" placeholder="Enter Employer email">  
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Phone Country Code</label>
                                                            <select class="form-select" id="phone_code" name="phone_code">
                                                                <option value="">select</option>
                                                                @foreach ($country_phone_code as $phone_code)
                                                                    <option value="{{ $phone_code['phone']}}">{{ $phone_code['name'] .' (+'. $phone_code['phone'].')'}}</option>       
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Contact Number</label>                                                               
                                                                <input type="text" class="form-control" value="{{old('employer_phone')}}" name="employer_phone" placeholder="Enter Contact Number">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Password</label>
                                                            <div class="input-group">
                                                                <div class="input-group-text" id="btnGroupAddon"><input type="checkbox" id="show-password" /></div>
                                                                <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" aria-label="Input group example" aria-describedby="btnGroupAddon">
                                                            </div> 
                                                            <div id="password-strength"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Confirm Password</label>
                                                            <div class="input-group">
                                                                <div class="input-group-text" id="btnGroupAddonNew"><input type="checkbox" id="show-confirm" /></div>
                                                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Enter Password" aria-label="Input group example" aria-describedby="btnGroupAddonNew">
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="clearfix">
                                                        <button type="button" id="basic_info_save" class="btn btn-primary float-end"> Save and Next<i
                                                            class="bx bx-chevron-right"></i></button> 
                                                    </div>
                                                </div>                                                 
                                            </form>
                                        </div>

                                        <div class="tab-pane" id="step-two">
                                            <div class="text-center mb-4">
                                                <h5>OTP Verification</h5>
                                                <p class="card-title-desc">Enter the OTP sent to your registered email address and phone number</p>
                                            </div>
                                            <form>  
                                                @csrf
                                                <div class="row mb-3">
                                                    <div class="col-lg-6">
                                                        <label class="form-label">Email OTP</label>
                                                        <input type="text" class="form-control" id="email_otp" name="email_otp" placeholder="Enter email otp"> 
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">SMS OTP</label>
                                                        <input type="text" class="form-control" id="phone_otp" name="phone_otp" placeholder="Enter phone otp"> 
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="clearfix">
                                                        <button type="button" id="check_validation" class="btn btn-primary float-end"> Save and Next<i
                                                            class="bx bx-chevron-right"></i></button> 
                                                    </div>
                                                </div>                                               
                                            </form>
                                        </div>
                                        <!-- tab pane -->
                                        <div class="tab-pane" id="step-three">
                                          <div>
                                            <div class="text-center mb-4">
                                                <h5>Company Information</h5>
                                                <p class="card-title-desc">Fill all information below</p>
                                            </div>
                                            <form>
                                                @csrf
                                                <div class="row mb-3">
                                                    <div class="col-lg-6">
                                                        <label class="form-label">Company Name</label>
                                                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Enter Company Name"> 
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label class="form-label">Company Size</label>
                                                        <input type="text" class="form-control" id="company_size" name="company_size" placeholder="Enter Company Size">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-lg-4">
                                                        <label class="form-label">Industry Type</label>
                                                        {{-- <input type="text" class="form-control" id="industry" name="industry" placeholder="Enter industry"> --}}
                                                        <select class="form-select" onchange="getSubIndustry(this.value)" id="industry_id" name="industry_id">
                                                            <option value="">select</option>
                                                            @foreach ($industry_list as $item)
                                                                <option value="{{ $item['id']}}">{{ $item['industry_name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-lg-4">
                                                        <label class="form-label">Sub Industry</label>
                                                        <select class="form-select" id="sub_industry_id" name="sub_industry_id">
                                                            <option value="">select</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Company Logo</label>
                                                        <input type="file" class="form-control" id="company_logo" name="company_logo">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-lg-12">
                                                        <label>Corporate Address</label>
                                                        <textarea id="office_address" name="office_address" class="form-control" rows="2" placeholder="Enter your address"></textarea>                                                        
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="clearfix">      
                                                        <button type="button" id="company_info_save" class="btn btn-primary float-end"> Save and Next<i class="bx bx-chevron-right"></i></button> 
                                                    </div>
                                                </div>      
                                            </form>
                                          </div>
                                        </div>
                                        <!-- tab pane -->

                                        <div class="tab-pane" id="step-four">
                                            <div>
                                                <div class="text-center mb-4">
                                                    <h5>Subscription Plan Selection</h5>
                                                    <p class="card-title-desc">Fill all information below</p>
                                                </div>
                                                <div class="row">
                                                    @foreach ($subscriptions_list as $item)
                                                        <!-- start col -->
                                                            <div class="col-xl-4 col-sm-6">
                                                                <div class="card {{ $item->duration_type == 'Year' ? 'bg-primary' : '' }} mb-xl-0">
                                                                    <div class="card-body">
                                                                        <div class="p-2">
                                                                            @php
                                                                                $class_type = "";
                                                                            @endphp 
                                                                            @if ($item->duration_type == 'Year')
                                                                            @php $class_type = "text-white"; @endphp 
                                                                                <div class="pricing-badge">
                                                                                    <span class="badge">Featured</span>
                                                                                </div>
                                                                            @endif                                                        
                                                                            <h5 class="font-size-16 {{ $class_type}}">{{ $item->plan_name }}</h5>
                                                                            <h1 class="mt-3 {{ $class_type}}">₹{{ $item->price }} <span class="{{ $item->duration_type == 'Year' ? 'text-white' : 'text-muted' }} font-size-16 fw-medium ">/ {{ $item->duration_type }}</span></h1>
                                                                            
                                                                            
                                                                            <div class="mt-4 pt-2 text-muted {{ $class_type}}">
                                                                                @php
                                                                                    preg_match_all('/<li>(.*?)<\/li>/', $item->features, $matches);
                                                                                    $featuresArray = $matches[1];
                                                                                @endphp
                                                                                @foreach ($featuresArray as $feature)
                                                                                <p class="mb-3 font-size-15 {{ $class_type}}">
                                                                                    <i class="mdi mdi-check-circle text-secondary font-size-18 me-2 {{ $class_type}}"></i>{{ $feature }}
                                                                                </p>
                                                                                @endforeach
                                                                            </div>            
                                                                            <div class="mt-4 pt-2">
                                                                                <button type="button" onclick="selectSubscriptionsPlan({{$item->id}})" class="btn {{ $item->duration_type == 'Year' ? 'btn-light' : 'btn-outline-primary' }} w-100">Choose Plan</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- end card body -->
                                                                </div>
                                                                <!-- end card -->
                                                            </div>
                                                        <!-- end col -->
                                                    @endforeach
                                                </div>                                                                                                
                                            </div>
                                        </div>
                                        <!-- tab pane -->
                                    </div>
                                    <!-- end tab content -->
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container fluid -->
        </div>
        <!-- JAVASCRIPT -->
        <script src="{{url('public')}}/assets/libs/jquery/jquery.min.js"></script>
        <script src="{{url('public')}}/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="{{url('public')}}/assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="{{url('public')}}/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="{{url('public')}}/assets/libs/node-waves/waves.min.js"></script>
        <script src="{{url('public')}}/assets/libs/feather-icons/feather.min.js"></script>
        <!-- pace js -->
        <script src="{{url('public')}}/assets/libs/pace-js/pace.min.js"></script>
        <!-- twitter-bootstrap-wizard js -->
        <script src="{{url('public')}}/assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
        <script src="{{url('public')}}/assets/libs/twitter-bootstrap-wizard/prettify.js"></script>
        <!-- form wizard init -->
        <script src="{{url('public')}}/assets/js/pages/form-wizard.init.js"></script>
        <!-- Bootstrap Toasts Js -->
        <script src="{{url('public')}}/assets/js/pages/bootstrap-toasts.init.js"></script>
        <script src="{{url('public')}}/assets/js/app.js"></script>
        <script src="{{url('public')}}/assets/js/custom/register.js"></script>
    </body>
</html>