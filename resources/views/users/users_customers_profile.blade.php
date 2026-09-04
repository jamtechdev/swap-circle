@extends('layout.users.master')

@section('page_title', 'Profile')
@section('page_subtitle', 'Manage your account and preferences')

@section('content')
    @php
        $fullName = trim(session('first_name') . ' ' . session('last_name'));
        $initials = strtoupper(substr(session('first_name') ?? 'U', 0, 1) . substr(session('last_name') ?? '', 0, 1));
    @endphp

    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="portal-profile-panel">
                    <div class="portal-profile-head">
                        <div class="portal-profile-head__text">
                            <span class="portal-profile-head__label">Your account</span>
                            <p class="portal-profile-head__lead">Manage profile, payments, and preferences</p>
                        </div>
                        <a href="{{ url('/users/profile_edit') }}" class="portal-profile-edit">Edit profile</a>
                    </div>

                    <div class="portal-profile-grid">
                        <div class="portal-profile-hero">
                            <div class="portal-profile-avatar">
                                <img src="" id="profile_pic" class="portal-profile-avatar__image d-none" alt="{{ $fullName }}" onerror="this.classList.add('d-none');this.nextElementSibling.classList.remove('d-none');">
                                <span class="portal-profile-avatar__initial">{{ $initials }}</span>
                            </div>
                            <div class="portal-profile-hero__body">
                                <h2 class="portal-profile-hero__name">{{ $fullName }}</h2>
                                <p class="portal-profile-hero__email">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="1.5"/><path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    {{ session('email') }}
                                </p>
                            </div>
                        </div>

                        <div class="portal-profile-card portal-profile-card--static">
                            <div class="portal-profile-card__icon portal-profile-card__icon--gift">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 12v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8M12 22V12M12 12H7.8a2 2 0 0 1-1.98-1.72L4.5 4H2M12 12h4.2a2 2 0 0 0 1.98-1.72L19.5 4H22M12 7V4M8 7V4M16 7V4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div class="portal-profile-card__content">
                                <h3 class="portal-profile-card__title">Referral code</h3>
                                <p class="portal-profile-card__desc">Share with friends and get $20 of free stocks</p>
                            </div>
                        </div>

                        <button type="button" class="portal-profile-card" data-bs-toggle="modal" data-bs-target="#mdl_feedback">
                            <div class="portal-profile-card__icon">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 8a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v5a4 4 0 0 1-4 4h-2.5L9 21v-4H8a4 4 0 0 1-4-4V8Z" stroke="currentColor" stroke-width="1.75" stroke-linejoin="round"/><path d="M8 10h8M8 13h5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/></svg>
                            </div>
                            <div class="portal-profile-card__content">
                                <h3 class="portal-profile-card__title">Feedback</h3>
                                <p class="portal-profile-card__desc">We'd love to hear your thoughts</p>
                            </div>
                            <span class="portal-profile-card__chevron" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        </button>

                        <a href="{{ url('/users/billing_payment') }}" class="portal-profile-card">
                            <div class="portal-profile-card__icon">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="6" width="18" height="13" rx="2" stroke="currentColor" stroke-width="1.75"/><path d="M3 10h18M7 15h4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/></svg>
                            </div>
                            <div class="portal-profile-card__content">
                                <h3 class="portal-profile-card__title">Bank accounts</h3>
                                <p class="portal-profile-card__desc">Manage linked payout bank accounts</p>
                            </div>
                            <span class="portal-profile-card__chevron" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        </a>

                        <a href="{{ url('/users/transactions') }}" class="portal-profile-card">
                            <div class="portal-profile-card__icon">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 7h10M7 12h10M7 17h6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/><rect x="4" y="3" width="16" height="18" rx="2" stroke="currentColor" stroke-width="1.75"/></svg>
                            </div>
                            <div class="portal-profile-card__content">
                                <h3 class="portal-profile-card__title">Transactions</h3>
                            </div>
                            <span class="portal-profile-card__chevron" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        </a>

                        <div class="portal-profile-card portal-profile-card--static">
                            <div class="portal-profile-card__icon">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.75"/><path d="M3 12h18M12 3c2.2 2.5 2.2 13.5 0 18M12 3c-2.2 2.5-2.2 13.5 0 18" stroke="currentColor" stroke-width="1.75"/></svg>
                            </div>
                            <div class="portal-profile-card__content">
                                <h3 class="portal-profile-card__title">Language</h3>
                                <p class="portal-profile-card__desc">English</p>
                            </div>
                        </div>

                        <a href="{{ url('/users/settings') }}" class="portal-profile-card">
                            <div class="portal-profile-card__icon">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="1.75"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div class="portal-profile-card__content">
                                <h3 class="portal-profile-card__title">Settings</h3>
                            </div>
                            <span class="portal-profile-card__chevron" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        </a>

                        <a href="{{ url('/users/faqs') }}" class="portal-profile-card">
                            <div class="portal-profile-card__icon">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.75"/><path d="M9.5 9.5a2.5 2.5 0 0 1 4.2 1.8c0 1.8-2.2 2.2-2.2 3.7M12 17h.01" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/></svg>
                            </div>
                            <div class="portal-profile-card__content">
                                <h3 class="portal-profile-card__title">FAQs</h3>
                            </div>
                            <span class="portal-profile-card__chevron" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        </a>

                        <button type="button" class="portal-profile-card portal-profile-card--danger" data-bs-toggle="modal" data-bs-target="#mdl_delete_account">
                            <div class="portal-profile-card__icon portal-profile-card__icon--danger">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2M10 11v6M14 11v6M6 7l1 13a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1l1-13" stroke="#ef4444" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div class="portal-profile-card__content">
                                <h3 class="portal-profile-card__title">Delete account</h3>
                            </div>
                            <span class="portal-profile-card__chevron" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Feedback modal --}}
    <div class="modal fade modal-lg" id="mdl_feedback" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content portal-profile-modal">
                <div class="modal-body p-4 p-md-5">
                    <div class="d-flex align-items-center mb-4">
                        <button type="button" class="portal-profile-modal__back" data-bs-dismiss="modal" aria-label="Close">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <h2 class="portal-profile-modal__title flex-grow-1 text-center mb-0">Feedback</h2>
                        <span class="portal-profile-modal__spacer" aria-hidden="true"></span>
                    </div>
                    <form id="frm_feedback">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label mb-2">Name</label>
                                    <input type="text" name="fb_name" id="fb_name" placeholder="Enter name" class="form-control">
                                    <span class="error_msg" id="error_fb_name"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label mb-2">Email</label>
                                    <input type="email" name="fb_email" id="fb_email" placeholder="Email address" class="form-control">
                                    <span class="error_msg" id="error_fb_email"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-4">
                                    <label class="form-label mb-2">Subject</label>
                                    <textarea name="fb_subject" id="fb_subject" rows="5" placeholder="Enter here" class="form-control"></textarea>
                                    <span class="error_msg" id="error_fb_subject"></span>
                                </div>
                            </div>
                        </div>
                        <div class="portal-modal-actions">
                            <button type="submit" class="btn btn-login btn-primary portal-profile-edit-save">Send feedback</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete account modal --}}
    <div class="modal fade" id="mdl_delete_account" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content portal-profile-modal">
                <div class="modal-body p-4 p-md-5">
                    <div class="d-flex align-items-center mb-4">
                        <button type="button" class="portal-profile-modal__back" data-bs-dismiss="modal" aria-label="Close">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <h2 class="portal-profile-modal__title flex-grow-1 text-center mb-0">Delete account</h2>
                        <span class="portal-profile-modal__spacer" aria-hidden="true"></span>
                    </div>
                    <form id="frm_delete_account">
                        @csrf
                        <h5 class="fw-bold mb-3">Why do you want to delete your Swap Circle account?</h5>
                        <div class="form-group mb-4">
                            <label class="form-label mb-2">Delete reason</label>
                            <input type="text" name="delete_reason" id="delete_reason" placeholder="Enter delete reason" class="form-control">
                            <span class="error_msg" id="error_delete_reason"></span>
                        </div>
                        <div class="form-group mb-4">
                            <label class="form-label mb-2">Comments</label>
                            <textarea name="comments" id="comments" rows="5" placeholder="Please provide additional information here..." class="form-control"></textarea>
                            <span class="error_msg" id="error_comments"></span>
                        </div>
                        <div class="portal-modal-actions">
                            <button type="submit" class="btn btn-login btn-primary w-100 portal-profile-delete-btn">Delete account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            get_profile_pic();
        });
    </script>
@endsection
