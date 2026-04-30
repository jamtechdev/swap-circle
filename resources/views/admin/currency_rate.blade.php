@extends('layout.admin.list_master')
@section('style')
<link href="{{ asset('users/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('users/assets/css/navbar.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('users/assets/plugin/splide/splide.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('users/assets/css/jquery.ui.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('users/assets/css/style.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('users/assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                @section('titleBar')
                <span class="ml-2">Currency Rate </span>
                @endsection 
                <div class="wallet-wrapper">
                    <div class="wallet-tabs">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card border-0 mb-3 bg-light p-2">
                                    <div class="card-body">
                                        {{-- <div class="d-flex align-items-center justify-content-around flex-wrap mb-4 gap-2">
                                             <div class="text-danger d-flex align-items-center">
                                                <span class="analysis-icon bg-danger me-2">
                                                <img class="img-fluid" src="{{ asset('users/assets/images/icons/mini-icon/send-down-2.png') }}" alt="">
                                                </span> <span>â‚¦889.00</span>
                                            </div>
                                            <div class="text-success d-flex align-items-center">
                                                <span class="analysis-icon bg-primary me-2">
                                                <img class="img-fluid" src="{{ asset('users/assets/images/icons/mini-icon/Send-2.png') }}" alt="">
                                                </span>
                                                <span>â‚¦889.00</span>
                                            </div>
                                            <div class="text-black d-flex align-items-center">
                                                <span class="analysis-icon bg-black me-2">
                                                <img class="img-fluid" src="{{ asset('users/assets/images/icons/mini-icon/account_balance-2.png') }}" alt="">
                                                </span>
                                                <span>â‚¦889.00</span>
                                            </div> 
                                        </div> --}}
                                        <div class="row flex-wrap">
                                            @foreach ($final_data as $item)
                                            <div class="col col-lg-3 mb-3">
                                                <div class="average-no bg-white p-3 text-center rounded-4">
                                                    <p class="mb-1">{{ $item['code'] }}</p>
                                                    <h4 class="fw-bold mb-0">{{ $item['symbol'] }} {{ $item['value'] }}</h4>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                            {{-- <div class="col col-lg-3 mb-3">
                                                <div class="average-no bg-white p-3 text-center rounded-4">
                                                    <p class="mb-1">Market Name</p>
                                                    <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                </div>
                                            </div>
                                            <div class="col col-lg-3 mb-3">
                                                <div class="average-no bg-white p-3 text-center rounded-4">
                                                    <p class="mb-1">Market Name</p>
                                                    <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                </div>
                                            </div>
                                            <div class="col col-lg-3 mb-3">
                                                <div class="average-no bg-white p-3 text-center rounded-4">
                                                    <p class="mb-1">Market Name</p>
                                                    <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                </div>
                                            </div>
                                            <div class="col col-lg-3 mb-3">
                                                <div class="average-no bg-white p-3 text-center rounded-4">
                                                    <p class="mb-1">Market Name</p>
                                                    <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                </div>
                                            </div>
                                            <div class="col col-lg-3 mb-3">
                                                <div class="average-no bg-white p-3 text-center rounded-4">
                                                    <p class="mb-1">Market Name</p>
                                                    <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>                                            
                            </div>
                            {{-- <div class="col-sm-12">
                                <div class="card border-0 mb-3 bg-light py-2">
                                    <div class="card-body">
                                         <div class="d-flex align-items-center justify-content-around flex-wrap mb-4 gap-2">
                                            <div class="text-danger d-flex align-items-center">
                                                <span class="analysis-icon bg-primary me-2">
                                                <img class="img-fluid" src="{{ asset('users/assets/images/icons/mini-icon/send-down-2..png') }}" alt="">
                                                </span>
                                                <span>â‚¦889.00</span>
                                            </div>
                                            <div class="text-success d-flex align-items-center">
                                                <span class="analysis-icon bg-primary me-2">
                                                <img class="img-fluid" src="{{ asset('users/assets/images/icons/mini-icon/Send-2.png') }}" alt="">
                                                </span>
                                                <span>â‚¦889.00</span>
                                            </div>
                                            <div class="text-black d-flex align-items-center">
                                                <span class="analysis-icon bg-black me-2">
                                                <img class="img-fluid" src="{{ asset('users/assets/images/icons/mini-icon/account_balance-2.png') }}" alt="">
                                                </span>
                                                <span>â‚¦889.00</span>
                                            </div>
                                        </div> 
                                        <div class="row flex-wrap">
                                            <div class="col col-lg-3 mb-3">
                                                <div class="average-no bg-white p-3 text-center rounded-4">
                                                    <p class="mb-1">Market Name</p>
                                                    <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                </div>
                                            </div>
                                            <div class="col col-lg-3 mb-3">
                                                <div class="average-no bg-white p-3 text-center rounded-4">
                                                    <p class="mb-1">Market Name</p>
                                                    <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                </div>
                                            </div>
                                            <div class="col col-lg-3 mb-3">
                                                <div class="average-no bg-white p-3 text-center rounded-4">
                                                    <p class="mb-1">Market Name</p>
                                                    <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                </div>
                                            </div>
                                            <div class="col col-lg-3 mb-3">
                                                <div class="average-no bg-white p-3 text-center rounded-4">
                                                    <p class="mb-1">Market Name</p>
                                                    <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                </div>
                                            </div>
                                            <div class="col col-lg-3 mb-3">
                                                <div class="average-no bg-white p-3 text-center rounded-4">
                                                    <p class="mb-1">Market Name</p>
                                                    <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                </div>
                                            </div>
                                            <div class="col col-lg-3 mb-3">
                                                <div class="average-no bg-white p-3 text-center rounded-4">
                                                    <p class="mb-1">Market Name</p>
                                                    <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                            
                            </div>
                                <!-- pagination -->
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <p class="text-black mb-0">Showing 1 to 10 of 57 entries</p>
                                <div class="pagination ms-auto d-flex justify-content-around flex-wrap" role="group" aria-label="Basic example">
                                    <a href="#" class="btn btn-outline-primary">Previous</a>
                                    <a href="#" class="btn btn-outline-primary active">1</a>
                                    <a href="#" class="btn btn-outline-primary">2</a>
                                    <a href="#" class="btn btn-outline-primary">3</a>
                                    <a href="#" class="btn btn-outline-primary">4</a>
                                    <a href="#" class="btn btn-outline-primary">5</a>
                                    <a href="#" class="btn btn-outline-primary">Next</a>
                                    </div>
                            </div> --}}
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div> 

    <script src="{{ asset('users/assets/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('users/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('users/assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('users/assets/js/jquery.ui.min.js') }}"></script>
    <script src="{{ asset('users/assets/js/jquery.additional.methods.js') }}"></script>

@endsection