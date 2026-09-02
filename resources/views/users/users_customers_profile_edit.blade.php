@extends('layout.users.master')

@section('page_title', 'Edit Profile')
@section('page_subtitle', 'Update your photo and password')

@section('content')
    @php
        $fullName = trim(session('first_name') . ' ' . session('last_name'));
        $initials = strtoupper(substr(session('first_name') ?? 'U', 0, 1) . substr(session('last_name') ?? '', 0, 1));
    @endphp

    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="portal-profile-edit-panel">
                    <div class="portal-profile-edit-top">
                        <nav class="portal-profile-breadcrumb" aria-label="Breadcrumb">
                            <ol class="portal-profile-breadcrumb__list">
                                <li class="portal-profile-breadcrumb__item">
                                    <a href="{{ url('/users/profile') }}">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        Profile
                                    </a>
                                </li>
                                <li class="portal-profile-breadcrumb__sep" aria-hidden="true">/</li>
                                <li class="portal-profile-breadcrumb__item portal-profile-breadcrumb__item--active" aria-current="page">Edit profile</li>
                            </ol>
                        </nav>
                        <a href="{{ url('/users/profile') }}" class="portal-profile-edit-back">Back to profile</a>
                    </div>

                    <div class="portal-profile-edit-intro">
                        <div class="portal-profile-edit-intro__icon" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="1.75"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c.26.604.852.997 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <span class="portal-profile-edit-intro__label">Account settings</span>
                            <h1 class="portal-profile-edit-intro__title">Personalize your profile</h1>
                            <p class="portal-profile-edit-intro__text">Update your photo and keep your password secure.</p>
                        </div>
                    </div>

                    <div class="portal-profile-edit-grid">
                        <section class="portal-profile-edit-card portal-profile-edit-card--photo">
                            <div class="portal-profile-edit-section__head">
                                <div class="portal-profile-edit-section__icon" aria-hidden="true">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="1.75"/><path d="M3 16.8V18a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-1.2M16 8l1.5-2h3L21 8" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/></svg>
                                </div>
                                <div>
                                    <h2 class="portal-profile-edit-section__title">Profile photo</h2>
                                    <p class="portal-profile-edit-section__hint">JPG or PNG · max 5 MB</p>
                                </div>
                            </div>

                            <div class="control-group file-upload portal-profile-edit-upload" id="file-upload1">
                                <div class="portal-profile-edit-avatar-wrap">
                                    <div class="edit-image-box portal-profile-edit-avatar">
                                        <img src="" id="edit_profile_pic" class="portal-profile-edit-avatar__img d-none" alt="{{ $fullName }}" onerror="this.classList.add('d-none');this.nextElementSibling.classList.remove('d-none');">
                                        <span class="portal-profile-avatar__initial">{{ $initials }}</span>
                                        <span class="portal-profile-edit-avatar__overlay">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="#fff" stroke-width="1.75"/><path d="M3 16.8V18a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-1.2M16 8l1.5-2h3L21 8" stroke="#fff" stroke-width="1.75" stroke-linecap="round"/></svg>
                                            <span>Change photo</span>
                                        </span>
                                        <span class="upload-image portal-profile-edit-avatar__badge" aria-hidden="true">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="#1a472a" stroke-width="2.25" stroke-linecap="round"/></svg>
                                        </span>
                                    </div>
                                </div>
                                <div class="controls d-none">
                                    <input type="file" accept="image/png, image/jpg, image/jpeg" name="contact_image_1" onchange="update_profile_pic(this)">
                                </div>
                            </div>

                            <div class="portal-profile-edit-user">
                                <p class="portal-profile-edit-user__name">{{ $fullName }}</p>
                                <p class="portal-profile-edit-user__email">{{ session('email') }}</p>
                            </div>

                            <ul class="portal-profile-edit-tips">
                                <li>Square photos work best</li>
                                <li>Your photo appears in messages and your profile</li>
                            </ul>
                        </section>

                        <section class="portal-profile-edit-card portal-profile-edit-card--password">
                            <div class="portal-profile-edit-section__head">
                                <div class="portal-profile-edit-section__icon portal-profile-edit-section__icon--shield" aria-hidden="true">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 3 20 7v6c0 5-3.5 8.5-8 9-4.5-.5-8-4-8-9V7l8-4Z" stroke="currentColor" stroke-width="1.75" stroke-linejoin="round"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div>
                                    <h2 class="portal-profile-edit-section__title">Change password</h2>
                                    <p class="portal-profile-edit-section__hint">Minimum 7 characters recommended</p>
                                </div>
                            </div>

                            <form id="frm_change_password" class="portal-profile-edit-form">
                                @csrf

                                <div class="portal-profile-edit-field">
                                    <label class="portal-profile-edit-field__label" for="old_password">Current password</label>
                                    <div class="portal-profile-edit-field__wrap">
                                        <span class="portal-profile-edit-field__icon" aria-hidden="true">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M7 10V8a5 5 0 0 1 10 0v2" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/><rect x="5" y="10" width="14" height="11" rx="2" stroke="currentColor" stroke-width="1.75"/></svg>
                                        </span>
                                        <input type="password" class="portal-profile-edit-field__input" placeholder="Enter current password" name="old_password" id="old_password" autocomplete="current-password">
                                        <button type="button" class="portal-profile-edit-field__toggle" onclick="show_hide_password('old_password')" aria-label="Show current password">
                                            <img src="{{ asset('users/assets/images/icons/eye_slash.png') }}" alt="" id="icon_old_password">
                                        </button>
                                    </div>
                                    <span class="error_msg" id="error_old_password"></span>
                                </div>

                                <div class="portal-profile-edit-divider"><span>New password</span></div>

                                <div class="portal-profile-edit-field">
                                    <label class="portal-profile-edit-field__label" for="new_password">New password</label>
                                    <div class="portal-profile-edit-field__wrap">
                                        <span class="portal-profile-edit-field__icon" aria-hidden="true">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M7 10V8a5 5 0 0 1 10 0v2" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/><rect x="5" y="10" width="14" height="11" rx="2" stroke="currentColor" stroke-width="1.75"/></svg>
                                        </span>
                                        <input type="password" class="portal-profile-edit-field__input" placeholder="Create a new password" name="new_password" id="new_password" autocomplete="new-password">
                                        <button type="button" class="portal-profile-edit-field__toggle" onclick="show_hide_password('new_password')" aria-label="Show new password">
                                            <img src="{{ asset('users/assets/images/icons/eye_slash.png') }}" alt="" id="icon_new_password">
                                        </button>
                                    </div>
                                    <div class="portal-profile-edit-strength" id="password_strength" aria-live="polite">
                                        <span class="portal-profile-edit-strength__bar"><i></i><i></i><i></i><i></i></span>
                                        <span class="portal-profile-edit-strength__label">Enter a password</span>
                                    </div>
                                    <span class="error_msg" id="error_new_password"></span>
                                </div>

                                <div class="portal-profile-edit-field">
                                    <label class="portal-profile-edit-field__label" for="confirm_password">Confirm new password</label>
                                    <div class="portal-profile-edit-field__wrap">
                                        <span class="portal-profile-edit-field__icon" aria-hidden="true">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 3a9 9 0 1 0 9 9" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/></svg>
                                        </span>
                                        <input type="password" class="portal-profile-edit-field__input" placeholder="Re-enter new password" name="confirm_password" id="confirm_password" autocomplete="new-password">
                                        <button type="button" class="portal-profile-edit-field__toggle" onclick="show_hide_password('confirm_password')" aria-label="Show confirm password">
                                            <img src="{{ asset('users/assets/images/icons/eye_slash.png') }}" alt="" id="icon_confirm_password">
                                        </button>
                                    </div>
                                    <p class="portal-profile-edit-match" id="password_match" aria-live="polite"></p>
                                    <span class="error_msg" id="error_confirm_password"></span>
                                </div>

                                <div class="portal-profile-edit-security">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3 20 7v6c0 5-3.5 8.5-8 9-4.5-.5-8-4-8-9V7l8-4Z" stroke="currentColor" stroke-width="1.75" stroke-linejoin="round"/></svg>
                                    <p>Use a unique password you don't use on other sites. You'll stay signed in after updating.</p>
                                </div>

                                <div class="portal-profile-edit-actions">
                                    <a href="{{ url('/users/profile') }}" class="portal-profile-edit-cancel">Cancel</a>
                                    <button type="submit" class="portal-profile-edit-save">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        Save changes
                                    </button>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#file-upload1 .controls input[type="file"]').on('change', function () {
                $('#edit_profile_pic').removeClass('d-none');
                $('#file-upload1 .portal-profile-avatar__initial').addClass('d-none');
            });

            function scorePassword(value) {
                if (!value) return 0;
                var score = 0;
                if (value.length >= 7) score++;
                if (value.length >= 10) score++;
                if (/[A-Z]/.test(value) && /[a-z]/.test(value)) score++;
                if (/\d/.test(value)) score++;
                if (/[^A-Za-z0-9]/.test(value)) score++;
                return Math.min(score, 4);
            }

            function updatePasswordUi() {
                var password = $('#new_password').val();
                var confirm = $('#confirm_password').val();
                var score = scorePassword(password);
                var $strength = $('#password_strength');
                var labels = ['Too weak', 'Fair', 'Good', 'Strong', 'Excellent'];
                var levels = ['is-weak', 'is-fair', 'is-good', 'is-strong', 'is-excellent'];

                $strength.removeClass('is-weak is-fair is-good is-strong is-excellent');
                if (password.length === 0) {
                    $strength.find('.portal-profile-edit-strength__label').text('Enter a password');
                } else {
                    $strength.addClass(levels[score]);
                    $strength.find('.portal-profile-edit-strength__label').text(labels[score]);
                }

                var $match = $('#password_match');
                if (!confirm.length) {
                    $match.text('').removeClass('is-match is-mismatch');
                    return;
                }
                if (password === confirm) {
                    $match.text('Passwords match').removeClass('is-mismatch').addClass('is-match');
                } else {
                    $match.text('Passwords do not match').removeClass('is-match').addClass('is-mismatch');
                }
            }

            $('#new_password, #confirm_password').on('input', updatePasswordUi);
        });
    </script>
@endsection
