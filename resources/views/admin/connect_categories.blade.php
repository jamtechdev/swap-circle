@extends('layout.admin.list_master')
@section('content')
    <style>
        .btn-light{
          padding-left:10px;
        }

         table.dataTable tbody td {
            font-size: 14px;
            padding: 12px 15px;
        }
        table.dataTable thead th {
            font-size: 14px;
            padding: 12px 15px;
        }
        table tbody tr td .btn{
            padding: 0.500rem 1.5rem;
            font-size: 14px;
        }
        .content-body .container-fluid{
            padding-top: 20px;
        }
        .container-fluid .row .btn{
            padding: 0.500rem 1.5rem;
        }
        .dataTables_length label, .dataTables_filter label{
            font-size: 14px;
            margin-bottom:0px;
        }
        /* .card{
            margin-bottom:0px;
            height: calc(96% - 30px);
        } */
        .card .card-body{
            padding: 1.875rem 1.875rem 0rem 1.875rem;
        }
        .dataTables_wrapper:after{
            display:none;
        }
        
        /* ==============================
        Action Buttons: Border Only
        (CSS-only, No HTML change)
        ============================== */

        /* Target action column buttons */
        table td .btn {
            background-color: transparent !important;
            box-shadow: none !important;
        }

        /* Keep border & icon color */
        table td .btn.btn-success {
            border: 1px solid #28a745 !important;
            color: #28a745 !important;
        }

        table td .btn.btn-danger {
            border: 1px solid #dc3545 !important;
            color: #dc3545 !important;
        }

        table td .btn.btn-warning {
            border: 1px solid #ffc107 !important;
            color: #ffc107 !important;
        }

        table td .btn.btn-secondary {
            border: 1px solid #6c757d !important;
            color: #6c757d !important;
        }

         table td .btn.btn-primary {
            border: 1px solid #6c757d !important;
            color: #6c757d !important;
        }

         table td .btn.btn-info {
            border: 1px solid #6c757d !important;
            color: #6c757d !important;
        }

        /* Hover effect (optional but clean) */
        table td .btn:hover {
            background-color: rgba(0, 0, 0, 0.03) !important;
        }

        /* Ensure icon keeps color */
        table td .btn i {
            color: inherit;
        }

        /* Connect Category modal */
        .connect-category-modal {
            background: #ffffff;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 10px;
        }

        /* Compact grid rows */
        .info-row {
            display: grid;
            grid-template-columns: 140px 12px 1fr;
            align-items: center;
            padding: 8px 12px;
            margin-bottom: 6px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: #ffffff;
            width: 446px;
        }

        /* Label */
        .info-row .label {
            font-size: 13px;
            font-weight: 500;
            color: #6b7280;
        }

        /* Colon */
        .info-row .colon {
            font-size: 13px;
            color: #6b7280;
        }

        /* Value */
        .info-row .value {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

        /* Status pill */
        .status-pill {
            padding: 3px 10px;
            border-radius: 12px;
            background: #f3f4f6;
            font-size: 12px;
            font-weight: 600;
        }

        /* Thumbnail */
        .thumb-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 10px rgba(0,0,0,0.12);
        }

        .thumb-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: zoom-in;
        }

        @media (min-width: 992px) {
            .modal-lg, .modal-xl {
                max-width: 500px;
            }
        }

    </style>
    <!-- Add Connect Category -->
    <div class="modal fade" id="exampleModalAddConnectCategory">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            @section('titleBar')
            <span class="ml-2">Add Connect Category</span>
            @endsection 
                <div class="modal-header">
                    <h5 class="modal-title">Add Connect Category</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="basic-form">

                        <div class="row col-md-12"> 
                            <div class="form-group col-md-12">
                                <b>Name</b>
                                <b><input  type="text" name="catname" class="form-control catname input" required></b>
                                <span class="error_msg" id="name_error"></span>
                            </div>
                        </div>
                        <div class="row col-md-12"> 
                            <div class="form-group col-md-12">
                                <b>Icon</b>
                                <b><input  type="file" name="icon" id="icon" class="form-control icon input" required multiple></b>
                                <span class="error_msg" id="icon_error"></span>
                                <textarea rows="10" cols="50" class="input" id="icon_string" hidden></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary add_connect_category">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Connect Category -->

    <!-- Edit Connect Category -->
    <div class="modal fade" id="editConnectCateyModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            @section('titleBar')
            <span class="ml-2">Edit Connect Category</span>
            @endsection 
                <div class="modal-header">
                    <h5 class="modal-title">Edit Connect Category</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="basic-form">

                        <div class="row col-md-12"> 
                            <div class="form-group col-md-12">
                                <b>Name</b>
                                <b><input  type="text" name="name" id="name" class="form-control input" required></b>
                            </div>
                        </div>
                        <input type="hidden" class="input" id="connect_categories_id">
                        <div class="row col-md-12"> 
                            <div class="form-group col-md-12">
                                <b>Icon</b>
                                <b><input  type="file" name="icon" id="edit_icon" class="form-control icon input" required multiple></b>
                                <textarea rows="10" cols="50" class="input" id="edit_icon_string" hidden></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <b>Old Icon</b>
                                <div class="image-box text-center mx-auto">
                                    <img src="" class="img-fluid" id="icon_preview" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="edit_connect_category">Edit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Connect Category -->

    <!-- View Connect Category -->
    <div class="modal fade" id="viewConnectCategoryModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title">View Connect Category</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
                </div>

                <!-- BODY -->
                <div class="modal-body connect-category-modal">

                    <div class="info-row">
                        <span class="label">Name</span>
                        <span class="colon">:</span>
                        <span class="value" id="cc_name">-</span>
                    </div>

                    <div class="info-row">
                        <span class="label">Status</span>
                        <span class="colon">:</span>
                        <span class="value">
                            <span id="cc_status" class="status-pill">-</span>
                        </span>
                    </div>

                    <div class="info-row image-row d-none">
                        <span class="label">Icon</span>
                        <span class="colon">:</span>
                        <span class="value">
                            <div class="thumb-wrapper">
                                <img id="cc_icon"
                                    src=""
                                    class="zoomable-image"
                                    alt="Category Icon">
                            </div>
                        </span>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>
    <!-- Edit Connect Category -->
    
    <!-- Image Zoom Modal -->
