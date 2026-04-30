@extends('layout.users.master')
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h3 class="sub-heading text-black fw-bold">Profile</h3>
                    <a href="{{ url('/users/profile_edit') }}" class="fw-bold">Edit Profile</a>
                </div>
                <div class="profile">
                    <div class="row mt-4">
                        <!-- profile -->
                        <div class="col-lg-4 mb-4">
                            <div class="card border-0 rounded-4">
                                <a href="javascript:void(0)">
                                    <div class="card-body d-flex align-items-center" id="profile">
                                        <img src="" id="profile_pic" class="img-fluid me-3 border border-1" alt="image"  style="width: 55px !important; height: 55px !important;">
                                        <div>
                                            <h4 class="sub-heading text-black fw-bold mb-2">{{ session('first_name') .' '. session('last_name') }}</h4>
                                            <p class="mb-0 d-flex align-items-center">
                                                <span class="me-2">
                                                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M19.8333 23.9163H8.16665C4.66665 23.9163 2.33331 22.1663 2.33331 18.083V9.91634C2.33331 5.83301 4.66665 4.08301 8.16665 4.08301H19.8333C23.3333 4.08301 25.6666 5.83301 25.6666 9.91634V18.083C25.6666 22.1663 23.3333 23.9163 19.8333 23.9163Z" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M19.8334 10.5L16.1817 13.4167C14.98 14.3733 13.0083 14.3733 11.8067 13.4167L8.16669 10.5" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </span>
                                                {{ session('email') }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- profile --> 

                        <!-- referral code -->
                        <div class="col-lg-4 mb-4">
                            <div class="card border-0 rounded-4">
                                <div class="card-body d-flex align-items-center">
                                    <svg class="svg me-2" width="83" height="83" viewBox="0 0 83 83" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21.1677 17.2912C19.9918 16.0116 20.0264 14.0403 21.2714 12.7953L26.1823 7.88449C27.4618 6.60491 29.5714 6.60491 30.851 7.88449L40.2577 17.2912H21.1677ZM69.0654 62.2497C69.0654 72.6247 65.6071 76.083 55.2321 76.083H27.5654C17.1904 76.083 13.7321 72.6247 13.7321 62.2497V34.583H69.0654V62.2497ZM52.1158 7.88449L42.7091 17.2912H61.7991C62.9749 16.0116 62.9404 14.0403 61.6954 12.7953L56.7845 7.88449C55.5049 6.60491 53.3954 6.60491 52.1158 7.88449Z" fill="#A6EBB8"/>
                                        <path fill-rule="evenodd" class="fill" clip-rule="evenodd" d="M67.4375 34.5837C72.5213 34.5837 74.3542 31.4712 74.3542 27.667V24.2087C74.3542 20.4045 72.5213 17.292 67.4375 17.292H15.5625C10.2713 17.292 8.64587 20.4045 8.64587 24.2087V27.667C8.64587 31.4712 10.2713 34.5837 15.5625 34.5837H30.9157V52.3595C30.9157 55.1262 33.959 56.7516 36.2761 55.2645L39.5269 53.1203C40.7027 52.3595 42.1898 52.3595 43.3311 53.1203L46.409 55.1953C48.6915 56.717 51.7694 55.0916 51.7694 52.3249V34.5837H67.4375Z" fill="#4BD16F"/>
                                    </svg>
                                    <div>
                                        <h4 class="sub-heading text-black fw-bold mb-2">Referral Code</h4>
                                        <a href="javascript:void(0)" class="text-success">Share your friend get $20 of free stocks</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- referral code -->

                        <!-- feedback -->
                        <div class="col-lg-4 mb-4">
                            <a href="#">
                                <div class="card border-0 rounded-4 with-hover" data-bs-toggle="modal" data-bs-target="#mdl_feedback">
                                    <a href="javascript:void(0)">
                                        <div class="card-body d-flex justify-content-between align-items-center gap-5">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="profile-icon me-2">
                                                    <svg  width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M32 13.333C42.0946 13.333 50.3176 21.3458 50.6559 31.3579L50.6667 31.9997V34.6663C50.6667 36.1391 49.4728 37.333 48 37.333C46.6325 37.333 45.5054 36.3036 45.3513 34.9773L45.3334 34.6663V31.9997C45.3334 24.6359 39.3638 18.6663 32 18.6663C24.83 18.6663 18.9819 24.3258 18.679 31.4213L18.6667 31.9997V34.6663C18.6667 36.1391 17.4728 37.333 16 37.333C14.6325 37.333 13.5054 36.3036 13.3513 34.9773L13.3334 34.6663V31.9997C13.3334 21.6904 21.6907 13.333 32 13.333Z" fill="#9EA3AE"/>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8 39.9997C8 34.1086 12.7756 29.333 18.6667 29.333H23.6863C23.8595 29.333 24 29.4735 24 29.6467V50.3526C24 50.5259 23.8595 50.6663 23.6863 50.6663H18.6667C12.7756 50.6663 8 45.8907 8 39.9997ZM40 29.6467C40 29.4735 40.1405 29.333 40.3137 29.333H45.3333C51.2244 29.333 56 34.1086 56 39.9997C56 45.8907 51.2244 50.6663 45.3333 50.6663H40.3137C40.1405 50.6663 40 50.5259 40 50.3526V47.9997H33C32.4477 47.9997 32 47.552 32 46.9997V43.6663C32 43.1141 32.4477 42.6663 33 42.6663H40V29.6467Z" fill="#4BD16F"/>
                                                    </svg>
                                                </div> 
                                                <p class="mb-0">We'd love to hear your <br/> feedback on threads, if <br/> you have any?</p>
                                            </div> 
                                            <span class="arrow">
                                                <svg width="14" height="26" viewBox="0 0 14 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.0398 8.19149C13.7055 10.8572 13.7944 15.1241 11.3064 17.8965L11.0398 18.1779L3.76747 25.0252C3.07806 25.7146 1.96029 25.7146 1.27087 25.0252C0.634489 24.3888 0.585536 23.3874 1.12402 22.6949L1.27087 22.5286L8.54318 15.6813C9.84944 14.375 9.9182 12.2999 8.74943 10.9127L8.54318 10.6881L1.27087 3.84081C0.581456 3.15139 0.581456 2.03363 1.27087 1.34421C1.90726 0.707826 2.90863 0.658874 3.60116 1.19735L3.76747 1.34421L11.0398 8.19149Z" fill="#21333B"/>
                                                </svg>                                                    
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            </a>
                            <!-- modal start -->
                            <div class="modal fade modal-lg" id="mdl_feedback" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-body p-5">
                                            <div class="row">
                                                <div class="col-9 mx-auto d-flex align-items-center mb-5">
                                                    <svg class="flex-grow-0 pointer" data-bs-dismiss="modal" aria-label="Close" width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10 20L0 10L10 0L11.775 1.775L3.55 10L11.775 18.225L10 20Z" fill="#4BD16F"/>
                                                    </svg>
                                                    <h2 class="flex-grow-1 modal-heading">Feedback</h2>
                                                </div>
                                            </div>
                                            <form id="frm_feedback">
                                                @csrf
                                                <div class="row mt-10">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-4">
                                                            <label class="form-label mb-3">Name</label>
                                                            <input type="text" name="fb_name" id="fb_name" placeholder="Enter Name" class="form-control">
                                                            <span class="error_msg" id="error_fb_name"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-4">
                                                            <label class="form-label mb-3">Email</label>
                                                            <input type="email" name="fb_email" id="fb_email" placeholder="Email Address" class="form-control">
                                                            <span class="error_msg" id="error_fb_email"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group mb-4">
                                                            <label class="form-label mb-3">Subject</label>
                                                            <textarea type="text" name="fb_subject" id="fb_subject" rows="5" placeholder="Enter here" class="form-control"></textarea>
                                                            <span class="error_msg" id="error_fb_subject"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 mx-auto">
                                                        <div class="mt-35">
                                                            <button type="submit" class="btn btn-login btn-primary w-100">Send</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- modal end -->
                        </div>
                        <!-- feedback -->

                        <!-- billing payment -->
                        <div class="col-lg-4 mb-4">
                            <a href="{{ url('/users/billing_payment') }}">
                                <div class="card border-0 rounded-4 with-hover">
                                    <div class="card-body d-flex justify-content-between align-items-center gap-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="profile-icon me-2">
                                                <svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.35754 20.4805C5.35754 19.9282 5.80526 19.4805 6.35754 19.4805H36.1341C36.6864 19.4805 37.1341 19.9282 37.1341 20.4805V25.6034C37.1341 30.0216 33.5524 33.6034 29.1341 33.6034H13.3575C8.93926 33.6034 5.35754 30.0217 5.35754 25.6034V20.4805Z" fill="#E8F9DC"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.6344 8.88867C8.16778 8.88867 5.35754 11.6989 5.35754 15.1655C5.35754 15.5988 5.70882 15.9501 6.14215 15.9501H36.3495C36.7828 15.9501 37.1341 15.5988 37.1341 15.1655C37.1341 11.6989 34.3238 8.88867 30.8572 8.88867H11.6344ZM13.419 23.0116C12.8667 23.0116 12.419 23.4593 12.419 24.0116V25.5423C12.419 26.0946 12.8667 26.5423 13.419 26.5423H23.7765C24.3288 26.5423 24.7765 26.0946 24.7765 25.5423V24.0116C24.7765 23.4593 24.3288 23.0116 23.7765 23.0116H13.419Z" fill="#4BD16F"/>
                                                </svg>
                                            </div> 
                                            <h4 class="sub-heading text-black fw-bold mb-2">Payout Methods</h4>
                                        </div> 
                                        <span class="arrow">
                                            <svg width="14" height="26" viewBox="0 0 14 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.0398 8.19149C13.7055 10.8572 13.7944 15.1241 11.3064 17.8965L11.0398 18.1779L3.76747 25.0252C3.07806 25.7146 1.96029 25.7146 1.27087 25.0252C0.634489 24.3888 0.585536 23.3874 1.12402 22.6949L1.27087 22.5286L8.54318 15.6813C9.84944 14.375 9.9182 12.2999 8.74943 10.9127L8.54318 10.6881L1.27087 3.84081C0.581456 3.15139 0.581456 2.03363 1.27087 1.34421C1.90726 0.707826 2.90863 0.658874 3.60116 1.19735L3.76747 1.34421L11.0398 8.19149Z" fill="#21333B"/>
                                            </svg>                                                    
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- billing payment -->

                        <!-- transactions -->
                        <div class="col-lg-4 mb-4">
                            <a href="#">
                                <div class="card border-0 rounded-4 with-hover">
                                    <a href="{{ url('/users/transactions') }}">
                                        <div class="card-body d-flex justify-content-between align-items-center gap-5">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="profile-icon me-2">
                                                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <mask id="mask0_406_3243" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="36" height="36">
                                                        <rect width="36" height="36" fill="#4BD16F"/>
                                                        </mask>
                                                        <g mask="url(#mask0_406_3243)">
                                                        <path d="M18 31.5C16.125 31.5 14.369 31.144 12.732 30.432C11.094 29.719 9.669 28.756 8.457 27.543C7.244 26.331 6.281 24.906 5.568 23.268C4.856 21.631 4.5 19.875 4.5 18C4.5 16.125 4.856 14.3685 5.568 12.7305C6.281 11.0935 7.244 9.6685 8.457 8.4555C9.669 7.2435 11.094 6.281 12.732 5.568C14.369 4.856 16.125 4.5 18 4.5C20.05 4.5 21.994 4.9375 23.832 5.8125C25.669 6.6875 27.225 7.925 28.5 9.525V6H31.5V15H22.5V12H26.625C25.6 10.6 24.3375 9.5 22.8375 8.7C21.3375 7.9 19.725 7.5 18 7.5C15.075 7.5 12.594 8.5185 10.557 10.5555C8.519 12.5935 7.5 15.075 7.5 18C7.5 20.925 8.519 23.406 10.557 25.443C12.594 27.481 15.075 28.5 18 28.5C20.625 28.5 22.9185 27.65 24.8805 25.95C26.8435 24.25 28 22.1 28.35 19.5H31.425C31.05 22.925 29.5815 25.781 27.0195 28.068C24.4565 30.356 21.45 31.5 18 31.5ZM22.2 24.3L16.5 18.6V10.5H19.5V17.4L24.3 22.2L22.2 24.3Z" fill="#4BD16F"/>
                                                        </g>
                                                    </svg>
                                                </div> 
                                                <h4 class="sub-heading text-black fw-bold mb-2">Transactions</h4>
                                            </div> 
                                            <span class="arrow">
                                                <svg width="14" height="26" viewBox="0 0 14 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.0398 8.19149C13.7055 10.8572 13.7944 15.1241 11.3064 17.8965L11.0398 18.1779L3.76747 25.0252C3.07806 25.7146 1.96029 25.7146 1.27087 25.0252C0.634489 24.3888 0.585536 23.3874 1.12402 22.6949L1.27087 22.5286L8.54318 15.6813C9.84944 14.375 9.9182 12.2999 8.74943 10.9127L8.54318 10.6881L1.27087 3.84081C0.581456 3.15139 0.581456 2.03363 1.27087 1.34421C1.90726 0.707826 2.90863 0.658874 3.60116 1.19735L3.76747 1.34421L11.0398 8.19149Z" fill="#21333B"/>
                                                </svg>                                                    
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            </a>
                        </div>
                        <!-- transactions -->

                        <!-- LANGUAGE START -->
                        <div class="col-lg-4 mb-4">
                            <a href="javascript:void(0)">
                                <div class="card border-0 rounded-4 with-hover">
                                <div class="card-body d-flex justify-content-between align-items-center gap-5">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="profile-icon me-2">
                                            <svg width="43" height="43" viewBox="0 0 57 57" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="56.4916" height="56.4916" rx="12" fill="#A6EBB8"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M26.4899 17.3251C26.4033 16.5791 25.7693 16 25 16C24.1716 16 23.5 16.6716 23.5 17.5V19H17.5L17.3251 19.0101C16.5791 19.0967 16 19.7307 16 20.5C16 21.3284 16.6716 22 17.5 22L23.4361 22.0008C23.2346 24.3539 22.5642 26.5376 21.5313 28.1903L21.4028 28.3826C20.8002 27.7663 20.499 27.1171 20.5 26.5024L20.4902 26.3275C20.4047 25.5813 19.7717 25.0012 19.0024 25C18.174 24.9987 17.5013 25.6692 17.5 26.4976C17.4976 27.9659 18.1371 29.3391 19.2803 30.4978C18.6972 30.8304 18.0954 31 17.5 31L17.3251 31.0101C16.5791 31.0967 16 31.7307 16 32.5C16 33.3284 16.6716 34 17.5 34C19.071 34 20.5667 33.4102 21.8701 32.3401C23.338 33.0814 25.0759 33.5917 26.9308 33.8375L25.1293 37.8908L25.0675 38.0547C24.8437 38.7716 25.1878 39.5583 25.8908 39.8707L26.0547 39.9325C26.7716 40.1563 27.5583 39.8122 27.8707 39.1092L28.807 37H36.1915L37.1293 39.1092L37.2096 39.265C37.5917 39.9115 38.4063 40.1831 39.1092 39.8707C39.8662 39.5343 40.2072 38.6478 39.8707 37.8908L33.8707 24.3908L33.7831 24.2215L33.6738 24.0622C33.0086 23.2156 31.6029 23.3251 31.1293 24.3908L28.2114 30.9561C26.6506 30.8357 25.1802 30.4994 23.9384 29.9889L24.0753 29.7804C25.4103 27.6444 26.2334 24.9058 26.4454 22.002L28 22L28.1749 21.9899C28.9209 21.9033 29.5 21.2693 29.5 20.5C29.5 19.6716 28.8284 19 28 19H26.5V17.5L26.4899 17.3251ZM32.5 28.6915L30.1405 34H34.8595L32.5 28.6915Z" fill="#4BD16F"/>
                                                </svg>
                                        </div> 
                                        <h4 class="sub-heading text-black fw-bold mb-2">Language</h4>
                                    </div> 
                                    <span class="arrow">
                                        <svg width="14" height="26" viewBox="0 0 14 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.0398 8.19149C13.7055 10.8572 13.7944 15.1241 11.3064 17.8965L11.0398 18.1779L3.76747 25.0252C3.07806 25.7146 1.96029 25.7146 1.27087 25.0252C0.634489 24.3888 0.585536 23.3874 1.12402 22.6949L1.27087 22.5286L8.54318 15.6813C9.84944 14.375 9.9182 12.2999 8.74943 10.9127L8.54318 10.6881L1.27087 3.84081C0.581456 3.15139 0.581456 2.03363 1.27087 1.34421C1.90726 0.707826 2.90863 0.658874 3.60116 1.19735L3.76747 1.34421L11.0398 8.19149Z" fill="#21333B"/>
                                        </svg>                                                    
                                    </span>
                                </div>
                                </div>
                            </a>
                        </div>
                        <!-- LANGUAGE END-->

                        <!-- SETTINGS START -->
                        <div class="col-lg-4 mb-4">
                            <a href="{{ url('/users/settings') }}">
                                <div class="card border-0 rounded-4 with-hover">
                                <div class="card-body d-flex justify-content-between align-items-center gap-5">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="profile-icon me-2">
                                            <svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M24.3706 4.35137C22.6229 3.34512 19.8866 3.34512 18.1389 4.35137L9.22369 9.66512C5.56939 12.1366 5.35754 12.5073 5.35754 16.4441V26.03C5.35754 29.9668 5.56939 30.3552 9.2943 32.862L18.1213 38.1404C19.0039 38.6524 20.1338 38.8995 21.2459 38.8995C22.3581 38.8995 23.488 38.6524 24.353 38.1404L33.2679 32.8267C36.9222 30.3552 37.1341 29.9844 37.1341 26.0477V16.4441C37.1341 12.5073 36.9222 12.1366 33.1973 9.62981C33.1973 9.62981 31.1649 8.45436 30.1486 7.86663C28.1225 6.69488 24.3706 4.35137 24.3706 4.35137Z" fill="#E8F9DC"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M21.2458 26.5424C18.3208 26.5424 15.9497 24.1712 15.9497 21.2463C15.9497 18.3213 18.3208 15.9502 21.2458 15.9502C24.1707 15.9502 26.5419 18.3213 26.5419 21.2463C26.5419 24.1712 24.1707 26.5424 21.2458 26.5424Z" fill="#4BD16F"/>
                                            </svg>
                                        </div> 
                                        <h4 class="sub-heading text-black fw-bold mb-2">Settings</h4>
                                    </div> 
                                    <span class="arrow">
                                        <svg width="14" height="26" viewBox="0 0 14 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.0398 8.19149C13.7055 10.8572 13.7944 15.1241 11.3064 17.8965L11.0398 18.1779L3.76747 25.0252C3.07806 25.7146 1.96029 25.7146 1.27087 25.0252C0.634489 24.3888 0.585536 23.3874 1.12402 22.6949L1.27087 22.5286L8.54318 15.6813C9.84944 14.375 9.9182 12.2999 8.74943 10.9127L8.54318 10.6881L1.27087 3.84081C0.581456 3.15139 0.581456 2.03363 1.27087 1.34421C1.90726 0.707826 2.90863 0.658874 3.60116 1.19735L3.76747 1.34421L11.0398 8.19149Z" fill="#21333B"/>
                                        </svg>                                                    
                                    </span>
                                </div>
                                </div>
                            </a>
                        </div>
                        <!-- SETTINGS END -->

                        <!-- faqs -->
                        <div class="col-lg-4 mb-4">
                            <a href="{{ url('/users/faqs') }}">
                                <div class="card border-0 rounded-4 with-hover">
                                    <div class="card-body d-flex justify-content-between align-items-center gap-5">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="profile-icon me-2">
                                            <svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="3.59216" y="3.5918" width="35.3073" height="35.3073" rx="6" fill="#E8F9DC"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M27.6465 10.8648C26.1612 9.59183 24.1807 8.88867 22.1285 8.88867H20.3631L19.891 8.90109C18.0091 9.00022 16.2161 9.68977 14.8452 10.8649C13.3041 12.1858 12.419 14.0174 12.419 15.9501C12.419 16.9251 13.2094 17.7155 14.1844 17.7155C15.1594 17.7155 15.9497 16.9251 15.9497 15.9501L15.9651 15.6587C16.0468 14.8844 16.4528 14.1371 17.143 13.5456C17.9753 12.8322 19.1379 12.4194 20.3631 12.4194H22.1285L22.4939 12.4317C23.583 12.505 24.5996 12.9035 25.3487 13.5456C26.1251 14.211 26.5419 15.0736 26.5419 15.9501C26.5881 16.8142 26.3819 17.5718 25.9575 18.2086C25.641 18.6832 25.3463 18.8455 24.8993 19.0918C24.7465 19.176 24.5759 19.27 24.3805 19.3896C24.3805 19.3896 23.1094 20.1732 22.6366 20.643C22.6107 20.6689 22.5835 20.6957 22.5554 20.7236C22.0714 21.2027 21.2783 21.9879 20.5478 23.2012C20.4492 23.365 20.3571 23.513 20.2716 23.6503C19.6876 24.5888 19.4145 25.0278 19.4821 26.6173C19.5235 27.5914 20.3467 28.3475 21.3208 28.3061C22.2949 28.2647 22.8795 27.4319 23.0096 26.4673C23.302 24.2997 24.7479 23.2953 25.2645 22.9844L25.5003 22.8562L25.7507 22.762C27.0283 22.2263 28.1231 21.325 28.8952 20.1671C29.7442 18.8934 30.1564 17.3784 30.0698 15.8502L30.0586 15.4838C29.9517 13.7754 29.0848 12.0977 27.6465 10.8648ZM21.2458 33.6038C22.2208 33.6038 23.0112 32.8134 23.0112 31.8384C23.0112 30.8634 22.2208 30.073 21.2458 30.073C20.2708 30.073 19.4805 30.8634 19.4805 31.8384C19.4805 32.8134 20.2708 33.6038 21.2458 33.6038Z" fill="#4BD16F"/>
                                            </svg>                                             
                                        </div> 
                                        <h4 class="sub-heading text-black fw-bold mb-2">FAQs</h4>
                                        </div> 
                                        <span class="arrow">
                                            <svg width="14" height="26" viewBox="0 0 14 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.0398 8.19149C13.7055 10.8572 13.7944 15.1241 11.3064 17.8965L11.0398 18.1779L3.76747 25.0252C3.07806 25.7146 1.96029 25.7146 1.27087 25.0252C0.634489 24.3888 0.585536 23.3874 1.12402 22.6949L1.27087 22.5286L8.54318 15.6813C9.84944 14.375 9.9182 12.2999 8.74943 10.9127L8.54318 10.6881L1.27087 3.84081C0.581456 3.15139 0.581456 2.03363 1.27087 1.34421C1.90726 0.707826 2.90863 0.658874 3.60116 1.19735L3.76747 1.34421L11.0398 8.19149Z" fill="#21333B"/>
                                            </svg>                                                    
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- faqs -->

                        <!-- delete account -->
                        <div class="col-lg-4 mb-4">
                            <a href="javascript:void(0)">
                                <div class="card border-0 rounded-4 with-hover" data-bs-toggle="modal" data-bs-target="#mdl_delete_account">
                                <div class="card-body d-flex justify-content-between align-items-center gap-5">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="profile-icon me-2">
                                            <svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M24.3706 4.35137C22.6229 3.34512 19.8866 3.34512 18.1389 4.35137L9.22369 9.66512C5.56939 12.1366 5.35754 12.5073 5.35754 16.4441V26.03C5.35754 29.9668 5.56939 30.3552 9.2943 32.862L18.1213 38.1404C19.0039 38.6524 20.1338 38.8995 21.2459 38.8995C22.3581 38.8995 23.488 38.6524 24.353 38.1404L33.2679 32.8267C36.9222 30.3552 37.1341 29.9844 37.1341 26.0477V16.4441C37.1341 12.5073 36.9222 12.1366 33.1973 9.62981C33.1973 9.62981 31.1649 8.45436 30.1486 7.86663C28.1225 6.69488 24.3706 4.35137 24.3706 4.35137Z" fill="#E8F9DC"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M21.2458 26.5424C18.3208 26.5424 15.9497 24.1712 15.9497 21.2463C15.9497 18.3213 18.3208 15.9502 21.2458 15.9502C24.1707 15.9502 26.5419 18.3213 26.5419 21.2463C26.5419 24.1712 24.1707 26.5424 21.2458 26.5424Z" fill="#4BD16F"/>
                                            </svg>
                                        </div> 
                                        <h4 class="sub-heading text-black fw-bold mb-2">Delete Account</h4>
                                    </div> 
                                    <span class="arrow">
                                        <svg width="14" height="26" viewBox="0 0 14 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.0398 8.19149C13.7055 10.8572 13.7944 15.1241 11.3064 17.8965L11.0398 18.1779L3.76747 25.0252C3.07806 25.7146 1.96029 25.7146 1.27087 25.0252C0.634489 24.3888 0.585536 23.3874 1.12402 22.6949L1.27087 22.5286L8.54318 15.6813C9.84944 14.375 9.9182 12.2999 8.74943 10.9127L8.54318 10.6881L1.27087 3.84081C0.581456 3.15139 0.581456 2.03363 1.27087 1.34421C1.90726 0.707826 2.90863 0.658874 3.60116 1.19735L3.76747 1.34421L11.0398 8.19149Z" fill="#21333B"/>
                                        </svg>                                                    
                                    </span>
                                </div>
                                </div>
                            </a>
                            <!-- modal start -->
                            <div class="modal fade" id="mdl_delete_account" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-body p-5">
                                            <div class="row">
                                                <div class="col-9 mx-auto d-flex align-items-center mb-5">
                                                    <svg class="flex-grow-0 pointer" data-bs-dismiss="modal" aria-label="Close" width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10 20L0 10L10 0L11.775 1.775L3.55 10L11.775 18.225L10 20Z" fill="#4BD16F"/>
                                                    </svg>
                                                    <h2 class="flex-grow-1 modal-heading">Delete Account</h2>
                                                </div>
                                            </div>
                                            <form id="frm_delete_account">
                                                @csrf
                                                <div class="row mt-30">
                                                    <h5 class="fw-bold mb-4">Why do you want to delete your Swap Circle account?</h5>
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-4">
                                                            <label class="form-label mb-3">Delete Reason</label>
                                                            <input type="text" name="delete_reason" id="delete_reason" placeholder="Enter delete reason" class="form-control">
                                                            <span class="error_msg" id="error_delete_reason"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group mb-4">
                                                            <label class="form-label mb-3">Comments</label>
                                                            <textarea type="text" name="comments" id="comments" rows="5" placeholder="Please provide additional information here..." class="form-control"></textarea>
                                                            <span class="error_msg" id="error_comments"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-8 mx-auto">
                                                        <div class="mt-37">
                                                            <button type="submit" class="btn btn-login btn-primary w-100">Delete</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- modal end -->
                        </div>
                        <!-- delete account -->
                    </div> 
                </div>
            </div>
        </div> 
    </div>
@endsection