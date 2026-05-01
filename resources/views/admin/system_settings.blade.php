@extends('layout.admin.list_master')

@section('content')
    <style>
        .imageUpload{
            display: none;
        }

        .profileImage{
            cursor: pointer;
            width: 100%;
        }

        #profile-container {
            margin: 20px auto;
            color: white;
            justify-content: center;
            overflow: hidden;
        }

        .error{
            color: red;
        }

        .errorto{
            color: red;
            background-color: rgb(244, 198, 198);
            padding-top: 15px;
            padding-bottom: 15px; 
            text-align: center;
        }

        .bootstrap-select {
            border: 10px solid red;
        }
    </style>
    <!--**********************************
           Chat box End
    ***********************************-->
    
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mb-n5">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <!-- <h4>Hi, welcome back!</h4> -->
                     
                        {{-- <p class="mb-0">Validation</p> --}}
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        @section('titleBar')
                        <span class="ml-2">Settings</span>
                        @endsection               
                    </ol>
                </div>
            </div>
            <!-- row -->
            <div class="row">
                <div class="col-lg-12">
                    @include('layout.admin.settings')
                    <div class="card">
                        <div class="card-body">
                            @php
                                $insuretechAdminBaseUrl = optional($system_settings->firstWhere('type', 'insuretech_admin_base_url'))->description ?? env('INSURETECH_ADMIN_BASE_URL', '');
                                $insuretechPartnerToken = optional($system_settings->firstWhere('type', 'insuretech_partner_token'))->description ?? env('INSURETECH_PARTNER_TOKEN', '');
                                $insuretechRequestTimeout = optional($system_settings->firstWhere('type', 'insuretech_request_timeout'))->description ?? env('INSURETECH_REQUEST_TIMEOUT', 20);
                            @endphp
                            <div class="basic-form">
                                <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/admin/system_settings_edit')}}">
                                    @csrf
                                    <div class="col-xl-12 form-group">
                                        <label class="col-sm-12 control-label">Invite text for app</label>
                                        <small class="col-sm-12 control-label">Add the text that will be forwarded when user invite others</small>
                                        <div class="col-sm-12">
                                            <textarea rows="5" class="input-mask form-control" name="{{ $system_settings[16]->type }}" required>{{ $system_settings[16]->description }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 form-group">
                                        <label class="col-sm-12 control-label">Transfer instructions for app</label>
                                        <div class="col-sm-12">
                                            <textarea rows="5" class="input-mask form-control" name="{{ $system_settings[22]->type }}" required>{{ $system_settings[22]->description }}</textarea>
                                        </div>
                                    </div>

                                    <legend class="col-xl-12">Insuretech Partner Connection</legend>
                                    <div class="row">
                                        <div class="col-xl-4 form-group">
                                            <label class="col-sm-12 control-label">Admin Base URL</label>
                                            <div class="col-sm-12">
                                                <input type="url" class="input-mask form-control" name="insuretech_admin_base_url" value="{{ $insuretechAdminBaseUrl }}" placeholder="https://admin.example.com">
                                            </div>
                                        </div>
                                        <div class="col-xl-4 form-group">
                                            <label class="col-sm-12 control-label">Partner Token</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="insuretech_partner_token" value="{{ $insuretechPartnerToken }}" placeholder="Paste partner token">
                                            </div>
                                        </div>
                                        <div class="col-xl-4 form-group">
                                            <label class="col-sm-12 control-label">Request Timeout (seconds)</label>
                                            <div class="col-sm-12">
                                                <input type="number" min="1" class="input-mask form-control" name="insuretech_request_timeout" value="{{ $insuretechRequestTimeout }}">
                                            </div>
                                        </div>
                                    </div>

                                    <legend class="col-xl-12">Geneeral Settings</legend>
                                    <div class="row">
                                        <div class="col-xl-3 form-group">
                                            <label class="col-sm-12 control-label">Commission Percentage</label>
                                            <div class="col-sm-12">
                                                <input type="number" min="0" max="100" class="input-mask form-control" name="{{ $system_settings[20]->type }}" value="{{ $system_settings[20]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 form-group">
                                            <label class="col-sm-12 control-label">Contact Email</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[1]->type }}" value="{{ $system_settings[1]->description }}" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3 form-group">
                                            <label class="col-sm-12 control-label">Contact Phone Number</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[2]->type }}" value="{{ $system_settings[2]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 form-group">
                                            <label class="col-sm-12 control-label">System Name</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[0]->type }}" value="{{ $system_settings[0]->description }}" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3 form-group">
                                            <label class="col-sm-12 control-label">Address</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[4]->type}}" value="{{ $system_settings[4]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 form-group">
                                            <label class="col-sm-12 control-label">Currency</label>
                                            <div class="col-sm-12">
                                            <select class="form-control" name="questionbook_id" required>
                                                <!-- <option value="" > --Select one--</option> -->
                                              @foreach($system_currency as $currency)
                                                <option value="{{ $currency->system_currencies_id }}" @if($currency->system_currencies_id==$system_settings[11]->description ) selected @endif> {{$currency->name}}</option>
                                                @endforeach
                                            </select>
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[11]->type }}" value="{{ $system_settings[11]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 form-group">
                                            <label class="col-sm-12 control-label">App Social Login</label>
                                            <div class="col-sm-12">
                                                <select class="input-mask form-control" name="{{ $system_settings[15]->type }}" required>
                                                    <option value="Yes" <?php if($system_settings[15]->description == 'Yes') echo "selected"; ?>>Yes</option>
                                                    <option value="No" <?php if($system_settings[15]->description == 'No') echo "selected"; ?>>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 form-group">
                                            <label class="col-sm-12 control-label">Swap Offers Expire (days)</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[21]->type }}" value="{{ $system_settings[21]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Login Main Heading Label 1</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[26]->type }}" value="{{ $system_settings[26]->description }}" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Login Main Heading Label 2</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[27]->type }}" value="{{ $system_settings[27]->description }}" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Login Sub Heading Label</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[28]->type }}" value="{{ $system_settings[28]->description }}" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Forgot Password Link Label</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[29]->type }}" value="{{ $system_settings[29]->description }}" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Login Button Label</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[30]->type }}" value="{{ $system_settings[30]->description }}" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Signup Text Label</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[31]->type }}" value="{{ $system_settings[31]->description }}" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Signup Link Label</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[32]->type }}" value="{{ $system_settings[32]->description }}" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 form-group">
                                        </div>
                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Login Backgroung Image</label>
                                            <div class="col-sm-7">
                                                <div class="border border-2 p-1" id="profile-container" style="cursor: pointer;">
                                                    <img id="imagePreview" class="imagePreview" src="{{asset($system_settings[24]->description)}}" width="200px" />
                                                </div>
                                                <input id="imageUpload" class="imageUpload" type="file" name="login_bg_image" placeholder="Image" onchange="loadFile(event)" capture>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Login Image</label>
                                            <div class="col-sm-7">
                                                <div class="border border-2 p-1" id="profile-container" style="cursor: pointer;">
                                                    <img id="imagePreview2" class="imagePreview" src="{{asset($system_settings[25]->description)}}" width="200px" />
                                                </div>
                                                <input id="imageUpload2" class="imageUpload" type="file" name="login_image" placeholder="Image" onchange="loadFile2(event)" capture>
                                            </div>
                                        </div>
                                        </div>
                                        
                                        <div class="col-xl-12 form-group">
                                            <label class="col-sm-12 control-label">Logo</label>
                                            <div class="col-sm-12">
                                                <div id="profile-container">
                                                    <img id="imagePreview3" class="imagePreview" src="{{asset('uploads/system_image/'.$system_settings[5]->description)}}" />
                                                </div>
                                                <input id="imageUpload3" class="imageUpload" type="file" name="image" placeholder="Image" onchange="loadFile3(event)" capture>
                                                <label id="empty"></label>
                                            </div>
                                            <small class="col-sm-12 control-label"> Size Recommended (744 * 138)</small>
                                        </div>
                                    </div>
                                   
                                    <div class="col-xl-12 form-group">
                                        <div class="col-sm-12">
                                            <input type="hidden" class="input-mask form-control" name="page_name" value="system_settings" required>
                                            <input type="submit" class="btn btn-primary" value="Update" style="float: right;"></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $("#imagePreview").click(function(e) {
            $("#imageUpload").click();
        });
        function loadFile(event) {
            var image = document.getElementById('imagePreview');
            image.src = URL.createObjectURL(event.target.files[0]);
        }

        $("#imagePreview2").click(function(e) {
            $("#imageUpload2").click();
        });
        function loadFile2(event) {
            var image = document.getElementById('imagePreview2');
            image.src = URL.createObjectURL(event.target.files[0]);
        }

        $("#imagePreview3").click(function(e) {
            $("#imageUpload3").click();
        });
        function loadFile3(event) {
            var image = document.getElementById('imagePreview3');
            image.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
@endsection