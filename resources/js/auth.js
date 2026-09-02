import '../css/auth.css';
import './cookie-consent.js';

document.addEventListener('DOMContentLoaded', () => {
    initAuthTabs();
    initPasswordToggles();
    initOtpInputs();
    window.initAuthTabs = initAuthTabs;
    window.initPasswordToggles = initPasswordToggles;
    window.initOtpInputs = initOtpInputs;
});

export function initAuthTabs() {
    document.querySelectorAll('[data-auth-tabs]').forEach((wrap) => {
        const tabs = wrap.querySelectorAll('[data-auth-tab]');
        const panels = wrap.querySelectorAll('[data-auth-panel]');

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const target = tab.dataset.authTab;
                tabs.forEach((t) => {
                    const active = t.dataset.authTab === target;
                    t.classList.toggle('auth-tab-active', active);
                    t.classList.toggle('auth-tab-inactive', !active);
                    t.setAttribute('aria-selected', active ? 'true' : 'false');
                });
                panels.forEach((panel) => {
                    panel.classList.toggle('hidden', panel.dataset.authPanel !== target);
                });
            });
        });
    });
}

export function initPasswordToggles() {
    const eyeSlash = '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>';
    const eyeOpen = '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>';

    document.querySelectorAll('[data-toggle-password]').forEach((btn) => {
        if (btn.dataset.toggleBound === 'true') return;
        btn.dataset.toggleBound = 'true';

        const selector = btn.getAttribute('data-toggle-password');
        if (!selector) return;

        if (!btn.innerHTML.trim()) btn.innerHTML = eyeSlash;

        btn.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();

            const input = document.querySelector(selector);
            if (!input) return;

            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.innerHTML = isHidden ? eyeOpen : eyeSlash;
            btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
            btn.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
        });
    });
}

export function initOtpInputs() {
    document.querySelectorAll('[data-otp-group]').forEach((group) => {
        const inputs = group.querySelectorAll('.auth-otp-input');
        if (!inputs.length) return;

        inputs.forEach((input, index) => {
            input.addEventListener('keyup', (e) => {
                const val = input.value.replace(/[^0-9]/g, '');
                input.value = val.slice(0, 1);
                if (val && inputs[index + 1]) inputs[index + 1].focus();
                if (e.key === 'Backspace' && !input.value && inputs[index - 1]) {
                    inputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasted = (e.clipboardData || window.clipboardData)
                    .getData('text')
                    .replace(/[^0-9]/g, '')
                    .substring(0, inputs.length);
                inputs.forEach((el, i) => {
                    el.value = pasted[i] || '';
                });
                inputs[Math.min(pasted.length, inputs.length - 1)].focus();
            });
        });

        inputs[0].focus();
    });
}
