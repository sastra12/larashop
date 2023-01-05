@extends('admin.admin_dashboard')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        </div>
        <!--end breadcrumb-->
        <div class="d-flex justify-content-between">
            <h6 class="text-uppercase">All Category</h6>
            <a href="{{ route('category.create') }}" class="btn btn-primary btn-sm">Add Category</a>
        </div>
        <hr>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap5">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="example" class="table table-striped table-bordered dataTable"
                                    style="width: 100%;" role="grid" aria-describedby="example_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-sort="ascending"
                                                aria-label="Name: activate to sort column descending" style="width: 106px;">
                                                No</th>
                                            <th class="sorting" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="Position: activate to sort column ascending"
                                                style="width: 170px;">Category Name</th>
                                            <th class="sorting" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="Office: activate to sort column ascending"
                                                style="width: 73px;">Category Image</th>
                                            <th class="sorting" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="Age: activate to sort column ascending"
                                                style="width: 27px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th rowspan="1" colspan="1">No</th>
                                            <th rowspan="1" colspan="1">Category Name</th>
                                            <th rowspan="1" colspan="1">Category Image</th>
                                            <th rowspan="1" colspan="1">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let table;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        function deleteData(url) {
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                                url: url,
                                method: 'DELETE',
                            })
                            .done((response) => {
                                swal("Success data has been deleted!", {
                                    icon: "success",
                                });
                                table.ajax.reload();
                            })
                            .fail((errors) => {
                                swal("Failed deleted data!", {
                                    icon: "warning",
                                });
                                return;
                            });

                    } else {
                        swal("Data is safe!");
                    }
                });
        }

        $(document).ready(function() {
            table = $('#example').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('category.data') }}",
                    type: 'GET'
                },
                columns: [{
                        // buat penomoran
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'category_name',
                        name: 'category_name',
                    },
                    {
                        data: 'category_image',
                        name: 'category_image',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        sortable: false
                    },
                ]
            });
        });
    </script>
@endpush
