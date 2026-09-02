@extends('layout.admin.list_master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                @include('layout.admin.settings')
                <div class="card">
                    <div class="card-body">
                        @php
                            $gdprText = optional(collect($system_settings)->firstWhere('type', 'gdpr_text'))->description ?? \App\Support\LegalContent::get('gdpr_text');
                        @endphp
                        <div class="basic-form">
                            <form method="post" action="{{ url('/admin/system_settings_edit') }}">
                                @csrf
                                <div class="form-group">
                                    <label>GDPR &amp; Data Protection narration (public /gdpr page)</label>
                                    <textarea rows="16" class="form-control" name="gdpr_text" required>{{ $gdprText }}</textarea>
                                    <small class="text-muted">Explain how you collect, use, and protect personal data. HTML is supported. Linked from signup consent and cookie banner.</small>
                                </div>
                                <input type="hidden" name="page_name" value="system_gdpr">
                                <button type="submit" class="btn btn-primary float-right">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('titleBar')
<span class="ml-2">GDPR Narration</span>
@endsection
