@extends('admin.layouts.master')

@section('title', 'All Users')

@section('style')
@endsection

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
                <a class="opacity-3 text-dark" href="javascript:;">
                    <svg width="12px" height="12px" class="mb-1" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>shop </title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-1716.000000, -439.000000)" fill="#252f40" fill-rule="nonzero">
                                <g transform="translate(1716.000000, 291.000000)">
                                    <g transform="translate(0.000000, 148.000000)">
                                        <path d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"></path>
                                        <path d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"></path>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Users</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">All Users</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">All Users</h6>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header pb-0">
                    <div class="d-lg-flex">
                        <div>
                            <h5 class="mb-0">All Users</h5>
                            <p class="text-sm mb-0">

                            </p>
                        </div>
                        <div class="ms-auto my-auto mt-lg-0 mt-4">
                            <div class="ms-auto my-auto">
                                {{--<a href="{{route('admin.users.create')}}" class="btn bg-gradient-primary btn-sm mb-0">+&nbsp; New User</a>--}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-0">
                    <div class="table-responsive p-3">
                        <table class="table table-flush" id="datatable">
                            <thead class="thead-light text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            <tr>
                                <td>#</td>
                                <td>Name</td>                                
                                <td>Mobile</td>
                                <td>Email</td>
                                <td>Status</td>
                                 <td>Create At</td> 
                            </tr>
                            </thead>
                            <tbody class="text-xs">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-create">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="add_form" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id"/>
                    <input type="hidden" name="user_id" id="user_id"/>
                    <div class="modal-header">
                        <h4 id="form_title" class="modal-title">Add</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input disabled type="text" class="form-control" name="name" id="name" placeholder="Enter Name">
                            <span id="name_error" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <div id="document_div"></div>
                        </div>
                        <div class="form-group hidden">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-control" name="status" id="status" placeholder="Select Status">
                                <option value="">-- Select Status --</option>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                            <span id="status_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="modal-footer text-right hidden">
                        <button type="submit" id="add_button" class="btn btn-dark">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!--  Datatable JS  -->
    <script src="{{asset('assets/js/plugins/datatables.js')}}"></script>
    <script>
        $(document).ready(function () {
            let datatable = $('#datatable').DataTable({
                //pagingType: 'numbers',
                //dom: '<"float-left"B><"row"<"col-sm-6"l><"float-right col-sm-6"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
                dom: '<"row"<"col-sm-6"l><"float-right col-sm-6"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
                language: {
                    paginate: {
                        next: '›',
                        previous: '‹'
                    }
                },
                "select": true,
                "paging": true,
                "pageLength": "10",
                "lengthMenu": [
                    [5, 10, 25, 50, 100, 1000, -1],
                    [5, 10, 25, 50, 100, 1000, 'ALL']
                ],
                "processing": true,
                "serverSide": true,
                "searching": true,
                "responsive": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "ajax": {
                    url: '{{ route('admin.users.index') }}',
                    data: function (d) {
                        d.advisor = $('#advisor').val(),
                        d.product = $('#product').val()
                    },
                },
                "columns": [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {data: 'name', name: 'name'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'email', name: 'email'},
                    // {data: 'topics', name: 'topics'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    // {data: 'action', orderable: false, searchable: false},
                ]
            });

            $('#filters').on('click change keyup', '#advisor, #product, #reset', function () {
                datatable.draw();
            });

            $(document).on("click", ".dverification", function () {
                $("#form_title").text('Document Verification');
                $("#id").val($(this).data('id'));
                $("#user_id").val($(this).data('user_id'));
                $("#name").val($(this).data('name'));
                $("#status").val($(this).data('status'));
                $('.hidden').show();
                $('#status_error').text('');
                $("#add_button").text('Update');
            });

            $('#datatable').on('click', 'tbody .delete', function() {
                var id = $(this).data('id');
                var url = '{{ route('admin.users.destroy', ':id') }}';
                url = url.replace(':id', id);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You restore this user only within 30 days!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: "DELETE",
                            dataType: "json",
                            data: {
                                id: id,
                                _token: '{{csrf_token()}}'
                            },
                            success: function(data) {
                                //console.log(data);
                                datatable.draw();
                                if (data.success === true) {
                                    toastr.success(data.message)
                                } else {
                                    toastr.error(data.message)
                                }
                            }
                        });
                    }
                })
            });

            $('#datatable').on('click', 'tbody .restore', function() {
                var id = $(this).data('id');
                var url = '{{-- route('admin.users.restore', ':id') --}}';
                url = url.replace(':id', id);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to restore this user!",
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: "POST",
                            dataType: "json",
                            data: {
                                id: id,
                                _token: '{{csrf_token()}}'
                            },
                            success: function(data) {
                                //console.log(data);
                                datatable.draw();
                                if (data.success === true) {
                                    toastr.success(data.message)
                                } else {
                                    toastr.error(data.message)
                                }
                            }
                        });
                    }
                })
            });

            $(function() {
                $(document).on('click','.changestatus',function(){
                var status = $(this).prop('checked') == true ? 'active' : 'inactive';
                var id = $(this).data('id');
                
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{ route("admin.users.status") }}',
                    data: {
                        'status': status,
                        'id': id
                    },
                    success: function(data) {
                        console.log(data.success)
                    }
                });
            })
        })

        });
    </script>
@endsection