<div class="modal fade" id="imageZoomModal">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">

            <div class="modal-header border-0">
                <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body text-center">
                <img id="zoomedImage"
                     src=""
                     class="img-fluid rounded shadow-lg"
                     style="max-height:90vh;">
            </div>

        </div>
    </div>
</div>


    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles mb-n5">
				<ol class="breadcrumb">
                    @section('titleBar')
                    <span class="ml-2">Connect Categories</span>
                    @endsection
				</ol>
            </div>
            <!-- row -->

            <div class="row">
                <div class="col-12">
                    

                    <div class="card">
                        <div class="card-body">                                    
                        <legend style="float: right;"><a style="float: right;" class="btn btn-primary"  data-toggle="modal" data-target="#exampleModalAddConnectCategory"> Add Connect Category </a></legend>
                            <div class="table-responsive">
                                <table id="example" class="table dt-responsive nowrap display min-w850">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Icon</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
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
    <script>
        $(document).ready(function(){
            // --------------- IMAGE PREVIEW & BSASE64 STRING --------------- //
            function previewImage (image,string) {
                var fileImage = image.files[0];
                var reader = new FileReader();

                reader.addEventListener("load", function() {

                    document.querySelector(string).value = reader.result.toString().replace(/^data:(.*,)?/, "");
                }, false);

                if (fileImage) {
                    reader.readAsDataURL(fileImage);
                }
            }

            document.querySelector("#icon").addEventListener("change", function() {
                previewImage(this,"#icon_string");
            });
            document.querySelector("#edit_icon").addEventListener("change", function() {
                previewImage(this,"#edit_icon_string");
            });
            fetch();
            function fetch() {
                    var settings = {
                    "url": "/admin/connect_categories_fetch",
                    "method": "GET",
                    "timeout": 0,
                };

                $.ajax(settings).done(function (response) {
                                $('tbody').html("");
                                $.each(response.connectCategories, function (key, item) { 
    
                                    var statusHtml = '';
                                    if (item.status == "Pending") {
                                        statusHtml = '<span class="btn m-1 btn-info">Pending</span>';
                                    } else if (item.status == "Active") {
                                        statusHtml = '<span class="btn m-1 btn-success">Active</span>';
                                    } else if (item.status == "Inactive") {
                                        statusHtml = '<span class="btn m-1 btn-warning">Inactive</span>';
                                    } else {
                                        statusHtml = '<span class="btn m-1 btn-danger">Deleted</span>';
                                    }

                                    var actionHtml = '';
                                    
                                        actionHtml += '<button class="btn m-1 btn-primary view_connect_category" value="' + item.connect_categories_id + '">';
                                        actionHtml += '<i class="fa fa-eye"></i>';
                                        actionHtml += '</button>';
                                        
                                        actionHtml += '<button class="btn m-1 btn-info edit_connect_category"  value="' + item.connect_categories_id + '">';
                                        actionHtml += '<i class="fa fa-edit"></i>';
                                        actionHtml += '</button>';
                                    if (item.status == "Active") {
                                        actionHtml += '<button class="btn m-1 btn-warning update_data" value="' + item.connect_categories_id + '" data-info="Inactive">';
                                        actionHtml += '<i class="fa fa-times"></i>';
                                        actionHtml += '</button>';
                                        
                                    } else if (item.status == "Inactive") {
                                        actionHtml += '<button class="btn m-1 btn-success update_data" value="' + item.connect_categories_id + '" data-info="Active" >';
                                        actionHtml += '<i class="fa fa-check"></i>';
                                        actionHtml += '</button>';
                                    }

                                    if (item.status == "Pending" || item.status == "Deleted") {
                                        actionHtml += '<button class="btn m-1 btn-warning update_data" value="' + item.connect_categories_id + '" data-info="Inactive">';
                                        actionHtml += '<i class="fa fa-times"></i>';
                                        actionHtml += '</button>';
                                        actionHtml += '<button class="btn m-1 btn-success update_data" value="' + item.connect_categories_id + '" data-info="Active">';
                                        actionHtml += '<i class="fa fa-check"></i>';
                                        actionHtml += '</button>';
                                    }

                                    if (item.status != "Deleted") {
                                        actionHtml += '<button class="btn m-1 btn-danger delete_data" value="' + item.connect_categories_id + '" data-info="Deleted">';
                                        actionHtml += '<i class="fa fa-trash"></i>';
                                        actionHtml += '</button>';
                                    }
                                    if (item.status === "Deleted") {
                                        return;
                                    }
                                    var profile_image = "{{ url('/public') }}" + "/" +item.icon;
                                    $('tbody').append('\
                                        <tr class="odd gradeX">\
                                        <td>' + (key+1) + '</td>\
                                        <td>' + item.name + '</td>\
                                        <td><img src="'+profile_image+'" class="img-fluid" alt="" srcset="" width="50px" height="50px"></td>\
                                        <td>' + statusHtml + '</td>\
                                        <td>' + actionHtml + '</td>\
                                        </tr>\
                                    ');
                                    });
                });
            }

            $(document).on("click",'.edit_connect_category', function (e) {
                e.preventDefault();
                var connect_categories_id=$(this).val();
                $('#editConnectCateyModal').modal('show');
                var settings = {
                "url": "/admin/connect_category_edit/"+connect_categories_id,
                "method": "GET",
                "timeout": 0,
            };

            $.ajax(settings).done(function (response) {
                        var profile_image = "{{ url('/public') }}" + "/" +response.data.icon;
                        if(response.status == "error"){
                            toastr.success(response.message);
                        }else{
                            $('#name').val(response.data.name);
                            $('#connect_categories_id').val(response.data.connect_categories_id);
                            $('#icon_preview').attr('src', profile_image);
                        }
                    });
            });

            $(document).on("click",'.delete_data', function (e) {
                    e.preventDefault();
                    var connect_categories_id=$(this).val();;
                    var settings = {
                    "url": "/admin/connect_category_delete",
                    "method": "POST",
                    "timeout": 0,
                    "data": {
                        'connect_categories_id':connect_categories_id,
                    },
                };
                $.ajax(settings).done(function (response) {
                    if(response.status == "success"){ 
                        fetch();
                        toastr.success(response.message);
                    }else{
                        toastr.success(response.message);
                    }
                });
            });

            $(document).on("click",'.update_data', function (e) {
                    e.preventDefault();
                    var connect_categories_id=$(this).val();
                    var status=$(this).data("info");
                    var settings = {
                    "url": "/admin/connect_category_update",
                    "method": "POST",
                    "timeout": 0,
                    "data": {
                        'connect_categories_id':connect_categories_id,
                        'status':status,
                    },
                };
                $.ajax(settings).done(function (response) {
                    if(response.status == "success"){ 
                        fetch();
                        toastr.success(response.message);
                    }else{
                        toastr.success(response.message);
                    }
                });
            });


            $(document).on("click", ".view_connect_category", function (e) {
                e.preventDefault();

                var connect_categories_id = $(this).val();
                $('#viewConnectCategoryModal').modal('show');

                $.get("/admin/connect_category_edit/" + connect_categories_id, function (response) {

                    if (response.status === "success") {

                        $('#cc_name').text(response.data.name);
                        $('#cc_status').text(response.data.status);

                        if (response.data.icon) {
                            $('.image-row').removeClass('d-none');
                            $('#cc_icon').attr(
                                'src',
                                "{{ url('/public') }}/" + response.data.icon
                            );
                        } else {
                            $('.image-row').addClass('d-none');
                        }

                    } else {
                        toastr.error(response.message);
                    }
                });
            });

              /* ---------------- IMAGE ZOOM ---------------- */
            $(document).on('click', '.zoomable-image', function () {
                $('#zoomedImage').attr('src', $(this).attr('src'));
                $('#imageZoomModal').modal('show');
            });


            
            $(document).on('click','#edit_connect_category',function(e){
                e.preventDefault();
                var settings = {
                "url": "/admin/connect_category_edit_data",
                "method": "POST",
                "timeout": 0,
                "data": {
                    'connect_categories_id':$("#connect_categories_id").val(),
                    'name':$('#name').val(),                    
                    'icon_image':$('#edit_icon_string').val(),
                },
             };

            $.ajax(settings).done(function (response) {
                        if (response.status == "success") {
                            toastr.success(response.message);
                            fetch();
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            $('.modal').removeClass('show');
                        } else {
                            toastr.error(response.message);
                            fetch();
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            $('.modal').removeClass('show');
                        }
            });
        });
        $(document).on('click','.add_connect_category',function(e){
                e.preventDefault();
                var settings = {
                "url": "/admin/connect_category_add_data",
                "method": "POST",
                "timeout": 0,
                "data": {
                    'name':$('.catname').val(),
                    'icon_image':$('#icon_string').val(),
                },
            };

            $.ajax(settings).done(function (response) {
                if (response.status == "success") {
                    toastr.success(response.message);
                    fetch();
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    $('.modal').removeClass('show');
                    $( ".input" ).each(function() {
                        $(this).val("");
                    });
                } else {
                    toastr.error(response.message);
                    fetch();
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    $('.modal').removeClass('show');
                    $( ".input" ).each(function() {
                        $(this).val("");
                    });
                }
            });
        });


        });
    </script>
@endsection