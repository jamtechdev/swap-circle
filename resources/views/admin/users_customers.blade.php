@extends('layout.admin.list_master')
@section('content')
    @php
        $filter = $filter ?? '';
    @endphp
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
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                        <div>
                            <a class="btn {{ ($filter ?? '') == '' ? 'btn-primary' : 'btn-info' }}" href="users_customers" style="color: white; margin-bottom: 10px;">All</a>
                            <a class="btn {{ ($filter ?? '') == 'Pending' ? 'btn-primary' : 'btn-info' }}" href="users_customers?filter=Pending" style="color: white; margin-bottom: 10px;">Pending</a>
                            <a class="btn {{ ($filter ?? '') == 'Active' ? 'btn-primary' : 'btn-info' }}" href="users_customers?filter=Active" style="color: white; margin-bottom: 10px;">Active</a>
                            <a class="btn {{ ($filter ?? '') == 'Inactive' ? 'btn-primary' : 'btn-info' }}" href="users_customers?filter=Inactive" style="color: white; margin-bottom: 10px;">Inactive</a>
                            <a class="btn {{ ($filter ?? '') == 'Deleted' ? 'btn-primary' : 'btn-info' }}" href="users_customers?filter=Deleted" style="color: white; margin-bottom: 10px;">Deleted</a>
                        </div>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_add_customer" style="margin-bottom: 10px;">
                            Add User <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <br>

                    <div class="modal fade" id="modal_add_customer">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add User</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <form method="post" action="{{ url('/admin/users_customers_add_data') }}">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label><b>Account Type</b></label>
                                                <select name="users_customers_type" class="form-control" required>
                                                    <option value="Individual">Individual</option>
                                                    <option value="Company">Company</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label><b>Status</b></label>
                                                <select name="status" class="form-control" required>
                                                    <option value="Active">Active</option>
                                                    <option value="Pending">Pending</option>
                                                    <option value="Inactive">Inactive</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label><b>First Name</b></label>
                                                <input type="text" name="first_name" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label><b>Last Name</b></label>
                                                <input type="text" name="last_name" class="form-control">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label><b>Company Name</b></label>
                                                <input type="text" name="company_name" class="form-control">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label><b>Email</b></label>
                                                <input type="email" name="email" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label><b>Phone</b></label>
                                                <input type="text" name="phone" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label><b>Password</b></label>
                                                <input type="password" name="password" class="form-control" minlength="7" required>
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label><b>Location</b></label>
                                                <input type="text" name="location" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Create User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

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

                                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_edit_customer{{ $items->users_customers_id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>

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
                                        <div class="modal fade" id="modal_edit_customer{{ $items->users_customers_id }}">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit User</h5>
                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                    </div>
                                                    <form method="post" action="{{ url('/admin/users_customers_edit_data') }}">
                                                        @csrf
                                                        <input type="hidden" name="users_customers_id" value="{{ $items->users_customers_id }}">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6 form-group">
                                                                    <label><b>Account Type</b></label>
                                                                    <select name="users_customers_type" class="form-control" required>
                                                                        <option value="Individual" {{ ($items->users_customers_type ?? '') == 'Individual' ? 'selected' : '' }}>Individual</option>
                                                                        <option value="Company" {{ ($items->users_customers_type ?? '') == 'Company' ? 'selected' : '' }}>Company</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label><b>Status</b></label>
                                                                    <select name="status" class="form-control" required>
                                                                        <option value="Pending" {{ ($items->status ?? '') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                                        <option value="Active" {{ ($items->status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                                                                        <option value="Inactive" {{ ($items->status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                                        <option value="Deleted" {{ ($items->status ?? '') == 'Deleted' ? 'selected' : '' }}>Deleted</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label><b>First Name</b></label>
                                                                    <input type="text" name="first_name" class="form-control" value="{{ $items->first_name }}" required>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label><b>Last Name</b></label>
                                                                    <input type="text" name="last_name" class="form-control" value="{{ $items->last_name }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label><b>Company Name</b></label>
                                                                    <input type="text" name="company_name" class="form-control" value="{{ $items->company_name }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label><b>Email</b></label>
                                                                    <input type="email" name="email" class="form-control" value="{{ $items->email }}" required>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label><b>Phone</b></label>
                                                                    <input type="text" name="phone" class="form-control" value="{{ $items->phone }}" required>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label><b>New Password</b> <small class="text-muted">(leave blank to keep old)</small></label>
                                                                    <input type="password" name="password" class="form-control" minlength="7">
                                                                </div>
                                                                <div class="col-md-12 form-group">
                                                                    <label><b>Location</b></label>
                                                                    <input type="text" name="location" class="form-control" value="{{ $items->location }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save User</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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