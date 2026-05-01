<?php

return [
    'admin_base_url' => env('INSURETECH_ADMIN_BASE_URL', 'http://127.0.0.1:8000'),
    'partner_token' => env('INSURETECH_PARTNER_TOKEN', ''),
    'request_timeout_seconds' => (int) env('INSURETECH_REQUEST_TIMEOUT', 20),
    'default_admin_product_code' => env('INSURETECH_DEFAULT_PRODUCT_CODE', ''),
    'default_sync_limit' => (int) env('INSURETECH_DEFAULT_SYNC_LIMIT', 25),
    'max_sync_limit' => (int) env('INSURETECH_MAX_SYNC_LIMIT', 200),
    'auto_pull_before_push' => (bool) env('INSURETECH_AUTO_PULL_BEFORE_PUSH', true),
];
