# Swap Circle — Production Go-Live Checklist

Use this before switching Stripe to **Live** or opening to paying customers at scale.

## 0. Pre-login QA (SC-01 → SC-14) — must stay green on staging

Deploy then hard-refresh. Walk these as a real user (phone + desktop):

1. **Signup phone (SC-01):** Individual (and corporate if enabled) — pick country, enter local number, submit; stored/API value must be E.164 (`+…`), never truncated to 11 digits.
2. **Login / forgot (SC-02):** Unknown email → same generic login error; forgot always shows “If an account exists…”.
3. **Email validation (SC-04):** `user@domain` (no TLD) rejected client + server.
4. **Short name (SC-05):** First name `Al` accepted.
5. **Login spam (SC-06):** Double-click Sign In → one request; button shows “Signing in…”.
6. **Headers (SC-07):** On HTTPS login response: HSTS, CSP, XFO, nosniff, Referrer-Policy.
7. **Optional photo (SC-08):** Signup without photo succeeds (default avatar).
8. **HTTP codes (SC-09):** Bad login returns **401** (Network tab), not always 200.
9. **Mobile (SC-10):** Login at 320–375px width — no horizontal scroll on the form.
10. **Forms (SC-11):** View source — auth forms have `method="post"`.
11. **Password toggle (SC-12):** Tab to eye control, Enter/Space toggles; screen reader label Show/Hide.
12. **404 (SC-13):** `/this-is-not-a-page` → branded page with Back to home + login.
13. **Copy (SC-14):** Forgot heading “Forgot Password?”; login “Welcome Back” (no `..!`).

If login/signup look **unstyled/blank**: `public/hot` was deployed or `npm run build` was skipped. Remove `public/hot`, rebuild, hard-refresh.

## 1. Environment

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL` / `ASSET_URL` / `API_URL` / `WEB_URL` point at **this** host (HTTPS) — wrong `API_URL` breaks login/signup AJAX
- [ ] `SESSION_SECURE_COOKIE=true` on HTTPS
- [ ] `SIGNUP_CORPORATE_ENABLED=false` unless corporate is approved
- [ ] Remove or unset `CLAIM_WAITING_DAYS=0` override for production (use system_settings default)
- [ ] `public/hot` **absent** on the server (Vite HMR file)
- [ ] `public/build/manifest.json` present and includes `auth.css` / `auth.js`

## 2. Stripe (Live)

- [ ] Live `STRIPE_SECRET` and `STRIPE_PUBLISHABLE` set
- [ ] Live `STRIPE_WEBHOOK_SECRET` set
- [ ] Stripe Dashboard webhook endpoint: `POST https://<host>/api/stripe/webhook`
- [ ] Webhook events enabled: `checkout.session.completed`, `checkout.session.async_payment_succeeded`
- [ ] Test: pay with live card (or live test if available) → purchase `Successful` without relying only on browser redirect
- [ ] Test: refresh success page → no duplicate confirmation email

## 3. Mail & storage

- [ ] Production SMTP credentials verified (send test to ops inbox) — signup OTP + forgot-password mail
- [ ] Invoice PDF downloads work for paid owner after login (`/users/download-invoice/{id}`)
- [ ] File uploads (claim docs, product images) writable and backed up

## 4. Security

- [ ] `/api/test-mail` not reachable outside `local`
- [ ] Stripe initiate/success require portal session
- [ ] New passwords use bcrypt; legacy MD5 upgrades on login
- [ ] Rate limits active on login, signup, forgot, email_exist + Stripe initiate
- [ ] Admin passwords reviewed (prefer non-plaintext where still used)
- [ ] Security headers present (SC-07): `Strict-Transport-Security`, `X-Frame-Options`, `Content-Security-Policy`, `X-Content-Type-Options`, `Referrer-Policy` (app middleware ships these; confirm on HTTPS staging/prod, optionally mirror in nginx)

## 5. Ops & reliability

- [ ] Scheduler running (`* * * * * php artisan schedule:run`) so `purchases:expire-pending` runs hourly
- [ ] `PURCHASE_PENDING_EXPIRE_HOURS` set (default 24)
- [ ] Admin → Transactions shows Stripe session/intent, sync status, refund action
- [ ] Insuretech partner token + base URL configured; failed sync visible + retry works
- [ ] Database backups scheduled and restore tested
- [ ] Deploy runs `npm run build` (or ships a fresh `public/build`) — see `scripts/deploy-staging.sh`

## 6. Monitoring

- [ ] Application log access (failed payments, webhook signature errors)
- [ ] Stripe Dashboard dispute/refund alerts enabled
- [ ] Uptime check on `/` and `/login`

## 7. Smoke test (staging then live)

1. Individual signup → verify OTP → login  
2. Buy Nigerian Community Beneficiary (or approved product) → Stripe Checkout → success  
3. Confirm purchase `Successful`, email received once, invoice downloadable  
4. Claims eligibility path (waiting days)  
5. Admin refund on a Test-mode Successful purchase (before Live money)

## 8. Rollback notes

- Keep previous `STRIPE_*` Test keys documented for emergency staging  
- Webhook can be disabled in Stripe Dashboard if finalize bugs appear  
- Pending expire command supports `--dry-run`

---

Corporate re-enable (Issue 13): set `SIGNUP_CORPORATE_ENABLED=true` and clear config cache.  
Wallet top-up remains **manual** via admin fund requests unless automation is approved later.
