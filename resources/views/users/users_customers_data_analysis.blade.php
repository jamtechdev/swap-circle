@extends('layout.users.master')
@section('content')
        <!-- CONTENT START -->
        <div class="page-content-wrapper">
            <div class="page-content-tab">
                <div class="container-fluid px-4 pb-4">
                    <div class="wallet-wrapper">
                       <!-- CREATE CATEGORIES -->
                        <div class="wallet-tabs">
                            <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
                                <ul class="nav nav-pills me-auto" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-offers" type="button" role="tab" aria-controls="pills-offers" aria-selected="true">All  offers value</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-buying" type="button" role="tab" aria-controls="pills-buying" aria-selected="false">All buying Value</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-transactions" type="button" role="tab" aria-controls="pills-transactions" aria-selected="false">Total transactions</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-average" type="button" role="tab" aria-controls="pills-average" aria-selected="false">Average value</button>
                                    </li>
                                </ul>
                                <div class="d-flex align-items-center ms-auto">
                                    <a class="btn btn-primary rounded-3 px-4 py-2 me-3">Generate Data</a>
                                    <div class="nav-item dropdown ">
                                        <a href="#" class="" role="button" id="navbarDropdown2" data-bs-toggle="dropdown" aria-expanded="false">
                                           <img src="{{ asset('users/assets/images/icons/filter.png') }}" class="img-fluid w-35" alt="" srcset="">
                                        </a>
            
                                        <ul class="dropdown-menu position-absolute  mt-3 dropdown-menu-end" aria-labelledby="navbarDropdown2">
                                            <li><a href="#" class="dropdown-item fw-bold py-2">Today</a></li>
                                            <li><a href="#" class="dropdown-item fw-bold py-2">Week</a></li>
                                            <li><a href="#" class="dropdown-item fw-bold py-2">Month</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" id="pills-tabContent">
                                <!-- ALL OFFERS VALUE START -->
                                <div class="tab-pane fade show active" id="pills-offers" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card border-0 mb-3">
                                                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <div class="wallet-icon me-3 bg-green">
                                                            <img src="{{ asset('users/assets/images/icons/send-1.png') }}" alt="" srcset="">
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-bolder">From James Anderson</p>
                                                            <small class="text-primary">Swap - 2:26pm</small>
                                                        </div>
                                                    </div>
                                                    <small class="text-success me-3">$63.98</small>
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card border-0 mb-3">
                                                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <div class="wallet-icon me-3 bg-green">
                                                            <img src="{{ asset('users/assets/images/icons/send-1.png') }}" alt="" srcset="">
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-bolder">From James Anderson</p>
                                                            <small class="text-primary">Swap - 2:26pm</small>
                                                        </div>
                                                    </div>
                                                    <small class="text-success me-3">$63.98</small>
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card border-0 mb-3">
                                                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <div class="wallet-icon me-3 bg-green">
                                                            <img src="{{ asset('users/assets/images/icons/send-1.png') }}" alt="" srcset="">
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-bolder">From James Anderson</p>
                                                            <small class="text-primary">Swap - 2:26pm</small>
                                                        </div>
                                                    </div>
                                                    <small class="text-success me-3">$63.98</small>
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card border-0 mb-3">
                                                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <div class="wallet-icon me-3 bg-green">
                                                            <img src="{{ asset('users/assets/images/icons/send-1.png') }}" alt="" srcset="">
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-bolder">From James Anderson</p>
                                                            <small class="text-primary">Swap - 2:26pm</small>
                                                        </div>
                                                    </div>
                                                    <small class="text-success me-3">$63.98</small>
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card border-0 mb-3">
                                                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <div class="wallet-icon me-3 bg-green">
                                                            <img src="{{ asset('users/assets/images/icons/send.png') }}" alt="" srcset="">
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-bolder">To James Anderson</p>
                                                            <small class="text-primary">Swap - 2:26pm</small>
                                                        </div>
                                                    </div>
                                                    <small class="text-danger me-3">$63.98</small>
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card border-0 mb-3">
                                                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <div class="wallet-icon me-3 bg-green">
                                                            <img src="{{ asset('users/assets/images/icons/send.png') }}" alt="" srcset="">
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-bolder">To James Anderson</p>
                                                            <small class="text-primary">Swap - 2:26pm</small>
                                                        </div>
                                                    </div>
                                                    <small class="text-danger me-3">$63.98</small>
                                                </div>
                                            </div>                                            
                                        </div>
                                        <!-- cards end -->
                                        <!-- pagination -->
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-3">
                                            <p class="text-black mb-0">Showing 1 to 10 of 57 entries</p>
                                            <div class="pagination ms-auto d-flex justify-content-around flex-wrap" role="group" aria-label="Basic example">
                                                <a href="#" class="btn btn-outline-primary btn-prev">Previous</a>
                                                <a href="#" class="btn btn-outline-primary active">1</a>
                                                <a href="#" class="btn btn-outline-primary">2</a>
                                                <a href="#" class="btn btn-outline-primary">3</a>
                                                <a href="#" class="btn btn-outline-primary">4</a>
                                                <a href="#" class="btn btn-outline-primary">5</a>
                                                <a href="#" class="btn btn-outline-primary btn-next">Next</a>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ALL OFFERS VALUE END -->

                                <!-- ðŸ”¥ ALL BUYING VALUE START -->
                                <div class="tab-pane fade" id="pills-buying" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card border-0 mb-3">
                                                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <div class="wallet-icon me-3 bg-green">
                                                            <img src="{{ asset('users/assets/images/icons/send.png') }}" alt="" srcset="">
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-bolder">To James Anderson</p>
                                                            <small class="text-primary">Swap - 2:26pm</small>
                                                        </div>
                                                    </div>
                                                    <small class="text-danger me-3">$63.98</small>
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card border-0 mb-3">
                                                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <div class="wallet-icon me-3 bg-green">
                                                            <img src="{{ asset('users/assets/images/icons/send.png') }}" alt="" srcset="">
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-bolder">To James Anderson</p>
                                                            <small class="text-primary">Swap - 2:26pm</small>
                                                        </div>
                                                    </div>
                                                    <small class="text-danger me-3">$63.98</small>
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>
                                <!-- ALL BUYING VALUE END -->

                                <!-- TOTAL TRANSACTIONS START -->
                                <div class="tab-pane fade" id="pills-transactions" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                    <div class="row">
                                        <!-- cards start -->
                                        <div class="col-sm-6">
                                            <div class="card border-0 mb-3">
                                                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <div class="wallet-icon me-3 bg-green">
                                                            <img src="{{ asset('users/assets/images/icons/send.png') }}" alt="" srcset="">
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-bolder">To James Anderson</p>
                                                            <small class="text-primary">Swap - 2:26pm</small>
                                                        </div>
                                                    </div>
                                                    <small class="text-danger me-3">$63.98</small>
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card border-0 mb-3">
                                                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <div class="wallet-icon me-3 bg-green">
                                                            <img src="{{ asset('users/assets/images/icons/send.png') }}" alt="" srcset="">
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-bolder">To James Anderson</p>
                                                            <small class="text-primary">Swap - 2:26pm</small>
                                                        </div>
                                                    </div>
                                                    <small class="text-danger me-3">$63.98</small>
                                                </div>
                                            </div>                                            
                                        </div>
                                        <!-- cards end -->
                                    </div>
                                </div>
                                <!-- TOTAL TRANSACTIONS END -->

                                 <!-- AVERAGE VALUE START -->
                                 <div class="tab-pane fade" id="pills-average" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card border-0 mb-3 bg-light p-2">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center justify-content-around flex-wrap mb-4 gap-2">
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
                                                    </div>
                                                    <div class="row flex-wrap">
                                                        <div class="col col-lg-6 mb-3">
                                                            <div class="average-no bg-white p-3 text-center rounded-4">
                                                                <p class="mb-1">Market Name</p>
                                                                <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col col-lg-6 mb-3">
                                                            <div class="average-no bg-white p-3 text-center rounded-4">
                                                                <p class="mb-1">Market Name</p>
                                                                <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col col-lg-6 mb-3">
                                                            <div class="average-no bg-white p-3 text-center rounded-4">
                                                                <p class="mb-1">Market Name</p>
                                                                <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col col-lg-6 mb-3">
                                                            <div class="average-no bg-white p-3 text-center rounded-4">
                                                                <p class="mb-1">Market Name</p>
                                                                <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col col-lg-6 mb-3">
                                                            <div class="average-no bg-white p-3 text-center rounded-4">
                                                                <p class="mb-1">Market Name</p>
                                                                <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col col-lg-6 mb-3">
                                                            <div class="average-no bg-white p-3 text-center rounded-4">
                                                                <p class="mb-1">Market Name</p>
                                                                <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card border-0 mb-3 bg-light py-2">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center justify-content-around flex-wrap mb-4 gap-2">
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
                                                    </div>
                                                    <div class="row flex-wrap">
                                                        <div class="col col-lg-6 mb-3">
                                                            <div class="average-no bg-white p-3 text-center rounded-4">
                                                                <p class="mb-1">Market Name</p>
                                                                <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col col-lg-6 mb-3">
                                                            <div class="average-no bg-white p-3 text-center rounded-4">
                                                                <p class="mb-1">Market Name</p>
                                                                <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col col-lg-6 mb-3">
                                                            <div class="average-no bg-white p-3 text-center rounded-4">
                                                                <p class="mb-1">Market Name</p>
                                                                <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col col-lg-6 mb-3">
                                                            <div class="average-no bg-white p-3 text-center rounded-4">
                                                                <p class="mb-1">Market Name</p>
                                                                <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col col-lg-6 mb-3">
                                                            <div class="average-no bg-white p-3 text-center rounded-4">
                                                                <p class="mb-1">Market Name</p>
                                                                <h4 class="fw-bold mb-0">â‚¦889.00</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col col-lg-6 mb-3">
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
                                        </div>
                                    </div>
                                </div>
                                <!-- AVERAGE VALUE END -->
                            </div>
                        </div> 
                    </div>
                </div>
            </div> 
        </div>  
        <!-- CONTENT END -->
@endsection       