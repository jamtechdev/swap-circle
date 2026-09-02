@extends('layout.admin.list_master')

@section('titleBar')
<span>Dashboard</span>
@endsection

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row g-3 mb-3">
            <div class="col-xl-3 col-md-6">
                <div class="card admin-stat-card admin-stat-card--users">
                    <div class="card-body">
                        <div class="admin-stat-icon">
                            <img src="{{ asset('images/users.svg') }}" alt="">
                        </div>
                        <div>
                            <h2 class="mb-0">{{ $total_users_customers }}</h2>
                            <span class="fs-14">Total Users</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card admin-stat-card admin-stat-card--trxns">
                    <div class="card-body">
                        <div class="admin-stat-icon">
                            <img src="{{ asset('images/total-transactions.svg') }}" alt="">
                        </div>
                        <div>
                            <h2 class="mb-0">{{ $total_transactions }}</h2>
                            <span class="fs-14">Total Transactions</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card admin-stat-card admin-stat-card--offers">
                    <div class="card-body">
                        <div class="admin-stat-icon">
                            <img src="{{ asset('images/total-offers.svg') }}" alt="">
                        </div>
                        <div>
                            <h2 class="mb-0">{{ $total_swap_offers }}</h2>
                            <span class="fs-14">Total Offers</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card admin-stat-card admin-stat-card--api">
                    <div class="card-body">
                        <div class="admin-stat-icon">
                            <img src="{{ asset('images/total-connects.svg') }}" alt="">
                        </div>
                        <div>
                            <h2 class="mb-0 {{ $isApiConnected ? 'text-success' : 'text-danger' }}">
                                {{ $isApiConnected ? 'Connected' : 'Not Connected' }}
                            </h2>
                            <span class="fs-14">Insurtech API</span>
                            <small class="text-muted">{{ $connectionMessage }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card admin-chart-card">
            <div class="card-header border-0 pb-0">
                <div class="d-flex align-items-center">
                    <span class="p-3 mr-3 rounded admin-chart-icon">
                        <i class="fa fa-chart-line" aria-hidden="true" style="color: #c8e635;"></i>
                    </span>
                    <div>
                        <h4 class="fs-20 mb-1">Transactions</h4>
                        <p class="fs-13 mb-0 text-muted">Monthly activity overview</p>
                    </div>
                </div>
            </div>
            <div class="card-body pt-2 pb-3">
                <div id="chartBar"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('vendor/apexchart/apexchart.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var chartEl = document.querySelector('#chartBar');
    if (!chartEl || typeof ApexCharts === 'undefined') {
        return;
    }

    new ApexCharts(chartEl, {
        series: [{
            name: 'Transactions',
            data: [20, 40, 20, 80, 40, 40, 20, 60, 60, 20, 110, 60]
        }],
        chart: {
            height: 320,
            type: 'area',
            toolbar: { show: false },
            zoom: { enabled: false },
            fontFamily: 'DM Sans, sans-serif'
        },
        dataLabels: { enabled: false },
        stroke: {
            width: 3,
            colors: ['#1a472a'],
            curve: 'smooth'
        },
        markers: {
            size: 5,
            strokeWidth: 3,
            strokeColors: ['#c8e635'],
            colors: ['#fff'],
            hover: { size: 7 }
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            labels: {
                style: { colors: '#64748b', fontSize: '12px', fontWeight: 500 }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                offsetX: -8,
                style: { colors: '#64748b', fontSize: '12px', fontWeight: 500 }
            }
        },
        fill: {
            colors: ['#c8e635'],
            type: 'gradient',
            gradient: {
                shadeIntensity: 0.3,
                opacityFrom: 0.35,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        colors: ['#c8e635'],
        grid: {
            borderColor: '#eef2e8',
            strokeDashArray: 4,
            xaxis: { lines: { show: false } }
        },
        tooltip: {
            theme: 'light',
            y: { formatter: function (val) { return val + ' txns'; } }
        }
    }).render();
});
</script>
@endsection
