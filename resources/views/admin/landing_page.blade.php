@extends('layout.admin.list_master')

@section('titleBar')
<span>Landing Page CMS</span>
@endsection

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="admin-page-heading">
            <p>Manage text and images on the homepage and auth pages (login, signup, etc.).</p>
        </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ url('/admin/landing_page') }}" enctype="multipart/form-data">
                        @csrf

                        <ul class="nav nav-pills landing-cms-tabs flex-wrap gap-2 mb-4" role="tablist">
                            @foreach (['hero' => 'Hero', 'bridge' => 'Bridge', 'features' => 'Features', 'how' => 'How It Works', 'community' => 'Community', 'products' => 'Products', 'testimonial' => 'Testimonial', 'partners' => 'Partners', 'cta' => 'CTA', 'app' => 'App', 'insights' => 'Insights', 'footer' => 'Footer', 'auth' => 'Auth Pages'] as $key => $label)
                                <li class="nav-item">
                                    <button type="button" class="nav-link {{ $loop->first ? 'active' : '' }}" data-landing-tab="{{ $key }}">{{ $label }}</button>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Hero --}}
                        <div class="landing-cms-section active" data-landing-panel="hero">
                            <p class="landing-cms-count mb-3">Homepage: 4 activity stats in the hero card + activity label</p>
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label">Eyebrow</label><input class="form-control" name="hero[eyebrow]" value="{{ $content['hero']['eyebrow'] }}"></div>
                                <div class="col-md-6"><label class="form-label">Title</label><input class="form-control" name="hero[title]" value="{{ $content['hero']['title'] }}"></div>
                                <div class="col-md-6"><label class="form-label">Title highlight</label><input class="form-control" name="hero[title_highlight]" value="{{ $content['hero']['title_highlight'] }}"></div>
                                <div class="col-12"><label class="form-label">Subtitle</label><textarea class="form-control" rows="3" name="hero[subtitle]">{{ $content['hero']['subtitle'] }}</textarea></div>
                                <div class="col-md-8"><label class="form-label">Hero image URL or path</label><input class="form-control" name="hero[image]" value="{{ $content['hero']['image'] }}"></div>
                                <div class="col-md-4"><label class="form-label">Upload hero image</label><input type="file" class="form-control" name="hero__image" accept="image/*">@if($content['hero']['image'])<img src="{{ \App\Support\LandingContent::assetUrl($content['hero']['image']) }}" class="landing-cms-preview mt-2" alt="">@endif</div>
                                <div class="col-md-6"><label class="form-label">Trust line 1</label><input class="form-control" name="hero[trust_line_1]" value="{{ $content['hero']['trust_line_1'] }}"></div>
                                <div class="col-md-6"><label class="form-label">Trust line 2</label><input class="form-control" name="hero[trust_line_2]" value="{{ $content['hero']['trust_line_2'] }}"></div>
                                <div class="col-12"><label class="form-label">Activity card label</label><input class="form-control" name="hero[activity_label]" value="{{ $content['hero']['activity_label'] ?? 'Live platform activity' }}"></div>
                                @foreach ([1, 2, 3, 4] as $n)
                                    <div class="col-12"><h6 class="mb-0 mt-2 text-muted">Stat {{ $n }}</h6></div>
                                    <div class="col-md-3"><label class="form-label">Value</label><input class="form-control" name="hero[stat_{{ $n }}_value]" value="{{ $content['hero']['stat_'.$n.'_value'] }}"></div>
                                    <div class="col-md-3"><label class="form-label">Suffix</label><input class="form-control" name="hero[stat_{{ $n }}_suffix]" value="{{ $content['hero']['stat_'.$n.'_suffix'] ?? '' }}"></div>
                                    <div class="col-md-6"><label class="form-label">Label</label><input class="form-control" name="hero[stat_{{ $n }}_label]" value="{{ $content['hero']['stat_'.$n.'_label'] }}"></div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Bridge --}}
                        <div class="landing-cms-section" data-landing-panel="bridge">
                            <div class="row g-3">
                                <div class="col-12"><label class="form-label">Title</label><input class="form-control" name="bridge[title]" value="{{ $content['bridge']['title'] }}"></div>
                                <div class="col-12"><label class="form-label">Text</label><textarea class="form-control" rows="3" name="bridge[text]">{{ $content['bridge']['text'] }}</textarea></div>
                            </div>
                        </div>

                        {{-- Features --}}
                        <div class="landing-cms-section" data-landing-panel="features">
                            <p class="landing-cms-count mb-3">Homepage: {{ count($content['features']['items']) }} feature cards</p>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4"><label class="form-label">Eyebrow</label><input class="form-control" name="features[eyebrow]" value="{{ $content['features']['eyebrow'] }}"></div>
                                <div class="col-md-8"><label class="form-label">Title</label><input class="form-control" name="features[title]" value="{{ $content['features']['title'] }}"></div>
                                <div class="col-md-4"><label class="form-label">Image badge</label><input class="form-control" name="features[badge]" value="{{ $content['features']['badge'] }}"></div>
                                <div class="col-md-5"><label class="form-label">Section image URL</label><input class="form-control" name="features[image]" value="{{ $content['features']['image'] }}"></div>
                                <div class="col-md-3"><label class="form-label">Upload image</label><input type="file" class="form-control" name="features__image" accept="image/*">@if($content['features']['image'])<img src="{{ \App\Support\LandingContent::assetUrl($content['features']['image']) }}" class="landing-cms-preview mt-2" alt="">@endif</div>
                            </div>
                            @foreach ($content['features']['items'] as $i => $item)
                                <div class="landing-cms-card">
                                    <h6>Feature {{ $i + 1 }}</h6>
                                    <div class="row g-2">
                                        <div class="col-md-2"><input class="form-control" name="features[items][{{ $i }}][icon]" value="{{ $item['icon'] }}" placeholder="Icon"></div>
                                        <div class="col-md-4"><input class="form-control" name="features[items][{{ $i }}][title]" value="{{ $item['title'] }}" placeholder="Title"></div>
                                        <div class="col-md-6"><input class="form-control" name="features[items][{{ $i }}][text]" value="{{ $item['text'] }}" placeholder="Description"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- How --}}
                        <div class="landing-cms-section" data-landing-panel="how">
                            <p class="landing-cms-count mb-3">Homepage: {{ count($content['how']['steps']) }} steps</p>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4"><label class="form-label">Eyebrow</label><input class="form-control" name="how[eyebrow]" value="{{ $content['how']['eyebrow'] }}"></div>
                                <div class="col-md-8"><label class="form-label">Title</label><input class="form-control" name="how[title]" value="{{ $content['how']['title'] }}"></div>
                                <div class="col-12"><label class="form-label">Subtitle</label><textarea class="form-control" rows="2" name="how[subtitle]">{{ $content['how']['subtitle'] }}</textarea></div>
                            </div>
                            @foreach ($content['how']['steps'] as $i => $step)
                                <div class="landing-cms-card">
                                    <h6>Step {{ $i + 1 }}</h6>
                                    <div class="row g-2">
                                        <div class="col-md-2"><input class="form-control" name="how[steps][{{ $i }}][number]" value="{{ $step['number'] }}" placeholder="#"></div>
                                        <div class="col-md-2"><input class="form-control" name="how[steps][{{ $i }}][icon]" value="{{ $step['icon'] }}" placeholder="Icon"></div>
                                        <div class="col-md-4"><input class="form-control" name="how[steps][{{ $i }}][title]" value="{{ $step['title'] }}" placeholder="Title"></div>
                                        <div class="col-md-4"><input class="form-control" name="how[steps][{{ $i }}][text]" value="{{ $step['text'] }}" placeholder="Text"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Community --}}
                        <div class="landing-cms-section" data-landing-panel="community">
                            <p class="landing-cms-count mb-3">Homepage: {{ count($content['community']['items']) }} bullet points · 4 avatars (1 main + 3 satellites)</p>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4"><label class="form-label">Eyebrow</label><input class="form-control" name="community[eyebrow]" value="{{ $content['community']['eyebrow'] }}"></div>
                                <div class="col-md-8"><label class="form-label">Title</label><input class="form-control" name="community[title]" value="{{ $content['community']['title'] }}"></div>
                            </div>
                            @foreach ($content['community']['items'] as $i => $item)
                                <div class="mb-2"><input class="form-control" name="community[items][{{ $i }}]" value="{{ $item }}" placeholder="Bullet {{ $i + 1 }}"></div>
                            @endforeach

                            <p class="landing-cms-help mt-4 mb-2">Community avatars — 4 images appear on the homepage (1 large center + 3 smaller satellites).</p>

                            <div class="landing-cms-card">
                                <h6>Image 1 — Main center avatar (large)</h6>
                                <div class="row g-2">
                                    <div class="col-md-8"><input class="form-control" name="community[main_image]" value="{{ $content['community']['main_image'] }}" placeholder="Main image URL"></div>
                                    <div class="col-md-4"><input type="file" class="form-control" name="community__main_image" accept="image/*"></div>
                                    @if($content['community']['main_image'])
                                    <div class="col-12"><img src="{{ \App\Support\LandingContent::assetUrl($content['community']['main_image']) }}" class="landing-cms-preview mt-1" alt="Main avatar preview"></div>
                                    @endif
                                </div>
                            </div>

                            @php
                                $satelliteLabels = [
                                    'Image 2 — Satellite avatar (top)',
                                    'Image 3 — Satellite avatar (bottom right)',
                                    'Image 4 — Satellite avatar (bottom left)',
                                ];
                            @endphp
                            @foreach ($content['community']['satellite_images'] as $i => $url)
                                <div class="landing-cms-card">
                                    <h6>{{ $satelliteLabels[$i] ?? ('Satellite avatar ' . ($i + 2)) }}</h6>
                                    <div class="row g-2">
                                        <div class="col-md-8"><input class="form-control" name="community[satellite_images][{{ $i }}]" value="{{ $url }}" placeholder="Image URL"></div>
                                        <div class="col-md-4"><input type="file" class="form-control" name="community__satellite_images_{{ $i }}" accept="image/*"></div>
                                        @if($url)
                                        <div class="col-12"><img src="{{ \App\Support\LandingContent::assetUrl($url) }}" class="landing-cms-preview mt-1" alt="Satellite avatar preview"></div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Products --}}
                        <div class="landing-cms-section" data-landing-panel="products">
                            <p class="landing-cms-count mb-3">Homepage: {{ count($content['products']['items']) }} product cards</p>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4"><label class="form-label">Eyebrow</label><input class="form-control" name="products[eyebrow]" value="{{ $content['products']['eyebrow'] }}"></div>
                                <div class="col-md-8"><label class="form-label">Title</label><input class="form-control" name="products[title]" value="{{ $content['products']['title'] }}"></div>
                                <div class="col-12"><label class="form-label">Subtitle</label><textarea class="form-control" rows="2" name="products[subtitle]">{{ $content['products']['subtitle'] }}</textarea></div>
                            </div>
                            @foreach ($content['products']['items'] as $i => $item)
                                <div class="landing-cms-card">
                                    <h6>Product {{ $i + 1 }}</h6>
                                    <div class="row g-2">
                                        <div class="col-md-3"><input class="form-control" name="products[items][{{ $i }}][badge]" value="{{ $item['badge'] }}" placeholder="Badge"></div>
                                        <div class="col-md-4"><input class="form-control" name="products[items][{{ $i }}][title]" value="{{ $item['title'] }}" placeholder="Title"></div>
                                        <div class="col-md-5"><input class="form-control" name="products[items][{{ $i }}][text]" value="{{ $item['text'] }}" placeholder="Description"></div>
                                        <div class="col-md-8"><input class="form-control" name="products[items][{{ $i }}][image]" value="{{ $item['image'] }}" placeholder="Image URL"></div>
                                        <div class="col-md-4"><input type="file" class="form-control" name="products__items_{{ $i }}_image" accept="image/*"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Testimonial --}}
                        <div class="landing-cms-section" data-landing-panel="testimonial">
                            <div class="row g-3">
                                <div class="col-12"><label class="form-label">Quote</label><textarea class="form-control" rows="3" name="testimonial[quote]">{{ $content['testimonial']['quote'] }}</textarea></div>
                                <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" name="testimonial[name]" value="{{ $content['testimonial']['name'] }}"></div>
                                <div class="col-md-6"><label class="form-label">Role</label><input class="form-control" name="testimonial[role]" value="{{ $content['testimonial']['role'] }}"></div>
                                <div class="col-md-6"><label class="form-label">Video/image URL</label><input class="form-control" name="testimonial[video_image]" value="{{ $content['testimonial']['video_image'] }}"></div>
                                <div class="col-md-6"><label class="form-label">Upload video/image</label><input type="file" class="form-control" name="testimonial__video_image" accept="image/*"></div>
                                <div class="col-md-6"><label class="form-label">Avatar URL</label><input class="form-control" name="testimonial[avatar]" value="{{ $content['testimonial']['avatar'] }}"></div>
                                <div class="col-md-6"><label class="form-label">Upload avatar</label><input type="file" class="form-control" name="testimonial__avatar" accept="image/*"></div>
                            </div>
                        </div>

                        {{-- Partners --}}
                        <div class="landing-cms-section" data-landing-panel="partners">
                            <p class="landing-cms-count mb-3">Homepage: {{ count($content['partners']['items']) }} partner logos · {{ count($content['partners']['trust_badges'] ?? []) }} trust badges</p>
                            <p class="landing-cms-help mb-3">Upload a logo for each partner. Abbreviation and badge colours are only used as a fallback when no logo is uploaded.</p>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4"><label class="form-label">Eyebrow</label><input class="form-control" name="partners[eyebrow]" value="{{ $content['partners']['eyebrow'] }}"></div>
                                <div class="col-md-8"><label class="form-label">Title</label><input class="form-control" name="partners[title]" value="{{ $content['partners']['title'] }}"></div>
                                <div class="col-12"><label class="form-label">Subtitle</label><textarea class="form-control" rows="2" name="partners[subtitle]">{{ $content['partners']['subtitle'] }}</textarea></div>
                            </div>
                            @foreach ($content['partners']['items'] as $i => $item)
                                <div class="landing-cms-card">
                                    <h6>Partner {{ $i + 1 }}</h6>
                                    <div class="row g-2">
                                        <div class="col-md-4"><label class="form-label">Name</label><input class="form-control" name="partners[items][{{ $i }}][name]" value="{{ $item['name'] }}" placeholder="Partner name"></div>
                                        <div class="col-md-2"><label class="form-label">Abbr</label><input class="form-control" name="partners[items][{{ $i }}][abbr]" value="{{ $item['abbr'] ?? '' }}" placeholder="AZ"></div>
                                        <div class="col-md-6"><label class="form-label">Fallback badge classes</label><input class="form-control" name="partners[items][{{ $i }}][badge]" value="{{ $item['badge'] ?? '' }}" placeholder="bg-forest text-lime"></div>
                                        <div class="col-md-8"><label class="form-label">Logo URL</label><input class="form-control" name="partners[items][{{ $i }}][image]" value="{{ $item['image'] ?? '' }}" placeholder="https:// or uploads/landing/..."></div>
                                        <div class="col-md-4"><label class="form-label">Upload logo</label><input type="file" class="form-control" name="partners__items_{{ $i }}_image" accept="image/*"></div>
                                        @if(!empty($item['image']))
                                            <div class="col-12">
                                                <img src="{{ \App\Support\LandingContent::assetUrl($item['image']) }}" class="landing-cms-preview mt-1" alt="{{ $item['name'] }} logo preview">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @foreach ($content['partners']['trust_badges'] ?? [] as $i => $badge)
                                <div class="mb-2"><input class="form-control" name="partners[trust_badges][{{ $i }}]" value="{{ $badge }}" placeholder="Trust badge {{ $i + 1 }}"></div>
                            @endforeach
                        </div>

                        {{-- CTA --}}
                        <div class="landing-cms-section" data-landing-panel="cta">
                            <div class="row g-3">
                                <div class="col-12"><label class="form-label">Title</label><input class="form-control" name="cta[title]" value="{{ $content['cta']['title'] }}"></div>
                                <div class="col-12"><label class="form-label">Text</label><textarea class="form-control" rows="3" name="cta[text]">{{ $content['cta']['text'] }}</textarea></div>
                                <div class="col-md-8"><label class="form-label">Image URL</label><input class="form-control" name="cta[image]" value="{{ $content['cta']['image'] }}"></div>
                                <div class="col-md-4"><label class="form-label">Upload image</label><input type="file" class="form-control" name="cta__image" accept="image/*"></div>
                            </div>
                        </div>

                        {{-- App --}}
                        <div class="landing-cms-section" data-landing-panel="app">
                            <p class="landing-cms-count mb-3">Homepage: {{ count($content['app']['rows']) }} app preview rows + CTA button + 2 floating toasts</p>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4"><label class="form-label">Eyebrow</label><input class="form-control" name="app[eyebrow]" value="{{ $content['app']['eyebrow'] }}"></div>
                                <div class="col-md-8"><label class="form-label">Title</label><input class="form-control" name="app[title]" value="{{ $content['app']['title'] }}"></div>
                                <div class="col-12"><label class="form-label">Text</label><textarea class="form-control" rows="3" name="app[text]">{{ $content['app']['text'] }}</textarea></div>
                            </div>
                            @foreach ($content['app']['rows'] as $i => $row)
                                <div class="mb-2"><input class="form-control" name="app[rows][{{ $i }}]" value="{{ $row }}" placeholder="App row {{ $i + 1 }}"></div>
                            @endforeach
                            <div class="row g-3 mt-2">
                                <div class="col-md-4"><label class="form-label">CTA button text</label><input class="form-control" name="app[cta_row]" value="{{ $content['app']['cta_row'] ?? '+ Create Swap Offer' }}"></div>
                                <div class="col-md-4"><label class="form-label">Toast 1 (top right)</label><input class="form-control" name="app[toast_1]" value="{{ $content['app']['toast_1'] ?? '✓ Transfer sent!' }}"></div>
                                <div class="col-md-4"><label class="form-label">Toast 2 (bottom left)</label><input class="form-control" name="app[toast_2]" value="{{ $content['app']['toast_2'] ?? 'New swap request' }}"></div>
                            </div>
                        </div>

                        {{-- Insights --}}
                        <div class="landing-cms-section" data-landing-panel="insights">
                            <p class="landing-cms-count mb-3">Homepage: eyebrow and title only · article cards load from <a href="{{ url('admin/connect_articles') }}">Connect Articles</a></p>
                            <p class="landing-cms-help mb-3">Active Connect articles appear in the homepage Insights carousel. When no articles exist, the fallback posts below are shown instead.</p>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4"><label class="form-label">Eyebrow</label><input class="form-control" name="insights[eyebrow]" value="{{ $content['insights']['eyebrow'] }}"></div>
                                <div class="col-md-8"><label class="form-label">Title</label><input class="form-control" name="insights[title]" value="{{ $content['insights']['title'] }}"></div>
                            </div>
                            @foreach ($content['insights']['posts'] as $i => $post)
                                <div class="landing-cms-card">
                                    <h6>Article {{ $i + 1 }}</h6>
                                    <div class="row g-2">
                                        <div class="col-md-3"><input class="form-control" name="insights[posts][{{ $i }}][date]" value="{{ $post['date'] }}" placeholder="Date"></div>
                                        <div class="col-md-9"><input class="form-control" name="insights[posts][{{ $i }}][title]" value="{{ $post['title'] }}" placeholder="Title"></div>
                                        <div class="col-md-8"><input class="form-control" name="insights[posts][{{ $i }}][image]" value="{{ $post['image'] }}" placeholder="Image URL"></div>
                                        <div class="col-md-4"><input type="file" class="form-control" name="insights__posts_{{ $i }}_image" accept="image/*"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Footer --}}
                        <div class="landing-cms-section" data-landing-panel="footer">
                            <div class="row g-3">
                                <div class="col-12"><label class="form-label">Tagline</label><textarea class="form-control" rows="3" name="footer[tagline]">{{ $content['footer']['tagline'] }}</textarea></div>
                                <div class="col-12"><label class="form-label">Newsletter text</label><textarea class="form-control" rows="2" name="footer[newsletter_text]">{{ $content['footer']['newsletter_text'] }}</textarea></div>
                                <div class="col-md-4"><label class="form-label">Instagram URL</label><input class="form-control" name="footer[instagram_url]" value="{{ $content['footer']['instagram_url'] ?? '' }}" placeholder="https://instagram.com/..."></div>
                                <div class="col-md-4"><label class="form-label">Facebook URL</label><input class="form-control" name="footer[facebook_url]" value="{{ $content['footer']['facebook_url'] ?? '' }}" placeholder="https://facebook.com/..."></div>
                                <div class="col-md-4"><label class="form-label">X URL</label><input class="form-control" name="footer[x_url]" value="{{ $content['footer']['x_url'] ?? '' }}" placeholder="https://x.com/..."></div>
                            </div>
                        </div>

                        {{-- Auth pages (login, signup, etc.) --}}
                        <div class="landing-cms-section" data-landing-panel="auth">
                            <p class="landing-cms-count mb-3">Login &amp; signup left panel: 1 hero illustration + default eyebrow, title &amp; text</p>
                            <p class="landing-cms-help mb-3">This image appears on the green panel for login, signup, forgot password, and verification pages. Individual pages may override the title and text, but the illustration is shared.</p>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label">Hero illustration URL or path</label>
                                    <input class="form-control" name="auth[image]" value="{{ $content['auth']['image'] ?? '' }}" placeholder="users/assets/images/Rocket_Boy_Flatline.png">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Upload illustration</label>
                                    <input type="file" class="form-control" name="auth__image" accept="image/*">
                                    @if(!empty($content['auth']['image']))
                                        <img src="{{ \App\Support\LandingContent::assetUrl($content['auth']['image']) }}" class="landing-cms-preview mt-2" alt="Auth hero preview">
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Eyebrow badge</label>
                                    <input class="form-control" name="auth[eyebrow]" value="{{ $content['auth']['eyebrow'] ?? '' }}">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Default title</label>
                                    <input class="form-control" name="auth[title]" value="{{ $content['auth']['title'] ?? '' }}" placeholder="Use &lt;br&gt; for line breaks">
                                    <small class="text-muted">HTML allowed for line breaks, e.g. Trade services.&lt;br&gt;Build connections.</small>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Default description</label>
                                    <textarea class="form-control" rows="3" name="auth[text]">{{ $content['auth']['text'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-success">Save landing page</button>
                            <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-primary">Preview homepage</a>
                            <a href="{{ url('/login') }}" target="_blank" class="btn btn-outline-primary">Preview login page</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.querySelectorAll('[data-landing-tab]').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var tab = btn.getAttribute('data-landing-tab');
        document.querySelectorAll('[data-landing-tab]').forEach(function (b) { b.classList.remove('active'); });
        document.querySelectorAll('[data-landing-panel]').forEach(function (p) { p.classList.remove('active'); });
        btn.classList.add('active');
        var panel = document.querySelector('[data-landing-panel="' + tab + '"]');
        if (panel) panel.classList.add('active');
    });
});
</script>
@endsection
