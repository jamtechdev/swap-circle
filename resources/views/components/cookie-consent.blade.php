<div id="scCookieConsent" class="sc-cookie-consent hidden" role="dialog" aria-live="polite" aria-label="Cookie consent">
    <div class="sc-cookie-consent__panel">
        <div class="sc-cookie-consent__copy">
            <p class="sc-cookie-consent__title">Your privacy matters</p>
            <p class="sc-cookie-consent__text" data-cookie-banner-text>{{ \App\Support\LegalContent::bannerText() }}</p>
            <div class="sc-cookie-consent__links">
                <a href="{{ url('/cookies') }}">Cookie Policy</a>
                <span aria-hidden="true">·</span>
                <a href="{{ url('/privacy') }}">Privacy Policy</a>
                <span aria-hidden="true">·</span>
                <a href="{{ url('/gdpr') }}">GDPR</a>
            </div>
        </div>
        <div class="sc-cookie-consent__actions">
            <button type="button" class="sc-cookie-consent__btn sc-cookie-consent__btn--ghost" data-cookie-reject>Essential only</button>
            <button type="button" class="sc-cookie-consent__btn sc-cookie-consent__btn--primary" data-cookie-accept>Accept all</button>
        </div>
    </div>
</div>
