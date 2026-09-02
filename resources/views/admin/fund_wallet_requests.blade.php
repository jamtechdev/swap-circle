@extends('layout.admin.list_master')

@section('titleBar')
<span>Fund Wallet Requests</span>
@endsection

@section('content')
    {{-- View modal --}}
    <div class="modal fade" id="viewFundWalletRequestModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Fund wallet request detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body admin-fund-modal">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="admin-fund-field">
                                <span class="admin-fund-field__label">Bank name</span>
                                <span class="admin-fund-field__value" id="bank_name">-</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="admin-fund-field">
                                <span class="admin-fund-field__label">Amount</span>
                                <span class="admin-fund-field__value admin-fund-field__value--amount" id="amount">-</span>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="admin-fund-field admin-fund-field--tall">
                                <span class="admin-fund-field__label">Description</span>
                                <span class="admin-fund-field__value" id="description">-</span>
                            </div>
                        </div>
                        <div class="col-md-6 image-box-wrapper d-none">
                            <div class="admin-fund-field admin-fund-field--tall">
                                <span class="admin-fund-field__label">Proof image</span>
                                <div class="admin-fund-thumb">
                                    <img id="image" src="" alt="Proof of payment" class="zoomable-image">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="admin-fund-field">
                                <span class="admin-fund-field__label">Date added</span>
                                <span class="admin-fund-field__value" id="date_added">-</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="admin-fund-field">
                                <span class="admin-fund-field__label">Status</span>
                                <span class="admin-fund-field__value" id="status_wrap">-</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Image zoom --}}
    <div class="modal fade" id="imageZoomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content admin-zoom-modal">
                <div class="modal-header border-0">
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img id="zoomedImage" src="" alt="Zoomed proof" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="container-fluid">
            <div class="admin-filter-bar">
                <div class="admin-filter-actions" id="fund_wallet_filters">
                    <button type="button" class="btn btn-primary" data-filter="">All</button>
                    <button type="button" class="btn btn-info" data-filter="Pending">Pending</button>
                    <button type="button" class="btn btn-info" data-filter="Funded">Funded</button>
                    <button type="button" class="btn btn-info" data-filter="Rejected">Rejected</button>
                    <button type="button" class="btn btn-info" data-filter="Deleted">Deleted</button>
                </div>
            </div>

            <div class="card admin-table-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped display w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Bank Name</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
