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
                        <div class="me-auto"><h4>All Mcqs</h4></div>
                        <div class="">
                            <a href="{{ route('programming-question.create') }}" class="btn btn-soft-primary"><i class="bx bxs-plus-square"></i> Create</a>
               
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered dt-responsive">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Question</th>
                                <th>Category Name</th>
                                <th>Action</th>
                            </tr>   
                        </thead>

                        <tbody>
                            @foreach ($question_list as $key => $value)
                                <tr id="jobRow{{ $value->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{!! $value->question_text !!}</td> 
                                    <td>{{ $value->programming_category->category_name }}</td> 
                                    <td class="d-flex align-items-center">
                                       
                                        {{-- <button type="button" data-bs-question_id="{{$value->id}}" class="btn btn-soft-primary me-2" data-bs-toggle="modal" data-bs-target="#mcqView">
                                            <i class="bx bx-show"></i>
                                        </button> --}}
                                     
                                        <a href="{{ route('programming-question.edit', $value->id) }}" class="btn btn-soft-warning me-2"><i class="bx bx-edit"></i></a>

                                        <form class="deleteQuestionForm" method="POST" action="javascript:void(0)">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="button" class="btn btn-soft-danger me-2" data-question-id="{{$value->id}}">
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

    <!-- Modal -->
    <div class="modal fade" id="mcqView" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mcqViewLabel" aria-hidden="true" style="margin-top: 65px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="mcqViewLabel">Mcq Question Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script src="{{ url('public') }}/assets/js/custom/pro_questions.js"></script>
@endpush
