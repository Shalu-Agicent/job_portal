@extends('dashboard.masterLayout')
@section('menu_title')  
{{ $menu_title }}
@endsection

@section('breadcrumb_title')
{{ $breadcrumb_title }}
@endsection

@section('page-content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border border-secondary">
            <div class="row g-0 align-items-center">  
                <div class="card-header bg-transparent border-secondary">                        
                    <div class="d-flex">
                        <div class="me-auto"><h5 class="card-title"><strong>{{ $job->title }}</strong></h5></div>
                        <div class="">
                            <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">   
                    <ul class="list-unstyled">
                        <li><strong>Location:</strong> {{ $job->location ?? 'Not provided' }}</li>
                        <li><strong>Employment Type:</strong> {{ $job->employment_type ?? 'Not provided' }}</li>
                        <li><strong>Posted Date:</strong> {{ $job->posted_at}}</li>
                        <li><strong>Salary Range:</strong> {{ $job->salary_range}}</li>
                        <li><strong>Status:</strong> 
                            @if($job->status === 'Open')
                                <span class="badge bg-primary">Open</span>
                            @else
                                <span class="badge bg-success">Closed</span>
                            @endif
                        </li>
                        <li><strong>Assessment:</strong> 
                            <a href="{{ route('mcqs-sets.show', $job->assessment) }}"> {{ $job->set_title}} </a>                         
                        </li>
                        
                        <li><strong>Description:</strong> {!! $job->description ?? 'No description provided' !!}</li>
                        <li><strong>Requirements:</strong> {!! $job->requirements ?? 'No requirements listed' !!}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div><!-- end col -->
</div>
@endsection