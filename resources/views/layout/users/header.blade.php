<div class="top-bar">
    <!-- base currency params -->
    <input type="hidden" id="system_currencies_id" value="" disabled>
    <input type="hidden" id="system_currencies_name" value="" disabled>
    <input type="hidden" id="system_currencies_code" value="" disabled>
    <input type="hidden" id="system_currencies_symbol" value="" disabled>
    <!-- base currency params -->

    <nav class="navbar navbar-expand-lg navbar-light bg-white px-4 py-3">
        <div class="d-flex align-items-center">
            <!-- <i class="fas fa-align-left me-3 fs-4 primary-text">@</i> -->
            <img src="{{ asset('users/assets/images/icons/div.png') }}" alt="" class="img-fluid me-3"  id="menu-toggle">
            <h3 class="fw-bolder m-0">Dashboard</h3>
        </div>
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center flex-row">
            <!-- NOTIFICATIONS START -->
            <li class="nav-item dropdown me-3 me-sm-5">
                <a href="#" class="nav-link d-flex align-items-center" role="button" id="navbarDropdown1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('users/assets/images/icons/notification.png') }}" class="img-fluid" alt="" srcset="" onclick="get_all_notifications()">
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-full bg-primary text-white visually-hidden" id="unread_notification">
                        <span id="unread_notifications"></span>
                        <span class="visually-hidden">unread Notification</span>
                    </span>
                </a>

                <ul class="dropdown-menu position-absolute mt-3 dropdown-menu-end py-0" style="width:410px;" aria-labelledby="navbarDropdown2" id="notification-dropdown">
                    <h6 class="dropdown-item-text font-15 m-0 py-3 border-bottom d-flex justify-content-between align-items-center">
                        Notifications <span class="badge bg-soft-primary badge-pill">2</span>
                    </h6>
                    <div class="notification-menu" id="notifications">
                        <!-- <a href="#" class="dropdown-item py-3">
                            <small class="float-end text-muted ps-2">2 min ago</small>
                            <div class="media d-flex">
                                <div class="media-body align-self-center ms-2 text-truncate">
                                    <h6 class="my-0 fw-normal text-dark">Your order is placed</h6>
                                    <small class="text-muted mb-0">Dummy text of the printing and industry.</small>
                                </div>
                            </div>
                        </a> 
                        <a href="#" class="dropdown-item py-3">
                            <small class="float-end text-muted ps-2">10 min ago</small>
                            <div class="media d-flex">
                                <div class="media-body align-self-center ms-2 text-truncate">
                                    <h6 class="my-0 fw-normal text-dark">Meeting with designers</h6>
                                    <small class="text-muted mb-0">It is a long established fact that a
                                        reader.</small>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="dropdown-item py-3">
                            <small class="float-end text-muted ps-2">40 min ago</small>
                            <div class="media d-flex">
                                <div class="media-body align-self-center ms-2 text-truncate">
                                    <h6 class="my-0 fw-normal text-dark">UX 3 Task complete.</h6>
                                    <small class="text-muted mb-0">Dummy text of the printing.</small>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="dropdown-item py-3">
                            <small class="float-end text-muted ps-2">1 hr ago</small>
                            <div class="media d-flex">
                                <div class="media-body align-self-center ms-2 text-truncate">
                                    <h6 class="my-0 fw-normal text-dark">Your order is placed</h6>
                                    <small class="text-muted mb-0">It is a long established fact that a
                                        reader.</small>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="dropdown-item py-3">
                            <small class="float-end text-muted ps-2">2 hrs ago</small>
                            <div class="media d-flex">
                                <div class="avatar-md bg-soft-primary">
                                    <i class="ti ti-users"></i>
                                </div>
                                <div class="media-body align-self-center ms-2 text-truncate">
                                    <h6 class="my-0 fw-normal text-dark">Payment Successfull</h6>
                                    <small class="text-muted mb-0">Dummy text of the printing.</small>
                                </div>
                            </div>
                        </a> -->
                    </div>
                </ul>
            </li>
            <!-- NOTIFICATIONS END -->

            <!-- MESSAGES START -->
            <li class="nav-item dropdown me-3 me-sm-5">
                <a href="{{ url('/users/message') }}" class="nav-link d-flex align-items-center" role="button" id="unread_messages">
                    <img src="{{ asset('users/assets/images/icons/messages-2.png') }}" class="img-fluid" alt="" srcset="">
                </a>
            </li>
            <!-- MESSAGES END -->
            
            <!-- profile start -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link d-flex align-items-center" role="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="me-4 d-none d-lg-block">
                        <h5 class="sub-heading text-black mb-1 fw-bolder">Hi, {{ session('first_name') .' '. session('last_name') }}</h5>
                        <!-- <span>Hello</span> -->
                    </div> 
                    <img src="" id="user_profile" class="img-fluid rounded-circle border border-1" alt="" srcset="" style="width: 45px !important; height: 45px !important;">
                </a>
                <ul class="dropdown-menu position-absolute dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li class="">
                        <a href="{{ url('/users/logout') }}" class="dropdown-item d-flex  align-items-center">
                            <?/*xml version="1.0"*/ ?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                            <svg width="28px" height="28px" viewBox="0 0 1000 1000" data-name="Layer 2" id="Layer_2" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:none;stroke:#020202;stroke-linecap:round;stroke-miterlimit:10;stroke-width:22px;}.cls-2{fill:#020202;}</style></defs><path class="cls-1" d="M591.61,280.48C693.9,317.86,766.91,416,766.91,531.26c0,147.41-119.5,266.91-266.91,266.91S233.09,678.67,233.09,531.26c0-115.22,73-213.4,175.3-250.78"/><rect class="cls-2" height="160.61" rx="35.92" width="71.84" x="464.08" y="201.83"/></svg>
                            <span class=" d-inline-block">Logout</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- profile end -->
        </ul>
    </nav>
</div>