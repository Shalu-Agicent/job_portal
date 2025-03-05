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
        <!-- Icons Css -->
        <link href="{{url('public')}}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{url('public')}}/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
  
    </head>

    <body>

        <!-- <body data-layout="horizontal"> -->
        <div class="auth-page">
            <div class="container-fluid p-0">                         
                <div class="bg-info bg-gradient position-relative" style="height: 130px;">
                    <div class="position-absolute top-50 start-50 translate-middle text-white fw-semibold display-4">Hiring</div>
                </div>                    
                <!-- container --> 
                <div class="container">
                    <div class="row mt-4">  
                        @foreach ($job_list as $list)
                            <!-- start col -->  
                            <div class="col-md-6 col-xl-3">
                                <!-- Simple card -->
                                <div class="card">
                                    @php
                                        $images = [
                                            url('public/assets/images/small/img-1.jpg'),
                                            url('public/assets/images/small/img-2.jpg'),
                                            url('public/assets/images/small/img-3.jpg'),
                                            url('public/assets/images/small/img-4.jpg'),
                                            url('public/assets/images/small/img-5.jpg'),
                                            url('public/assets/images/small/img-6.jpg'),
                                            url('public/assets/images/small/img-7.jpg'),
                                            'https://images.pexels.com/photos/3760069/pexels-photo-3760069.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                            'https://images.pexels.com/photos/9870153/pexels-photo-9870153.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                            'https://media.istockphoto.com/id/1071488226/photo/business-concepts.jpg?s=1024x1024&w=is&k=20&c=vWLwbd87G7Nru62vdX5pgayXvKC6twuywxZ_LyxC_Sg=',
                                            'https://media.istockphoto.com/id/642501464/photo/wonderful-ill-see-you-first-thing-on-monday.jpg?s=1024x1024&w=is&k=20&c=YIhPhT6ZrGe8J_5NIZ9eroXQvio7VoSNsm8N80H-n3A='
                                        ]; 
                                        $random_image = $images[array_rand($images)];
                                    @endphp
                                    
                                    <img class="card-img-top img-fluid" src="{{ $random_image }}" alt="Card image cap">

                                    <div class="card-body">
                                        <h4 class="card-title">{{ $list->title }}</h4>
                                        <p class="card-text">
                                            <strong>Location: </strong>  {!! $list->location !!}<br>
                                            <strong>Salary Range: </strong>  {!! $list->salary_range !!}<br>
                                            <strong>Employment Type: </strong> {!! $list->employment_type !!}<br>
                                            <strong>Status: </strong>{!! $list->status !!}<br>
                                            {!! $list->description !!}
                                        </p>
                                        <a href="{{ route('view-job',$list->id) }}" class="btn btn-primary waves-effect waves-light">View</a>
                                    </div>
                                </div>        
                            </div>
                        @endforeach
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
        <script src="{{url('public')}}/assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="{{url('public')}}/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="{{url('public')}}/assets/libs/node-waves/waves.min.js"></script>
        <script src="{{url('public')}}/assets/libs/feather-icons/feather.min.js"></script>
        <!-- pace js -->
        <script src="{{url('public')}}/assets/libs/pace-js/pace.min.js"></script>
        <!-- password addon init -->
        <script src="{{url('public')}}/assets/js/pages/pass-addon.init.js"></script>
        <!-- Bootstrap Toasts Js -->
        <script src="{{url('public')}}/assets/js/pages/bootstrap-toasts.init.js"></script>
        {{-- <script src="{{url('public')}}/assets/js/app.js"></script> --}}
        @if(session('error'))
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