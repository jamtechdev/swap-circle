@extends('layout.admin.list_master')

@section('titleBar')
<span>Swap Offers</span>
@endsection

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="admin-filter-bar">
            <div class="admin-filter-actions">
                <a class="btn {{ $filter == '' ? 'btn-primary' : 'btn-info' }}" href="{{ url('admin/swap_offers') }}">All</a>
                <a class="btn {{ $filter == 'Pending' ? 'btn-primary' : 'btn-info' }}" href="{{ url('admin/swap_offers?filter=Pending') }}">Pending</a>
                <a class="btn {{ $filter == 'Accepted' ? 'btn-primary' : 'btn-info' }}" href="{{ url('admin/swap_offers?filter=Accepted') }}">Accepted</a>
                <a class="btn {{ $filter == 'Rejected' ? 'btn-primary' : 'btn-info' }}" href="{{ url('admin/swap_offers?filter=Rejected') }}">Rejected</a>
            </div>
            @if($adminshare && $adminshare->totalAdminShare)
            <div class="admin-earnings-card">
                <div class="card admin-stat-card admin-stat-card--earnings mb-0">
                    <div class="card-body">
                        <div class="admin-stat-icon">
                            <i class="fa fa-coins" aria-hidden="true"></i>
                        </div>
                        <div>
                            <span class="fs-14">Total Earnings</span>
                            <h2 class="mb-0">{{ $adminshare->system_currency->code ?? '' }} {{ number_format($adminshare->totalAdminShare, 2) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="card admin-table-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped display w-100">
                        <thead>
                            <tr>
                                <th>Sr No</th>
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
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->offerCreatedBy->first_name }}</td>
                                <td>
                                    @if ($data->from_currency) {{ $data->from_currency->code }} @endif
                                    {{ $data->from_amount }}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#offers_model_{{ $key + 1 }}">View All Offers</button>

                                    <div class="modal fade" id="offers_model_{{ $key + 1 }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Swap Offers</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if(!count($data->swap_offers_requests))
                                                    <p class="mb-0 text-muted">No users have sent requests for this offer yet.</p>
                                                    @else
                                                    <div class="table-responsive">
                                                        <table class="table table-sm mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sr No</th>
                                                                    <th>From User</th>
                                                                    <th>Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($data->swap_offers_requests as $offer_key => $offers)
                                                                    @php
                                                                        $offers_users_data = DB::table('users_customers')->where('users_customers_id', $offers->from_users_customers_id)->first();
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ $offer_key + 1 }}</td>
                                                                        <td>{{ $offers_users_data->first_name ?? '—' }}</td>
                                                                        <td>{{ $offers->status }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
@endsection
