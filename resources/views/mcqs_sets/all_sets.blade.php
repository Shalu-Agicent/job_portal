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
                        <div class="me-auto"><h4>All Mcqs Sets</h4></div>
                        <div class="">
                            <a href="{{ route('mcqs-sets.create') }}" class="btn btn-soft-primary"><i class="bx bxs-plus-square"></i> Create</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Set Title</th>
                            <th>Action</th>
                        </tr>   
                        </thead>

                        <tbody>
                            @foreach ($sets_list as $key => $value)
                                <tr id="jobRow{{ $value->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value->set_title }}</td> 
                                    <td class="d-flex align-items-center">
                                       
                                        <a href="{{ route('mcqs-sets.show', $value->id) }}" class="btn btn-soft-primary me-2"><i class="bx bx-show"></i> </a>
                                     
                                        <a href="{{ route('mcqs-sets.edit', $value->id) }}" class="btn btn-soft-warning me-2"><i class="bx bx-edit"></i></a>

                                        <form class="deleteMcqSetForm" method="POST" action="javascript:void(0)">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="button" class="btn btn-soft-danger me-2" data-set-id="{{$value->id}}">
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
@endsection

@push('script')
<script src="{{ url('public') }}/assets/js/custom/sets.js"></script>
@endpush