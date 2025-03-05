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
                <div class="card-header">
                    <div class="d-flex">
                        <div class="me-auto"><h4>Job</h4></div>
                        <div class="">
                            <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
                <form id="job_create_form">
                    @csrf
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <label class="form-label">Job Title</label>
                                <input class="form-control" type="text" id="title" name="title">
                                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Location</label>
                                <input class="form-control" type="text" id="location" name="location" value="{{ old('location') }}">                            
                                @error('location') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>                          
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-4">                            
                                <label class="form-label">Employment Type</label>
                                <select class="form-select" id="employment_type" name="employment_type">
                                    <option value="">Select</option>
                                    <option value="Full-time" selected>Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                </select>
                                @error('employment_type') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-lg-4">                            
                                <label class="form-label">Status</label>
                                <select class="form-select" id="job_status" name="status">
                                    <option value="">Select</option>
                                    <option value="Open" selected>Open</option>
                                    <option value="Closed">Closed</option>
                                </select>    
                                @error('status') <span class="text-danger">{{ $message }}</span> @enderror                       
                            </div>

                            <div class="col-lg-4">                            
                                <label class="form-label">Posted Date</label>
                                <input class="form-control" id="posted_at" type="date" name="posted_at" value="{{ date('Y-m-d') }}">
                                @error('posted_at') <span class="text-danger">{{ $message }}</span> @enderror  
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-lg-6">                            
                                <label class="form-label">Salary Range</label><span class="text-secondary"> (eg:10,000 - 15,000)</span>
                                <input class="form-control" type="text" id="salary_range" name="salary_range" value="{{ old('salary_range') }}">                            
                                @error('salary_range') <span class="text-danger">{{ $message }}</span> @enderror                          
                            </div>

                            <div class="col-lg-6">                            
                                <label class="form-label">Assessment</label>
                                <select class="form-select" id="assessment" name="assessment">
                                    <option value="">Select</option>
                                    @foreach ($assesment_list as $item_list)
                                        <option value="{{ $item_list['id'] }}">{{ $item_list['set_title'] }}</option>
                                    @endforeach
                                </select>    
                                @error('assessment') <span class="text-danger">{{ $message }}</span> @enderror                       
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-6">                            
                                <label class="form-label">Description</label>
                                <textarea name="description" id="ckeditor-classic">{{ old('description') }}</textarea>    
                                @error('description') <span class="text-danger">{{ $message }}</span> @enderror                        
                            </div>

                            <div class="col-lg-6">
                                <div class="mt-3 mt-lg-0">                                
                                    <label  class="form-label">Requirements</label>
                                    <textarea name="requirements" id="ckeditor-classic-other">{{ old('requirements') }}</textarea>
                                    @error('requirements') <span class="text-danger">{{ $message }}</span> @enderror        
                                </div>  
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
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