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
            height: calc(96% - 30px);
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
                    <span class="ml-2">Manage Users</span>
                    @endsection
				</ol>
            </div>
            <!-- row -->

            <div class="row">
                <div class="col-12">
                    <a class="btn <?php if($filter == '') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_customers" style="color: white; margin-bottom: 20px;">All</a>
                    <a class="btn <?php if($filter == 'Pending') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_customers?filter=Pending" style="color: white; margin-bottom: 20px;">Pending</a>
                    <a class="btn <?php if($filter == 'Active') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_customers?filter=Active" style="color: white; margin-bottom: 20px;">Active</a>
                    <a class="btn <?php if($filter == 'Inactive') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_customers?filter=Inactive" style="ccolor: white; margin-bottom: 20px;">Inactive</a>
                    <a class="btn <?php if($filter == 'Deleted') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_customers?filter=Deleted" style="color: white; margin-bottom: 20px;">Deleted</a>     
                    <br>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table dt-responsive nowrap display min-w850">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Account Type</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $key => $items)
                                        <tr class="odd gradeX">
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $items->users_customers_type }}</td>
                                            <td>
                                                @if($items->profile_pic)  
                                                <img src="{{ asset($items->profile_pic)}}" width="50px" height="50px">
                                                @else
                                                <img src="{{asset('uploads/placeholder/default.png')}}" height="50px" width="50px">
                                                @endif

                                                {{ $items->first_name }} {{ $items->last_name }}
                                            </td>
                                            <td>{{ $items->email }}</td>
                                            <td>{{ $items->phone }}</td>
                                            <td>
                                                @if ($items->status=='Pending')
                                                <span class="btn btn-info">Pending</span>
                                                @elseif ($items->status=='Active')
                                                <span class="btn btn-success">Active</span>
                                                @elseif ($items->status=='Inactive')
                                                <span class="btn btn-warning">Inactive</span>
                                                @else 
                                                <span class="btn btn-danger">Deleted</span>
                                                @endif
                                            </td>

                                            <td>
                                                <a class="btn btn-secondary" href="{{url('/admin/users_customers_view/' . $items->users_customers_id)}}"> 
                                                    <i class="fa fa-eye"></i> 
                                                </a>

                                                @if ($items->status=='Active')
                                                <a class="btn btn-warning" href="{{url('/admin/users_customers_update/' . $items->users_customers_id.'/Inactive')}}"> 
                                                    <i class="fa fa-times"></i> 
                                                </a>
                                                @elseif ($items->status=='Inactive')
                                                <a class="btn btn-success" href="{{url('/admin/users_customers_update/' . $items->users_customers_id.'/Active')}}"> 
                                                    <i class="fa fa-check"></i> 
                                                </a>
                                                @endif

                                                @if ($items->status=='Pending' || $items->status=='Deleted')
                                                <a class="btn btn-warning" href="{{url('/admin/users_customers_update/' . $items->users_customers_id.'/Inactive')}}"> 
                                                    <i class="fa fa-times"></i> 
                                                </a>

                                                <a class="btn btn-success" href="{{url('/admin/users_customers_update/' . $items->users_customers_id.'/Active')}}"> 
                                                    <i class="fa fa-check"></i> 
                                                </a>
                                                @endif

                                                @if ($items->status!='Deleted')
                                                <a class="btn btn-danger" href="{{url('/admin/users_customers_delete/' . $items->users_customers_id)}}"> 
                                                    <i class="fa fa-trash"></i> 
                                                </a>
                                                @endif
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