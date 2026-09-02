{!! \App\Support\EmailStyles::wrap(\App\Support\EmailStyles::card(
    $details['title'] ?? 'Swap Circle',
    'Account notification',
    '<p class="email-card-text">' . e($details['body'] ?? '') . '</p>'
        . (isset($details['highlight']) ? '<p class="email-highlight">' . e($details['highlight']) . '</p>' : '')
)) !!}
