@extends('dashboard.masterLayout')
@section('menu_title')  
{{ $menu_title }}
@endsection

@section('breadcrumb_title')
{{ $breadcrumb_title }}
@endsection

@section('page-content')
    <div class="row">
        <div class="col-lg-8">
            <div class="row">
                @foreach ($subscriptions_list as $item)
                    <div class="col-lg-6">                            
                        <div class="card">                
                            <div class="card-header bg-transparent border-bottom">
                                <div class="d-flex">
                                    <div class="me-auto text-uppercase"><h4>{{ $item->plan_name }}</h4></div>
                                    <div class="">
                                        <h4 class="card-title">{{ $item->price }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{ $item->duration }} - {{ $item->duration_type }}</p>
                                <p class="card-text">{{ $item->features }}</p>
                                <a href="{{ route('buy-subscription',[$item->id]) }}" class="btn btn-primary">Buy</a>
                            </div>
                        </div>
                    </div><!-- end col -->
                @endforeach
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">                
                <h4 class="card-header bg-success bg-gradient bg-primary-subtle border-bottom">  
                    My Subscriptions Plan Details              
                </h4>
                <div class="card-body" style="background-color: #eff0fb;">
                    <dl class="row mb-0">
                        <dt class="col-sm-6">Plan Name:</dt>
                        <dd class="col-sm-6">{{$user_last_subscription['plan_name']}}</dd>

                        <dt class="col-sm-6">Start Date:</dt>
                        <dd class="col-sm-6">{{$user_last_subscription['start_date']}}</dd>

                        <dt class="col-sm-6">End Date:</dt>
                        <dd class="col-sm-6">{{$user_last_subscription['end_date']}}</dd>

                        <dt class="col-sm-6">Status:</dt>
                        <dd class="col-sm-6">{{$user_last_subscription['status']}}</dd>
                    </dl>
                    <h4 class="card-title">Features</h4>
                    <p class="card-title-desc"> {{$user_last_subscription['features']}} </p>


                </div>
            </div>
        </div> 
    </div>
<!-- end row -->
@endsection