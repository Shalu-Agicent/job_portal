<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Hiring | Job Portal</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{url('public')}}/assets/images/favicon.ico">
        <!-- preloader css -->
        <link rel="stylesheet" href="{{url('public')}}/assets/css/preloader.min.css" type="text/css" />
        <!-- Bootstrap Css -->
        <link href="{{url('public')}}/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />  
    </head>

    <body>     
        <!-- <body data-layout="horizontal"> -->
        <div class="auth-page">
            <div class="container-fluid p-0">             
                @if(session('success') || session('error'))
                    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11;">
                        <div id="borderedToast1" class="toast overflow-hidden mt-3" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="align-items-center text-white {{ session('success') ? 'bg-success' : (session('error') ? 'bg-danger' : '') }} border-0">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        {{ session('success') ?? session('error') }}
                                    </div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    </div> 
                @endif
               <!-- As a link -->
                <div class="bg-info bg-gradient position-relative" style="height: 130px;">
                    <div class="position-absolute top-50 start-50 translate-middle text-white fw-semibold display-4">Hiring</div>
                </div> 
                <!-- container --> 
                <div class="container">
                    <div class="row mt-4">  
                        <div class="card">
                            <div class="card-body">
                                <div class="">
                                    <div class="text-center mb-3">
                                        <h4>{{ $job_details['title'] }}</h4>
                                    </div>
                                    <hr>

                                    <div class="text-center">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div>
                                                    <h6 class="mb-2">Location</h6>
                                                    <p class="text-muted font-size-15">{{ $job_details['location'] }}</p>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mt-4 mt-sm-0">
                                                    <h6 class="mb-2">Salary Range</h6>
                                                    <p class="text-muted font-size-15">{{ $job_details['salary_range'] }}</p>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mt-4 mt-sm-0">
                                                    <h6 class="mb-2">Employment Type</h6>
                                                    <p class="text-muted font-size-15">{{ $job_details['employment_type'] }}</p>
                                                </div>
                                            </div>                                          
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="mt-4">
                                        <div class="text-muted font-size-14">
                                            <p>{!! $job_details['description'] !!}</p><br>  
                                            <p>{!! $job_details['requirements'] !!}</p>
                                        </div>
                                        <hr>

                                        <div class="mt-5">
                                            <h5 class="font-size-16 mb-3">Apply:</h5>

                                            <form method="POST" action="{{ route('save-application') }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Name</label><span class="text-danger">*</span>
                                                            <input type="text" class="form-control" name="user_name" placeholder="Enter name">
                                                            <input type="hidden" name="job_id" value="{{ $job_details['id'] }}">
                                                            @error('user_name')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Email</label><span class="text-danger">*</span>
                                                            <input type="email" class="form-control" name="email" placeholder="Enter email">
                                                            @error('email')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Phone Number</label><span class="text-danger">*</span>
                                                            <input type="text" class="form-control" name="phone_number" placeholder="Enter Contact Number">
                                                            @error('phone_number')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Resume/CV (pdf,doc)</label><span class="text-danger">*</span>
                                                            <input type="file" class="form-control" name="resume">
                                                            @error('resume')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label  class="form-label">Cover Letter</label>
                                                    <textarea class="form-control" name="cover_letter" placeholder="Your message..." rows="3"></textarea>
                                                </div>

                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary w-sm">Submit</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end row -->
                </div>      
                <!-- end container -->          
            </div>
            <!-- end container fluid -->
        </div>

        <!-- JAVASCRIPT -->
        <script src="{{url('public')}}/assets/libs/jquery/jquery.min.js"></script>
        <script src="{{url('public')}}/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Bootstrap Toasts Js -->
        <script src="{{url('public')}}/assets/js/pages/bootstrap-toasts.init.js"></script>
        @if(session('success')||session('error'))
            <script>
                // Make sure the DOM is fully loaded before initializing the toast
                document.addEventListener("DOMContentLoaded", function() {
                    var toastElement = document.getElementById('borderedToast1');
                    if (toastElement) {
                        var toast = new bootstrap.Toast(toastElement);
                        toast.show();
                    }
                });
            </script>
        @endif

    </body>
</html>