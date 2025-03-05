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
                        <div class="me-auto"><h5>{{ $data['set_title']}} </h5></div>
                        <div class="">
                            <a href="{{ route('mcqs-sets.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (!empty($data['category_question_list']))
                        @foreach ($data['category_question_list'] as $category)
                            @if (!empty($category['question_list']))
                                <div class="row bg-light bg-gradient pt-2 pb-2 mb-2">
                                    <h5>{{ $category['category_name'] }}</h5>
                                </div>                                
                                @foreach ($category['question_list'] as $item_list)
                                    <div class="row">
                                        <div class="col-md-12"><strong>Qusetion {{$loop->iteration}}:  </strong>{{ $item_list['question_text'] }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6"><strong>A: </strong>{{ $item_list['option_a'] }}</div>
                                        <div class="col-md-6"><strong>B: </strong>{{ $item_list['option_b'] }}</div>
                                        <div class="col-md-6"><strong>C: </strong>{{ $item_list['option_c'] }}</div>
                                        <div class="col-md-6"><strong>D: </strong>{{ $item_list['option_d'] }}</div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-md-4"><strong>Correct Answer: </strong>{{ $item_list['correct_answer'] }}</div>
                                    </div> --}}
                                    <hr>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                </div>
                
                
            </div>
        </div>
    </div><!-- end col -->
</div>
@endsection
