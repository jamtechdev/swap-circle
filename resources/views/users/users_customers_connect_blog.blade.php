@extends('layout.users.master')

@section('page_title', optional($article)->title ?? 'Article')
@section('page_subtitle', 'Community insight')

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <input type="hidden" id="connect_articles_id" value="{{ $blog_id }}">

                <div class="portal-connect-article-shell">
                    <a href="{{ url('/users/connect') }}" class="portal-connect-article-back">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Back to Connect
                    </a>

                    @if(!$article)
                        <div class="portal-connect-empty">
                            <p>This article could not be found or is no longer available.</p>
                            <a href="{{ url('/users/connect') }}" class="btn btn-login btn-primary mt-3">Back to Connect</a>
                        </div>
                    @else
                        <article class="portal-connect-article-panel">
                            <div class="portal-connect-article__hero">
                                <img
                                    class="portal-connect-article__image{{ !empty($article->image_is_fallback) ? ' portal-connect-article__image--fallback' : '' }}"
                                    src="{{ $article->image_url }}"
                                    alt="{{ $article->title }}"
                                >
                                <div class="portal-connect-article__hero-actions">
                                    @if($article->liked === 'Yes')
                                        <button type="button" class="portal-connect-action portal-connect-action--liked" onclick="unlike_other_article({{ $article->connect_articles_id }}); setTimeout(function(){ window.location.reload(); }, 400);" aria-label="Remove from favorites">
                                            <svg width="18" height="16" viewBox="0 0 20 18" fill="none" aria-hidden="true"><path d="M10.62 17.71C10.28 17.83 9.72 17.83 9.38 17.71C6.48 16.72 0 12.59 0 5.59C0 2.5 2.49 0 5.56 0C7.38 0 8.99 0.88 10 2.24C11.01 0.88 12.63 0 14.44 0C17.51 0 20 2.5 20 5.59C20 12.59 13.52 16.72 10.62 17.71Z" fill="currentColor"/></svg>
                                        </button>
                                    @else
                                        <button type="button" class="portal-connect-action" onclick="like_other_article({{ $article->connect_articles_id }}); setTimeout(function(){ window.location.reload(); }, 400);" aria-label="Add to favorites">
                                            <svg width="18" height="16" viewBox="0 0 20 18" fill="none" aria-hidden="true"><path d="M10.62 17.71C10.28 17.83 9.72 17.83 9.38 17.71C6.48 16.72 0 12.59 0 5.59C0 2.5 2.49 0 5.56 0C7.38 0 8.99 0.88 10 2.24C11.01 0.88 12.63 0 14.44 0C17.51 0 20 2.5 20 5.59C20 12.59 13.52 16.72 10.62 17.71Z" stroke="currentColor" stroke-width="1.5"/></svg>
                                        </button>
                                    @endif
                                    <button type="button" class="portal-connect-action portal-connect-action--share" data-clipboard-text="{{ url('/users/connect/blog/' . $article->connect_articles_id) }}" aria-label="Copy article link">
                                        <svg width="16" height="16" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M2.75 1.875v8.978c0 .735.345 1.432.938 1.875l3.907 2.925c.833.623 1.98.623 2.813 0l3.907-2.925c.593-.443.938-1.14.938-1.875V1.875H2.75Z" stroke="currentColor" stroke-width="1.5"/><path d="M1.5 1.875h15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M6 6h6M6 9.75h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="portal-connect-article__content">
                                @if(!empty($article->added_date))
                                    <span class="portal-connect-article__date">{{ \Carbon\Carbon::parse($article->added_date)->format('F j, Y') }}</span>
                                @endif
                                <h1 class="portal-connect-article__title">{{ $article->title }}</h1>
                                <div class="portal-connect-article__body">{{ $article->description }}</div>
                            </div>
                        </article>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            recordConnectArticleView();
        });

        function recordConnectArticleView() {
            var connectArticlesId = Number($('#connect_articles_id').val());

            if (!connectArticlesId || !users_customers_id) {
                return;
            }

            $.ajax({
                url: "{{ rtrim(config('app.api_url'), '/') }}/connect_article_view",
                method: "POST",
                headers: { "Content-Type": "application/json" },
                data: JSON.stringify({
                    users_customers_id: users_customers_id,
                    connect_articles_id: connectArticlesId,
                }),
            });
        }
    </script>
@endsection
