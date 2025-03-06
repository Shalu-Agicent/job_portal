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
                        <div class="me-auto"><h4>Programming Sets</h4></div>
                        <div class="">
                            <a href="{{ route('programming-sets.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
                <form id="programming_sets_create_form">
                    @csrf
                    <div class="card-body p-4">                        

                        <div class="row mb-3">
                            <div class="col-lg-12">                            
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" name="set_title" id="set_title" value="{{ old('set_title') }}">
                                @error('set_title') <span class="text-danger">{{ $message }}</span> @enderror  
                            </div>
                        </div>
                     

                        <div class="row mb-3">
                            <div class="col-lg-12">                            
                                <label class="form-label">Select Category</label>
                                <select class="form-select" onchange="getQuestion(this)" multiple="multiple" id="category_ids" name="category_ids[]">
                                    <option value="">Select</option>
                                    @foreach ($language_cat_list as $item)
                                        <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                    @endforeach
                                </select>
                                @error('correct_answer') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <hr>

                        <div class="mt-4 mb-4">
                            <div class="card-title mb-4">
                                <h5>Selected Category Related Questions List</h5>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <input type="checkbox" id="select_all" class="me-2"> <strong>Select All</strong>
                                </div>
                            </div>
                            <div class="mt-4 mb-4" id="question_div">
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
<script src="{{ url('public') }}/assets/js/custom/programming_sets.js"></script>
@endpush