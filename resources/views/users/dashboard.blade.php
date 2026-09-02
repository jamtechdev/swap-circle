@extends('layout.users.master')

@section('page_title', 'Home')
@section('page_subtitle')
    @if($hasPurchases ?? true)
        Your purchased products and activity
    @else
        Your Swap Circle home dashboard
    @endif
@endsection

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="portal-welcome mb-4">
                    <h2>Welcome back, {{ session('first_name') ?: 'there' }} 👋</h2>
                    @if($hasPurchases ?? true)
                        <p>Your active products are listed below. Claims open {{ $claimWaitingDays }} days after purchase.</p>
                    @else
                        <p>Get started by browsing our marketplace. Once you purchase a product, it will appear here on your home dashboard.</p>
                    @endif
                </div>

                @if(!($hasPurchases ?? true))
                    <div class="portal-home-onboarding mb-4">
                        <div class="portal-home-onboarding__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><path d="M3.3 7.7L12 12.5l8.7-4.8M12 22.5V12.5"/></svg>
                        </div>
                        <div class="portal-home-onboarding__body">
                            <h3>No products yet</h3>
                            <p>Explore health, protection and service products built for diaspora families. Your purchases and claim eligibility will show up here.</p>
                            <a href="{{ url('/users/products') }}" class="btn btn-primary">Browse products</a>
                        </div>
                    </div>
                @else
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-xl-4">
                        <div class="portal-stat-card d-flex align-items-center gap-3">
                            <div class="stat-icon">📦</div>
                            <div>
                                <div class="stat-value">{{ $ownedProductsCount }}</div>
                                <div class="stat-label">Your Products</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-4">
                        <div class="portal-stat-card d-flex align-items-center gap-3">
                            <div class="stat-icon">🛡️</div>
                            <div>
                                <div class="stat-value">{{ $claimWaitingDays }}</div>
                                <div class="stat-label">Claim Wait (days)</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-4">
                        <div class="portal-stat-card d-flex align-items-center gap-3">
                            <div class="stat-icon">➕</div>
                            <div>
                                <a href="{{ url('/users/products') }}" class="btn btn-sm btn-outline-primary mt-1">Browse more products</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wallet-wrapper">
                    <div class="wallet-tabs mt-0">
                        <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-transactions" type="button" role="tab" aria-controls="pills-transactions" aria-selected="true">My Products</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-offers" type="button" role="tab" aria-controls="pills-offers" aria-selected="false">Forex Transactions</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-transactions" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="portal-card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                                            <h4>Your Purchased Products</h4>
                                            <a href="{{ url('/users/claims') }}" class="btn btn-sm btn-outline-primary">Submit a claim</a>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="example" class="table dt-responsive nowrap display min-w850 mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Product</th>
                                                        <th>Type</th>
                                                        <th>Purchased</th>
                                                        <th>Cover Period</th>
                                                        <th>Claim Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($products_purchases as $key => $item)
                                                        @php
                                                            $eligible = $item->claim_eligibility['eligible'] ?? false;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>
                                                                <a href="{{ url('/users/product/view/'.$item->products_id) }}" class="text-decoration-none">
                                                                    {{ $item->product->name ?? 'Product' }}
                                                                </a>
                                                            </td>
                                                            <td><span class="portal-badge portal-badge-type">{{ $item->product->type ?? 'A' }}</span></td>
                                                            <td>{{ \Carbon\Carbon::parse($item->date_added)->format('d M Y') }}</td>
                                                            <td>
                                                                @if($item->cover_start_date && $item->cover_end_date)
                                                                    {{ \Carbon\Carbon::parse($item->cover_start_date)->format('d M Y') }}
                                                                    –
                                                                    {{ \Carbon\Carbon::parse($item->cover_end_date)->format('d M Y') }}
                                                                @else
                                                                    <span class="text-muted">—</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($eligible)
                                                                    <span class="portal-badge portal-badge-active">Eligible</span>
                                                                @else
                                                                    <span class="portal-badge portal-badge-pending">{{ $item->claim_eligibility['reason'] ?? 'Not eligible yet' }}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ url('/users/product/view/'.$item->products_id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center text-muted py-5">No purchased products yet.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-offers" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="portal-card-header">
                                            <h4>Forex Transactions</h4>
                                        </div>
                                        <div class="row" id="transactions"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var isFirstView = localStorage.getItem('isFirstView') || '';
        if (isFirstView !== 'Yes') {
            localStorage.setItem('isFirstView', 'Yes');
        }

        @if($hasPurchases ?? true)
        $(document).ready(function() {
            get_transactions();
        });
        @endif
    </script>
@endsection
