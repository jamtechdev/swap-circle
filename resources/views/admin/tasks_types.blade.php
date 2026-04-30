 @extends('layout.admin.list_master')

@section('content')
    <?php $system_currency    = DB::table('system_settings')->select('description')->where('type', 'system_currency')->get()->first(); ?>

    <style>
        input{
           border-radius: 20px;
        }
        .avatar {
          vertical-align: middle;
          width: 50px;
          height: 50px;
          border-radius: 50%;
        }
        .imageUpload
        {
            display: none;
        }

        .profileImage
        {
            /* margin-top: -40px; */
            cursor: pointer;
            width: 100%;
        }

        #profile-container {
            margin: 20px auto;
            width: 130px;
            height: 130px;
            color: white;
            justify-content: center;
            border: 1px solid #8f8989;
            overflow: hidden;
        }

        #profile-container img {
            width: 150px;
            height: 150px;
           
        }

        
        table.dataTable tbody td {
            font-size: 14px;
            padding: 12px 15px;
        }
        table.dataTable thead th {
            font-size: 14px;
            padding: 12px 15px;
        }
        table tbody tr td .btn{
            padding: 0.500rem 1.5rem;
            font-size: 14px;
        }
        .content-body .container-fluid{
            padding-top: 20px;
        }
        .container-fluid .row .btn{
            padding: 0.500rem 1.5rem;
        }
        .dataTables_length label, .dataTables_filter label{
            font-size: 14px;
            margin-bottom:0px;
        }
        .card{
            margin-bottom:0px;
            height: calc(106% - 30px);
        }
        .card .card-body{
            padding: 1.875rem 1.875rem 0rem 1.875rem;
        }
        .dataTables_wrapper:after{
            display:none;
        }
        
        /* ==============================
        Action Buttons: Border Only
        (CSS-only, No HTML change)
        ============================== */

        /* Target action column buttons */
        table td .btn {
            background-color: transparent !important;
            box-shadow: none !important;
        }

        /* Keep border & icon color */
        table td .btn.btn-success {
            border: 1px solid #28a745 !important;
            color: #28a745 !important;
        }

        table td .btn.btn-danger {
            border: 1px solid #dc3545 !important;
            color: #dc3545 !important;
        }

        table td .btn.btn-warning {
            border: 1px solid #ffc107 !important;
            color: #ffc107 !important;
        }

        table td .btn.btn-secondary {
            border: 1px solid #6c757d !important;
            color: #6c757d !important;
        }

        table td .btn.btn-primary {
            border: 1px solid #6c757d !important;
            color: #6c757d !important;
        }

        /* Hover effect (optional but clean) */
        table td .btn:hover {
            background-color: rgba(0, 0, 0, 0.03) !important;
        }

        /* Ensure icon keeps color */
        table td .btn i {
            color: inherit;
        }
    </style>
    <!--**********************************
        Chat box End
    ***********************************-->
    
    <div class="content-body">
        <div class="container-fluid">
            <div class="col-md-12 mb-n5">
                <div class="col-sm-12 p-md-0">
                    <div class="col-sm-12 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        @section('titleBar')
                        <span class="ml-2">Manage Task Types</span>
                        @endsection
                    </div>
                </div>
                <!-- modal add start -->
                <div class="modal fade" id="modal_add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                     <div class="modal-dialog modal-md modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <h4 class="">Add Task Type</h4>
                                </div>
                                <form method="post" action="{{ url('/admin/tasks_types_add') }}">
                                    @csrf
                                    <div class="row mt-0">
                                        <div class="col-lg-12 col-md-6">
                                            <div class="form-group mb-4">
                                                <input type="text" class="form-control" placeholder="Enter Task Type" name="name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary btn-login mr-2">Save</button>
                                        <button type="button" class="btn btn-danger btn-login" data-dismiss="modal">Close</button>
                                    </div>
                                </form>                     
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal add end -->

                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="basic-form" style="width: 100%;">
                                    <legend style="float: right;"><a style="float: right;" class="btn btn-primary" data-toggle="modal" data-target="#modal_add">Add Task Type</a></legend>
                                    <div class="table-responsive">
                                        <table id="example" class="table dt-responsive nowrap display min-w850">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th style="min-width: 300px;">Task Type</th>
                                                    <th style="min-width: 300px;">Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               @foreach($tasks_types as $key => $item)
                                                <tr class="odd gradeX">
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>
                                                        @if ($item->status=='Active')
                                                            <span class="btn btn-success" style="cursor: default;">{{ $item->status }}</span>
                                                        @else 
                                                            <span class="btn btn-secondary" style="cursor: default;">{{ $item->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td> 
                                                        <a class="btn btn-primary" data-toggle="modal" data-target="#modal_edit{{ $item->tasks_types_id }}"><i class="fas fa-edit"></i></a>
                                                        @if($item->status == 'Active')
                                                            <a href="{{ url('/admin/tasks_types_update/Inactive/' . $item->tasks_types_id) }}" class="btn btn-secondary" title="Deactivate"><i class="fas fa-times"></i></a>
                                                        @endif 
                                                        @if($item->status == 'Inactive')
                                                            <a href="{{ url('/admin/tasks_types_update/Active/' . $item->tasks_types_id) }}" class="btn btn-success" title="Activate"><i class="fas fa-check"></i></a>
                                                        @endif 
                                                        <a href="{{ url('/admin/tasks_types_update/Deleted/' . $item->tasks_types_id) }}" class="btn btn-danger" title="Delete"><i class="far fa-trash-alt"></i></a>                       
                                                    </td>
                                                    <!-- modal edit start -->
                                                    <div class="modal fade" id="modal_edit{{ $item->tasks_types_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                         <div class="modal-dialog modal-md modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-body p-4">
                                                                    <div class="d-flex align-items-center mb-4">
                                                                        <h4 class="">Edit Task Type</h4>
                                                                    </div>
                                                                    <form method="post" action="{{ url('/admin/tasks_types_edit') }}">
                                                                        @csrf
                                                                        <div class="row mt-0">
                                                                            <div class="col-lg-12 col-md-6">
                                                                                <div class="form-group mb-4">
                                                                                    <input type="text" class="form-control" value="{{ $item->name }}" placeholder="Enter Task Type" name="edit_name" required>
                                                                                </div>
                                                                            </div>
                                                                            <input type="hidden" name="tasks_types_id" value="{{ $item->tasks_types_id }}" readonly>
                                                                        </div>
                                                                        <div class="d-flex justify-content-end mt-3">
                                                                            <button type="submit" class="btn btn-primary btn-login mr-2">Save</button>
                                                                            <button type="button" class="btn btn-danger btn-login" data-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </form>                     
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- modal edit end -->
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection