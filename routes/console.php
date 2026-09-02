<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('landing:audit', function () {
    $result = \App\Support\LandingContentAudit::run();
    $failed = 0;

    foreach ($result['checks'] as $check) {
        $line = ($check['passed'] ? '<info>PASS</info>' : '<error>FAIL</error>') . ' ' . $check['name'];
        if (!$check['passed'] && !empty($check['detail'])) {
            $line .= ' — ' . $check['detail'];
        }
        $this->line($line);
        if (!$check['passed']) {
            $failed++;
        }
    }

    $total = count($result['checks']);
    $passed = $total - $failed;
    $this->newLine();
    $this->info("Landing CMS parity: {$passed}/{$total} checks passed.");

    return $failed === 0 ? 0 : 1;
})->describe('Audit landing page CMS ↔ homepage field parity');

Artisan::command('auth:audit', function () {
    $result = \App\Support\AuthFlowAudit::run();
    $failed = 0;

    foreach ($result['checks'] as $check) {
        $line = ($check['passed'] ? '<info>PASS</info>' : '<error>FAIL</error>') . ' ' . $check['name'];
        if (!$check['passed'] && !empty($check['detail'])) {
            $line .= ' — ' . $check['detail'];
        }
        $this->line($line);
        if (!$check['passed']) {
            $failed++;
        }
    }

    $total = count($result['checks']);
    $passed = $total - $failed;
    $this->newLine();
    $this->info("Auth flow audit: {$passed}/{$total} checks passed.");

    return $failed === 0 ? 0 : 1;
})->describe('Audit login, signup, forgot password, and verification flows');
