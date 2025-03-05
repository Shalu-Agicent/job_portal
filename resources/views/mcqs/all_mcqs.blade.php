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
                            <a href="{{ route('mcqs.create') }}" class="btn btn-soft-primary"><i class="bx bxs-plus-square"></i> Create</a>
                            <a href="{{ route('mcqs-export') }}" class="btn btn-soft-warning"><i class="bx bx-export"></i> Export MCQs</a>

                            <button type="button" class="btn btn-soft-info me-2" data-bs-toggle="modal" data-bs-target="#importData">
                                <i class="bx bx-import"></i> Import MCQs
                            </button>                          
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
                            @foreach ($mcqs_list as $key => $value)
                                <tr id="jobRow{{ $value->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value->question_text }}</td> 
                                    <td>{{ $value->category->category_name }}</td> 
                                    <td class="d-flex align-items-center">
                                       
                                        <button type="button" data-bs-question_id="{{$value->id}}" class="btn btn-soft-primary me-2" data-bs-toggle="modal" data-bs-target="#mcqView">
                                            <i class="bx bx-show"></i>
                                        </button>
                                     
                                        <a href="{{ route('mcqs.edit', $value->id) }}" class="btn btn-soft-warning me-2"><i class="bx bx-edit"></i></a>

                                        <form class="deleteMcqForm" method="POST" action="javascript:void(0)">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="button" class="btn btn-soft-danger me-2" data-mcq-id="{{$value->id}}">
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

    <!-- import Modal -->
    <div class="modal fade modal-lg" id="importData" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="importDataLabel" aria-hidden="true" style="margin-top: 65px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="importDataLabel">Import Mcq Questions</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">                   
                    <div class="card">                                            
                        <div class="card-body">
                            <form action="{{ route('mcqs-import')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-lg-12 mb-3">                            
                                        <label class="form-label">Mcq Category <span class="text-danger fw-semibold">*</span></label>
                                        <select class="form-control" name="mcq_category_id" required>
                                            <option value="">select</option>
                                            @foreach ($mcq_category_list as $items_list)
                                                <option value="{{$items_list->id}}">{{$items_list->category_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-12">  
                                        <label class="form-label">File <span class="text-danger fw-semibold">*</span></label>
                                        <input type="file" class="form-control" name="mcq_import_file" id="mcq_import_file">
                                    </div>        
                                </div>
                                <div class="d-flex">
                                    <div class="me-auto">
                                        <button type="submit" class="btn btn-primary">Submit</button> 
                                    </div>
                                    <div class="">
                                        <a href="{{url('/')}}/public/assets/sample-files/sample_mcqs_import_file.csv" class="btn btn-link"><i class="bx bxs-download"></i> Download Sample File</a>                      
                                    </div>
                                </div>                               
                            </form>
                        </div>
                    </div>                   
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
<script src="{{ url('public') }}/assets/js/custom/mcq_master.js"></script>
@endpush
