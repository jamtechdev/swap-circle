@extends('layout.admin.list_master')

@section('titleBar')
<span>Currency Rate</span>
@endsection

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="admin-currency-head">
            <div>
                <p class="admin-currency-head__label">Live exchange rates</p>
                <h2 class="admin-currency-head__title">Base currency: {{ $baseCode }} @if($system_currency && $system_currency->symbol)<span class="admin-currency-head__symbol">({{ $system_currency->symbol }})</span>@endif</h2>
                <p class="admin-currency-head__meta">Source: {{ $rateSource }} · Updated {{ $fetchedAt }}</p>
            </div>
            <a href="{{ url('admin/rate_api') }}" class="btn btn-outline-primary btn-sm">Manage Rate APIs</a>
        </div>

        @if(!empty($fetchError))
            <div class="alert alert-warning admin-currency-alert" role="alert">
                {{ $fetchError }}
            </div>
        @endif

        @if(empty($final_data) && empty($fetchError))
            <div class="card admin-table-card">
                <div class="card-body admin-currency-empty">
                    <p class="mb-2">No currency rates are available right now.</p>
                    <p class="text-muted mb-0">Ensure at least one active currency is configured in system settings.</p>
                </div>
            </div>
        @else
            <div class="row g-3 admin-currency-grid">
                @foreach($final_data as $item)
                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="admin-currency-card">
                            <div class="admin-currency-card__top">
                                <span class="admin-currency-card__code">{{ $item['code'] }}</span>
                                @if(!empty($item['symbol']))
                                    <span class="admin-currency-card__symbol">{{ $item['symbol'] }}</span>
                                @endif
                            </div>
                            <p class="admin-currency-card__name">{{ $item['name'] }}</p>
                            <p class="admin-currency-card__rate">{{ $item['value'] }}</p>
                            <p class="admin-currency-card__hint">1 {{ $baseCode }} = {{ $item['value'] }} {{ $item['code'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
