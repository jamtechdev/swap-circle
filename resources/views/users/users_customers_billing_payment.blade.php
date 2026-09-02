@extends('layout.users.master')

@section('page_title', 'Payout Accounts')
@section('page_subtitle', 'Manage your linked bank accounts')

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-5">
                <div class="portal-billing-panel">
                    <nav class="portal-profile-breadcrumb" aria-label="Breadcrumb">
                        <ol class="portal-profile-breadcrumb__list">
                            <li class="portal-profile-breadcrumb__item">
                                <a href="{{ url('/users/profile') }}">Profile</a>
                            </li>
                            <li class="portal-profile-breadcrumb__sep" aria-hidden="true">/</li>
                            <li class="portal-profile-breadcrumb__item portal-profile-breadcrumb__item--active" aria-current="page">Accounts</li>
                        </ol>
                    </nav>

                    <div class="portal-billing-head">
                        <div>
                            <h1 class="portal-billing-head__title">Your accounts</h1>
                            <p class="portal-billing-head__lead">Add and manage payout bank accounts.</p>
                        </div>
                        <button type="button" class="portal-billing-add" onclick="get_add_account_params()">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/></svg>
                            Add account
                        </button>
                    </div>

                    <div class="row g-3" id="all_accounts"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Withdraw modal --}}
    <div class="modal fade modal-lg" id="mdl_withdraw_amount" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content portal-profile-modal">
                <div class="modal-body p-4 p-md-5">
                    <div class="d-flex align-items-center mb-4">
                        <button type="button" class="portal-modal-back" data-bs-dismiss="modal" aria-label="Close">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <h2 class="portal-profile-modal__title flex-grow-1 text-center mb-0">Withdraw funds</h2>
                        <span class="portal-profile-modal__spacer" aria-hidden="true"></span>
                    </div>
                    <form id="frm_withdraw_amount">
                        @csrf
                        <input type="hidden" id="wa_accounts_id" value="" readonly>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="wa_currency">Currency</label>
                                <select class="form-select" name="wa_currency" id="wa_currency"></select>
                                <span class="error_msg" id="error_wa_currency"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="wa_bank_name">Bank name</label>
                                <input type="text" name="wa_bank_name" id="wa_bank_name" class="form-control" readonly>
                                <span class="error_msg" id="error_wa_bank_name"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="wa_account_number">Account number</label>
                                <input type="text" name="wa_account_number" id="wa_account_number" class="form-control" readonly>
                                <span class="error_msg" id="wa_account_number"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="wa_amount">Amount</label>
                                <input type="text" name="wa_amount" id="wa_amount" placeholder="Enter amount" class="form-control" min="1" step="0.01">
                                <span class="error_msg" id="error_wa_amount"></span>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="wa_account_notes">Account notes</label>
                                <textarea name="wa_account_notes" id="wa_account_notes" placeholder="Optional notes" class="form-control" rows="3"></textarea>
                                <span class="error_msg" id="error_wa_account_notes"></span>
                            </div>
                        </div>
                        <div class="portal-modal-actions">
                            <button type="submit" class="btn btn-login btn-primary portal-profile-edit-save">Withdraw</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Add account modal --}}
    <div class="modal fade modal-lg" id="mdl_add_account" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content portal-profile-modal">
                <div class="modal-body p-4 p-md-5">
                    <div class="d-flex align-items-center mb-4">
                        <button type="button" class="portal-modal-back" data-bs-dismiss="modal" aria-label="Close">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <h2 class="portal-profile-modal__title flex-grow-1 text-center mb-0">Add account</h2>
                        <span class="portal-profile-modal__spacer" aria-hidden="true"></span>
                    </div>
                    <form id="frm_add_account">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="account_currency">Currency</label>
                                <select class="form-select portal-select-search" name="account_currency" id="account_currency" data-placeholder="Select currency">
                                    <option value="" disabled selected hidden>Select currency</option>
                                </select>
                                <span class="error_msg" id="error_account_currency"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="account_holder_name">Account holder name</label>
                                <input type="text" name="account_holder_name" id="account_holder_name" placeholder="Full name on account" class="form-control">
                                <span class="error_msg" id="error_account_holder_name"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="account_bank_name">Bank name</label>
                                <input type="text" name="account_bank_name" id="account_bank_name" placeholder="Bank name" class="form-control">
                                <span class="error_msg" id="error_account_bank_name"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="account_branch_code">Branch code</label>
                                <input type="text" name="account_branch_code" id="account_branch_code" placeholder="Branch code" class="form-control">
                                <span class="error_msg" id="error_account_branch_code"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="account_number">Account number</label>
                                <input type="text" name="account_number" id="account_number" placeholder="Account number" class="form-control">
                                <span class="error_msg" id="error_account_number"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="account_iban">BIC / IBAN</label>
                                <input type="text" name="account_iban" id="account_iban" placeholder="BIC or IBAN" class="form-control">
                                <span class="error_msg" id="error_account_iban"></span>
                            </div>
                        </div>
                        <div class="portal-modal-actions">
                            <button type="submit" class="btn btn-login btn-primary portal-profile-edit-save">Save account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            get_all_accounts();
        });
    </script>
@endsection
