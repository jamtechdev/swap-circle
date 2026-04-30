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
        .card{
            margin-bottom:0px;
            height: calc(106% - 30px);
        }
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

        /* Hover effect (optional but clean) */
        table td .btn:hover {
            background-color: rgba(0, 0, 0, 0.03) !important;
        }

        /* Ensure icon keeps color */
        table td .btn i {
            color: inherit;
        }
    </style>
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles mb-n5">
				<ol class="breadcrumb">
                    @section('titleBar')
                    <span class="ml-2">Products Claims</span>
                    @endsection
				</ol>
            </div>
            <!-- row -->

            <div class="row">
                <div class="col-12">
                    

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table dt-responsive nowrap display min-w850">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Claimed By</th>
                                            <th>Date of Incident</th>
                                            <th>Description</th>
                                            <th>Images</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products_purchases_claims as $key => $item)
                                        <tr class="odd gradeX">
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->products_purchases->products->name }}</td>
                                            <td>{{ $item->products_purchases->users_customers->first_name.' '.$item->products_purchases->users_customers->last_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                                            <td>{!! wordwrap(e($item->description), 50, '<br>') !!}</td>
                                            <td>
                                                <div style="display: flex; gap: 8px;">
                                                    <img src="{{ asset($item->image1) }}" class="border border-2 p-1" alt="Image 1" style="width:70px; height:70px;">
                                                    <img src="{{ asset($item->image2) }}" class="border border-2 p-1" alt="Image 2" style="width:70px; height:70px;">
                                                    <img src="{{ asset($item->image3) }}" class="border border-2 p-1" alt="Image 3" style="width:70px; height:70px;">
                                                </div>
                                                </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
          	</div>
        </div>
    </div>
@endsection