@extends('layout.admin.list_master')
@section('titleBar')
<span class="ml-2">Manage Products</span>
@endsection
@section('content')
    <style>
        .admin-products-page .container-fluid { padding-top: 16px; }
        .admin-products-page .card {
            margin-bottom: 0;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
            overflow: hidden;
        }
        .admin-products-page .card .card-body { padding: 1.25rem 1.35rem 1.5rem; }
        .admin-products-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.15rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eef2f7;
        }
        .admin-products-toolbar__copy h2 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 700;
            color: #0f172a;
        }
        .admin-products-toolbar__copy p {
            margin: 0.25rem 0 0;
            font-size: 0.875rem;
            color: #64748b;
        }
        .admin-products-toolbar__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.55rem;
        }
        .admin-products-toolbar__actions .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 0.55rem 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            line-height: 1.2;
        }
        .admin-products-toolbar__actions .btn svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }
        .admin-products-toolbar__actions .btn svg.is-spinning {
            animation: admin-products-spin 0.8s linear infinite;
        }
        @keyframes admin-products-spin {
            to { transform: rotate(360deg); }
        }
        .admin-products-page .admin-action-group {
            display: inline-flex !important;
            align-items: center;
            gap: 0.5rem !important;
            flex-wrap: nowrap;
        }
        .admin-products-page .admin-action-group .btn,
        .admin-products-page .admin-action-group a.btn {
            width: 36px !important;
            height: 36px !important;
            min-width: 36px !important;
            max-width: 36px !important;
            padding: 0 !important;
            margin: 0 !important;
            border-radius: 10px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            box-shadow: none !important;
        }
        .admin-products-page .admin-action-group .btn i {
            font-size: 0.875rem !important;
            line-height: 1 !important;
            color: inherit !important;
            margin: 0 !important;
        }
        .admin-products-page .admin-action-group .btn.btn-info {
            background: #eef5ea !important;
            border: 1px solid #cfe0c8 !important;
            color: #1a472a !important;
        }
        .admin-products-page .admin-action-group .btn.btn-warning {
            background: #fff7ed !important;
            border: 1px solid #fed7aa !important;
            color: #c2410c !important;
        }
        .admin-products-page .admin-action-group .btn.btn-success {
            background: #ecfdf5 !important;
            border: 1px solid #a7f3d0 !important;
            color: #047857 !important;
        }
        .admin-products-page .admin-action-group .btn:hover { filter: brightness(0.97); }
        .admin-products-page .dataTables_wrapper .dataTables_length,
        .admin-products-page .dataTables_wrapper .dataTables_filter { margin-bottom: 0.85rem; }
        .admin-products-page .dataTables_length label,
        .admin-products-page .dataTables_filter label {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 0;
        }
        .admin-products-page .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        .admin-products-page .dataTables_wrapper {
            width: 100%;
        }
        .admin-products-page table.dataTable,
        .admin-products-page table.dataTable.no-footer {
            width: 100% !important;
            border-collapse: collapse !important;
            border-spacing: 0;
            margin: 0 !important;
        }
        .admin-products-page table.dataTable thead th {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #1a472a;
            background: #f3f8f1 !important;
            border-bottom: 1px solid #d9e7d6 !important;
            padding: 12px 14px !important;
            white-space: nowrap;
            box-sizing: border-box;
        }
        .admin-products-page table.dataTable thead th:last-child,
        .admin-products-page table.dataTable tbody td:last-child {
            width: 1%;
            white-space: nowrap;
        }
        .admin-products-page table.dataTable tbody td.product-description-cell,
        .admin-products-page table.dataTable tbody td.product-name-cell {
            width: auto;
        }
        .admin-products-page table.dataTable tbody td {
            font-size: 13.5px;
            padding: 14px !important;
            vertical-align: middle !important;
            border-top: 1px solid #eef2f7 !important;
            color: #334155;
            box-sizing: border-box;
        }
        .admin-products-page table.dataTable tbody tr:hover td { background: #fafdf8 !important; }
        .admin-products-page .product-name-cell {
            min-width: 180px;
            max-width: 240px;
            white-space: normal !important;
        }
        .admin-products-page .product-name-cell__title {
            display: block;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.35;
        }
        .admin-products-page .product-name-cell__meta {
            display: block;
            margin-top: 0.2rem;
            font-size: 12px;
            color: #94a3b8;
        }
        .admin-products-page .product-price-cell {
            white-space: nowrap;
            font-weight: 700;
            color: #0f172a;
        }
        .admin-products-page .product-price-cell small {
            display: block;
            margin-top: 0.15rem;
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
        }
        .admin-products-page table.dataTable tbody td.product-description-cell {
            max-width: 300px;
            min-width: 220px;
            white-space: normal !important;
        }
        .admin-products-page .product-description-preview {
            display: -webkit-box;
            line-clamp: 2;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            max-height: 42px;
            overflow: hidden;
            line-height: 1.45;
            color: #64748b;
            font-size: 13px;
        }
        .admin-products-page .product-description-preview.expanded {
            display: block;
            height: auto;
            max-height: 120px;
            overflow-y: auto;
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #fff;
            white-space: pre-wrap;
        }
        .admin-products-page .product-description-toggle {
            border: 0;
            background: transparent;
            color: #1a472a;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 0 0;
        }
        .admin-products-page .product-table-image {
            width: 56px;
            height: 56px;
            object-fit: contain;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: #fff;
            padding: 4px;
            display: block;
        }
        .admin-products-page .product-image-placeholder {
            width: 56px;
            height: 56px;
            border: 1px dashed #d1d9e4;
            border-radius: 12px;
            background: linear-gradient(160deg, #f8fafc, #f1f5f9);
            color: #94a3b8;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            font-size: 10px;
            font-weight: 600;
            line-height: 1;
        }
        .admin-products-page .product-image-placeholder i { font-size: 16px; opacity: 0.8; }
        .admin-products-page .dataTables_wrapper:after { display: none; }

        .product-info-editor-wrap {
            border: 1px solid #d7dde5;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
        }
        .product-info-editor-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            padding: 10px;
            background: #f6f8fb;
            border-bottom: 1px solid #d7dde5;
        }
        .product-info-editor-toolbar button {
            border: 1px solid #cfd6df;
            background: #fff;
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 13px;
            cursor: pointer;
        }
        .product-info-editor-toolbar button:hover {
            background: #e9f7ef;
            border-color: #38d77c;
        }
        .product-info-rich-editor {
            min-height: 190px;
            max-height: 360px;
            overflow-y: auto;
            padding: 14px;
            outline: none;
        }
        .product-info-rich-editor:empty:before {
            content: attr(data-placeholder);
            color: #9aa3af;
        }
        .product-info-rich-editor img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 8px 0;
        }
        .admin-product-modal .modal-content {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
        }
        .admin-product-modal .modal-header {
            background: linear-gradient(135deg, #102a43, #243b53);
            color: #fff;
            border-bottom: none;
            padding: 1rem 1.25rem;
        }
        .admin-product-modal .modal-title { font-weight: 700; color: #fff; }
        .admin-product-modal .close { color: #fff; opacity: 0.85; text-shadow: none; }
        .admin-product-modal .modal-body {
            background: #f8fafc;
            padding: 1.25rem;
            max-height: min(72vh, 760px);
            overflow-y: auto;
        }
        .admin-product-section {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1rem 1.1rem 0.35rem;
            margin-bottom: 1rem;
        }
        .admin-product-section__title {
            margin: 0 0 0.85rem;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #64748b;
        }
        .admin-product-help {
            display: block;
            margin-top: 0.35rem;
            font-size: 0.78rem;
            color: #64748b;
            line-height: 1.45;
        }
        .admin-product-label {
            display: block;
            margin-bottom: 0.35rem;
            font-size: 0.84rem;
            font-weight: 700;
            color: #334155;
        }
        .admin-product-modal .form-control,
        .admin-product-modal select.form-control {
            border-radius: 10px;
            border-color: #d7dde5;
            min-height: 42px;
        }
        .admin-product-modal textarea.form-control { min-height: 96px; }
        .admin-product-image-card {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .admin-product-image-preview {
            width: 96px;
            height: 96px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            object-fit: cover;
            cursor: pointer;
        }
        .admin-product-image-empty {
            width: 96px;
            height: 96px;
            border-radius: 12px;
            border: 1px dashed #cbd5e1;
            background: #f8fafc;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            text-align: center;
            padding: 0.5rem;
            cursor: pointer;
        }
        .admin-product-image-actions {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
            min-width: 180px;
        }
        .admin-product-image-actions .btn { border-radius: 10px; font-weight: 600; }
        .admin-product-image-filename {
            font-size: 0.78rem;
            color: #64748b;
            word-break: break-all;
        }
        .admin-product-modal__actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.65rem;
            padding-top: 0.35rem;
        }
        .admin-product-modal__actions .btn { border-radius: 10px; min-width: 110px; }
        @media (max-width: 767.98px) {
            .admin-products-toolbar { flex-direction: column; }
            .admin-products-toolbar__actions { width: 100%; }
            .admin-products-toolbar__actions .btn { flex: 1 1 auto; justify-content: center; }
        }
    </style>

    <!-- Add Product -->
    <div class="modal fade admin-product-modal" id="modal_add">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ url('admin/products_add') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="admin-product-section">
                            <p class="admin-product-section__title">Basics</p>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="admin-product-label">Product Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="admin-product-label">Type</label>
                                    <select name="type" class="form-control" required>
                                        <option value="A">A — Beneficiary</option>
                                        <option value="B">B — Beneficiary</option>
                                        <option value="C">C — Task / Delivery</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="admin-product-label">Delivery Request Limit (Type C)</label>
                                    <input type="number" min="1" name="delivery_request_limit" class="form-control" placeholder="Only used for type C">
                                </div>
                            </div>
                        </div>

                        <div class="admin-product-section">
                            <p class="admin-product-section__title">Pricing</p>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="admin-product-label">Monthly Price</label>
                                    <input type="number" step="0.01" min="0" name="price" class="form-control" required>
                                    <span class="admin-product-help">Annual cover charges 12× this amount at checkout.</span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="admin-product-label">Currency Code</label>
                                    <input type="text" name="currency_code" value="NGN" maxlength="10" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="admin-product-label">Currency Symbol</label>
                                    <input type="text" name="currency_symbol" value="₦" maxlength="10" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="admin-product-section">
                            <p class="admin-product-section__title">Customer-facing copy</p>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="admin-product-label">Short Description</label>
                                    <textarea rows="3" name="description" class="form-control" required placeholder="Keep this short — used on marketplace cards and Stripe checkout."></textarea>
                                    <span class="admin-product-help">Marketplace card + Stripe payment description. Prefer 1–3 short sentences.</span>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="admin-product-label">Product Information <small class="text-muted font-weight-normal">(Details modal)</small></label>
                                    <textarea name="product_information" class="product-info-source d-none"></textarea>
                                    <div class="product-info-editor-wrap mt-1">
                                        <div class="product-info-editor-toolbar">
                                            <button type="button" data-command="bold"><b>B</b></button>
                                            <button type="button" data-command="italic"><i>I</i></button>
                                            <button type="button" data-command="insertUnorderedList">Bullets</button>
                                            <button type="button" data-command="insertOrderedList">Numbers</button>
                                            <button type="button" class="product-info-link-btn">Link</button>
                                            <button type="button" class="product-info-image-btn">Upload Image</button>
                                            <input type="file" class="product-info-image-input d-none" accept="image/*">
                                        </div>
                                        <div class="product-info-rich-editor" contenteditable="true" data-placeholder="Add benefits, terms, and details shown when customers click Details."></div>
                                    </div>
                                    <span class="admin-product-help">This is what customers see in the marketplace Details popup — not Stripe.</span>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="admin-product-label">Product Image / Logo</label>
                                    <div class="admin-product-image-card">
                                        <div class="admin-product-image-empty uploadBox" data-role="product-image-preview">No image yet</div>
                                        <div class="admin-product-image-actions">
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-role="product-image-pick">
                                                <i class="fa fa-upload mr-1"></i> Upload image
                                            </button>
                                            <span class="admin-product-image-filename" data-role="product-image-filename">PNG or JPG · marketplace + Details modal</span>
                                            <span class="admin-product-help mb-0">Click Upload image to choose a logo file.</span>
                                        </div>
                                        <input type="file" name="image" accept="image/*" class="d-none" data-role="product-image-input">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-product-modal__actions">
                            <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Product -->

    <div class="content-body admin-products-page">
        <div class="container-fluid">
            <div class="page-titles mb-n5">
                <ol class="breadcrumb">
                </ol>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="admin-products-toolbar">
                                <div class="admin-products-toolbar__copy">
                                    <h2>Manage Products</h2>
                                    <p>{{ count($products) }} catalog products · edit copy, pricing, and logos for marketplace &amp; checkout</p>
                                </div>
                                <div class="admin-products-toolbar__actions">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_add">
                                        @include('partials.admin.icon-plus')
                                        Add Product
                                    </button>
                                    <button id="swap-insuretech-sync-btn" type="button" class="btn btn-light border" title="InsureTech sync (verify connection and push completed sales)">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true"><path d="M21 12a9 9 0 0 0-15.5-6.36M3 4v5h5"/><path d="M3 12a9 9 0 0 0 15.5 6.36M21 20v-5h-5"/></svg>
                                        Sync Sales
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="example" class="table display min-w850 w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Logo</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $key => $item)
                                        @php
                                            $displayPrice = $item->custom_price ?? $item->price ?? null;
                                            $currencySymbol = $item->currency_symbol ?? '₦';
                                            $currencyCode = strtoupper((string) ($item->currency_code ?? ''));
                                            $image = trim((string) ($item->image ?? ''));
                                            $imageHost = $image ? parse_url($image, PHP_URL_HOST) : null;
                                            $isDeadLocalImage = in_array($imageHost, ['127.0.0.1', 'localhost'], true);
                                            $productImageUrl = $image && !$isDeadLocalImage
                                                ? (\Illuminate\Support\Str::startsWith($image, ['http://', 'https://']) ? $image : asset($image))
                                                : null;
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td class="product-name-cell">
                                                <span class="product-name-cell__title">{{ $item->name }}</span>
                                                <span class="product-name-cell__meta">ID {{ $item->products_id }} · Type {{ $item->type ?? 'A' }}</span>
                                            </td>
                                            <td class="product-price-cell">
                                                @if($displayPrice !== null && $displayPrice !== '')
                                                    {{ $currencySymbol }}{{ number_format((float) $displayPrice, 2) }}
                                                    @if($currencyCode !== '')
                                                        <small>{{ $currencyCode }} / month</small>
                                                    @else
                                                        <small>Monthly base</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Price not set</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($productImageUrl)
                                                    <img src="{{ $productImageUrl }}" alt="{{ $item->name }} logo" class="product-table-image" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                                    <div class="product-image-placeholder" style="display:none;"><i class="fa fa-image"></i><span>None</span></div>
                                                @else
                                                    <div class="product-image-placeholder"><i class="fa fa-image"></i><span>None</span></div>
                                                @endif
                                            </td>
                                            <td class="product-description-cell">
                                                @if($item->description)
                                                    <div class="product-description-preview">{{ $item->description }}</div>
                                                    @if(\Illuminate\Support\Str::length($item->description) > 120)
                                                        <button type="button" class="product-description-toggle">Show more</button>
                                                    @endif
                                                @else
                                                    <span class="text-muted">No description</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->status=='Active')
                                                    <span class="admin-status-badge admin-status-badge--active">{{ $item->status }}</span>
                                                @elseif ($item->status=='Inactive')
                                                    <span class="admin-status-badge admin-status-badge--inactive">{{ $item->status }}</span>
                                                @else
                                                    <span class="admin-status-badge admin-status-badge--deleted">{{ $item->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="admin-action-group">
                                                    <button
                                                        type="button"
                                                        class="btn btn-info"
                                                        data-toggle="modal"
                                                        data-target="#modal_edit{{ $item->products_id }}"
                                                        title="Edit product"
                                                        aria-label="Edit product">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    @if($item->status == 'Active')
                                                    <a href="{{ url('admin/products_update/Inactive/'.$item->products_id) }}"
                                                        class="btn btn-warning"
                                                        title="Deactivate product"
                                                        aria-label="Deactivate product"
                                                        onclick="return confirm('Deactivate this product?')">
                                                        <i class="fa fa-ban"></i>
                                                    </a>
                                                    @else
                                                    <a href="{{ url('admin/products_update/Active/'.$item->products_id) }}"
                                                        class="btn btn-success"
                                                        title="Activate product"
                                                        aria-label="Activate product"
                                                        onclick="return confirm('Activate this product?')">
                                                        <i class="fa fa-check"></i>
                                                    </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Edit Modals (outside table to prevent table layout breakage) --}}
            @foreach($products as $key => $item)
            <div class="modal fade admin-product-modal" id="modal_edit{{ $item->products_id }}">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Product Details</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            @php
                                $editImage = trim((string) ($item->image ?? ''));
                                $editImageHost = $editImage ? parse_url($editImage, PHP_URL_HOST) : null;
                                $editImageDead = in_array($editImageHost, ['127.0.0.1', 'localhost'], true);
                                $editImageUrl = $editImage && !$editImageDead
                                    ? (\Illuminate\Support\Str::startsWith($editImage, ['http://', 'https://']) ? $editImage : asset($editImage))
                                    : null;
                            @endphp
                            <form method="post" action="{{ url('admin/products_edit') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="products_id" value="{{ $item->products_id }}">

                                <div class="admin-product-section">
                                    <p class="admin-product-section__title">Basics</p>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="admin-product-label">Product Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="admin-product-label">Type</label>
                                            <select name="type" class="form-control" required>
                                                <option value="A" {{ ($item->type ?? 'A') == 'A' ? 'selected' : '' }}>A — Beneficiary</option>
                                                <option value="B" {{ ($item->type ?? 'A') == 'B' ? 'selected' : '' }}>B — Beneficiary</option>
                                                <option value="C" {{ ($item->type ?? 'A') == 'C' ? 'selected' : '' }}>C — Task / Delivery</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="admin-product-label">Delivery Request Limit (Type C)</label>
                                            <input type="number" min="1" name="delivery_request_limit" class="form-control" value="{{ $item->delivery_request_limit ?? '' }}" placeholder="Only used for type C">
                                        </div>
                                    </div>
                                </div>

                                <div class="admin-product-section">
                                    <p class="admin-product-section__title">Pricing</p>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="admin-product-label">Monthly Price</label>
                                            <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ $item->custom_price ?? $item->price ?? '' }}" required>
                                            <span class="admin-product-help">Annual cover charges 12× this amount at checkout.</span>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="admin-product-label">Currency Code</label>
                                            <input type="text" maxlength="10" name="currency_code" class="form-control" value="{{ $item->currency_code ?? 'NGN' }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="admin-product-label">Currency Symbol</label>
                                            <input type="text" maxlength="10" name="currency_symbol" class="form-control" value="{{ $item->currency_symbol ?? '₦' }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="admin-product-section">
                                    <p class="admin-product-section__title">Customer-facing copy</p>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="admin-product-label">Short Description</label>
                                            <textarea rows="3" name="description" class="form-control" required>{{ $item->description }}</textarea>
                                            <span class="admin-product-help">Marketplace card + Stripe payment description. Prefer 1–3 short sentences.</span>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="admin-product-label">Product Information <small class="text-muted font-weight-normal">(Details modal)</small></label>
                                            <textarea name="product_information" class="product-info-source d-none">{{ $item->product_information ?? '' }}</textarea>
                                            <div class="product-info-editor-wrap mt-1">
                                                <div class="product-info-editor-toolbar">
                                                    <button type="button" data-command="bold"><b>B</b></button>
                                                    <button type="button" data-command="italic"><i>I</i></button>
                                                    <button type="button" data-command="insertUnorderedList">Bullets</button>
                                                    <button type="button" data-command="insertOrderedList">Numbers</button>
                                                    <button type="button" class="product-info-link-btn">Link</button>
                                                    <button type="button" class="product-info-image-btn">Upload Image</button>
                                                    <input type="file" class="product-info-image-input d-none" accept="image/*">
                                                </div>
                                                <div class="product-info-rich-editor" contenteditable="true" data-placeholder="Add benefits, terms, and details shown when customers click Details."></div>
                                            </div>
                                            <span class="admin-product-help">This is what customers see in the marketplace Details popup — not Stripe.</span>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="admin-product-label">Product Image / Logo</label>
                                            <div class="admin-product-image-card">
                                                @if($editImageUrl)
                                                    <img src="{{ $editImageUrl }}" alt="Current product image" class="admin-product-image-preview previewImage" data-role="product-image-preview">
                                                @else
                                                    <div class="admin-product-image-empty uploadBox" data-role="product-image-preview">No image yet</div>
                                                @endif
                                                <div class="admin-product-image-actions">
                                                    <button type="button" class="btn btn-outline-primary btn-sm" data-role="product-image-pick">
                                                        <i class="fa fa-upload mr-1"></i> {{ $editImageUrl ? 'Change image' : 'Upload image' }}
                                                    </button>
                                                    <span class="admin-product-image-filename" data-role="product-image-filename">
                                                        {{ $editImageUrl ? 'Current logo shown · choose a new file to replace it' : 'PNG or JPG · marketplace + Details modal' }}
                                                    </span>
                                                    <span class="admin-product-help mb-0">Use Change image to replace the logo. Leave unchanged to keep the current one.</span>
                                                </div>
                                                <input type="file" name="image" accept="image/*" class="d-none" data-role="product-image-input">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="admin-product-modal__actions">
                                    <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Product</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.jQuery && $.fn.DataTable && $.fn.DataTable.isDataTable('#example')) {
                var productsTable = $('#example').DataTable();
                setTimeout(function () {
                    productsTable.columns.adjust().draw(false);
                }, 50);
                $(window).on('resize', function () {
                    productsTable.columns.adjust();
                });
            }

            var insuretechSyncBtn = document.getElementById('swap-insuretech-sync-btn');

            function postJson(url, payload) {
                return fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload || {})
                }).then(function (response) { return response.json(); });
            }

            if (insuretechSyncBtn) {
                insuretechSyncBtn.addEventListener('click', function () {
                    insuretechSyncBtn.disabled = true;
                    var icon = insuretechSyncBtn.querySelector('svg');
                    if (icon) {
                        icon.classList.add('is-spinning');
                    }

                    postJson('/api/insuretech/sync', { limit: 200 })
                        .then(function (data) {
                            if (data && data.ok) {
                                var total = (data.success_count || 0) + (data.failed_count || 0);
                                var pull = data.products_pull || {};
                                var pullOk = pull.ok !== false;
                                var syncedProducts = typeof pull.synced_products === 'number' ? pull.synced_products : null;
                                var msg = 'InsureTech sales sync OK.';
                                if (syncedProducts !== null && syncedProducts > 0) {
                                    msg += ' Existing product mappings checked: ' + syncedProducts + '.';
                                }
                                if (total === 0 && data.mode === 'batch') {
                                    msg += ' No mapped purchases to push.';
                                } else if (data.mode === 'batch') {
                                    msg += ' Pushes — success: ' + (data.success_count || 0) + ', failed: ' + (data.failed_count || 0) + '.';
                                }
                                if (!pullOk) {
                                    msg += ' (product pull reported issues — check details)';
                                }
                                alert(msg);
                                window.location.reload();
                                return;
                            }
                            alert((data && data.message) ? data.message : 'InsureTech sync failed. Check config/logs.');
                        })
                        .catch(function () {
                            alert('InsureTech sync failed due to network or server error.');
                        })
                        .finally(function () {
                            insuretechSyncBtn.disabled = false;
                            if (icon) {
                                icon.classList.remove('is-spinning');
                            }
                        });
                });
            }

            document.querySelectorAll('.swap-sync-single-product').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var productId = Number(btn.getAttribute('data-product-id') || '0');
                    if (!productId) {
                        alert('Invalid product selected for sync.');
                        return;
                    }

                    btn.disabled = true;
                    var originalText = btn.innerText;
                    btn.innerText = 'Syncing...';

                    postJson('/api/insuretech/sync', { product_id: productId, limit: 200 })
                        .then(function (data) {
                            if (data && data.ok) {
                                alert('Product sales synced. Success: ' + (data.success_count || 0) + ', Failed: ' + (data.failed_count || 0));
                                window.location.reload();
                                return;
                            }
                            alert('Product sales sync failed.');
                        })
                        .catch(function () {
                            alert('Product sales sync failed due to network or server error.');
                        })
                        .finally(function () {
                            btn.disabled = false;
                            btn.innerText = originalText;
                        });
                });
            });

            document.querySelectorAll('.product-description-toggle').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var preview = btn.previousElementSibling;
                    if (!preview) {
                        return;
                    }

                    preview.classList.toggle('expanded');
                    btn.innerText = preview.classList.contains('expanded') ? 'Show less' : 'Show more';
                });
            });

            document.querySelectorAll('.product-info-editor-wrap').forEach(function (wrap) {
                var source = wrap.previousElementSibling;
                var editor = wrap.querySelector('.product-info-rich-editor');
                var imageInput = wrap.querySelector('.product-info-image-input');

                if (!source || !editor) {
                    return;
                }

                editor.innerHTML = source.value || '';

                function syncProductInformation() {
                    source.value = editor.innerHTML.trim();
                }

                wrap.querySelectorAll('[data-command]').forEach(function (button) {
                    button.addEventListener('click', function () {
                        editor.focus();
                        document.execCommand(button.getAttribute('data-command'), false, null);
                        syncProductInformation();
                    });
                });

                var linkButton = wrap.querySelector('.product-info-link-btn');
                if (linkButton) {
                    linkButton.addEventListener('click', function () {
                        var url = window.prompt('Enter link URL');
                        if (!url) {
                            return;
                        }
                        editor.focus();
                        document.execCommand('createLink', false, url);
                        syncProductInformation();
                    });
                }

                var imageButton = wrap.querySelector('.product-info-image-btn');
                if (imageButton && imageInput) {
                    imageButton.addEventListener('click', function () {
                        imageInput.click();
                    });

                    imageInput.addEventListener('change', function () {
                        var file = imageInput.files && imageInput.files[0];
                        if (!file) {
                            return;
                        }

                        var formData = new FormData();
                        formData.append('image', file);

                        imageButton.disabled = true;
                        imageButton.innerText = 'Uploading...';

                        fetch("{{ url('admin/products/information-image') }}", {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            body: formData
                        })
                            .then(function (response) {
                                return response.json().then(function (data) {
                                    if (!response.ok) {
                                        throw new Error(data.message || 'Image upload failed.');
                                    }
                                    return data;
                                });
                            })
                            .then(function (data) {
                                if (!data.url) {
                                    throw new Error('Image upload failed.');
                                }
                                editor.focus();
                                document.execCommand('insertImage', false, data.url);
                                syncProductInformation();
                            })
                            .catch(function (error) {
                                alert(error.message || 'Image upload failed.');
                            })
                            .finally(function () {
                                imageInput.value = '';
                                imageButton.disabled = false;
                                imageButton.innerText = 'Upload Image';
                            });
                    });
                }

                editor.addEventListener('input', syncProductInformation);
                var form = wrap.closest('form');
                if (form) {
                    form.addEventListener('submit', syncProductInformation);
                }
            });
        });

        function previewSelectedImage(input) {
            const file = input.files && input.files[0];
            const card = input.closest('.admin-product-image-card');
            if (!file || !card) {
                return;
            }

            const filename = card.querySelector('[data-role="product-image-filename"]');
            const pickBtn = card.querySelector('[data-role="product-image-pick"]');
            if (filename) {
                filename.textContent = 'Selected: ' + file.name;
            }
            if (pickBtn) {
                pickBtn.innerHTML = '<i class="fa fa-upload mr-1"></i> Change image';
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                let preview = card.querySelector('[data-role="product-image-preview"]');
                if (!preview) {
                    return;
                }

                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                    return;
                }

                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Product Image';
                img.className = 'admin-product-image-preview previewImage';
                img.setAttribute('data-role', 'product-image-preview');
                preview.replaceWith(img);
            };
            reader.readAsDataURL(file);
        }

        document.addEventListener('click', function (event) {
            var pickBtn = event.target.closest('[data-role="product-image-pick"]');
            var preview = event.target.closest('[data-role="product-image-preview"]');
            var card = (pickBtn || preview) ? (pickBtn || preview).closest('.admin-product-image-card') : null;
            if (!card) {
                return;
            }
            var input = card.querySelector('[data-role="product-image-input"]');
            if (input) {
                input.click();
            }
        });

        document.addEventListener('change', function (event) {
            if (event.target && event.target.matches('[data-role="product-image-input"]')) {
                previewSelectedImage(event.target);
            }
        });
        </script>
@endsection