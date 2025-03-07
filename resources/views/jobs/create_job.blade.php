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
                <div class="card-body p-4">
                    <form id="job_create_form">
                        @csrf                    
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <label class="form-label">Job Title</label>
                                    <input class="form-control" type="text" id="title" name="title">
                                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-lg-4">                            
                                    <label class="form-label">Role</label>
                                    <input class="form-control" id="role" type="text" name="role" value="">
                                    @error('role') <span class="text-danger">{{ $message }}</span> @enderror  
                                </div> 
                                <div class="col-lg-4">
                                    <label class="form-label">Location</label>
                                    <input class="form-control" type="text" id="location" name="location" value="{{ old('location') }}">                            
                                    @error('location') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>                                                       
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-3">                            
                                    <label class="form-label">Work Mode</label>
                                    <select class="form-select" id="work_mode" name="work_mode">
                                        <option value="">Select</option>
                                        <option value="Work From Office">Work From Office</option>
                                        <option value="Work From Home">Work From Home</option>
                                        <option value="Hybird">Hybird</option>
                                        <option value="Remote">Remote</option>
                                    </select>
                                    @error('work_mode') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-lg-3">                            
                                    <label class="form-label">Employment Type</label>
                                    <select class="form-select" id="employment_type" name="employment_type">
                                        <option value="">Select</option>
                                        <option value="Full-time">Full-time</option>
                                        <option value="Part-time">Part-time</option>
                                        <option value="Contract">Contract</option>
                                    </select>
                                    @error('employment_type') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-lg-3">                            
                                    <label class="form-label">Status</label>
                                    <select class="form-select" id="job_status" name="status">
                                        <option value="">Select</option>
                                        <option value="Open" selected>Open</option>
                                        <option value="Closed">Closed</option>
                                    </select>    
                                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror                       
                                </div>

                                <div class="col-lg-3">                            
                                    <label class="form-label">Experience</label><span class="text-secondary"> (0 - 50) year </span>
                                    <input type="range" id="experience" value="0" name="experience" min="0" max="50" oninput="updateVolume(this.value)">
                                    <span id="vol-value">0</span> <!-- Default value display -->
                                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror                       
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-3">                            
                                    <label class="form-label">Min Salary</label><span class="text-secondary"> (eg:3 LPA)</span>
                                    <input type="range" id="min_salary" value="0" name="min_salary" min="0" max="50" oninput="updateMinSalary(this.value)">
                                    <span id="min_salary_value">0</span>                                   
                                    @error('salary_range') <span class="text-danger">{{ $message }}</span> @enderror                          
                                </div>
                                <div class="col-lg-3">                            
                                    <label class="form-label">Max Salary</label><span class="text-secondary"> (eg:6 LPA)</span>
                                    <input type="range" id="max_salary" value="0" name="max_salary" min="0" max="50" oninput="updateMaxSalary(this.value)">
                                    <span id="max_salary_value">0</span>    
                                    @error('salary_range') <span class="text-danger">{{ $message }}</span> @enderror                          
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label me-2">Assessment</label><span class="text-secondary">(If you select "Yes," the next step will be unlocked)</span>
                                    <div>
                                        <input type="radio" name="assessment" value="Yes" id="assessment_yes">
                                        <label for="assessment_yes">Yes</label>                                
                                        <input type="radio" name="assessment" value="No" id="assessment_no" class="ms-3">
                                        <label for="assessment_no">No</label>
                                    </div>
                                    @error('assessment') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>                                
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-12">                            
                                    <label class="form-label">Skills</label><br>
                                    <input type="text" name="skills" id="tag-input" data-role="tagsinput" class="form-control" placeholder="Enter tags"/>
                                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror                        
                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col-lg-12">                            
                                    <label class="form-label">Job Description</label>
                                    <textarea name="description" id="ckeditor-classic">{{ old('description') }}</textarea>    
                                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror                        
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                    </form>                    
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    <!-- end row -->
@endsection
<script>
    var input = document.querySelector("#tag-input");
    new bootstrap.Tagsinput(input);
</script>
<script>
    function updateVolume(value) {
        document.getElementById("vol-value").textContent = value;
    }
    function updateMinSalary(value){
        document.getElementById("min_salary_value").textContent = value;
    }
    function updateMaxSalary(value){
        document.getElementById("max_salary_value").textContent = value;
    }
    </script>
@push('script')
     <!-- ckeditor -->
     <script src="{{ url('public') }}/assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js"></script>
     <!-- init js -->
     <script src="{{ url('public') }}/assets/js/pages/form-editor.init.js"></script>
     <script src="{{ url('public') }}/assets/js/custom/jobs.js"></script>
@endpush