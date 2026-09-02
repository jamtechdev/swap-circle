@extends('layout.admin.list_master')

@section('titleBar')
<span>View Profile</span>
@endsection

@section('content')
@php
    $user = $users_data[0] ?? null;
    $profilePic = ($user && !empty($user->profile_pic))
        ? asset($user->profile_pic)
        : asset('uploads/placeholder/default.png');
    $validDocument = ($user && !empty($user->valid_document)) ? $user->valid_document : null;
@endphp

<div class="content-body">
    <div class="container-fluid">
        @if(!$user)
        <div class="card admin-table-card">
            <div class="card-body">
                <p class="mb-0 text-muted">User not found.</p>
            </div>
        </div>
        @else
        <div class="admin-profile-toolbar">
            <ol class="admin-profile-breadcrumb">
                <li><a href="{{ url('admin/users_customers') }}">Users</a></li>
                <li aria-hidden="true">/</li>
                <li class="is-active">View Profile</li>
            </ol>
            <a href="{{ url('admin/users_customers') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Users
            </a>
        </div>

        <div class="card admin-profile-header">
            <div class="card-body">
                <div class="admin-profile-identity">
                    <div class="admin-profile-avatar">
                        <img src="{{ $profilePic }}" alt="{{ $user->first_name }} {{ $user->last_name }}" onerror="this.src='{{ asset('uploads/placeholder/default.png') }}'">
                    </div>
                    <div>
                        @if($user->users_customers_type === 'Company')
                        <h1 class="admin-profile-name">
                            {{ $user->company_name }}
                            <small>Representative: {{ trim($user->first_name . ' ' . $user->last_name) }}</small>
                        </h1>
                        @else
                        <h1 class="admin-profile-name">{{ trim($user->first_name . ' ' . $user->last_name) }}</h1>
                        @endif
                        @if(!empty($user->location))
                        <p class="admin-profile-meta">{{ $user->location }}</p>
                        @endif
                    </div>
                </div>

                @if($user->status === 'Pending')
                <span class="admin-status-badge admin-status-badge--pending">{{ $user->status }}</span>
                @elseif($user->status === 'Active')
                <span class="admin-status-badge admin-status-badge--active">{{ $user->status }}</span>
                @elseif($user->status === 'Inactive')
                <span class="admin-status-badge admin-status-badge--inactive">{{ $user->status }}</span>
                @else
                <span class="admin-status-badge admin-status-badge--deleted">{{ $user->status }}</span>
                @endif
            </div>
        </div>

        <div class="card admin-profile-card">
            <div class="card-body">
                <div class="admin-profile-section-head">
                    <h5>General</h5>
                </div>

                <div class="admin-profile-grid">
                    <div>
                        @if($user->users_customers_type === 'Company')
                        <div class="admin-profile-field">
                            <div class="admin-profile-field-label">Company Name</div>
                            <div class="admin-profile-field-value">{{ $user->company_name ?: '—' }}</div>
                        </div>
                        @endif
                        <div class="admin-profile-field">
                            <div class="admin-profile-field-label">Account Type</div>
                            <div class="admin-profile-field-value">{{ $user->users_customers_type ?: '—' }}</div>
                        </div>
                    </div>

                    <div>
                        <div class="admin-profile-field">
                            <div class="admin-profile-field-label">Full Name</div>
                            <div class="admin-profile-field-value">{{ trim($user->first_name . ' ' . $user->last_name) ?: '—' }}</div>
                        </div>
                        <div class="admin-profile-field">
                            <div class="admin-profile-field-label">Email</div>
                            <div class="admin-profile-field-value">{{ $user->email ?: '—' }}</div>
                        </div>
                    </div>

                    <div>
                        <div class="admin-profile-field">
                            <div class="admin-profile-field-label">Phone Number</div>
                            <div class="admin-profile-field-value">{{ $user->phone ?: '—' }}</div>
                        </div>
                        <div class="admin-profile-field">
                            <div class="admin-profile-field-label">Location</div>
                            <div class="admin-profile-field-value">{{ $user->location ?: '—' }}</div>
                        </div>
                    </div>
                </div>

                <div class="admin-profile-document">
                    <div class="admin-profile-field-label">Valid Document</div>
                    @if($validDocument)
                    <a href="{{ asset($validDocument) }}" target="_blank" rel="noopener noreferrer" class="admin-document-preview-wrap">
                        <img
                            src="{{ asset($validDocument) }}"
                            alt="Valid document"
                            class="admin-document-preview"
                            onerror="this.closest('.admin-document-preview-wrap').outerHTML='<div class=\'admin-document-empty\'><i class=\'fa fa-file-image\' aria-hidden=\'true\'></i><p>Document file could not be loaded</p></div>';"
                        >
                    </a>
                    @else
                    <div class="admin-document-empty">
                        <i class="fa fa-file-alt" aria-hidden="true"></i>
                        <p>No document uploaded</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
