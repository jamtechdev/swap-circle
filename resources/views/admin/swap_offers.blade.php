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
            height: calc(96% - 30px);
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
        /* table td .btn {
            background-color: transparent !important;
            box-shadow: none !important;
        } */

        /* Keep border & icon color */
        /* table td .btn.btn-success {
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
        } */

        /* Hover effect (optional but clean) */
        /* table td .btn:hover {
            background-color: rgba(0, 0, 0, 0.03) !important;
        } */

        /* Ensure icon keeps color */
        /* table td .btn i {
            color: inherit;
        } */
    </style>
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles mb-n5">
				<ol class="breadcrumb">
                    @section('titleBar')
                    <span class="ml-2">Swap Offers</span>
                    @endsection
				</ol>
            </div>
            <!-- row -->

            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-9">
                            <a class="btn <?php if($filter == '') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="swap_offers" style="color: white; margin-bottom: 20px;">All</a>
                            <a class="btn <?php if($filter == 'Pending') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="swap_offers?filter=Pending" style="color: white; margin-bottom: 20px;">Pending</a>
                            <a class="btn <?php if($filter == 'Accepted') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="swap_offers?filter=Accepted" style="color: white; margin-bottom: 20px;">Accepted</a>
                            <a class="btn <?php if($filter == 'Rejected') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="swap_offers?filter=Rejected" style="ccolor: white; margin-bottom: 20px;">Rejected</a>
                        </div>
                        @if($adminshare && $adminshare->totalAdminShare)
                        <div class="col-md-3">
    						<div class="widget-stat card bg-success ">
    							<div class="card-body p-2">
    								<div class="media">
    									<span class="mr-1">
    										<i class="flaticon-381-diamond"></i>
    									</span>
    									<div class="media-body text-white text-right">
    										<p class="mb-1">Earning</p>
    										<h3 class="text-white">{{$adminshare->system_currency->code}} {{$adminshare->totalAdminShare}}</h3>
    									</div>
    								</div>
    							</div>
    						</div>
                        </div>
                        @endif
                        {{--  <!-- <span style="float: right;"><span style="float: right;" class="btn btn-primary"> Total Admin Share : <strong>{{$adminshare->system_currency->code}} {{$adminshare->totalAdminShare}} </span></span> -->--}}
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table dt-responsive nowrap display min-w850">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Sender</th>
                                            <th>Send Amount</th>
                                            <th>Receiver</th>
                                            <th>Received Amount</th>
                                            <th>Admin Share</th>
                                            <th>Exchange Rate</th>
                                            <th>Base Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($get_data as $key => $data)
                                        <tr class="odd gradeX">
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->offerCreatedBy->first_name }}</td>
                                            <td>
                                                @if ($data->from_currency) {{ $data->from_currency->code }} @endif
                                                {{ $data->from_amount }}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#offers_model_{{ $key + 1 }}">View All Offers</button>

                                                <div class="modal fade" id="offers_model_{{ $key + 1 }}" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Swap Offers</h5>
                                                                <button type="button" class="close" data-dismiss="modal"><span>×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @if(!count($data->swap_offers_requests)>0)
                                                                <h5>Users not sends requests to get offer</h5>
                                                                @else
                                                                <div class="table-responsive">
                                                                    <table id="example" class="table ">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <th>From User</th>
                                                                                <th>Status</th>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody>
                                                                            @php 
                                                                                    foreach($data->swap_offers_requests as $offer_key => $offers){
                                                                                        $offers_users_data = DB::table('users_customers')->where('users_customers_id',$offers->from_users_customers_id)->first();
                                                                            @endphp
                                                                                        <tr>
                                                                                            <td>{{ $offer_key + 1 }}</td>
                                                                                            <th>{{ $offers_users_data->first_name }}</th>
                                                                                            <th>{{ $offers->status }}</th>
                                                                                        </tr>
                                                                            @php
                                                                                    }
                                                                            @endphp
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger light" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($data->to_currency) {{ $data->to_currency->code }} @endif
                                                {{ $data->to_amount }}
                                            </td>
                                            <td>{{ $data->system_currency->code }} {{ $data->admin_share_amount }} ({{ $data->admin_share }}%)</td>
                                            <td>{{ $data->exchange_rate }}</td>
                                            <td>{{ $data->system_currency->code }} {{ $data->base_amount }}</td>
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
@endsection