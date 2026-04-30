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

        /* ===== PERFECT VIEW FAQ MODAL ===== */

        .faq-view-wrapper {
            padding: 8px 4px;
        }

        /* Each field as card row */
        .faq-view-wrapper .info-row {
            display: grid;
            grid-template-columns: 100px 12px 1fr;
            align-items: center;
            padding: 12px 16px;
            margin-bottom: 12px;
            border-radius: 10px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        /* Label */
        .faq-view-wrapper .label {
            font-size: 13px;
            font-weight: 500;
            color: #6b7280;
        }

        /* Colon */
        .faq-view-wrapper .colon {
            color: #9ca3af;
        }

        /* Value */
        .faq-view-wrapper .value {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            line-height: 1.6;
        }

        /* Answer block */
        .faq-answer {
            max-height: 110px;
            overflow-y: auto;
            padding-right: 6px;
        }

        /* Status pill */
        .faq-status {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            background: #e0f2fe;
            color: #0369a1;
        }

        /* Modal footer button */
        .modal-footer .btn-danger.light {
            padding: 10px 26px;
            border-radius: 14px;
            font-weight: 600;
        }

                /* Compact info row (perfect alignment) */
        .info-row {
            display: grid;
            grid-template-columns: 120px 10px 1fr;
            align-items: center;
            padding: 10px 14px;
            margin-bottom: 8px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #ffffff;
            width: 100%;
        }

        /* Label */
        .info-row .label {
            font-size: 13px;
            font-weight: 500;
            color: #6b7280;
            text-align: start !important;
        }
        
        /* Value */
        .info-row .value {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

    </style>
    <!-- Add FAQ -->
    <div class="modal fade" id="exampleModalAddFaq">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            @section('titleBar')
            <span class="ml-2">Add FAQ</span>
            @endsection 
                <div class="modal-header">
                    <h5 class="modal-title">Add FAQ</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="basic-form">

                        <div class="row col-md-12"> 
                            <div class="form-group col-md-12">
                                <b>Question</b>
                                <b><input  type="text" name="question" class="form-control question input" required></b>
                            </div>
                        </div>
                        
                        <div class="row col-md-12"> 
                            <div class="form-group col-md-12">
                                <b>Answer</b>
                                <b><textarea style="border:1px solid" class="form-control answer input" name="answer" id="anwser" cols="20" rows="5" required></textarea></b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary add_faq">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add FAQ -->

    <!-- Edit FAQ -->
    <div class="modal fade" id="editfaqModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            @section('titleBar')
            <span class="ml-2">Edit FAQ</span>
            @endsection 
                <div class="modal-header">
                    <h5 class="modal-title">Edit FAQ</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="basic-form">

                        <div class="row col-md-12"> 
                            <div class="form-group col-md-12">
                                <b>Question</b>
                                <b><input  type="text" name="question" id="question" class="form-control input" required></b>
                            </div>
                        </div>
                        <input type="hidden" class="input" id="faqs_id">
                        
                        <div class="row col-md-12"> 
                            <div class="form-group col-md-12">
                                <b>Answer</b>
                                <b><textarea style="border:1px solid" id="answer" class="form-control input" name="answer" id="anwser" cols="20" rows="5" required></textarea></b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="edit_faq">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit FAQ -->

    <!-- View FAQ -->
    <div class="modal fade" id="viewfaqModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            @section('titleBar')
            <span class="ml-2">View FAQ</span>
            @endsection 
                <div class="modal-header">
                    <h5 class="modal-title">View FAQ</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="FaqViewModel">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit FAQ -->

    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles mb-n5">
				<ol class="breadcrumb">
                    @section('titleBar')
                    <span class="ml-2">FAQs</span>
                    @endsection
				</ol>
            </div>
            <!-- row -->

            <div class="row">
                <div class="col-12">
                    

                    <div class="card">
                        <div class="card-body">                                    
                        <legend style="float: right;"><a style="float: right;" class="btn btn-primary"  data-toggle="modal" data-target="#exampleModalAddFaq"> Add FAQ </a></legend>
                            <div class="table-responsive">
                                <table id="example" class="table dt-responsive nowrap display min-w850">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Question</th>
                                            <th>Answer</th>
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
            fetch();
            function fetch() {
                        var settings = {
                        "url": "/admin/users_customers_faqs_fetch",
                        "method": "GET",
                        "timeout": 0,
                    };

                    $.ajax(settings).done(function (response) {
                        $('tbody').html("");
                        $.each(response.faqs, function (key, item) { 
                            var questiontxt= item.question;
                            var question='';
                            if(questiontxt.length > 40){
                                question=questiontxt.substring(0,40) + '.....';
                            }else{
                                question=item.question;
                            }

                            var txt= item.answer;
                            var ans='';
                            if(txt.length > 40){
                                ans=txt.substring(0,40) + '.....';
                            }else{
                                ans=item.answer;
                            }

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
                            
                                actionHtml += '<button class="btn m-1 btn-primary view_faq" value="' + item.faqs_id + '">';
                                actionHtml += '<i class="fa fa-eye"></i>';
                                actionHtml += '</button>';
                                
                                actionHtml += '<button class="btn m-1 btn-info edit_faq"  value="' + item.faqs_id + '">';
                                actionHtml += '<i class="fa fa-edit"></i>';
                                actionHtml += '</button>';
                            if (item.status == "Active") {
                                actionHtml += '<button class="btn m-1 btn-warning update_data" value="' + item.faqs_id + '" data-info="Inactive">';
                                actionHtml += '<i class="fa fa-times"></i>';
                                actionHtml += '</button>';
                                
                            } else if (item.status == "Inactive") {
                                actionHtml += '<button class="btn m-1 btn-success update_data" value="' + item.faqs_id + '" data-info="Active" >';
                                actionHtml += '<i class="fa fa-check"></i>';
                                actionHtml += '</button>';
                            }

                            if (item.status == "Pending" || item.status == "Deleted") {
                                actionHtml += '<button class="btn m-1 btn-warning update_data" value="' + item.faqs_id + '" data-info="Inactive">';
                                actionHtml += '<i class="fa fa-times"></i>';
                                actionHtml += '</button>';
                                actionHtml += '<button class="btn m-1 btn-success update_data" value="' + item.faqs_id + '" data-info="Active">';
                                actionHtml += '<i class="fa fa-check"></i>';
                                actionHtml += '</button>';
                            }

                            if (item.status != "Deleted") {
                                actionHtml += '<button class="btn m-1 btn-danger delete_data" value="' + item.faqs_id + '" data-info="Deleted">';
                                actionHtml += '<i class="fa fa-trash"></i>';
                                actionHtml += '</button>';
                            }

                            $('tbody').append('\
                                <tr class="odd gradeX">\
                                <td>' + (key+1) + '</td>\
                                <td>' + question + '</td>\
                                <td>' + ans + '</td>\
                                <td>' + statusHtml + '</td>\
                                <td>' + actionHtml + '</td>\
                                </tr>\
                            ');
                        });
                });
            }

            $(document).on("click",'.edit_faq', function (e) {
                    e.preventDefault();
                    var faqs_id=$(this).val();
                    $('#editfaqModal').modal('show');
                    var settings = {
                    "url": "/admin/users_customers_edit_faq/"+faqs_id,
                    "method": "GET",
                    "timeout": 0,
                };

                $.ajax(settings).done(function (response) {
                    if(response.status == "error"){
                        toastr.success(response.message);
                    }else{
                        $('#question').val(response.data.question);
                        $('#answer').val(response.data.answer);
                        $('#faqs_id').val(response.data.faqs_id);
                    }
                });
            });

            $(document).on("click",'.delete_data', function (e) {
                    e.preventDefault();
                    var faqs_id=$(this).val();;
                    var settings = {
                    "url": "/admin/users_customers_delete_faq",
                    "method": "POST",
                    "timeout": 0,
                    "data": {
                        'faqs_id':faqs_id,
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
                    var faqs_id=$(this).val();
                    var status=$(this).data("info");
                    var settings = {
                    "url": "/admin/users_customers_update_faq",
                    "method": "POST",
                    "timeout": 0,
                    "data": {
                        'faqs_id':faqs_id,
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


            $(document).on("click", ".view_faq", function (e) {
                e.preventDefault();

                let faqs_id = $(this).val();
                $('#viewfaqModal').modal('show');

                $.get("/admin/users_customers_edit_faq/" + faqs_id, function (response) {

                    if (response.status !== "success") {
                        toastr.error(response.message);
                        return;
                    }

                    $('#FaqViewModel').html(`
                        <div class="info-row">
                            <span class="label">Question</span>
                            <span class="colon">:</span>
                            <span class="value">${response.data.question}</span>
                        </div>

                        <div class="info-row">
                            <span class="label">Answer</span>
                            <span class="colon">:</span>
                            <span class="value faq-answer">
                                ${response.data.answer}
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="label">Status</span>
                            <span class="colon">:</span>
                            <span class="value">
                                <span class="faq-status">${response.data.status}</span>
                            </span>
                        </div>
                    `);
                });
            });

            $(document).on('click','#edit_faq',function(e){
                    e.preventDefault();
                    var settings = {
                    "url": "/admin/users_customers_edit_faq_data",
                    "method": "POST",
                    "timeout": 0,
                    "data": {
                        'faqs_id':$("#faqs_id").val(),
                        'question':$('#question').val(),
                        'answer':$('#answer').val(),
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
            $(document).on('click','.add_faq',function(e){
                    e.preventDefault();
                    var settings = {
                    "url": "/admin/users_customers_add_faq_data",
                    "method": "POST",
                    "timeout": 0,
                    "data": {
                        'question':$('.question').val(),
                        'answer':$('.answer').val(),
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