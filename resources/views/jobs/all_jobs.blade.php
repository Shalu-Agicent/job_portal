@extends('dashboard.masterLayout')
@section('menu_title')  
{{ $menu_title }}
@endsection

@section('breadcrumb_title')
{{ $breadcrumb_title }}
@endsection

@section('page-content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="me-auto"><h4>All Post</h4></div>
                        <div class="">
                            <a href="{{ route('jobs.create') }}" class="btn btn-primary">Create</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Job Title</th>
                            <th>Location</th>
                            <th>Employment Type</th>
                            <th>Status</th>
                            <th>Posted Date</th>
                            {{-- <th>Expires Date</th> --}}
                            <th>Action</th>
                        </tr>   
                        </thead>

                        <tbody>
                            @foreach ($job_list as $key => $value)
                                <tr id="jobRow{{ $value->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value->title }}</td> 
                                    <td>{{ $value->location }}</td> 
                                    <td>{{ $value->employment_type }}</td> 
                                    <td>
                                        @if($value->status === 'Open')
                                            <span class="badge bg-primary">Open</span>
                                        @else
                                            <span class="badge bg-success">Closed</span>
                                        @endif
                                    </td>
                                    <td>{{ date($value->posted_at) }}</td> 
                                    {{-- <td>{{ $value->expires_at }}</td>  --}}
                                    <td class="d-flex align-items-center">
                                        <a href="{{ route('jobs.show', $value->id) }}" class="btn btn-soft-primary me-2"><i class="bx bx-show"></i></a>
                                    
                                        <a href="{{ route('applicant', ['id' => $value->id]) }}" class="btn btn-soft-info me-2"><i class="bx bxs-user-detail"></i></a>

                                        <a href="{{ route('jobs.edit', $value->id) }}" class="btn btn-soft-warning me-2"><i class="bx bx-edit"></i></a>

                                        <form class="deleteJobForm" method="POST" action="javascript:void(0)">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="button" class="btn btn-soft-danger" data-job-id="{{$value->id}}">
                                                <i class=" bx bx-trash"></i>
                                            </button>
                                        </form>
                                        
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