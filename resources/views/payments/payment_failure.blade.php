<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Job Portal | Payment Failure</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <div class="my-5 pt-5">
            <div class="container">
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mb-3">                           
                            <h4 class="">There was an issue processing your payment. Please try again later or contact support.</h4>
                            <div class="mt-3 text-center">
                                <a class="btn btn-primary waves-effect waves-light" href="{{ route('login')}}">Back to Login</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-10 col-xl-8">
                        <img src="{{ url('public') }}/assets/images/payment_failure.png" alt="" class="img-fluid">
                    </div>
                    <!-- end row -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end content -->

        <!-- JAVASCRIPT -->
        <script src="{{ url('public') }}/assets/libs/jquery/jquery.min.js"></script>
        <script src="{{ url('public') }}/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
