@extends('layout.users.master')

@section('page_title', 'FAQs')
@section('page_subtitle', 'Answers to common questions')

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="portal-faq-panel">
                    <nav class="portal-profile-breadcrumb" aria-label="Breadcrumb">
                        <ol class="portal-profile-breadcrumb__list">
                            <li class="portal-profile-breadcrumb__item">
                                <a href="{{ url('/users/profile') }}">Profile</a>
                            </li>
                            <li class="portal-profile-breadcrumb__sep" aria-hidden="true">/</li>
                            <li class="portal-profile-breadcrumb__item portal-profile-breadcrumb__item--active" aria-current="page">FAQs</li>
                        </ol>
                    </nav>

                    <div class="portal-faq-panel__head">
                        <h1 class="portal-faq-panel__title">Frequently asked questions</h1>
                        <p class="portal-faq-panel__lead">Find quick answers about your Swap Circle account.</p>
                    </div>

                    <div class="portal-faq-list" id="accordionExample"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            get_faqs();
        });
    </script>
@endsection
