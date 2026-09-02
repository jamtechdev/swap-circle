{{-- Send offer --}}
<div class="modal fade" id="mdl_send_offer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content portal-profile-modal">
            <div class="modal-body p-4 p-md-5 text-center">
                <input type="hidden" id="so_swap_offers_id" value="">
                <div class="portal-offer-summary mb-4">
                    <p class="mb-0 portal-offer-summary__currency" id="so_from_currency"></p>
                    <span class="portal-offer-summary__icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M11 5l3 3-3 3" stroke="#1a472a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                    <p class="mb-0 portal-offer-summary__currency" id="so_exchange_rate"></p>
                </div>
                <div class="portal-offer-details text-start mb-4">
                    <div class="portal-offer-details__row"><span>Amount</span><strong class="text-danger" id="so_amount"></strong></div>
                    <div class="portal-offer-details__row"><span>Converted amount</span><strong class="text-success" id="so_converted_amount"></strong></div>
                    <div class="portal-offer-details__row"><span>Base amount</span><strong id="so_base_amount"></strong></div>
                </div>
                <button type="button" class="btn btn-login btn-primary w-100 portal-profile-edit-save" data-bs-dismiss="modal" onclick="send_offer()">Send offer</button>
            </div>
        </div>
    </div>
</div>

{{-- Create offer --}}
<div class="modal fade modal-xl" id="mdl_create_offer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content portal-profile-modal">
            <div class="modal-body p-4 p-md-5">
                <div class="d-flex align-items-center mb-4">
                    <button type="button" class="portal-modal-back" data-bs-dismiss="modal" aria-label="Close">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <h2 class="portal-profile-modal__title flex-grow-1 text-center mb-0">Create offer</h2>
                    <span class="portal-profile-modal__spacer" aria-hidden="true"></span>
                </div>
                <form id="frm_create_offer">
                    @csrf
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label" for="co_from_account">From account</label>
                            <select class="form-select" name="co_from_account" id="co_from_account" data-placeholder="Select account">
                                <option value="" disabled selected hidden>Select account</option>
                            </select>
                            <span class="error_msg" id="error_co_from_account"></span>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label" for="co_total_amount">Total amount</label>
                            <input type="text" name="co_total_amount" id="co_total_amount" placeholder="Enter amount" class="form-control" min="1" step="0.01">
                            <span class="error_msg" id="error_co_total_amount"></span>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label" for="co_exchange_currency">Exchange currency</label>
                            <select class="form-select portal-select-search" name="co_exchange_currency" id="co_exchange_currency" data-placeholder="Select currency">
                                <option value="" disabled selected hidden>Select currency</option>
                            </select>
                            <span class="error_msg" id="error_co_exchange_currency"></span>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label" for="co_exchange_rate">Exchange rate</label>
                            <input type="text" name="co_exchange_rate" id="co_exchange_rate" placeholder="Enter rate" class="form-control" min="0.01" step="0.01">
                            <span class="error_msg" id="error_co_exchange_rate"></span>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label" for="co_expires_in">Expires in (hours)</label>
                            <input type="text" name="co_expires_in" id="co_expires_in" placeholder="e.g. 24" class="form-control">
                            <span class="error_msg" id="error_co_expires_in"></span>
                        </div>
                    </div>
                    <div class="portal-modal-actions">
                        <button type="submit" class="btn btn-login btn-primary portal-profile-edit-save">Save offer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
