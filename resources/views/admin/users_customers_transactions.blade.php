@extends('layout.admin.list_master')
@section('content')
    <style>
        .btn-light {
            padding-left: 10px;
        }


        table.dataTable tbody td {
            font-size: 14px;
            padding: 12px 15px;
        }

        table.dataTable thead th {
            font-size: 14px;
            padding: 12px 15px;
        }

        table tbody tr td .btn {
            padding: 0.500rem 1.5rem;
            font-size: 14px;
        }

        .content-body .container-fluid {
            padding-top: 20px;
        }

        .container-fluid .row .btn {
            padding: 0.500rem 1.5rem;
        }

        .dataTables_length label,
        .dataTables_filter label {
            font-size: 14px;
            margin-bottom: 0px;
        }

        .transactions_card {
            margin-bottom: 0px;
            height: calc(91% - 30px);
        }

        .card .card-body {
            padding: 1.875rem 1.875rem 0rem 1.875rem;
        }

        .dataTables_wrapper:after {
            display: none;
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
                <div class="admin-filter-bar">
                    <div class="admin-filter-actions">
                        <a class="btn {{ $filter == '' ? 'btn-primary' : 'btn-info' }}" href="{{ url('admin/swap_offers') }}">All</a>
                        <a class="btn {{ $filter == 'Pending' ? 'btn-primary' : 'btn-info' }}" href="{{ url('admin/swap_offers?filter=Pending') }}">Pending</a>
                        <a class="btn {{ $filter == 'Approved' ? 'btn-primary' : 'btn-info' }}" href="{{ url('admin/swap_offers?filter=Approved') }}">Approved</a>
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

                <div class="card transactions_card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table  nowrap display min-w850">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Sender</th>
                                        <th>Send Amount</th>
                                        <th>Receiver</th>
                                        <th>Receive Amount</th>
                                        <th>Payment Method</th>
                                        <th>Admin Share</th>
                                        <th>System Country</th>
                                        <th>Base Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($get_data as $key => $data)
                                        <tr class="odd gradeX">
                                            <td>{{ $key + 1 }}</td>

                                            {{-- Sender --}}
                                            <td>
                                                @if ($data->sender)
                                                    {{ $data->sender->first_name }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>

                                            {{-- Send Amount --}}
                                            <td>
                                                @if ($data->sender_currency)
                                                    {{ $data->sender_currency->code }}
                                                @endif
                                                {{ $data->from_amount ?? '0' }}
                                            </td>

                                            {{-- Receiver --}}
                                            <td>
                                                @if ($data->receiver)
                                                    {{ $data->receiver->first_name }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>

                                            {{-- Receive Amount --}}
                                            <td>
                                                @if ($data->receiver_currency)
                                                    {{ $data->receiver_currency->code }}
                                                @endif
                                                {{ $data->to_amount ?? '0' }}
                                            </td>

                                            {{-- Payment Method --}}
                                            <td>
                                                @if ($data->payment_method)
                                                    {{ $data->payment_method->name }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>

                                            {{-- Admin Share --}}
                                            <td>
                                                @if ($data->system_currency)
                                                    {{ $data->system_currency->code }}
                                                @endif
                                                {{ $data->admin_share_amount ?? '0' }}
                                                ({{ $data->admin_share ?? '0' }}%)
                                            </td>

                                            {{-- System Country --}}
                                            <td>
                                                @if ($data->system_country)
                                                    {{ $data->system_country->name }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>

                                            {{-- Base Amount --}}
                                            <td>
                                                @if ($data->system_currency)
                                                    {{ $data->system_currency->code }}
                                                @endif
                                                {{ $data->base_amount ?? '0' }}
                                            </td>

                                            {{-- Status --}}
                                            <td>
                                                @if ($data->status == 'Pending')
                                                    <span class="admin-status-badge admin-status-badge--pending">Pending</span>
                                                @elseif ($data->status == 'Approved')
                                                    <span class="admin-status-badge admin-status-badge--approved">Approved</span>
                                                @elseif ($data->status == 'Rejected')
                                                    <span class="admin-status-badge admin-status-badge--rejected">Rejected</span>
                                                @else
                                                    <span class="admin-status-badge admin-status-badge--unknown">Unknown</span>
                                                @endif
                                            </td>

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
