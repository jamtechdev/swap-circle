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

        .widget-stat .media>span {
            height: 60px;
            width: 60px;
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
                <div class="row">
                    <div class="col-md-9">
                        <a class="btn <?php if ($filter == '') {
                            echo 'btn-primary';
                        } else {
                            echo 'btn-info';
                        } ?>" href="swap_offers"
                            style="color: white; margin-bottom: 20px;">All</a>
                        <a class="btn <?php if ($filter == 'Pending') {
                            echo 'btn-primary';
                        } else {
                            echo 'btn-info';
                        } ?>" href="swap_offers?filter=Pending"
                            style="color: white; margin-bottom: 20px;">Pending</a>
                        <a class="btn <?php if ($filter == 'Approved') {
                            echo 'btn-primary';
                        } else {
                            echo 'btn-info';
                        } ?>" href="swap_offers?filter=Approved"
                            style="color: white; margin-bottom: 20px;">Approved</a>
                        <a class="btn <?php if ($filter == 'Rejected') {
                            echo 'btn-primary';
                        } else {
                            echo 'btn-info';
                        } ?>" href="swap_offers?filter=Rejected"
                            style="ccolor: white; margin-bottom: 20px;">Rejected</a>
                    </div>

                    <div class="col-md-3">
                        <div class="widget-stat card  bg-success ">
                            <div class="card-body p-2">
                                <div class="media">
                                    <span class="mr-1">
                                        <i class="flaticon-381-diamond"></i>
                                    </span>
                                    <div class="media-body text-white text-right">
                                        <p class="mb-1">Earning</p>
                                        <h3 class="text-white">{{ $adminshare->system_currency->code ?? '' }}
                                            {{ $adminshare->totalAdminShare }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Total Admin Share : {{ $adminshare->system_currency->code ?? '' }} {{ $adminshare->totalAdminShare }} --}}
                </div>

                <div class="card transactions_card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table  nowrap display min-w850">
                                <thead>
                                    <tr>
                                        <th>#</th>
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
                                                    <span class="btn btn-info">Pending</span>
                                                @elseif ($data->status == 'Approved')
                                                    <span class="btn btn-success">Approved</span>
                                                @elseif ($data->status == 'Rejected')
                                                    <span class="btn btn-warning">Rejected</span>
                                                @else
                                                    <span class="btn btn-secondary">Unknown</span>
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
