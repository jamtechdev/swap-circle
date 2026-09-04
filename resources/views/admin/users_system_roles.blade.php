@extends('layout.admin.list_master')

@section('titleBar')
<span>Manage System Roles</span>
@endsection

@section('content')
@php
    $permissionColumns = [
        'dashboard' => 'Dashboard',
        'users_customers' => 'Users Customers',
        'users_system' => 'Users System',
        'users_system_roles' => 'System Roles',
        'system_settings' => 'System Settings',
        'account_settings' => 'Accounts Settings',
        'swap_offers' => 'Swap Offers',
        'users_customers_trxns' => 'Customer Transactions',
        'admin_rate' => 'Admin Rate',
        'rate_api' => 'Rate API',
        'currency_rate' => 'Currency Rate',
        'connect_categories' => 'Connect Categories',
        'connect_articles' => 'Connect Articles',
        'users_customers_faqs' => 'Customer FAQs',
        'fund_wallet_requests' => 'Fund Wallet Requests',
    ];
@endphp

<div class="content-body">
    <div class="container-fluid">
        <div class="admin-filter-bar">
            <div class="admin-filter-actions">
                <p class="admin-page-lead mb-0">Define role permissions for each admin module.</p>
            </div>
            <div class="admin-filter-cta">
                <a class="btn btn-primary btn-sm" href="{{ url('/admin/users_system_roles_add') }}">
                    @include('partials.admin.icon-plus') Add Role
                </a>
            </div>
        </div>

        <div class="card admin-table-card admin-table-card--wide">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped display w-100 admin-roles-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Status</th>
                                <th>Name</th>
                                @foreach ($permissionColumns as $label)
                                <th>{{ $label }}</th>
                                @endforeach
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users_system_roles as $key => $items)
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
                                <td>{{ $items->name }}</td>
                                @foreach (array_keys($permissionColumns) as $field)
                                <td>
                                    @if (($items->{$field} ?? '') === 'Yes')
                                    <span class="admin-perm-yes">Yes</span>
                                    @else
                                    <span class="admin-perm-no">No</span>
                                    @endif
                                </td>
                                @endforeach
                                <td>
                                    <div class="admin-action-group">
                                        <a class="btn btn-info" href="{{ url('/admin/users_system_roles_edit/' . $items->users_system_roles_id) }}" title="Edit">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>

                                        @if ($items->status !== 'Deleted')
                                        <a class="btn btn-danger" href="{{ url('/admin/users_system_roles_delete/' . $items->users_system_roles_id) }}" title="Delete">
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
@endsection
