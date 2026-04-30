@extends('layout.admin.list_master')
@section('content')
<style>
    .btn-light{
      padding-left:10px;
    }
    .space {
     margin-left: 5px; 
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
            height: calc(97.5% - 30px);
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

        table td .btn.btn-primary {
             border: 1px solid #6c757d !important;
            color: #6c757d !important;
        }

        table td .btn.btn-secondary {
            border: 1px solid #6c757d !important;
            color: #6c757d !important;
        }

        table td .btn.btn-info {
            border: 1px solid #1EA7C5 !important;
            color: #1EA7C5 !important;
        }

        /* Hover effect (optional but clean) */
        table td .btn:hover {
            background-color: rgba(0, 0, 0, 0.03) !important;
        }

        /* Ensure icon keeps color */
        table td .btn i {
            color: inherit;
        }

        .modal-header .btn-close {
        position: absolute;
        top: 14px;
        right: 16px;
        width: 32px;
        height: 32px;
        padding: 0;
        border: 1px solid #d0d5dd;
        border-radius: 8px;
        background: transparent;
        opacity: 1;
        cursor: pointer;
        outline: none;
        }

        /* Draw the X */
        .modal-header .btn-close::before,
        .modal-header .btn-close::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 14px;
        height: 2px;
        background: #111;   /* X color */
        transform-origin: center;
        }

        .modal-header .btn-close::before {
        transform: translate(-50%, -50%) rotate(45deg);
        }

        .modal-header .btn-close::after {
        transform: translate(-50%, -50%) rotate(-45deg);
        }

        /* Hover */
        .modal-header .btn-close:hover {
        background: rgba(0,0,0,0.04);
        }

        .fund-modal {
            background: #fafafa;
        }

        .info-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Fixed heights */
        .h-row-1 { height: 70px; }
        .h-row-2 { height: 160px; }
        .h-row-3 { height: 70px; }

        /* Label */
        .field-label {
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            white-space: nowrap;
        }

        /* Value */
        .field-value {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
        }

        /* Amount */
        .field-value.amount {
            color: #10b981;
            font-size: 17px;
        }

        /* Status */
        .status-pill {
            padding: 5px 14px;
            border-radius: 20px;
            background: #f3f4f6;
            font-size: 13px;
            font-weight: 600;
        }

        /* Image thumbnail */
        .thumb-wrapper {
            width: 120px;
            height: 150px;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .thumb-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Zoom */
        .zoomable-image {
            cursor: zoom-in;
        }




</style>
<!-- View User -->
<script src="jquery.min.js"></script>
<script src="bootstrap.bundle.js"></script>


<div class="col-xl-12">
    <div class="card">
        <div class="card-body p-0">
            <!-- Modal -->
            <div class="modal fade" id="viewFundWalletRequestModal" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">

                        <!-- HEADER -->
                        <div class="modal-header">
                            <h5 class="modal-title">Fund Wallet Request Detail</h5>
                           <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
                        </div>

                        <!-- BODY -->
                        <div class="modal-body fund-modal">
                            <div class="container-fluid">

                                <!-- ROW 1 -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="info-card h-row-1">
                                            <span class="field-label">Bank Name :</span>
                                            <span class="field-value" id="bank_name">-</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="info-card h-row-1">
                                            <span class="field-label">Amount :</span>
                                            <span class="field-value amount" id="amount">$0.00</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- ROW 2 -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="info-card h-row-2">
                                            <span class="field-label">Description :</span>
                                            <span class="field-value" id="description">-</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 image-box-wrapper d-none">
                                        <div class="info-card h-row-2">
                                            <span class="field-label">Uploaded Image :</span>
                                            <span class="field-value">
                                                <div class="thumb-wrapper">
                                                    <img id="image" src="" class="zoomable-image">
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- ROW 3 -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-card h-row-3">
                                            <span class="field-label">Date Added :</span>
                                            <span class="field-value" id="date_added">-</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="info-card h-row-3">
                                            <span class="field-label">Status :</span>
                                            <span class="field-value">
                                                <span id="status" class="status-pill">-</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">
                                Close
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        
        </div>
    </div>
</div>
<!-- View User -->

<!-- IMAGE ZOOM MODAL -->
<div class="modal fade" id="imageZoomModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">

            <div class="modal-header border-0">
                <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body text-center">
                <img id="zoomedImage"
                     src=""
                     class="img-fluid rounded shadow-lg"
                     style="max-height:90vh;">
            </div>

        </div>
    </div>
</div>


<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles mb-n5">
            <ol class="breadcrumb">
                @section('titleBar')
                <span class="ml-2">Fund Wallets Requests</span>
                @endsection
            </ol>
        </div>
        <!-- row -->

        <div class="row">
            <div class="col-12">
                <span id="filter_d"></span>    
                <br>

                <div class="card">
                    <div class="card-body">             
                        <div class="table-responsive">
                            <table id="example" class="table dt-responsive nowrap display min-w850">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Bank Name</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Image</th>
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

                                    {{-- <td><img src="'+profile_image+'" width="80px" height="80px"><span class="space">\
                                        '+ item.first_name + '</span>\
                                        <span class="space">'+ item.last_name + '</span></td>\  --}}
<script src="{{ asset('users/assets/js/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('users/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('users/assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('users/assets/js/jquery.ui.min.js') }}"></script>
<script src="{{ asset('users/assets/js/jquery.additional.methods.js') }}"></script>
<script>
    $(document).ready(function () {

    // IMPORTANT: if list_master already initializes #example, this will fix it
    const table = $('#example').DataTable({
        processing: true,
        destroy: true,
        ajax: {
        url: "/admin/fund_wallet_requests_fetch",
        type: "GET",
        data: function (d) {
            d.filter = window.__fw_filter || '';
        },
        dataSrc: function (json) {

            // Build filter buttons
            const filter = json.filter || '';
            const filterButtons =
            '<button id="filter_data" class="btn ' + (filter === '' ? 'btn-primary' : 'btn-info') + '" style="color:white;margin-bottom:20px;">All</button>' +
            '<button id="filter_data_Pending" class="btn ' + (filter === 'Pending' ? 'btn-primary' : 'btn-info') + '" style="color:white;margin-bottom:20px;margin-left:1px;">Pending</button>' +
            '<button id="filter_data_Funded" class="btn ' + (filter === 'Funded' ? 'btn-primary' : 'btn-info') + '" style="color:white;margin-bottom:20px;margin-left:1px;">Funded</button>' +
            '<button id="filter_data_Rejected" class="btn ' + (filter === 'Rejected' ? 'btn-primary' : 'btn-info') + '" style="color:white;margin-bottom:20px;margin-left:1px;">Rejected</button>' +
            '<button id="filter_data_Deleted" class="btn ' + (filter === 'Deleted' ? 'btn-primary' : 'btn-info') + '" style="color:white;margin-bottom:20px;margin-left:1px;">Deleted</button>';

            $('#filter_d').html(filterButtons);

            return json.fundWallets || [];
        }
        },
        columns: [
        { data: null, render: (d,t,r,meta) => meta.row + 1 },

        { data: "bank_name" },

        { data: "amount" },

        { data: "description", render: (d) => d ? d : '-' },

        {
            data: "image",
            orderable: false,
            searchable: false,
            render: function (img) {
            if (!img) return '-';
            const src = "{{ url('/public') }}/" + img;
            return `<img src="${src}" width="80" height="80" class="zoomable-image">`;
            }
        },

        {
            data: "status",
            render: function (s) {
            if (s === "Pending")  return '<span class="btn m-1 btn-info">Pending</span>';
            if (s === "Funded")   return '<span class="btn m-1 btn-success">Funded</span>';
            if (s === "Rejected") return '<span class="btn m-1 btn-warning">Rejected</span>';
            return '<span class="btn m-1 btn-warning">Deleted</span>';
            }
        },

        {
            data: null,
            orderable: false,
            searchable: false,
            render: function (item) {
            let html = `
                <button class="btn m-1 btn-primary view_fund_wallet_request" value="${item.fund_wallets_id}">
                <i class="fa fa-eye"></i>
                </button>
            `;

            if (item.status === "Pending") {
                html += `
                <button class="btn m-1 btn-warning update_data" value="${item.fund_wallets_id}" data-info="Funded">
                    <i class="fa fa-check"></i>
                </button>
                <button class="btn m-1 btn-success update_data" value="${item.fund_wallets_id}" data-info="Rejected">
                    <i class="fa fa-times"></i>
                </button>
                `;
            }

            if (item.status !== "Deleted") {
                html += `
                <button class="btn m-1 btn-danger delete_data" value="${item.fund_wallets_id}" data-info="Deleted">
                    <i class="fa fa-trash"></i>
                </button>
                `;
            }

            return html;
            }
        }
        ]
    });

    // global filter state
    window.__fw_filter = '';

    function reloadTable() {
        table.ajax.reload(null, true); // true = go to page 1
    }

    // Filters
    $(document).on('click', '#filter_data', function(){ window.__fw_filter=''; reloadTable(); });
    $(document).on('click', '#filter_data_Pending', function(){ window.__fw_filter='Pending'; reloadTable(); });
    $(document).on('click', '#filter_data_Funded', function(){ window.__fw_filter='Funded'; reloadTable(); });
    $(document).on('click', '#filter_data_Rejected', function(){ window.__fw_filter='Rejected'; reloadTable(); });
    $(document).on('click', '#filter_data_Deleted', function(){ window.__fw_filter='Deleted'; reloadTable(); });

    // Delete
    $(document).on("click", ".delete_data", function (e) {
        e.preventDefault();
        const id = $(this).val();

        $.ajax({
        url: "/admin/fund_wallet_requests_delete",
        method: "POST",
        data: { fund_wallets_id: id }
        }).done(function (response) {
        if (response.status === "success") toastr.success(response.message);
        else toastr.error(response.message);
        reloadTable();
        });
    });

    // Update
    $(document).on("click", ".update_data", function (e) {
        e.preventDefault();
        const id = $(this).val();
        const status = $(this).data("info");

        $.ajax({
        url: "/admin/fund_wallet_requests_update",
        method: "POST",
        data: { fund_wallets_id: id, status: status }
        }).done(function (response) {
        if (response.status === "success") toastr.success(response.message);
        else toastr.error(response.message);
        reloadTable();
        });
    });

    // View modal
    $(document).on("click", ".view_fund_wallet_request", function () {
        const id = $(this).val();

        // If Bootstrap 5:
        // new bootstrap.Modal(document.getElementById('viewFundWalletRequestModal')).show();

        // If Bootstrap 4:
        $('#viewFundWalletRequestModal').modal('show');

        $.get("/admin/fund_wallet_requests_edit/" + id, function (response) {
        if (response.status === "success") {
            $('#bank_name').text(response.data.bank_name);
            $('#amount').text('$ ' + response.data.amount);
            $('#description').text(response.data.description || '-');
            $('#date_added').text(response.data.date_added);
            $('#status').text(response.data.status);

            if (response.data.image) {
            $('.image-box-wrapper').removeClass('d-none');
            $('#image').attr('src', "{{ url('/public') }}/" + response.data.image);
            } else {
            $('.image-box-wrapper').addClass('d-none');
            }
        }
        });
    });

    // Image zoom
    $(document).on('click', '.zoomable-image', function () {
        $('#zoomedImage').attr('src', $(this).attr('src'));

        // If Bootstrap 5:
        // new bootstrap.Modal(document.getElementById('imageZoomModal')).show();

        // If Bootstrap 4:
        $('#imageZoomModal').modal('show');
    });

});
</script>

@endsection