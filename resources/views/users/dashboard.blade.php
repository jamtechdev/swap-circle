@extends('layout.users.master')
@section('content') 
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="wallet-wrapper">
                    <div class="wallet-tabs mt-0">
                        <ul class="nav nav-pills mb-4 mx-auto" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                              <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-transactions" type="button" role="tab" aria-controls="pills-transactions" aria-selected="true">Products</button>
                            </li>
                            <li class="nav-item" role="presentation">
                              <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-offers" type="button" role="tab" aria-controls="pills-offers" aria-selected="false">Forex Transactions</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <!-- transactions start -->
                            <div class="tab-pane fade show active" id="pills-transactions" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                   <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                  <table id="example" class="table dt-responsive nowrap display min-w850">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Product</th>
                                                                <th>Type</th>
                                                                <th>Price</th>
                                                                <th>Status</th>
                                                                <th>Date Added</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($products as $key => $item)
                                                            <tr class="odd gradeX">
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>
                                                                    <a href="{{ url('/users/product/view/'.$item->products_id) }}" class="text-decoration-none">
                                                                        {{ $item->name }}
                                                                    </a>
                                                                </td>
                                                                <td>{{ $item->type ?? 'A' }}</td>
                                                                <td>
                                                                    @php
                                                                        $displayPrice = $item->custom_price ?? $item->price ?? null;
                                                                        $currencySymbol = $item->currency_symbol ?? '₦';
                                                                    @endphp
                                                                    @if($displayPrice !== null && $displayPrice !== '')
                                                                        {{ $currencySymbol }}{{ number_format((float) $displayPrice, 2) }}
                                                                    @else
                                                                        <span class="text-muted">Price not set</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $item->status }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($item->date_added)->format('d-m-Y') }}</td>
                                                                <td>
                                                                    <a href="{{ url('/users/product/view/'.$item->products_id) }}" class="btn btn-sm btn-outline-primary">View</a>
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
                            <!-- transactions end -->

                            <!-- transactions start -->
                            <div class="tab-pane fade" id="pills-offers" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                <div class="row" id="transactions"></div>
                            </div>
                            <!-- transactions end -->
                        </div>
                    </div> 
                </div>
            </div>
        </div>  
    </div>
@endsection
@section('script') 
    <script>
        /* --------------- HANDLE LOGIN TOASTER --------------- */
        var isFirstView = localStorage.getItem('isFirstView') || '';
        if (isFirstView !== 'Yes') {
            /* show message to use as this is first view. */
            localStorage.setItem('isFirstView', 'Yes');
        }
        /* --------------- HANDLE LOGIN TOASTER --------------- */

        $(document).ready(function() {
            get_transactions();
        });
    </script>
@endsection