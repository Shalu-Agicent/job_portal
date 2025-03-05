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
                        <div class="me-auto"><h4>MCQ Sets</h4></div>
                        <div class="">
                            <a href="{{ route('mcqs-sets.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="old_question" value="{{ $data['mcqs_questions'] }}">
                <input type="hidden" id="set_id" value="{{ $data['id'] }}">
                <form id="mcqs_sets_edit_form">
                    @csrf
                    <div class="card-body p-4">                        

                        <div class="row mb-3">
                            <div class="col-lg-12">                            
                                <label class="form-label">Question</label>
                                <input type="text" class="form-control" name="set_title" id="set_title" value="{{ $data['set_title'] }}">
                                @error('set_title') <span class="text-danger">{{ $message }}</span> @enderror  
                            </div>
                        </div>                     

                        <div class="row mb-3">
                            <div class="col-lg-12">                            
                                <label class="form-label">Select Category</label>
                                <select class="form-select" onchange="getEditQuestion(this)" multiple="multiple" id="edit_category_ids" name="category_ids[]">
                                    <option value="">Select</option>                                    
                                    @foreach ($mcq_category_list as $item)
                                        <option value="{{ $item->id }}" 
                                            @if(in_array($item->id, $data['category_ids'])) selected @endif>
                                            {{ $item->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('correct_answer') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-12">                            
                                <label class="form-label">Select Questions</label>
                                <select class="form-select" multiple="multiple" id="edit_mcqs_questions" name="mcqs_questions[]">                                   
                                </select>
                                @error('correct_answer') <span class="text-danger">{{ $message }}</span> @enderror
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
<script src="{{ url('public') }}/assets/js/custom/sets.js"></script>
@endpush