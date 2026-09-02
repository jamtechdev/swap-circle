@extends('layout.users.master')

@section('page_title', 'Track')
@section('page_subtitle', 'Monitor exchange rates and convert currencies')

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="portal-track-panel">
                    <div class="portal-track-head">
                        <div class="portal-track-head__main">
                            <div class="portal-track-head__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path d="M3 12h18M12 3a15 15 0 010 18M12 3a15 15 0 000 18"/>
                                </svg>
                            </div>
                            <div class="portal-track-head__text">
                                <span class="portal-track-head__label">Currency tracker</span>
                                <h2 class="portal-track-head__title">Convert currencies in real time</h2>
                                <p class="portal-track-head__lead">Compare live and admin rates, then convert instantly for buy or sell.</p>
                            </div>
                        </div>
                    </div>

                    <div class="wallet-tabs portal-track-tabs">
                        <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-buy-tab" data-bs-toggle="pill" data-bs-target="#pills-buy" type="button" role="tab" aria-controls="pills-buy" aria-selected="true">Buy</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-sell-tab" data-bs-toggle="pill" data-bs-target="#pills-sell" type="button" role="tab" aria-controls="pills-sell" aria-selected="false">Sell</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                            {{-- Buy --}}
                            <div class="tab-pane fade show active" id="pills-buy" role="tabpanel" aria-labelledby="pills-buy-tab" tabindex="0">
                                <div class="portal-track-converter" data-track-mode="buy">
                                    <div class="portal-track-card portal-track-card--from">
                                        <div class="portal-track-card__header">
                                            <span class="portal-track-card__badge">From</span>
                                        </div>
                                        <div class="portal-track-card__body">
                                            <div class="portal-track-amount-row">
                                                <div class="portal-track-currency-picker">
                                                    <span class="portal-track-field-label">Currency</span>
                                                    <select class="portal-track-select" aria-label="Buy from currency" id="buy_from_currency">
                                                        <option value="">Loading…</option>
                                                    </select>
                                                </div>
                                                <div class="portal-track-amount-field">
                                                    <span class="portal-track-field-label">Amount</span>
                                                    <div class="portal-track-input-shell">
                                                        <input
                                                            type="text"
                                                            inputmode="decimal"
                                                            class="portal-track-amount"
                                                            placeholder="0.00"
                                                            id="buy_entered_amount"
                                                            autocomplete="off"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="portal-track-swap-wrap">
                                        <button type="button" class="portal-track-swap" data-tab="buy" aria-label="Swap currencies">
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M7 10l-3 3 3 3M17 14l3-3-3-3M4 13h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="portal-track-card portal-track-card--to">
                                        <div class="portal-track-card__header">
                                            <span class="portal-track-card__badge">To</span>
                                        </div>
                                        <div class="portal-track-card__body">
                                            <div class="portal-track-amount-row portal-track-amount-row--output">
                                                <div class="portal-track-currency-picker">
                                                    <span class="portal-track-field-label">Currency</span>
                                                    <select class="portal-track-select" aria-label="Buy to currency" id="buy_to_currency">
                                                        <option value="">Loading…</option>
                                                    </select>
                                                </div>
                                                <div class="portal-track-amount-field">
                                                    <span class="portal-track-field-label">You receive</span>
                                                    <div class="portal-track-input-shell portal-track-input-shell--output">
                                                        <p class="portal-track-result" id="buy_converted_amount">
                                                            <span class="portal-track-result__placeholder">—</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="portal-track-rates d-none" id="buy_rates">
                                        <div class="portal-track-rate">
                                            <span class="portal-track-rate__label">Live rate</span>
                                            <span class="portal-track-rate__value" id="buy_live_rate">—</span>
                                        </div>
                                        <div class="portal-track-rate">
                                            <span class="portal-track-rate__label">Admin rate</span>
                                            <span class="portal-track-rate__value" id="buy_admin_rate">—</span>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-login btn-primary portal-track-cta" id="buy_convert_btn" onclick="convert_buy_currency()" data-label="Convert currency">
                                        <span class="portal-track-cta__icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 10l-3 3 3 3M17 14l3-3-3-3M4 13h16"/></svg>
                                        </span>
                                        Convert currency
                                    </button>
                                </div>
                            </div>

                            {{-- Sell --}}
                            <div class="tab-pane fade" id="pills-sell" role="tabpanel" aria-labelledby="pills-sell-tab" tabindex="0">
                                <div class="portal-track-converter" data-track-mode="sell">
                                    <div class="portal-track-card portal-track-card--from">
                                        <div class="portal-track-card__header">
                                            <span class="portal-track-card__badge">From</span>
                                        </div>
                                        <div class="portal-track-card__body">
                                            <div class="portal-track-amount-row">
                                                <div class="portal-track-currency-picker">
                                                    <span class="portal-track-field-label">Currency</span>
                                                    <select class="portal-track-select" aria-label="Sell from currency" id="sell_from_currency">
                                                        <option value="">Loading…</option>
                                                    </select>
                                                </div>
                                                <div class="portal-track-amount-field">
                                                    <span class="portal-track-field-label">Amount</span>
                                                    <div class="portal-track-input-shell">
                                                        <input
                                                            type="text"
                                                            inputmode="decimal"
                                                            class="portal-track-amount"
                                                            placeholder="0.00"
                                                            id="sell_entered_amount"
                                                            autocomplete="off"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="portal-track-swap-wrap">
                                        <button type="button" class="portal-track-swap" data-tab="sell" aria-label="Swap currencies">
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M7 10l-3 3 3 3M17 14l3-3-3-3M4 13h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="portal-track-card portal-track-card--to">
                                        <div class="portal-track-card__header">
                                            <span class="portal-track-card__badge">To</span>
                                        </div>
                                        <div class="portal-track-card__body">
                                            <div class="portal-track-amount-row portal-track-amount-row--output">
                                                <div class="portal-track-currency-picker">
                                                    <span class="portal-track-field-label">Currency</span>
                                                    <select class="portal-track-select" aria-label="Sell to currency" id="sell_to_currency">
                                                        <option value="">Loading…</option>
                                                    </select>
                                                </div>
                                                <div class="portal-track-amount-field">
                                                    <span class="portal-track-field-label">You receive</span>
                                                    <div class="portal-track-input-shell portal-track-input-shell--output">
                                                        <p class="portal-track-result" id="sell_converted_amount">
                                                            <span class="portal-track-result__placeholder">—</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="portal-track-rates d-none" id="sell_rates">
                                        <div class="portal-track-rate">
                                            <span class="portal-track-rate__label">Live rate</span>
                                            <span class="portal-track-rate__value" id="sell_live_rate">—</span>
                                        </div>
                                        <div class="portal-track-rate">
                                            <span class="portal-track-rate__label">Admin rate</span>
                                            <span class="portal-track-rate__value" id="sell_admin_rate">—</span>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-login btn-primary portal-track-cta" id="sell_convert_btn" onclick="convert_sell_currency()" data-label="Convert currency">
                                        <span class="portal-track-cta__icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 10l-3 3 3 3M17 14l3-3-3-3M4 13h16"/></svg>
                                        </span>
                                        Convert currency
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        window.trackCurrenciesReady = false;

        function formatTrackOption(option) {
            if (!option.id) {
                return option.text;
            }

            var code = $(option.element).attr('code') || option.text;
            var symbol = $(option.element).attr('symbol') || '';
            return $('<span class="portal-track-option"><strong>' + code + '</strong><span>' + symbol + '</span></span>');
        }

        function formatTrackPicker(option) {
            if (!option.id) {
                return 'Select';
            }

            var code = $(option.element).attr('code') || option.text;
            var symbol = $(option.element).attr('symbol') || '';
            return $('<span class="portal-track-picker-label">' + code + ' <small>' + symbol + '</small></span>');
        }

        function trackCurrencyMatcher(params, data) {
            if ($.trim(params.term) === '') {
                return data;
            }

            if (typeof data.text === 'undefined') {
                return null;
            }

            var term = params.term.toLowerCase();
            var $el = data.element ? $(data.element) : null;
            var code = (($el && $el.attr('code')) || '').toLowerCase();
            var symbol = (($el && $el.attr('symbol')) || '').toLowerCase();
            var name = (($el && $el.attr('data-name')) || '').toLowerCase();
            var text = (data.text || '').toLowerCase();

            if (
                code.indexOf(term) > -1 ||
                symbol.indexOf(term) > -1 ||
                name.indexOf(term) > -1 ||
                text.indexOf(term) > -1
            ) {
                return data;
            }

            return null;
        }

        function focusTrackCurrencySearch() {
            setTimeout(function () {
                var $field = $('.portal-track-select2-dropdown .select2-search__field:visible').last();
                $field.attr('placeholder', 'Search by code, name or symbol').trigger('focus');
            }, 10);
        }

        function initTrackCurrencySelects() {
            ['#buy_from_currency', '#buy_to_currency', '#sell_from_currency', '#sell_to_currency'].forEach(function (selector) {
                var $select = $(selector);

                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }

                $select.select2({
                    dropdownParent: $('body'),
                    width: '100%',
                    minimumResultsForSearch: 0,
                    matcher: trackCurrencyMatcher,
                    templateSelection: formatTrackPicker,
                    templateResult: formatTrackOption,
                    dropdownCssClass: 'portal-track-select2-dropdown',
                    language: {
                        noResults: function () {
                            return 'No currencies found';
                        },
                        searching: function () {
                            return 'Searching…';
                        },
                        inputTooShort: function () {
                            return 'Type to search currencies';
                        }
                    }
                }).on('select2:open', focusTrackCurrencySearch);
            });

            window.trackCurrenciesReady = true;
        }

        function resetTrackResult(prefix) {
            $('#' + prefix + '_converted_amount').html('<span class="portal-track-result__placeholder">—</span>');
            $('#' + prefix + '_converted_amount').closest('.portal-track-input-shell').removeClass('has-value');
            $('#' + prefix + '_rates').addClass('d-none');
            $('#' + prefix + '_live_rate, #' + prefix + '_admin_rate').text('—');
        }

        function bindTrackAmountInput(selector, convertFn) {
            $(selector).on('input', function () {
                this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
            }).on('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    convertFn();
                }
            });
        }

        $(document).ready(function () {
            get_buy_sell_currencies();
            $(document).on('track:currencies-loaded', initTrackCurrencySelects);

            bindTrackAmountInput('#buy_entered_amount', convert_buy_currency);
            bindTrackAmountInput('#sell_entered_amount', convert_sell_currency);

            $('#buy_from_currency, #buy_to_currency, #sell_from_currency, #sell_to_currency').on('change', function () {
                var id = this.id.replace(/_(from|to)_currency$/, '');
                resetTrackResult(id);
            });

            $('.portal-track-swap').on('click', function (event) {
                event.preventDefault();
                var tab = $(this).data('tab');
                var $from = $('#' + tab + '_from_currency');
                var $to = $('#' + tab + '_to_currency');
                var fromVal = $from.val();
                var toVal = $to.val();

                $from.val(toVal).trigger('change');
                $to.val(fromVal).trigger('change');
                resetTrackResult(tab);
            });
        });
    </script>
@endsection
