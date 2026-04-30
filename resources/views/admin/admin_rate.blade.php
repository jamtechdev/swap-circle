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
</style>

<!-- Edit Rate -->
<div class="modal fade" id="editRateApiModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        @section('titleBar')
        <span class="ml-2">Admin Rate</span>
        @endsection 
            <div class="modal-header">
                <h5 class="modal-title">Edit Admin Rate</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="basic-form">

                    <div class="row col-md-12"> 
                        <div class="form-group col-md-12">
                            <b>Name</b>
                            <div>
                                <label id="name" class="mt-1"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row col-md-12"> 
                        <div class="form-group col-md-12">
                            <b>Admin Rate</b>
                            <b><input  type="text" name="number" id="admin_rate" class="form-control input" required></b>
                        </div>
                    </div>
                    <input type="hidden" class="input" id="system_currencies_id">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="edit_rate_api">Edit</button>
            </div>
        </div>
    </div>
</div>
<!-- Edit Rate  -->

<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles mb-n5">
            <ol class="breadcrumb">
                @section('titleBar')
                <span class="ml-2">Admin Rate</span>
                @endsection
            </ol>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">                            
                        <legend style="float: right;"><a style="float: right;" class="btn btn-primary refresh_rate">Refresh Rate</a></legend>                                
                        <div class="table-responsive">
                            <table id="example" class="table dt-responsive nowrap display min-w850">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Symbol</th>
                                        <th>Admin Rate</th>
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

        // 1) Init DataTable with ajax
        const table = $('#example').DataTable({
            processing: true,
            destroy: true,      // important if list_master also initializes
            ajax: {
            url: "/admin/admin_rate_fetch",
            type: "GET",
            dataSrc: function (json) {
                return json.admin_rate || [];
            }
            },
            columns: [
            { data: null, render: (d,t,r,meta) => meta.row + 1 }, // #
            { data: "name" },
            { data: "code" },
            { data: "symbol" },
            { data: "admin_rate" },
            {
                data: null,
                render: function (data) {
                if (data.status === "Active")   return '<span class="btn m-1 btn-success">Active</span>';
                if (data.status === "Inactive") return '<span class="btn m-1 btn-warning">Inactive</span>';
                return '<span class="btn m-1 btn-danger">Deleted</span>';
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data) {
                return `
                    <button class="btn m-1 btn-info edit_rate_api" value="${data.system_currencies_id}">
                    <i class="fa fa-edit"></i>
                    </button>
                `;
                }
            }
            ]
        });

        // 2) When you need refresh
        function reloadTable() {
            table.ajax.reload(null, false); // keep current page
        }

        // Edit click
        $(document).on("click", ".edit_rate_api", function (e) {
            e.preventDefault();
            const id = $(this).val();

            // If you are truly on Bootstrap 5, this is the correct way:
            // new bootstrap.Modal(document.getElementById('editRateApiModal')).show();

            // If you are on Bootstrap 4, keep this:
            $('#editRateApiModal').modal('show');

            $.ajax({
            url: "/admin/admin_rate_edit/" + id,
            method: "GET"
            }).done(function (response) {
            if (response.status === "error") {
                toastr.error(response.message);
            } else {
                $('#admin_rate').val(response.data.admin_rate);
                $('#name').html(response.data.name);
                $('#system_currencies_id').val(response.data.system_currencies_id);
            }
            });
        });

        // Refresh rate
        $(document).on("click", ".refresh_rate", function (e) {
            e.preventDefault();
            $('.refresh_rate').text('Refreshing ....');

            $.ajax({
            url: "/admin/refresh_rate_data",
            method: "GET"
            }).done(function (response) {
            $('.refresh_rate').text('Refresh Rate');
            reloadTable();
            if (response.status === "error") toastr.error(response.message);
            else toastr.success(response.message);
            });
        });

        // Save edit
        $(document).on("click", "#edit_rate_api", function (e) {
            e.preventDefault();

            $.ajax({
            url: "/admin/admin_rate_edit_data",
            method: "POST",
            data: {
                system_currencies_id: $("#system_currencies_id").val(),
                admin_rate: $("#admin_rate").val()
            }
            }).done(function (response) {
            if (response.status === "success") toastr.success(response.message);
            else toastr.error(response.message);

            reloadTable();

            // close modal (bootstrap 4)
            $('#editRateApiModal').modal('hide');
            });
        });

    });
    </script>
@endsection