@extends('layout.admin.list_master')

@section('titleBar')
<span>Manage Users</span>
@endsection

@section('content')
@php
    $filter = $filter ?? '';
@endphp
<div class="content-body">
    <div class="container-fluid">
        <div class="admin-filter-bar">
            <div class="admin-filter-actions">
                <a class="btn {{ $filter === '' ? 'btn-primary' : 'btn-info' }}" href="{{ url('admin/users_customers') }}">All</a>
                <a class="btn {{ $filter === 'Pending' ? 'btn-primary' : 'btn-info' }}" href="{{ url('admin/users_customers?filter=Pending') }}">Pending</a>
                <a class="btn {{ $filter === 'Active' ? 'btn-primary' : 'btn-info' }}" href="{{ url('admin/users_customers?filter=Active') }}">Active</a>
                <a class="btn {{ $filter === 'Inactive' ? 'btn-primary' : 'btn-info' }}" href="{{ url('admin/users_customers?filter=Inactive') }}">Inactive</a>
                <a class="btn {{ $filter === 'Deleted' ? 'btn-primary' : 'btn-info' }}" href="{{ url('admin/users_customers?filter=Deleted') }}">Deleted</a>
            </div>
            <div class="admin-filter-cta">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_add_customer">
                    Add User <i class="fas fa-plus" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <div class="modal fade" id="modal_add_customer" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form method="post" action="{{ url('/admin/users_customers_add_data') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="form-label">Account Type</label>
                                    <select name="users_customers_type" class="form-control" required>
                                        <option value="Individual">Individual</option>
                                        <option value="Company">Company</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="Active">Active</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" name="company_name" class="form-control">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" minlength="7" required>
                                </div>
                                <div class="col-md-12 form-group mb-0">
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card admin-table-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped display w-100">
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
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $items->users_customers_type }}</td>
                                <td>
                                    <div class="admin-user-cell">
                                        @if($items->profile_pic)
                                        <img src="{{ asset($items->profile_pic) }}" alt="">
                                        @else
                                        <img src="{{ asset('uploads/placeholder/default.png') }}" alt="">
                                        @endif
                                        <span>{{ trim($items->first_name . ' ' . $items->last_name) }}</span>
                                    </div>
                                </td>
                                <td>{{ $items->email }}</td>
                                <td>{{ $items->phone }}</td>
                                <td>
                                    @if ($items->status === 'Pending')
                                    <span class="admin-status-badge admin-status-badge--pending">Pending</span>
                                    @elseif ($items->status === 'Active')
                                    <span class="admin-status-badge admin-status-badge--active">Active</span>
                                    @elseif ($items->status === 'Inactive')
                                    <span class="admin-status-badge admin-status-badge--inactive">Inactive</span>
                                    @else
                                    <span class="admin-status-badge admin-status-badge--deleted">Deleted</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="admin-action-group">
                                        <a class="btn btn-secondary" href="{{ url('/admin/users_customers_view/' . $items->users_customers_id) }}" title="View">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>

                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_edit_customer{{ $items->users_customers_id }}" title="Edit">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </button>

                                        @if ($items->status === 'Active')
                                        <a class="btn btn-warning" href="{{ url('/admin/users_customers_update/' . $items->users_customers_id . '/Inactive') }}" title="Deactivate">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                        @elseif ($items->status === 'Inactive')
                                        <a class="btn btn-success" href="{{ url('/admin/users_customers_update/' . $items->users_customers_id . '/Active') }}" title="Activate">
                                            <i class="fa fa-check" aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if ($items->status === 'Pending' || $items->status === 'Deleted')
                                        <a class="btn btn-warning" href="{{ url('/admin/users_customers_update/' . $items->users_customers_id . '/Inactive') }}" title="Deactivate">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                        <a class="btn btn-success" href="{{ url('/admin/users_customers_update/' . $items->users_customers_id . '/Active') }}" title="Activate">
                                            <i class="fa fa-check" aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if ($items->status !== 'Deleted')
                                        <a class="btn btn-danger" href="{{ url('/admin/users_customers_delete/' . $items->users_customers_id) }}" title="Delete">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                        @endif
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

@foreach ($users as $items)
<div class="modal fade" id="modal_edit_customer{{ $items->users_customers_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ url('/admin/users_customers_edit_data') }}">
                @csrf
                <input type="hidden" name="users_customers_id" value="{{ $items->users_customers_id }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label">Account Type</label>
                            <select name="users_customers_type" class="form-control" required>
                                <option value="Individual" {{ ($items->users_customers_type ?? '') === 'Individual' ? 'selected' : '' }}>Individual</option>
                                <option value="Company" {{ ($items->users_customers_type ?? '') === 'Company' ? 'selected' : '' }}>Company</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="Pending" {{ ($items->status ?? '') === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Active" {{ ($items->status ?? '') === 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ ($items->status ?? '') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="Deleted" {{ ($items->status ?? '') === 'Deleted' ? 'selected' : '' }}>Deleted</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="{{ $items->first_name }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="{{ $items->last_name }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="{{ $items->company_name }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $items->email }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ $items->phone }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">New Password <small class="text-muted">(leave blank to keep old)</small></label>
                            <input type="password" name="password" class="form-control" minlength="7">
                        </div>
                        <div class="col-md-12 form-group mb-0">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" value="{{ $items->location }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
