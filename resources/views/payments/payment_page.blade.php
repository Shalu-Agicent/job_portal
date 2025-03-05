<!doctype html>
<html lang="en">

    <head>        
        <meta charset="utf-8" />
        <title>Pricing</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ url('public') }}/assets/images/favicon.ico">
        <!-- preloader css -->
        <link rel="stylesheet" href="{{ url('public') }}/assets/css/preloader.min.css" type="text/css" />
        <!-- Bootstrap Css -->
        <link href="{{ url('public') }}/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ url('public') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ url('public') }}/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    </head>

    <body>
        

        <div class="container"> 
            <div class="p-sm-5 p-4">
                <div class="text-center">
                    <a href="#" class="d-block auth-logo">
                        <img src="{{url('public')}}/assets/images/logo-sm.svg" alt="" height="28"> <span class="logo-txt">Minia</span>
                    </a>
                </div>
            </div> 
            <div class="row">
                <div class="col-lg-3"></div>

                <div class="col-lg-6">                  
                    <div class="card">                        
                        <div class="card-header d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Make  Payment</h4>                            
                        </div>
                        <div class="card-body">  
                            <div class="row">
                                <form id="form-payment" class="form-control" action="{{ route('process-payment') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{ $selected_plan['id'] }}">
                                    <input type="hidden" name="employer_id" value="{{ $employer_id }}">
                                    <input type="hidden" name="price" value="1">
                                    <div class="card-body">
                                        <div class="p-2">
                                            <dl class="row mb-0">
                                                <dt class="col-sm-3">Plan Name:</dt>
                                                <dd class="col-sm-9 blockquote-reverse">{{ $selected_plan['plan_name'] }}</dd>
            
                                                <dt class="col-sm-3">Price:</dt>
                                                <dd class="col-sm-9 blockquote-reverse">${{ $selected_plan['price'] }} <span class="text-muted">/ {{ $selected_plan['duration_type'] }} </span></dd>
                                               
                                                <dt class="col-sm-3">Total amount:</dt>
                                                <dd class="col-sm-9 blockquote-reverse"><strong>${{ $selected_plan['price'] }}</strong></dd>
                                            </dl>
                                            <input type="hidden" name="stripeToken" id="token_value">
                                            <label for="card-element">Card</label>
                                            <div id="card-element"></div>

                                            <div class="mt-4 pt-2">
                                                <button type="button" onclick="createToken()" class="btn btn-outline-primary">Pay</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>                                
                            </div>
                            <!-- end row -->     
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
                <div class="col-lg-3"></div>
            </div>
            <!-- end row -->           
        </div> 
        <!-- container -->                    

        <!-- JAVASCRIPT -->
        <script src="{{ url('public') }}/assets/libs/jquery/jquery.min.js"></script>
        <script src="{{ url('public') }}/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="{{ url('public') }}/assets/libs/node-waves/waves.min.js"></script>
        <script src="{{ url('public') }}/assets/libs/pace-js/pace.min.js"></script>
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            var stripe = Stripe('{{ env("STRIPE_KEY")}}');

            var elements = stripe.elements();
            var cardElement = elements.create('card');
            cardElement.mount('#card-element');

            function createToken(){
                stripe.createToken(cardElement).then(function(result) {
                    if(result.token){
                        document.getElementById('token_value').value = result.token.id;
                        document.getElementById('form-payment').submit();
                    }
                });
            }

        </script>
    </body>

</html>
