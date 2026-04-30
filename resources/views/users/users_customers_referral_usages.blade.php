@extends('layout.users.master')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid px-4 pb-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ url('/users/profile') }}">
                        <svg width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 20L0 10L10 0L11.775 1.775L3.55 10L11.775 18.225L10 20Z" fill="#4BD16F"/>
                        </svg>
                    </a>
                    <h3 class="sub-heading text-black fw-bold mb-0">Referral Code Usage</h3>
                </div>
                <div>
                    <span class="text-muted small">Your Code: </span>
                    <span class="fw-bold text-success">{{ $refer_code ?? 'N/A' }}</span>
                    <span class="ms-3 text-muted small">Total Used: </span>
                    <span class="fw-bold text-success">{{ $usages->count() }}</span>
                </div>
            </div>

            <div class="card border-0 rounded-4">
                <div class="card-body p-0">
                    @if($usages->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Date Used</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usages as $index => $usage)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($usage->profile_pic)
                                                <img src="{{ asset($usage->profile_pic) }}" alt="pic" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;">
                                            @else
                                                <div class="rounded-circle bg-success d-flex align-items-center justify-content-center text-white fw-bold" style="width:36px;height:36px;font-size:14px;">
                                                    {{ strtoupper(substr($usage->first_name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <span class="fw-semibold">{{ $usage->first_name }} {{ $usage->last_name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $usage->email }}</td>
                                    <td class="text-muted">{{ \Carbon\Carbon::parse($usage->date_used)->format('d M Y, h:i A') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <p class="text-muted mb-1">No one has used your referral code yet.</p>
                        <p class="text-success small">Share your code <strong>{{ $refer_code }}</strong> and earn rewards!</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