$(document).ready(function () {
    const assetBase = @json(url('/'));
    const placeholderImage = @json(asset('images/upload.svg'));
    window.__fw_filter = '';

    function resolveImageUrl(path) {
        if (!path) {
            return placeholderImage;
        }
        if (/^https?:\/\//i.test(path)) {
            return path;
        }
        return assetBase.replace(/\/$/, '') + '/' + String(path).replace(/^\//, '');
    }

    function escapeHtml(value) {
        return $('<div>').text(value ?? '').html();
    }

    function statusBadge(status) {
        return window.adminStatusBadge(status);
    }

    function setActiveFilter(filter) {
        $('#fund_wallet_filters [data-filter]').each(function () {
            const isActive = $(this).data('filter') === filter;
            $(this)
                .toggleClass('btn-primary', isActive)
                .toggleClass('btn-info', !isActive);
        });
    }

    const table = $('#example').DataTable({
        processing: true,
        destroy: true,
        ajax: {
            url: '/admin/fund_wallet_requests_fetch',
            type: 'GET',
            data: function (d) {
                d.filter = window.__fw_filter || '';
            },
            dataSrc: function (json) {
                setActiveFilter(json.filter || '');
                return json.fundWallets || [];
            }
        },
        columns: [
            { data: null, render: (d, t, r, meta) => meta.row + 1 },
            { data: 'bank_name', render: (value) => escapeHtml(value) },
            {
                data: 'amount',
                render: function (value) {
                    return '<span class="admin-fund-amount">' + escapeHtml(String(value ?? '-')) + '</span>';
                }
            },
            {
                data: 'description',
                render: function (value) {
                    const text = value ? escapeHtml(value) : '-';
                    return '<span class="admin-table-note" title="' + text + '">' + text + '</span>';
                }
            },
            {
                data: 'image',
                orderable: false,
                searchable: false,
                render: function (img) {
                    const src = resolveImageUrl(img);
                    return '<button type="button" class="admin-fund-image-btn zoomable-image" data-src="' + escapeHtml(src) + '">' +
                        '<img src="' + escapeHtml(src) + '" alt="Proof" class="admin-fund-image-thumb" loading="lazy" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\';">' +
                    '</button>';
                }
            },
            {
                data: 'status',
                render: function (status) {
                    return statusBadge(status);
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (item) {
                    let html = '<div class="admin-action-group">';
                    html += '<button type="button" class="btn btn-secondary view_fund_wallet_request" value="' + item.fund_wallets_id + '" title="View"><i class="fa fa-eye"></i></button>';

                    if (item.status === 'Pending') {
                        html += '<button type="button" class="btn btn-success update_data" value="' + item.fund_wallets_id + '" data-info="Funded" title="Mark funded"><i class="fa fa-check"></i></button>';
                        html += '<button type="button" class="btn btn-warning update_data" value="' + item.fund_wallets_id + '" data-info="Rejected" title="Reject"><i class="fa fa-times"></i></button>';
                    }

                    if (item.status !== 'Deleted') {
                        html += '<button type="button" class="btn btn-danger delete_data" value="' + item.fund_wallets_id + '" title="Delete"><i class="fa fa-trash"></i></button>';
                    }

                    html += '</div>';
                    return html;
                }
            }
        ]
    });

    function reloadTable() {
        table.ajax.reload(null, true);
    }

    $(document).on('click', '#fund_wallet_filters [data-filter]', function () {
        window.__fw_filter = $(this).data('filter') || '';
        reloadTable();
    });

    $(document).on('click', '.delete_data', function (e) {
        e.preventDefault();
        if (!confirm('Delete this fund wallet request?')) {
            return;
        }
        $.post('/admin/fund_wallet_requests_delete', {
            fund_wallets_id: $(this).val()
        }).done(function (response) {
            if (response.status === 'success') {
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
            reloadTable();
        });
    });

    $(document).on('click', '.update_data', function (e) {
        e.preventDefault();
        const status = $(this).data('info');
        const label = status === 'Funded' ? 'mark this request as funded' : 'reject this request';
        if (!confirm('Are you sure you want to ' + label + '?')) {
            return;
        }
        $.post('/admin/fund_wallet_requests_update', {
            fund_wallets_id: $(this).val(),
            status: status
        }).done(function (response) {
            if (response.status === 'success') {
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
            reloadTable();
        });
    });

    $(document).on('click', '.view_fund_wallet_request', function () {
        const id = $(this).val();
        $('#viewFundWalletRequestModal').modal('show');

        $.get('/admin/fund_wallet_requests_edit/' + id, function (response) {
            if (response.status !== 'success') {
                return;
            }
            $('#bank_name').text(response.data.bank_name || '-');
            $('#amount').text(response.data.amount ?? '-');
            $('#description').text(response.data.description || '-');
            $('#date_added').text(response.data.date_added || '-');
            $('#status_wrap').html(statusBadge(response.data.status));

            if (response.data.image) {
                $('.image-box-wrapper').removeClass('d-none');
                $('#image').attr('src', resolveImageUrl(response.data.image));
            } else {
                $('.image-box-wrapper').addClass('d-none');
            }
        });
    });

    $(document).on('click', '.zoomable-image', function () {
        const src = $(this).data('src') || $(this).attr('src') || $(this).find('img').attr('src');
        if (!src || src === placeholderImage) {
            return;
        }
        $('#zoomedImage').attr('src', src);
        $('#imageZoomModal').modal('show');
    });
});
</script>
@endsection
