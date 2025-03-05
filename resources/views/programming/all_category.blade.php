@extends('dashboard.masterLayout')
@section('menu_title')  
{{ $menu_title }}
@endsection

@section('breadcrumb_title')
{{ $breadcrumb_title }}
@endsection
/
@section('page-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="me-auto"><h4>All Programming Category</h4></div>
                        <div class="">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mcqsCategoryModal">
                                Create
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category Name</th>
                                <th>Action</th>
                            </tr>   
                        </thead>

                        <tbody>
                            @foreach ($category_list as $key => $value)
                                <tr id="jobRow{{ $value->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value->category_name }}</td> 
                                    <td class="d-flex">                                      

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-soft-warning me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false" style="height: 32px;">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-md p-4" style="width: 310px;">
                                                <form action="{{ route('program-category.update',[$value->id]) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-lg-12">                            
                                                                <label class="form-label">Category Name</label>
                                                                <input type="text" class="form-control" name="category_name" value="{{ $value->category_name }}" required>
                                                            </div>
                                                        </div>
                                                    </div>                    
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>  
                                        
                                        <form class="deleteProgramCategoryForm" method="POST" action="javascript:void(0)">
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
    <div class="modal fade" id="mcqsCategoryModal" style="margin-top: 65px;" tabindex="-1" aria-labelledby="mcqsCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="mcqsCategoryModalLabel">Create Programming Category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('program-category.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-lg-12">                            
                                <label class="form-label">Category Name</label>
                                <input type="text" class="form-control" name="category_name" id="category_name" required>
                            </div>
                        </div>
                    </div>                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        let base_url = document.getElementById('app').getAttribute('data-url');
        var toastElement = document.getElementById('borderedToast1');

        /**
         * delete function for delete mcqs
         */
        $(document).on('click', '.deleteProgramCategoryForm button', function (e) {
            
            e.preventDefault();
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    var jobId = $(this).data('mcq-id');
                    $.ajax({
                        url: base_url+'/program-category/' + jobId, 
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $("input[name='_token']").val(),
                        },
                        success: function(response) {                       
                        
                            if (toastElement) {
                                var toast = new bootstrap.Toast(toastElement);
                                $("#toast_color").removeClass(function (index, className) {
                                    return (className.match(/(^|\s)bg-\S+/g) || []).join(' ');
                                });
                                $("#toast_color").addClass('bg-success');
                                $('.toast-body').text(response.message);
                                toast.show();
                            }
                            setTimeout(() => {
                                window.location.href = base_url + "/program-category";
                            }, 1200);   
                        },
                        error: function(xhr, status, error) {
                            Swal.fire("An error occurred while deleting this record.");
                        }
                    });
                }
            });    
        });
    </script>
@endpush
