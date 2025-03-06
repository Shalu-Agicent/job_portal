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
                        <div class="me-auto"><h4>Question</h4></div>
                        <div class="">
                            <a href="{{ route('programming-question.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
                <form id="question_create_form">
                    @csrf
                    <div class="card-body p-4">                        

                        <div class="row mb-3">
                            <div class="col-lg-12">                          
                                <label class="form-label">Programming Category</label>
                                <select class="form-select" id="programming_cat_id" name="programming_cat_id">
                                    <option value="">Select</option>
                                    @foreach ($programming_category_list as $cat_items)
                                        <option value="{{ $cat_items->id }}">{{ $cat_items->category_name }}</option>
                                    @endforeach
                                </select>    
                                @error('programming_cat_id') <span class="text-danger">{{ $message }}</span> @enderror   
                            </div> 
                        </div>
                     

                        <div class="row mb-3">
                            <div class="col-lg-12">                            
                                <label class="form-label">Question</label>
                                <textarea name="question_text" id="ckeditor-classic">{{ old('question_text') }}</textarea>    
                                @error('question_text') <span class="text-danger">{{ $message }}</span> @enderror                        
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
     <script src="{{ url('public') }}/assets/js/custom/pro_questions.js"></script>
@endpush