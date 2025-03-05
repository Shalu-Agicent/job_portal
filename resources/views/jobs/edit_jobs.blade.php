@extends('dashboard.masterLayout')
@section('menu_title')  
{{ $menu_title }}
@endsection

@section('breadcrumb_title')
{{ $breadcrumb_title }}
@endsection

@section('page-content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header"></div>
                <form id="job_edit_form">
                    @csrf
                    <div class="card-body p-4">
                        <input type="hidden" id="job_id" value="{{ $jobs->id }}">

                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <label class="form-label">Job Title</label>
                                <input class="form-control" type="text" id="title" name="title" value="{{ $jobs->title }}">
                                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Location</label>
                                <input class="form-control" type="text" id="location" name="location" value="{{ $jobs->location }}">                            
                                @error('location') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>                            
                        </div>

                        <div class="row mb-3">
                            
                            <div class="col-lg-4">                            
                                <label class="form-label">Employment Type</label>
                                <select class="form-select" id="employment_type" name="employment_type">
                                    <option value="">Select</option>
                                    <option value="Full-time" @if ($jobs->employment_type == 'Full-time'){{ 'selected' }} @endif>Full-time</option>
                                    <option value="Part-time" @if ($jobs->employment_type == 'Part-time'){{ 'selected' }} @endif>Part-time</option>
                                    <option value="Contract" @if ($jobs->employment_type == 'Contract'){{ 'selected' }} @endif>Contract</option>
                                </select>
                                @error('employment_type') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                           
                            <div class="col-lg-4">                            
                                <label class="form-label">Status</label>
                                <select class="form-select" id="job_status" name="status">
                                    <option value="">Select</option>
                                    <option value="Open"  @if ($jobs->status == 'Open'){{ 'selected' }} @endif>Open</option>
                                    <option value="Closed"  @if ($jobs->status == 'Closed'){{ 'selected' }} @endif>Closed</option>
                                </select>    
                                @error('status') <span class="text-danger">{{ $message }}</span> @enderror                       
                            </div>

                            <div class="col-lg-4">                            
                                <label class="form-label">Posted Date</label>
                                <input class="form-control" id="posted_at" type="date" name="posted_at" value="{{ $jobs->posted_at }}">
                                @error('posted_at') <span class="text-danger">{{ $message }}</span> @enderror  
                            </div>
                        </div>


                        <div class="row mb-3">   
                            <div class="col-lg-6">     
                                <label class="form-label">Salary Range</label><span class="text-secondary"> (eg:10,000 - 15,000)</span>
                                <input class="form-control" type="text" id="salary_range" name="salary_range" value="{{ $jobs->salary_range }}">                            
                                @error('salary_range') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>

                            <div class="col-lg-6">                            
                                <label class="form-label">Assessment</label>
                                <select class="form-select" id="assessment" name="assessment">
                                    <option value="">Select</option>
                                    @foreach ($assesment_list as $item_list)
                                        <option value="{{ $item_list['id'] }}" @if ($jobs->assessment == $item_list['id']){{ 'selected' }} @endif>{{ $item_list['set_title'] }}</option>
                                    @endforeach
                                </select>    
                                @error('assessment') <span class="text-danger">{{ $message }}</span> @enderror                       
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-lg-6">                            
                                <label class="form-label">Description</label>
                                <textarea name="description" id="ckeditor-classic">{{ $jobs->description }}</textarea>    
                                @error('description') <span class="text-danger">{{ $message }}</span> @enderror                        
                            </div>

                            <div class="col-lg-6">
                                <div class="mt-3 mt-lg-0">                                
                                    <label  class="form-label">Requirements</label>
                                    <textarea name="requirements" id="ckeditor-classic-other">{{ $jobs->requirements }}</textarea>
                                    @error('requirements') <span class="text-danger">{{ $message }}</span> @enderror        
                                </div>  
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>                    
                </form>
            </div>
        </div> <!-- end col -->
    </div>
    <!-- end row -->
@endsection

@push('script')
     <!-- ckeditor -->
     <script src="{{ url('public') }}/assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js"></script>
     <!-- init js -->
     <script src="{{ url('public') }}/assets/js/pages/form-editor.init.js"></script>
     <script src="{{ url('public') }}/assets/js/custom/jobs.js"></script>
@endpush