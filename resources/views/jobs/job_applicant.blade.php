@extends('dashboard.masterLayout')
@section('menu_title')  
{{ $menu_title }}
@endsection

@section('breadcrumb_title')
{{ $breadcrumb_title }}
@endsection

@section('page-content')
    <!-- Single Post Modal -->
    <div style="margin-top: 85px;" class="modal fade" id="singleApplicant" data-bs-backdrop="static" data-bs-keyboard="false"  tabindex="-1" aria-labelledby="singleApplicantLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="singleApplicantLabel">Applicant detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
        
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="me-auto"><h4>All Applicant</h4></div>
                        <div class="">
                            <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Status</th>
                                <th>Applied Date</th>
                                <th>Action</th>
                            </tr>   
                        </thead>

                        <tbody>
                            @foreach ($applicants_list as $key => $value)
                                <tr id="jobRow{{ $value->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value->user_name }}</td> 
                                    <td>{{ $value->email }}</td> 
                                    <td>{{ $value->phone_number }}</td> 
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                {{ $value->Interview_status->status_title." " }} <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-md p-4">
                                                <form>
                                                    <div class="mb-2">
                                                        <label class="form-label" for="exampleDropdownFormEmail">Status</label>
                                                        <input type="email" class="form-control" id="exampleDropdownFormEmail"
                                                            placeholder="email@example.com">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Sign in</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ date("Y-m-d", strtotime($value->created_at)) }}</td> 
                                    <td class="d-flex align-items-center">

                                        <button type="button" data-bs-postId="{{ $value->id }}"  data-bs-toggle="modal" data-bs-target="#singleApplicant" class="btn btn-soft-primary me-2"><i class="bx bx-show"></i></button> 

                                        <a href="{{ url('storage/app/public/'.$value->resume) }}" class="btn btn-soft-warning me-2" download>
                                            <i class="bx bxs-download"></i> 
                                        </a>                                   
                                    
                                    </td> 
                                </tr>
                            @endforeach 
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> 
@endsection

@push('script')
<script src="{{ url('public') }}/assets/js/custom/jobs.js"></script>
@endpush

{{-- CREATE TABLE interview_status (
    id int NOT NULL,
    title varchar(100) NOT NULL,
    PRIMARY KEY (id)
); --}}