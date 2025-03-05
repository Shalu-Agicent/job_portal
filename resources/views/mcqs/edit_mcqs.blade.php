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
                        <div class="me-auto"><h4>MCQ</h4></div>
                        <div class="">
                            <a href="{{ route('mcqs.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
                <form id="mcqs_edit_form">
                    @csrf
                    <div class="card-body p-4">                      
                        <input type="hidden" name="mcq_id" id="mcq_id" value="{{ $mcq_data['id'] }}">
                        <div class="row mb-3">
                            <div class="col-lg-12">                            
                                <label class="form-label">Question</label>
                                <textarea class="form-control" name="question_text" id="question_text">{{ $mcq_data['question_text'] }}</textarea>
                                @error('question_text') <span class="text-danger">{{ $message }}</span> @enderror  
                            </div>
                        </div>
                     

                        <div class="row mb-3">
                            <div class="col-lg-6">                            
                                <label class="form-label">Option A</label>
                                <textarea class="form-control" name="option_a" id="option_a">{{ $mcq_data['option_a'] }}</textarea>
                                @error('option_a') <span class="text-danger">{{ $message }}</span> @enderror  
                            </div>

                            <div class="col-lg-6">                            
                                <label class="form-label">Option B</label>
                                <textarea class="form-control" name="option_b" id="option_b">{{ $mcq_data['option_b'] }}</textarea>
                                @error('option_b') <span class="text-danger">{{ $message }}</span> @enderror  
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-6">                            
                                <label class="form-label">Option C</label>
                                <textarea class="form-control" name="option_c" id="option_c">{{ $mcq_data['option_c'] }}</textarea>
                                @error('option_c') <span class="text-danger">{{ $message }}</span> @enderror  
                            </div>

                            <div class="col-lg-6">                            
                                <label class="form-label">Option D</label>
                                <textarea class="form-control" name="option_d" id="option_d">{{ $mcq_data['option_d'] }}</textarea>
                                @error('option_d') <span class="text-danger">{{ $message }}</span> @enderror  
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-6">                            
                                <label class="form-label">Correct Answer</label>
                                <select class="form-select" id="correct_answer" name="correct_answer">
                                    <option value="">Select</option>
                                    <option value="A" {{ $mcq_data['correct_answer'] == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ $mcq_data['correct_answer'] == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="C" {{ $mcq_data['correct_answer'] == 'C' ? 'selected' : '' }}>C</option>
                                    <option value="D" {{ $mcq_data['correct_answer'] == 'D' ? 'selected' : '' }}>D</option>
                                </select>
                                @error('correct_answer') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="col-lg-6">                          
                                <label class="form-label">Mcq Category</label>
                                <select class="form-select" id="mcq_category_id" name="mcq_category_id">
                                    <option value="">Select</option>
                                    @foreach ($mcq_category_list as $cat_items)
                                        <option value="{{ $cat_items->id }}" {{ $mcq_data['mcq_category_id'] == $cat_items->id ? 'selected' : '' }}>{{ $cat_items->category_name }}</option>
                                    @endforeach
                                </select>    
                                @error('mcq_category_id') <span class="text-danger">{{ $message }}</span> @enderror   
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
     <script src="{{ url('public') }}/assets/js/custom/mcq_master.js"></script>
@endpush