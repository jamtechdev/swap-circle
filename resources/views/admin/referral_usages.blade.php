@extends('layout.admin.list_master')
@section('content')
<style>
    table.dataTable tbody td { font-size: 14px; padding: 12px 15px; }
    table.dataTable thead th { font-size: 14px; padding: 12px 15px; }
    .content-body .container-fluid { padding-top: 20px; }
    .card { margin-bottom: 0px; }
    .card .card-body { padding: 1.875rem 1.875rem 0rem 1.875rem; }
</style>
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles mb-n5">
            <ol class="breadcrumb">
                @section('titleBar')
                <span class="ml-2">Referral Usages</span>
                @endsection
            </ol>
        </div>

        <div class="row mb-3 mt-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <span class="text-muted">Total: <strong>{{ $total }}</strong></span>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if(isset($partnerConnection) && $partnerConnection)
                            @if(!$swapStatus || !$swapStatus->is_swapped)
                                <button class="btn btn-success float-right" id="swapBtn" style="margin-bottom:10px;"><i class="fa fa-exchange"></i> Swap Referrals</button>
                            @else
                                <button class="btn btn-warning float-right mr-2" id="refreshSwapBtn" style="margin-bottom:10px;"><i class="fa fa-refresh"></i> Refresh Swap</button>
                                <button class="btn btn-danger float-right mr-2" id="unswapBtn" style="margin-bottom:10px;"><i class="fa fa-times"></i> Unswap Referrals</button>
                            @endif
                        @endif
                        <div id="swapMsg" class="mb-2"></div>
                        <div class="table-responsive">
                            <table id="example" class="table dt-responsive nowrap display min-w850">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Referrer</th>
                                        <th>Referrer Email</th>
                                        <th>Refer Code</th>
                                        <th>Used By</th>
                                        <th>Used By Email</th>
                                        <th>Date Used</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($usages as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($item->referrer_pic)
                                                    <img src="{{ asset($item->referrer_pic) }}" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;">
                                                @endif
                                                {{ $item->referrer_first_name }} {{ $item->referrer_last_name }}
                                            </div>
                                        </td>
                                        <td>{{ $item->referrer_email }}</td>
                                        <td><span class="badge badge-success">{{ $item->refer_code }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($item->used_by_pic)
                                                    <img src="{{ asset($item->used_by_pic) }}" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;">
                                                @endif
                                                {{ $item->used_by_first_name }} {{ $item->used_by_last_name }}
                                            </div>
                                        </td>
                                        <td>{{ $item->used_by_email }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->date_used)->format('d M Y, h:i A') }}</td>
                                    </tr>
                                    @endforeach
                                    @if($usages->isEmpty())
                                    <tr><td colspan="7" class="text-center text-muted py-4">No referral usages found.</td></tr>
                                    @endif
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

@section('scripts')
<script>
$(document).ready(function() {
    function doSwap(url, btnId, msg) {
        $(btnId).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Please wait...');
        $('#swapMsg').html('');
        $.ajax({
            url: url, type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                if (res.status === 'success') {
                    $('#swapMsg').html('<div class="alert alert-success">' + res.message + '</div>');
                    setTimeout(function() { location.reload(); }, 1500);
                } else {
                    $('#swapMsg').html('<div class="alert alert-danger">' + res.message + '</div>');
                    $(btnId).prop('disabled', false).html(msg);
                }
            },
            error: function() {
                $('#swapMsg').html('<div class="alert alert-danger">Something went wrong.</div>');
                $(btnId).prop('disabled', false).html(msg);
            }
        });
    }
    $('#swapBtn').click(function() { doSwap('{{ url("admin/swap_referral_usages") }}', '#swapBtn', '<i class="fa fa-exchange"></i> Swap Referrals'); });
    $('#refreshSwapBtn').click(function() { doSwap('{{ url("admin/swap_referral_usages") }}', '#refreshSwapBtn', '<i class="fa fa-refresh"></i> Refresh Swap'); });
    $('#unswapBtn').click(function() {
        if (!confirm('Are you sure?')) return;
        doSwap('{{ url("admin/unswap_referral_usages") }}', '#unswapBtn', '<i class="fa fa-times"></i> Unswap Referrals');
    });
});
</script>
@endsection
