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
            <img src="{{ asset('users/assets/images/icons/div.png') }}" alt="" class="img-fluid me-3" id="menu-toggle">
            <div>
                <h3 class="portal-page-title fw-bolder m-0">@yield('page_title', 'Dashboard')</h3>
                <p class="portal-page-subtitle d-none d-md-block mb-0 mt-1">@yield('page_subtitle', 'Manage your Swap Circle account')</p>
            </div>
        </div>
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center flex-row gap-2">
            <!-- NOTIFICATIONS START -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link p-0" role="button" id="navbarDropdown1" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="portal-header-icon position-relative" onclick="get_all_notifications()">
                        <img src="{{ asset('users/assets/images/icons/notification.png') }}" alt="Notifications">
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white visually-hidden" id="unread_notification" style="font-size:10px;">
                            <span id="unread_notifications"></span>
                        </span>
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
            <li class="nav-item">
                <a href="{{ url('/users/message') }}" class="nav-link p-0" id="unread_messages">
                    <span class="portal-header-icon">
                        <img src="{{ asset('users/assets/images/icons/messages-2.png') }}" alt="Messages">
                    </span>
                </a>
            </li>
            <!-- MESSAGES END -->

            <!-- profile start -->
            @php
                $headerName = trim(session('first_name') . ' ' . session('last_name')) ?: 'User';
                $headerInitials = collect(preg_split('/\s+/', trim($headerName)) ?: [])
                    ->filter()
                    ->take(2)
                    ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                    ->join('') ?: 'U';
            @endphp
            <li class="nav-item dropdown ms-1">
                <a href="#" class="nav-link p-0 portal-user-chip" role="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
                    <span class="portal-user-chip__text d-none d-md-flex">
                        <span class="portal-user-chip__greeting">Hi,</span>
                        <span class="portal-user-chip__name" title="{{ $headerName }}">{{ $headerName }}</span>
                    </span>
                    <span class="portal-header-avatar">
                        <img src="" id="user_profile" class="d-none" alt="" onerror="this.classList.add('d-none');document.getElementById('user_profile_initial').classList.add('is-visible');">
                        <span class="portal-header-avatar__initial is-visible" id="user_profile_initial">{{ $headerInitials }}</span>
                    </span>
                    <span class="portal-user-chip__chevron" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25"><path d="M6 9l6 6 6-6"/></svg>
                    </span>
                </a>
                <ul class="dropdown-menu portal-user-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li class="portal-user-menu__head">
                        <span class="portal-user-menu__avatar">{{ $headerInitials }}</span>
                        <div class="portal-user-menu__meta">
                            <strong title="{{ $headerName }}">{{ $headerName }}</strong>
                            <span>{{ session('email') }}</span>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <a href="{{ url('/users/profile') }}" class="dropdown-item portal-user-menu__item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            My profile
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/users/logout') }}" class="dropdown-item portal-user-menu__item portal-user-menu__item--danger">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/></svg>
                            Logout
                        </a>
                    </li>
                </ul>
            </li>
            <!-- profile end -->
        </ul>
    </nav>
</div>