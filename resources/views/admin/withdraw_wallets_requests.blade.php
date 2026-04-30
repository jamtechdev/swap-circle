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

        .withdraw-modal {
            background: #fafafa;
            padding: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* One field per row */
        .info-row {
            display: grid;
            grid-template-columns: 140px 12px 1fr;
            align-items: center;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 10px;
            width: 740px;
        }

        /* Label */
        .info-row .label {
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
        }

        /* Colon */
        .info-row .colon {
            font-weight: 600;
            color: #6b7280;
        }

        /* Value */
        .info-row .value {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

        /* Amount highlight */
        .info-row .value.amount {
            color: #10b981;
            font-size: 15px;
        }

        /* Status pill */
        .status-pill {
            padding: 4px 14px;
            border-radius: 20px;
            background: #f3f4f6;
            font-size: 13px;
            font-weight: 600;
        }

</style>
<!-- View User -->

<div class="col-xl-12">
    <div class="card">
        <div class="card-body p-0">
            <!-- Modal -->
            <div class="modal fade" id="viewWithdrawWalletRequestModal">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">

                        <!-- HEADER -->
                        <div class="modal-header">
                            <h5 class="modal-title">Withdraw Wallet Request Detail</h5>
                            <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
                        </div>

                        <!-- BODY -->
                        <div class="modal-body withdraw-modal">

                            <div class="info-row">
                                <span class="label">User</span>
                                <span class="colon">:</span>
                                <span class="value" id="user">-</span>
                            </div>

                            <div class="info-row">
                                <span class="label">Wallet</span>
                                <span class="colon">:</span>
                                <span class="value" id="wallet">-</span>
                            </div>

                            <div class="info-row">
                                <span class="label">Amount</span>
                                <span class="colon">:</span>
                                <span class="value amount" id="amount">-</span>
                            </div>

                            <div class="info-row">
                                <span class="label">Description</span>
                                <span class="colon">:</span>
                                <span class="value" id="description">-</span>
                            </div>

                            <div class="info-row">
                                <span class="label">Date Added</span>
                                <span class="colon">:</span>
                                <span class="value" id="date_added">-</span>
                            </div>

                            <div class="info-row">
                                <span class="label">Status</span>
                                <span class="colon">:</span>
                                <span class="value">
                                    <span id="status" class="status-pill">-</span>
                                </span>
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

<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles mb-n5">
            <ol class="breadcrumb">
                @section('titleBar')
                <span class="ml-2">Withdraw Wallets Requests</span>
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
                                        <th>User</th>
                                        <th>Wallet</th>
                                        <th>Amount</th>
                                        <th>Description</th>
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

    let currentFilter = '';

    const table = $('#example').DataTable({
        processing: true,
        destroy: true,
        ajax: {
        url: "/admin/withdraw_wallets_requests_fetch",
        type: "GET",
        data: function (d) {
            d.filter = currentFilter;
        },
        dataSrc: function (json) {

            // Render filter buttons
            const f = json.filter || '';
            $('#filter_d').html(`
            <button id="f_all" class="btn ${f==''?'btn-primary':'btn-info'}" style="color:white">All</button>
            <button id="f_pending" class="btn ${f=='Pending'?'btn-primary':'btn-info'}" style="color:white">Pending</button>
            <button id="f_accepted" class="btn ${f=='Accepted'?'btn-primary':'btn-info'}" style="color:white">Accepted</button>
            <button id="f_rejected" class="btn ${f=='Rejected'?'btn-primary':'btn-info'}" style="color:white">Rejected</button>
            <button id="f_deleted" class="btn ${f=='Deleted'?'btn-primary':'btn-info'}" style="color:white">Deleted</button>
            `);

            return json.WithdrawWallets || [];
        }
        },
        columns: [
        { data: null, render: (d,t,r,m)=>m.row+1 },
        { data: null, render: d => `${d.users_customer ? d.users_customer.first_name+' '+d.users_customer.last_name : 'Deleted'}` },
        { data: null, render: d => `${d.system_currency ? d.system_currency.code+' ('+d.system_currency.symbol+')' : 'N/A'}` },
        { data: "amount" },
        { data: "description" },
        {
            data: "status",
            render: s => {
            if(s=="Pending") return '<span class="btn btn-info">Pending</span>';
            if(s=="Accepted") return '<span class="btn btn-success">Accepted</span>';
            if(s=="Rejected") return '<span class="btn btn-warning">Rejected</span>';
            return '<span class="btn btn-warning">Deleted</span>';
            }
        },
        {
            data: null,
            orderable:false,
            searchable:false,
            render: d => {
            let html = `
                <button class="btn btn-primary view" value="${d.withdraw_wallets_requests_id}">
                <i class="fa fa-eye"></i>
                </button>
            `;
            if(d.status=="Pending"){
                html += `
                <button class="btn btn-warning update" data-id="${d.withdraw_wallets_requests_id}" data-s="Accepted"><i class="fa fa-check"></i></button>
                <button class="btn btn-success update" data-id="${d.withdraw_wallets_requests_id}" data-s="Rejected"><i class="fa fa-times"></i></button>
                `;
            }
            if(d.status!="Deleted"){
                html += `<button class="btn btn-danger del" data-id="${d.withdraw_wallets_requests_id}"><i class="fa fa-trash"></i></button>`;
            }
            return html;
            }
        }
        ]
    });

    const reload = ()=>table.ajax.reload(null,true);

    // Filters
    $(document).on('click','#f_all',()=>{currentFilter='';reload();});
    $(document).on('click','#f_pending',()=>{currentFilter='Pending';reload();});
    $(document).on('click','#f_accepted',()=>{currentFilter='Accepted';reload();});
    $(document).on('click','#f_rejected',()=>{currentFilter='Rejected';reload();});
    $(document).on('click','#f_deleted',()=>{currentFilter='Deleted';reload();});

    // Update
    $(document).on('click','.update',function(){
        $.post('/admin/withdraw_wallets_requests_update',{
        withdraw_wallets_requests_id:$(this).data('id'),
        status:$(this).data('s')
        },reload);
    });

    // Delete
    $(document).on('click','.del',function(){
        $.post('/admin/withdraw_wallets_requests_delete',{
        withdraw_wallets_requests_id:$(this).data('id')
        },reload);
    });

    // View
    $(document).on('click','.view',function(){
        const id=$(this).val();
        new bootstrap.Modal('#viewWithdrawWalletRequestModal').show();
        $.get('/admin/withdraw_wallets_requests_edit/'+id,r=>{
        $('#user').text(r.data.users_customer ? r.data.users_customer.first_name+' '+r.data.users_customer.last_name : 'Deleted');
        $('#wallet').text(r.data.system_currency ? r.data.system_currency.code+' '+r.data.system_currency.symbol : 'N/A');
        $('#amount').text(r.data.amount);
        $('#description').text(r.data.description);
        $('#date_added').text(r.data.date_added);
        $('#status').text(r.data.status);
        });
    });

    });
</script>

@endsection