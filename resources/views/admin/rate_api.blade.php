@extends('layout.admin.list_master')
@section('content')
    <style>
        .btn-light{
          padding-left:10px;
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
        /* .card{
            margin-bottom:0px;
            height: calc(96% - 30px);
        } */
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

         table td .btn.btn-info {
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

        #viewRateApiModal .card {
            background: #fafafa;
        }

        #viewRateApiModal .fw-semibold {
            font-size: 15px;
        }

        #viewRateApiModal .badge {
            font-size: 14px;
        }

    </style>
    <!-- Add Rate Api -->
    <div class="modal fade" id="exampleModalAddRateApi">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            @section('titleBar')
            <span class="ml-2">Add Rate Api</span>
            @endsection 
                <div class="modal-header">
                    <h5 class="modal-title">Add Rate Api</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="basic-form">

                        <div class="row col-md-12"> 
                            <div class="form-group col-md-12">
                                <b>Name</b>
                                <b><input  type="text" name="catname" class="form-control catname input" required></b>
                                <span class="error_msg" id="name_error"></span>
                            </div>
                        </div>
                        <div class="row col-md-12"> 
                            <div class="form-group col-md-12">
                                <b>URL</b>
                                <b><input  type="text" name="url" class="form-control url input" required></b>
                                <span class="error_msg" id="url_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary add_rate_api">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Rate Api -->

    <!-- Edit Rate Api -->
    <div class="modal fade" id="editRateApiModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            @section('titleBar')
            <span class="ml-2">Edit Rate Api</span>
            @endsection 
                <div class="modal-header">
                    <h5 class="modal-title">Edit Rate Api</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="basic-form">

                        <div class="row col-md-12"> 
                            <div class="form-group col-md-12">
                                <b>Name</b>
                                <b><input  type="text" name="name" id="name" class="form-control input" required></b>
                            </div>
                        </div>
                        <div class="row col-md-12"> 
                            <div class="form-group col-md-12">
                                <b>URL</b>
                                <b><input  type="text" name="url" id="url" class="form-control input" required></b>
                            </div>
                        </div>
                        <input type="hidden" class="input" id="rate_api_id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="edit_rate_api">Edit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Rate Api -->

    <!-- View Rate Api -->
    <div class="modal fade" id="viewRateApiModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">View Rate Api</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <div id="RateApiViewModal"></div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>
    <!-- Edit Rate Api -->

    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles mb-n5">
				<ol class="breadcrumb">
                    @section('titleBar')
                    <span class="ml-2">Rate Api</span>
                    @endsection
				</ol>
            </div>
            <!-- row -->

            <div class="row">
                <div class="col-12">
                    

                    <div class="card">
                        <div class="card-body">                                    
                        <legend style="float: right;"><a style="float: right;" class="btn btn-primary"  data-toggle="modal" data-target="#exampleModalAddRateApi"> Add Rate Api </a></legend>
                            <div class="table-responsive">
                                <table id="example" class="table dt-responsive nowrap display min-w850">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>URL</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
          	</div>
        </div>
    </div>
    <script src="{{ asset('users/assets/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('users/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('users/assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('users/assets/js/jquery.ui.min.js') }}"></script>
    <script src="{{ asset('users/assets/js/jquery.additional.methods.js') }}"></script>
    <script>
        $(document).ready(function () {

        const table = $('#example').DataTable({
            processing: true,
            destroy: true,
            ajax: {
            url: "/admin/rate_api_fetch",
            type: "GET",
            dataSrc: function (json) {
                return json.rate_api || [];
            }
            },
            columns: [
            { data: null, render: (d,t,r,m)=>m.row+1 },

            { data: "name" },

            { data: "url" },

            {
                data: "status",
                render: function (s) {
                if (s === "Active") return '<span class="btn btn-success">Active</span>';
                if (s === "Inactive") return '<span class="btn btn-warning">Inactive</span>';
                return '<span class="btn btn-danger">Deleted</span>';
                }
            },

            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (item) {
                let html = `
                    <button class="btn btn-primary view_rate_api" value="${item.rate_api_id}">
                    <i class="fa fa-eye"></i>
                    </button>

                    <button class="btn btn-info edit_rate_api" value="${item.rate_api_id}">
                    <i class="fa fa-edit"></i>
                    </button>
                `;

                if (item.status === "Active") {
                    html += `<button class="btn btn-warning update_data" data-id="${item.rate_api_id}" data-s="Inactive">
                            <i class="fa fa-times"></i>
                            </button>`;
                } else if (item.status === "Inactive") {
                    html += `<button class="btn btn-success update_data" data-id="${item.rate_api_id}" data-s="Active">
                            <i class="fa fa-check"></i>
                            </button>`;
                }

                if (item.status !== "Deleted") {
                    html += `<button class="btn btn-danger delete_data" data-id="${item.rate_api_id}">
                            <i class="fa fa-trash"></i>
                            </button>`;
                }

                return html;
                }
            }
            ]
        });

        const reload = () => table.ajax.reload(null, false);

        // EDIT
        $(document).on("click", ".edit_rate_api", function () {
            const id = $(this).val();
            $('#editRateApiModal').modal('show');

            $.get("/admin/rate_api_edit/" + id, function (r) {
            $('#name').val(r.data.name);
            $('#url').val(r.data.url);
            $('#rate_api_id').val(r.data.rate_api_id);
            });
        });

        // UPDATE STATUS
        $(document).on("click", ".update_data", function () {
            $.post("/admin/rate_api_update", {
            rate_api_id: $(this).data('id'),
            status: $(this).data('s')
            }, reload);
        });

        // DELETE
        $(document).on("click", ".delete_data", function () {
            $.post("/admin/rate_api_delete", {
            rate_api_id: $(this).data('id')
            }, reload);
        });

        // VIEW
        $(document).on("click", ".view_rate_api", function () {
            const id = $(this).val();
            $('#viewRateApiModal').modal('show');

            $.get("/admin/rate_api_edit/" + id, function (r) {
            $('#RateApiViewModal').html(`
                <div class="card p-3">
                <p><b>Name:</b> ${r.data.name}</p>
                <p><b>URL:</b> ${r.data.url}</p>
                <p><b>Status:</b> ${r.data.status}</p>
                </div>
            `);
            });
        });

        // ADD
        $(document).on("click", ".add_rate_api", function () {
            $.post("/admin/rate_api_add_data", {
            name: $('.catname').val(),
            url: $('.url').val()
            }, function () {
            reload();
            $('#exampleModalAddRateApi').modal('hide');
            $('.input').val('');
            });
        });

        });
    </script>
@endsection