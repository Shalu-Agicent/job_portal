@extends('dashboard.masterLayout')

@section('menu_title')  
{{ $menu_title }}
@endsection

@section('breadcrumb_title')
{{ $breadcrumb_title }}
@endsection

@section('page-content')
<style>
    .profile-design{
        background: url({{url('public/assets/images/background.jpg')}}) no-repeat center center;
        background-size: cover;
        height: 185px;
    }
    .subscription_card {
        width: 260px;
        height: -webkit-fill-available;
        background: linear-gradient(177deg, rgb(194 96 11 / 90%) 0%, rgb(194 121 32 / 94%) 35%, rgba(181, 127, 33, 0.5) 100%);
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease-in-out;
        border-radius: 10px;
        padding: 15px;
    }
    .subscription-footer {
        margin-top: 10px;
    }

    .renew-btn {
        background: white;
        color: #ff5722;
        border: none;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s ease-in-out;
    }

    .renew-btn:hover {
        background: rgba(255, 255, 255, 0.8);
    }
</style>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm order-2 order-sm-1">
                        <div class="mt-3 mt-sm-0">
                            <div class="position-relative profile-design">
                                <div class="d-flex justify-content-center mb-4 position-relative" style="top: 25px;">
                                    <div class="mt-4">
                                        <img src="{{ empty($employer_data['image']) ? url('public/assets/images/users/avatar-2.jpg') : url('public/storage/'.$employer_data['image']) }}" 
                                        alt="" class="img-fluid rounded-circle d-block border border-5">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center" style="margin-top: 90px;">
                                <div class="mt-4">                                    
                                    <h5 class="font-size-16 mb-1"><i class="bx bxs-user text-danger"></i> {{ $employer_data['employer_name']}}</h5>

                                    <div class="text-muted mb-1"><i class="bx bx-mail-send text-danger"></i> {{ $employer_data['employer_email'] }}</div>

                                    <div class="text-muted mb-1"><i class="bx bx-phone text-danger"></i> {{ '+('.$employer_data['phone_code'].') '. $employer_data['employer_phone']}}</div>

                                    <div class="d-flex justify-content-center gap-lg-3 mb-1">
                                        @if ($employer_data['email_verified']==1)
                                            <div><i class="mdi mdi-circle-medium me-1 text-success align-middle"></i>Email Verified</div>
                                        @endif
                                        @if ($employer_data['employer_phone_verify']==1)
                                            <div><i class="mdi mdi-circle-medium me-1 text-success align-middle"></i>Phone Verified</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
               <ul class="nav nav-tabs-custom card-header-tabs" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link px-3 active" data-bs-toggle="tab" href="#overview" role="tab">Basic Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" data-bs-toggle="tab" href="#about" role="tab">Company Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" data-bs-toggle="tab" href="#post" role="tab">Subscription</a>
                    </li>
                </ul>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->

        <div class="tab-content">
            <div class="tab-pane active" id="overview" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Basic Details</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('update-employer') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="pb-3">
                                <div class="row">
                                    <input type="hidden" name= "employer_id" value="{{ $employer_data['id'] }}">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Employer Name</label>
                                        <input type="text" class="form-control" name="employer_name" id="employer_name" value="{{ $employer_data['employer_name'] }}">
                                        @error('employer_name') <span class="text-danger">{{ $message }}</span> @enderror  
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Employer Email</label>
                                        <input type="text" class="form-control" name="employer_email" id="employer_email" value="{{ $employer_data['employer_email'] }}">
                                        @error('employer_email') <span class="text-danger">{{ $message }}</span> @enderror  
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone Code</label>
                                        <select class="form-select" name="phone_code" id="phone_code">
                                            @foreach ($country_phone_code as $code_list)
                                                <option value='{{ $code_list['phone'] }}' @if ($code_list['phone']==$code_list['phone'])
                                                    selected
                                                @endif>{{ $code_list['name'] .' (+'. $code_list['phone'].')'}}</option>
                                            @endforeach
                                            <option value=""></option>
                                        </select>
                                        @error('phone_code') <span class="text-danger">{{ $message }}</span> @enderror  
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Employer Phone</label>
                                        <input type="text" class="form-control" name="employer_phone" id="employer_phone" value="{{ $employer_data['employer_phone'] }}">
                                        @error('employer_phone') <span class="text-danger">{{ $message }}</span> @enderror  
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Profile Photo</label>
                                        <input type="file" class="form-control" name="profile_image" id="profile_image">
                                        @error('profile_image') <span class="text-danger">{{ $message }}</span> @enderror  
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>  
                        </form>    
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end tab pane -->

            <div class="tab-pane" id="about" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Company Info</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('update-company-info')}}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <input type="hidden" value="{{ $company_data['id'] }}" name="company_id">
                            <div class="pb-3">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Company Name</label>
                                        <input type="text" class="form-control" name="company_name" id="company_name" value="{{ $company_data['company_name'] }}">
                                        @error('company_name') <span class="text-danger">{{ $message }}</span> @enderror  
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Company Size</label>
                                        <input type="text" class="form-control" name="company_size" id="company_size" value="{{ $company_data['company_size'] }}">
                                        @error('company_size') <span class="text-danger">{{ $message }}</span> @enderror  
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Industry</label>
                                        <select class="form-select" onchange="getIndustryFun(this.value)" name="industry_id" id="industry_id">
                                            <option value="">select</option>
                                            @foreach ($industry_list as $item_list)
                                                <option value="{{ $item_list['id']}}" @if ($company_data['industry_id']==$item_list['id'])
                                                selected
                                                @endif>{{ $item_list['industry_name']}}</option>
                                            @endforeach
                                        </select>
                                        @error('industry_id') <span class="text-danger">{{ $message }}</span> @enderror  
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Sub Industry</label>
                                        <select class="form-select" name="sub_industry_id" id="sub_industry_id">
                                            @foreach ($subIndustry_list as $sub_item_list)
                                                <option value="{{ $sub_item_list['id']}}" @if ($company_data['sub_industry_id']==$sub_item_list['id'])
                                                selected
                                                @endif>{{ $sub_item_list['sub_industry_name']}}</option>
                                            @endforeach
                                        </select>
                                        @error('sub_industry_id') <span class="text-danger">{{ $message }}</span> @enderror  
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Company Logo</label>
                                        <input type="file" class="form-control" name="company_logo" id="company_logo" value="">
                                        @error('company_logo') <span class="text-danger">{{ $message }}</span> @enderror  
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Corporate Address</label>
                                        <textarea class="form-control" name="corporate_address" rows="6" id="corporate_address">{{ $company_data['corporate_address']}}</textarea>
                                        @error('corporate_address') <span class="text-danger">{{ $message }}</span> @enderror  
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>  
                        </form> 
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end tab pane -->

            <div class="tab-pane" id="post" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Subscription</h5>
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-center">
                        <div class="card">
                            @php
                                $currentDate = now();
                                $expiryDate = \Carbon\Carbon::parse($user_last_subscription['end_date']);
                                $daysLeft = floor($currentDate->diffInDays($expiryDate, false));
                            @endphp
                
                            <img class="card-img img-fluid" style="width: 500px;" src="{{ url('public') }}/assets/images/sub_background.jpeg" alt="Card image">
                            <div class="card-img-overlay">
                                <div class="p-3 subscription_card">
                                    <div class="d-flex justify-content-start">
                                        <h4 class="text-white py-2 rounded-pill" style="background:rgb(71 196 12);;">
                                            <i class="bx bx-check-circle bx-tada me-1"></i> Active
                                        </h4>
                                    </div>
                                    
                                    
                                    <h4 class="card-text text-white">₹ {{ $user_last_subscription['price'] .' / '. $user_last_subscription['duration_type']  }}</h4>
                                    <h5 class="card-text text-white">{{ $user_last_subscription['plan_name'] .' Plan' }}</h5> 
                                    <hr>                               
                                    <p class="card-text text-white"><i class="fas fa-calendar-alt bx-tada"></i>  Expire On: <strong>{{ date('F d, Y', strtotime($user_last_subscription['end_date'])) }}</strong></p>
                                    <p class="card-text text-white">
                                        @if ($daysLeft > 0)
                                            <i class="fas fa-clock bx-spin "></i> {{ $daysLeft }} days left
                                        @elseif ($daysLeft == 0)
                                            <i class="fas fa-exclamation-circle bx-flashing"></i>  Expires today
                                        @else
                                           <i class="fas fa-times-circle bx-spin"></i> Expired {{ abs($daysLeft) }} days ago
                                        @endif
                                    </p>
                                    <hr>
                                    {{-- @if ($daysLeft <= 0)
                                        <span class="text-white">
                                            @if ($daysLeft == 0)
                                                <div class="subscription-footer">
                                                    <button class="renew-btn">Renew Now</button>
                                                </div>
                                            @else
                                                <div class="subscription-footer">
                                                    <button class="renew-btn">Renew Now</button>
                                                </div>
                                            @endif
                                        </span>
                                    @endif --}}
                                    <div class="subscription-footer">
                                        <button class="renew-btn">Renew Now</button>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end tab pane -->
        </div>
        <!-- end tab content -->
    </div>
</div>
@endsection

@push('script')
    <script>
        let base_url = document.getElementById('app').getAttribute('data-url');
        function getIndustryFun(industry_id){
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
    </script>    
@endpush