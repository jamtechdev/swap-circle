import '../css/cookie-consent.css';

const STORAGE_KEY = 'sc_cookie_consent_v1';

export function initCookieConsent() {
    const banner = document.getElementById('scCookieConsent');
    if (!banner) return;

    const existing = readConsent();
    if (existing) {
        applyConsent(existing);
        return;
    }

    banner.classList.remove('hidden');

    banner.querySelector('[data-cookie-accept]')?.addEventListener('click', () => {
        saveConsent('all');
        banner.classList.add('hidden');
        applyConsent(readConsent());
        window.dispatchEvent(new CustomEvent('sc:cookie-consent', { detail: { level: 'all' } }));
    });

    banner.querySelector('[data-cookie-reject]')?.addEventListener('click', () => {
        saveConsent('essential');
        banner.classList.add('hidden');
        applyConsent(readConsent());
        window.dispatchEvent(new CustomEvent('sc:cookie-consent', { detail: { level: 'essential' } }));
    });
}

export function readConsent() {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        return raw ? JSON.parse(raw) : null;
    } catch {
        return null;
    }
}

export function hasAnalyticsConsent() {
    const consent = readConsent();
    return consent?.level === 'all';
}

function saveConsent(level) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify({
        level,
        timestamp: new Date().toISOString(),
    }));
}

function applyConsent(consent) {
    document.documentElement.dataset.cookieConsent = consent?.level || 'pending';
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCookieConsent);
} else {
    initCookieConsent();
}
