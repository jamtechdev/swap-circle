@extends('layout.users.master')
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="profile-edit">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb d-flex align-items-center">
                            <li class="breadcrumb-item"><a href="{{ url('/users/profile') }}" class="text-primary">Profile</a></li>
                            <li class="mx-3  d-flex align-items-center">
                                <svg width="5" height="10" viewBox="0 0 5 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.21749 3.11406C5.22417 4.12074 5.25773 5.73205 4.31816 6.77904L4.21749 6.88529L1.47119 9.47108C1.21084 9.73143 0.788734 9.73143 0.528385 9.47108C0.288062 9.23076 0.269576 8.8526 0.472926 8.59107L0.528385 8.52827L3.27468 5.94248C3.76797 5.44919 3.79393 4.66553 3.35257 4.14168L3.27468 4.05687L0.528385 1.47108C0.268035 1.21073 0.268035 0.78862 0.528385 0.52827C0.768707 0.287947 1.14686 0.269461 1.40839 0.472811L1.47119 0.52827L4.21749 3.11406Z" fill="#21333B"/>
                                </svg>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{ url('/users/profile_edit') }}">Edit Profile</a>
                            </li> 
                        </ol>
                    </nav>
                    <form id="frm_change_password">
                        @csrf
                        <div class="row mt-5">
                            <!-- update profile image -->
                            <div class="col-xl-3 col-md-5">
                                <label class="sub-heading text-black mb-5 fw-bolder">Update Profile Image</label>
                                <div class="control-group file-upload" id="file-upload1">
                                    <div class="edit-image-box text-center position-relative d-flex align-items-center border border-1">
                                        <img src="" id="edit_profile_pic" class="img-fluid" alt="">
                                        <span class="upload-image position-absolute badge rounded-full" style="cursor:pointer;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <mask id="mask0_500_8881" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
                                                    <rect width="24" height="24" fill="#D9D9D9"/>
                                                </mask>
                                                <g mask="url(#mask0_500_8881)">
                                                    <path d="M5 21C4.45 21 3.979 20.8043 3.587 20.413C3.19567 20.021 3 19.55 3 19V5C3 4.45 3.19567 3.979 3.587 3.587C3.979 3.19567 4.45 3 5 3H14V5H5V19H19V10H21V19C21 19.55 20.8043 20.021 20.413 20.413C20.021 20.8043 19.55 21 19 21H5ZM17 9V7H15V5H17V3H19V5H21V7H19V9H17ZM6 17H18L14.25 12L11.25 16L9 13L6 17Z" fill="#4BD16F"/>
                                                </g>
                                            </svg>                                              
                                            <span class="visually-hidden">unread messages</span>
                                        </span> 
                                    </div>
                                    <div class="controls d-none">
                                        <input type="file" accept="image/png, image/jpg, image/jpeg" name="contact_image_1" id="" value="./assets/images/profile.png" onchange="update_profile_pic(this)">
                                    </div>
                                </div>
                            </div>
                            <!-- update profile image -->

                            <!-- change password -->
                            <div class="col-xl-4 col-md-7 mt-3 mt-md-0">
                                <label class="sub-heading text-black mb-5 fw-bolder">Change Password</label>
                                <div class="form-group position-relative w-pass mb-3">
                                    <span class="input-icon">
                                        <img src="{{ asset('users/assets/images/icons/lock.png') }}" alt="icon" class="img-fluid">
                                    </span>
                                    <input type="password" class="form-control" placeholder="Old password" aria-label="old-password" name="old_password" id="old_password">
                                    <span class="input-icon right">
                                        <img src="{{ asset('users/assets/images/icons/eye_slash.png') }}" alt="icon" class="img-fluid"  onclick="show_hide_password('old_password')" id="icon_old_password">
                                    </span>
                                    <span class="error_msg" id="error_old_password"></span>
                                </div>
                                <div class="form-group position-relative w-pass mb-3">
                                    <span class="input-icon">
                                        <img src="{{ asset('users/assets/images/icons/lock.png') }}" alt="icon" class="img-fluid">
                                    </span>
                                    <input type="password" class="form-control" placeholder="New password" aria-label="new-password" name="new_password" id="new_password">
                                    <span class="input-icon right">
                                        <img src="{{ asset('users/assets/images/icons/eye_slash.png') }}" alt="icon" class="img-fluid"  onclick="show_hide_password('new_password')" id="icon_new_password">
                                    </span>
                                    <span class="error_msg" id="error_new_password"></span>
                                </div>
                                <div class="form-group position-relative w-pass mb-3">
                                    <span class="input-icon">
                                        <img src="{{ asset('users/assets/images/icons/lock.png') }}" alt="icon" class="img-fluid">
                                    </span>
                                    <input type="password" class="form-control" placeholder="Confirm password" aria-label="confirm-password" name="confirm_password" id="confirm_password">
                                    <span class="input-icon right">
                                        <img src="{{ asset('users/assets/images/icons/eye_slash.png') }}" alt="icon" class="img-fluid" onclick="show_hide_password('confirm_password')" id="icon_confirm_password">
                                    </span>
                                    <span class="error_msg" id="error_confirm_password"></span>
                                </div>
                                <div class="col-xl-12 col-md-5 d-grid mt-5">
                                    <button type="submit" class="btn btn-primary py-3 rounded-4 d-block">Save</button>
                                </div>
                            </div>
                            <!-- change password -->
                        </div>
                    </form>
                </div> 
            </div>
        </div> 
    </div>
@endsection