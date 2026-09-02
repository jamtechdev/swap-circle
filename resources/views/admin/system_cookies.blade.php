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
                            $cookieText = optional(collect($system_settings)->firstWhere('type', 'cookie_text'))->description ?? \App\Support\LegalContent::get('cookie_text');
                            $bannerText = optional(collect($system_settings)->firstWhere('type', 'cookie_banner_text'))->description ?? \App\Support\LegalContent::get('cookie_banner_text');
                        @endphp
                        <div class="basic-form">
                            <form method="post" action="{{ url('/admin/system_settings_edit') }}">
                                @csrf
                                <div class="form-group">
                                    <label>Cookie banner message</label>
                                    <textarea rows="3" class="form-control" name="cookie_banner_text" required>{{ $bannerText }}</textarea>
                                    <small class="text-muted">Shown in the cookie consent banner on the homepage and auth pages.</small>
                                </div>
                                <div class="form-group mt-4">
                                    <label>Cookie Policy (public /cookies page)</label>
                                    <textarea rows="12" class="form-control" name="cookie_text" required>{{ $cookieText }}</textarea>
                                    <small class="text-muted">HTML is supported.</small>
                                </div>
                                <input type="hidden" name="page_name" value="system_cookies">
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
<span class="ml-2">Cookie Policy</span>
@endsection
