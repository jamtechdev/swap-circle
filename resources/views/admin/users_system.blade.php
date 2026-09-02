@extends('layout.admin.list_master')

@section('titleBar')
<span>Manage Users System</span>
@endsection

@section('content')
@php
    $roles = DB::table('users_system_roles')->get()->keyBy('users_system_roles_id');
@endphp

<div class="content-body">
    <div class="container-fluid">
        <div class="admin-filter-bar">
            <div class="admin-filter-actions">
                <p class="admin-page-lead mb-0">Manage admin portal accounts, roles, and access status.</p>
            </div>
            <div class="admin-filter-cta">
                <a class="btn btn-primary btn-sm" href="{{ url('/admin/users_system_add') }}">
                    Add User <i class="fas fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <div class="card admin-table-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped display w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Status</th>
                                <th>ID</th>
                                <th>Role</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>City</th>
                                <th>Address</th>
                                <th>Image</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $items)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    @if ($items->status === 'Active')
                                    <span class="admin-status-badge admin-status-badge--active">Active</span>
                                    @elseif ($items->status === 'Deleted')
                                    <span class="admin-status-badge admin-status-badge--deleted">Deleted</span>
                                    @else
                                    <span class="admin-status-badge admin-status-badge--inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $items->users_system_id }}</td>
                                <td>{{ optional($roles->get($items->users_system_roles_id))->name ?? '—' }}</td>
                                <td>{{ $items->first_name }}</td>
                                <td>{{ $items->mobile }}</td>
                                <td>{{ $items->email }}</td>
                                <td>{{ $items->city }}</td>
                                <td>{{ $items->address }}</td>
                                <td>
                                    @if ($items->user_image)
                                    <img src="{{ asset($items->user_image) }}" alt="" class="admin-connect-thumb">
                                    @else
                                    <img src="{{ asset('uploads/placeholder/default.png') }}" alt="" class="admin-connect-thumb">
                                    @endif
                                </td>
                                <td>{{ $items->created_at }}</td>
                                <td>{{ $items->updated_at }}</td>
                                <td>
                                    @if ($items->users_system_id != session('id'))
                                    <div class="admin-action-group">
                                        <a class="btn btn-info" href="{{ url('/admin/users_system_edit/' . $items->users_system_id) }}" title="Edit">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>

                                        @if ($items->status === 'Active')
                                        <a class="btn btn-warning" href="{{ url('/admin/users_system_update/' . $items->users_system_id . '/Inactive') }}" title="Deactivate">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                        @elseif ($items->status !== 'Deleted')
                                        <a class="btn btn-success" href="{{ url('/admin/users_system_update/' . $items->users_system_id . '/Active') }}" title="Activate">
                                            <i class="fa fa-check" aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if ($items->status !== 'Deleted')
                                        <a class="btn btn-danger" href="{{ url('/admin/users_system_delete/' . $items->users_system_id) }}" title="Delete">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-muted">Current user</span>
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
@endsection
