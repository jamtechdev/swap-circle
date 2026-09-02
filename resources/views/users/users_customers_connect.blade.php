@extends('layout.users.master')

@section('page_title', 'Connect')
@section('page_subtitle', 'Community articles and insights')

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="portal-connect-panel">
                    <div class="portal-connect-head">
                        <div class="portal-connect-head__text">
                            <span class="portal-connect-head__label">Swap Circle Connect</span>
                            <p class="portal-connect-head__lead">Discover articles, tips and community insights</p>
                        </div>
                    </div>

                    <section class="portal-connect-section portal-connect-section--categories">
                        <div class="portal-connect-section__head portal-connect-section__head--stacked">
                            <h2 class="portal-connect-section__title">Categories</h2>
                            <p class="portal-connect-section__sub">Tap a category to filter articles</p>
                        </div>
                        <div class="portal-connect-categories" id="connect_categories" role="list"></div>
                    </section>

                    <section class="portal-connect-section">
                        <div class="portal-connect-section__head">
                            <h2 class="portal-connect-section__title">Most popular</h2>
                            <span class="portal-connect-section__hint">Trending in the community</span>
                        </div>
                        <div class="splide portal-connect-slider portal-connect-slider--popular" id="slider-1" aria-label="Most popular articles">
                            <div class="splide__track">
                                <ul class="splide__list" id="popular_articles"></ul>
                            </div>
                        </div>
                        <div class="portal-connect-empty d-none" id="popular_articles_empty">
                            <p>No popular articles yet. Check back soon.</p>
                        </div>
                    </section>

                    <section class="portal-connect-section">
                        <div class="portal-connect-section__head">
                            <h2 class="portal-connect-section__title">More to explore</h2>
                            <span class="portal-connect-section__hint">Latest articles and updates</span>
                        </div>
                        <div class="splide portal-connect-slider portal-connect-slider--wide" id="slider-2" aria-label="Other articles">
                            <div class="splide__track">
                                <ul class="splide__list" id="other_articles"></ul>
                            </div>
                        </div>
                        <div class="portal-connect-empty d-none" id="other_articles_empty">
                            <p>No articles available right now.</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            get_connect_categories();
            get_popular_articles();
            get_other_articles();
        });
    </script>
@endsection
