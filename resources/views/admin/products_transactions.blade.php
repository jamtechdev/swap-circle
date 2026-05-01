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
        .card{
            margin-bottom:0px;
            height: calc(108% - 30px);
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

        .truncate-text {
            max-width: 260px;       /* adjust width as needed */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
            vertical-align: middle;
            cursor: pointer;
        }

        /* Tooltip using title attribute */
        .truncate-text:hover {
            text-decoration: underline;
        }
        
      
    </style>
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles mb-n5">
				<ol class="breadcrumb">
                    @section('titleBar')
                    <span class="ml-2">User Customers Transactions</span>
                    @endsection
				</ol>
            </div>
            <!-- row -->

            <div class="row">
                <div class="col-12">
                    

                    <div class="card">
                        <div class="card-body">
                            <legend style="float: right;">
                                <button id="swap-sync-btn-transactions-page" type="button" class="btn btn-primary">
                                    Sync Transactions to Admin Portal
                                </button>
                            </legend>
                            <div class="table-responsive">
                                <table id="example" class="table dt-responsive nowrap display min-w850">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Initiator's Name</th>
                                            <th>Product</th>
                                            <!-- <th>Beneficiary's Name</th> -->
                                            <th>Amount Paid</th>
                                            <th>Payment Status</th>
                                            <th>Transaction No.</th>
                                            <th>Transaction Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products_purchases as $key => $item)
                                        @php
                                            $productType = (string) (optional($item->product)->type ?? '');
                                            $isBeneficiaryProduct = in_array($productType, ['A', 'B'], true);
                                            $isTaskProduct = $productType === 'C';
                                        @endphp
                                        <tr class="odd gradeX">
                                            <td>{{ $key + 1 }}</td>
                                         <!--   <td>{{ $item->initiator->first_name.' '.$item->initiator->last_name }}</td> -->
                                            <td>
                                              {{ optional($item->initiator)->first_name ?? 'N/A' }}
                                               {{ optional($item->initiator)->last_name ?? '' }}
                                            </td>
                                            <td>{{ optional($item->product)->name ?? 'N/A' }}</td>
                                    
                                            <td>₦{{ number_format((float) (optional($item->product)->price ?? 0), 2) }}</td>
                                            <td>{{ $item->payment_status }}</td>
                                            <td>
                                                <span 
                                                    class="truncate-text"
                                                    title="{{ $item->stripe_payment_intent }}"
                                                >
                                                    {{ $item->stripe_payment_intent }}
                                                </span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($item->date_added)->format('d-m-Y H:i:s') }}</td>
                                            <td>
                                                <a class="btn btn-primary" data-toggle="modal" data-target="#modal_view{{ $item->products_purchases_id }}"><i class="fas fa-eye"></i></a>
                                                <button
                                                    type="button"
                                                    class="btn btn-success swap-sync-single-transaction"
                                                    data-purchase-id="{{ $item->products_purchases_id }}"
                                                    title="Sync this transaction to admin portal"
                                                >
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </td>
                                            <!-- modal view start -->
                                            <div class="modal fade" id="modal_view{{ $item->products_purchases_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header px-3 pt-4">
                                                           @if ($isBeneficiaryProduct)
                                                                <h4>View Beneficiary's Details</h4>
                                                            @elseif ($isTaskProduct)
                                                                <h4>View Task Details</h4>
                                                            @else
                                                                <h4>View Transaction Details</h4>
                                                            @endif

                                                           <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                        </div>
                                                        <div class="modal-body px-4 pb-4">
                                                            <div class="row px-3 mt-0">
                                                                @if($isBeneficiaryProduct)
                                                                    <div class="col-lg-4 col-md-4 px-1">
                                                                        <div class="form-group mb-4">
                                                                            <label><b>Name</b></label>
                                                                            <input type="text" class="form-control" value="{{ optional($item->beneficiary)->first_name }} {{ optional($item->beneficiary)->surname }}" readonly>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($isBeneficiaryProduct)
                                                                    <div class="col-lg-4 col-md-4 px-1">
                                                                        <div class="form-group mb-4">
                                                                            <label><b>Gender</b></label>
                                                                            <input type="text" class="form-control" value="{{ optional($item->beneficiary)->gender }}" readonly>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($isBeneficiaryProduct)
                                                                    <div class="col-lg-4 col-md-4 px-1">
                                                                        <div class="form-group mb-4">
                                                                            <label><b>Date of Birth</b></label>
                                                                            <input type="text" class="form-control" value="{{ optional($item->beneficiary)->date_of_birt? \Carbon\Carbon::parse($item->beneficiary->date_of_birth)->format('d-m-Y') : '' }}" readonly>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($isBeneficiaryProduct)
                                                                    <div class="col-lg-4 col-md-4 px-1">
                                                                        <div class="form-group mb-4">
                                                                            <label><b>Address</b></label>
                                                                            <input type="text" class="form-control" value="{{ optional($item->beneficiary)->address }}" readonly>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($isBeneficiaryProduct)
                                                                    <div class="col-lg-4 col-md-4 px-1">
                                                                        <div class="form-group mb-4">
                                                                            <label><b>Occupation</b></label>
                                                                            <input type="text" class="form-control" value="{{ optional($item->beneficiary)->occupation }}" readonly>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($isBeneficiaryProduct)
                                                                    <div class="col-lg-4 col-md-4 px-1">
                                                                        <div class="form-group mb-4">
                                                                            <label><b>Relationship</b></label>
                                                                            <input type="text" class="form-control" value="{{ optional($item->beneficiary)->relationship }}" readonly>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($isBeneficiaryProduct)
                                                                    <div class="col-lg-4 col-md-4 px-1">
                                                                        <div class="form-group mb-4">
                                                                            <label><b>NIN/NIN Document</b></label>
                                                                            @if(optional($item->beneficiary)->nin)
                                                                                <input type="text" class="form-control" value="{{ $item->beneficiary->nin }}" readonly>
                                                                            @endif
                                                                            @if(optional($item->beneficiary)->nin_document)
                                                                                <img src="{{ asset($item->beneficiary->nin_document) }}"alt="Image 1" style="width:130px; height:120px;">
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($isTaskProduct)
                                                                    <div class="col-lg-4 col-md-4 px-1">
                                                                        <div class="form-group mb-4">
                                                                            <label><b>Task Type</b></label>
                                                                            <input type="text" class="form-control" value="{{ optional($item->task_details)->task_type }}" readonly>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($isTaskProduct)
                                                                    <div class="col-lg-4 col-md-4 px-1">
                                                                        <div class="form-group mb-4">
                                                                            <label><b>Task Name</b></label>
                                                                            <input type="text" class="form-control" value="{{ optional($item->task_details)->task }}" readonly>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($isTaskProduct)
                                                                    <div class="col-lg-4 col-md-4 px-1">
                                                                        <div class="form-group mb-4">
                                                                            <label><b>Task Date</b></label>
                                                                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($item->task_details->task_date)->format('d-m-Y') }}" readonly>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($isTaskProduct)
                                                                    <div class="col-lg-12 col-md-12 px-1">
                                                                        <div class="form-group mb-4">
                                                                            <label><b>Description</b></label>
                                                                            <textarea class="form-control" rows="5" readonly>{{ $item->task_details->description }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($isTaskProduct)
                                                                    <div class="col-lg-4 col-md-4 px-1">
                                                                        <div class="form-group mb-4">
                                                                            <label><b>Contact Person Name</b></label>
                                                                            <input type="text" class="form-control" value="{{ $item->task_details->recipient_name }}" readonly>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($isTaskProduct)
                                                                    <div class="col-lg-4 col-md-4 px-1">
                                                                        <div class="form-group mb-4">
                                                                            <label><b>Contact Person Phone No.</b></label>
                                                                            <input type="text" class="form-control" value="{{ $item->task_details->recipient_phone }}" readonly>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>                 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- modal view end -->
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function postJson(url, payload) {
                return fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload || {})
                }).then(function (response) { return response.json(); });
            }

            var bulkSyncBtn = document.getElementById('swap-sync-btn-transactions-page');
            if (bulkSyncBtn) {
                bulkSyncBtn.addEventListener('click', function () {
                    bulkSyncBtn.disabled = true;
                    var originalText = bulkSyncBtn.innerText;
                    bulkSyncBtn.innerText = 'Syncing...';

                    postJson('/api/insuretech/sync-all', { limit: 200 })
                        .then(function (data) {
                            if (data && data.ok) {
                                alert('Transactions synced to Admin Portal. Success: ' + (data.success_count || 0) + ', Failed: ' + (data.failed_count || 0));
                                return;
                            }
                            alert('Bulk transaction sync failed.');
                        })
                        .catch(function () {
                            alert('Bulk transaction sync failed due to network or server error.');
                        })
                        .finally(function () {
                            bulkSyncBtn.disabled = false;
                            bulkSyncBtn.innerText = originalText;
                        });
                });
            }

            document.querySelectorAll('.swap-sync-single-transaction').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var purchaseId = Number(btn.getAttribute('data-purchase-id') || '0');
                    if (!purchaseId) {
                        alert('Invalid transaction selected for sync.');
                        return;
                    }

                    btn.disabled = true;
                    var originalHtml = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                    postJson('/api/insuretech/push-purchase', { products_purchases_id: purchaseId })
                        .then(function (data) {
                            if (data && data.ok) {
                                alert('Transaction synced successfully to Admin Portal.');
                                return;
                            }
                            alert('Transaction sync failed.');
                        })
                        .catch(function () {
                            alert('Transaction sync failed due to network or server error.');
                        })
                        .finally(function () {
                            btn.disabled = false;
                            btn.innerHTML = originalHtml;
                        });
                });
            });
        });
    </script>
@endsection