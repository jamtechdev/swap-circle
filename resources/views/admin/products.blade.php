@extends('layout.admin.list_master')
@section('titleBar')
<span class="ml-2">Manage Products</span>
@endsection
@section('content')
    <style>
        .btn-light{
          padding-left:10px;
        }

        
        table.dataTable tbody td {
            font-size: 14px;
            padding: 12px 15px;
        }
        table.dataTable thead th {
            font-size: 14px;
            padding: 12px 15px;
        }
        table tbody tr td .btn{
            padding: 0.500rem 1.5rem;
            font-size: 14px;
        }
        .content-body .container-fluid{
            padding-top: 20px;
        }
        .container-fluid .row .btn{
            padding: 0.500rem 1.5rem;
        }
        .dataTables_length label, .dataTables_filter label{
            font-size: 14px;
            margin-bottom:0px;
        }
        .card{
            margin-bottom:0px;
            height: calc(105% - 30px);
        }
        .card .card-body{
            padding: 1.875rem 1.875rem 0rem 1.875rem;
        }
        .dataTables_wrapper:after{
            display:none;
        }
        
        /* ==============================
        Action Buttons: Border Only
        (CSS-only, No HTML change)
        ============================== */

        /* Target action column buttons */
        table td .btn {
            background-color: transparent !important;
            box-shadow: none !important;
        }

        /* Keep border & icon color */
        table td .btn.btn-success {
            border: 1px solid #28a745 !important;
            color: #28a745 !important;
        }

        table td .btn.btn-danger {
            border: 1px solid #dc3545 !important;
            color: #dc3545 !important;
        }

        table td .btn.btn-warning {
            border: 1px solid #ffc107 !important;
            color: #ffc107 !important;
        }

        table td .btn.btn-secondary {
            border: 1px solid #6c757d !important;
            color: #6c757d !important;
        }

        table td .btn.btn-primary {
            border: 1px solid #6c757d !important;
            color: #6c757d !important;
        }

        /* Hover effect (optional but clean) */
        table td .btn:hover {
            background-color: rgba(0, 0, 0, 0.03) !important;
        }

        /* Ensure icon keeps color */
        table td .btn i {
            color: inherit;
        }
        table.dataTable tbody td.product-description-cell {
            max-width: 320px;
            min-width: 260px;
            white-space: normal !important;
        }
        .product-description-preview {
            display: -webkit-box;
            line-clamp: 3;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            max-height: 63px;
            overflow: hidden;
            line-height: 1.45;
            color: #4b5563;
        }
        .product-description-preview.expanded {
            display: block;
            height: 72px;
            max-height: 72px;
            overflow-y: auto;
            padding: 8px 10px;
            border: 1px solid #d8dde6;
            border-radius: 8px;
            background: #fff;
            white-space: pre-wrap;
        }
        .product-description-toggle {
            border: 0;
            background: transparent;
            color: #28c76f;
            cursor: pointer;
            font-size: 12px;
            padding: 4px 0 0;
        }
        .product-table-image {
            width: 72px;
            height: 72px;
            object-fit: contain;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: #fff;
            padding: 4px;
            display: block;
        }
        .product-image-placeholder {
            width: 72px;
            height: 72px;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            background: #f8fafc;
            color: #94a3b8;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            font-size: 11px;
            line-height: 1;
        }
        .product-image-placeholder i {
            font-size: 20px;
        }
        .product-info-editor-wrap {
            border: 1px solid #d7dde5;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }
        .product-info-editor-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            padding: 8px;
            background: #f6f8fb;
            border-bottom: 1px solid #d7dde5;
        }
        .product-info-editor-toolbar button {
            border: 1px solid #cfd6df;
            background: #fff;
            border-radius: 5px;
            padding: 5px 9px;
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
            padding: 12px;
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
    </style>
    <!-- Add Product -->
    <div class="modal fade" id="modal_add">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ url('admin/products_add') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                <label><b>Product Name</b></label>
                                <input type="text" name="name" class="form-control mt-1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label><b>Type</b></label>
                                <select name="type" class="form-control mt-1" required>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label><b>Price</b></label>
                                <input type="number" step="0.01" min="0" name="price" class="form-control mt-1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label><b>Currency Code</b></label>
                                <input type="text" name="currency_code" value="NGN" maxlength="10" class="form-control mt-1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label><b>Currency Symbol</b></label>
                                <input type="text" name="currency_symbol" value="₦" maxlength="10" class="form-control mt-1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label><b>Delivery Request Limit (Type C)</b></label>
                                <input type="number" min="1" name="delivery_request_limit" class="form-control mt-1" placeholder="Only used for type C">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                <label><b>Description</b></label>
                                <textarea rows="3" name="description" class="form-control mt-1" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                <label><b>Product Information</b> <small class="text-muted">(rich text, HTML and images)</small></label>
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
                                    <div class="product-info-rich-editor" contenteditable="true" data-placeholder="Add detailed information, terms, benefits, images, and product notes here."></div>
                                </div>
                                </div>
                            </div>
                            <div class="col-md-12"> 
                                <div class="form-group">
                                    <b>Image</b> <br>
                                    <div
                                        class="uploadBox"
                                        onclick="this.nextElementSibling.click();"
                                        style="width: 170px; height: 150px; border: 2px dashed #aaa; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #777;"
                                    >
                                        <small>Click to Upload Image</small>
                                    </div>
                                    <input
                                        type="file"
                                        name="image"
                                        accept="image/*"
                                        style="display: none;"
                                        onchange="previewSelectedImage(this)"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary ml-2">Add Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Product -->

    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles mb-n5">
				<ol class="breadcrumb">
				</ol>
            </div>
            <!-- row -->

            <div class="row">
                <div class="col-12">
                    

                    <div class="card">
                        <div class="card-body">                                    
                        <legend style="float: right;">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_add">
                                Add Product <i class="fas fa-plus"></i>
                            </button>
                            <button id="swap-insuretech-sync-btn" type="button" class="btn btn-light border" title="InsureTech sync (verify connection and push completed sales)">
                                Sync Sales <i class="fas fa-sync-alt"></i>
                            </button>
                        </legend>
                            <div class="table-responsive">
                                <table id="example" class="table dt-responsive nowrap display min-w850">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Price</th>
                                            <th>Image</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->type ?? 'A' }}</td>
                                            <td>
                                                @php
                                                    $displayPrice = $item->custom_price ?? $item->price ?? null;
                                                    $currencySymbol = $item->currency_symbol ?? '₦';
                                                @endphp
                                                @if($displayPrice !== null && $displayPrice !== '')
                                                    {{ $currencySymbol }}{{ number_format((float)$displayPrice, 2) }}
                                                @else
                                                    <span class="text-muted">Price not set</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $image = trim((string) ($item->image ?? ''));
                                                    $imageHost = $image ? parse_url($image, PHP_URL_HOST) : null;
                                                    $isDeadLocalImage = in_array($imageHost, ['127.0.0.1', 'localhost'], true);
                                                    $productImageUrl = $image && !$isDeadLocalImage
                                                        ? (\Illuminate\Support\Str::startsWith($image, ['http://', 'https://']) ? $image : asset($image))
                                                        : null;
                                                @endphp
                                                @if($productImageUrl)
                                                    <img src="{{ $productImageUrl }}" alt="Product image" class="product-table-image" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                                    <div class="product-image-placeholder" style="display:none;"><i class="fa fa-image"></i><span>No image</span></div>
                                                @else
                                                    <div class="product-image-placeholder"><i class="fa fa-image"></i><span>No image</span></div>
                                                @endif
                                            </td>
                                            <td class="product-description-cell">
                                                @if($item->description)
                                                    <div class="product-description-preview">{{ $item->description }}</div>
                                                    @if(\Illuminate\Support\Str::length($item->description) > 220)
                                                        <button type="button" class="product-description-toggle">Show more</button>
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->status=='Active')
                                                    <span class="btn btn-success" style="cursor: default;">{{ $item->status }}</span>
                                                @else 
                                                    <span class="btn btn-secondary" style="cursor: default;">{{ $item->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- <span class="btn btn-light" style="cursor: default; border: 1px solid #ced4da; color: #6c757d;">
                                                    Managed by Admin
                                                </span> --}}
                                                {{-- <button
                                                    type="button"
                                                    class="btn btn-outline-primary ml-1 swap-sync-single-product"
                                                    data-product-id="{{ $item->products_id }}"
                                                >
                                                    Sync Sales
                                                </button> --}}
                                                <button 
                                                    type="button" 
                                                    class="btn btn-warning ml-1"
                                                    data-toggle="modal" 
                                                    data-target="#modal_edit{{ $item->products_id }}"
                                                    title="Edit Product" >
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                @if($item->status == 'Active')
                                                <a href="{{ url('admin/products_update/Inactive/'.$item->products_id) }}" 
                                                    class="btn btn-danger ml-1" 
                                                    title="Deactivate"
                                                    onclick="return confirm('Deactivate this product?')">
                                                    <i class="fa fa-ban"></i>
                                                </a>
                                                @else
                                                <a href="{{ url('admin/products_update/Active/'.$item->products_id) }}" 
                                                    class="btn btn-success ml-1" 
                                                    title="Activate"
                                                    onclick="return confirm('Activate this product?')">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                                @endif
                                            <!-- modal start -->
                                            <div class="modal fade" id="modal_edit{{ $item->products_id }}">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Product Details</h5>
                                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="basic-form">
                                                                <form method="post" action="{{ url('admin/products_edit') }}" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input type="hidden" name="products_id" value="{{ $item->products_id }}">
                                                                    <div class="row">
                                                                        <div class="col-md-12 mb-3">
                                                                            <div class="form-group">
                                                                                <b>Name</b>
                                                                                <input type="text" name="name" class="form-control mt-1" value="{{ $item->name }}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="form-group">
                                                                                <b>Type</b>
                                                                                <select name="type" class="form-control mt-1" required>
                                                                                    <option value="A" {{ ($item->type ?? 'A') == 'A' ? 'selected' : '' }}>A</option>
                                                                                    <option value="B" {{ ($item->type ?? 'A') == 'B' ? 'selected' : '' }}>B</option>
                                                                                    <option value="C" {{ ($item->type ?? 'A') == 'C' ? 'selected' : '' }}>C</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="form-group">
                                                                                <b>Price</b>
                                                                                <input type="number" step="0.01" min="0" name="price" class="form-control mt-1" value="{{ $item->custom_price ?? $item->price ?? '' }}" placeholder="Enter price" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="form-group">
                                                                                <b>Currency Code</b>
                                                                                <input type="text" maxlength="10" name="currency_code" class="form-control mt-1" value="{{ $item->currency_code ?? 'NGN' }}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="form-group">
                                                                                <b>Currency Symbol</b>
                                                                                <input type="text" maxlength="10" name="currency_symbol" class="form-control mt-1" value="{{ $item->currency_symbol ?? '₦' }}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="form-group">
                                                                                <b>Delivery Request Limit (Type C)</b>
                                                                                <input type="number" min="1" name="delivery_request_limit" class="form-control mt-1" value="{{ $item->delivery_request_limit ?? '' }}" placeholder="Only used for type C">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="form-group">
                                                                                <b>Image</b>
                                                                                <input type="file" name="image" accept="image/*" class="form-control mt-1">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 mb-3">
                                                                            <div class="form-group">
                                                                                <b>Description</b>
                                                                                <textarea rows="3" name="description" class="form-control mt-1" required>{{ $item->description }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 mb-3">
                                                                            <div class="form-group">
                                                                                <b>Product Information</b> <small class="text-muted">(rich text, HTML and images)</small>
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
                                                                                    <div class="product-info-rich-editor" contenteditable="true" data-placeholder="Add detailed information, terms, benefits, images, and product notes here."></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-end mt-3">
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary ml-2">Save Product</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- modal end -->
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
          	</div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                    var icon = insuretechSyncBtn.querySelector('i');
                    if (icon) {
                        icon.classList.add('fa-spin');
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
                                icon.classList.remove('fa-spin');
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
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = input.previousElementSibling; // either <img> or <div.uploadBox>

                    if (container.classList.contains('uploadBox')) {
                        // Replace upload box with image preview
                        container.outerHTML = `<img 
                            class="previewImage" 
                            src="${e.target.result}" 
                            alt="Product Image" 
                            style="width: 150px; height: 150px; object-fit: cover; cursor: pointer; border-radius: 8px; border: 1px solid #ccc;"
                            onclick="this.nextElementSibling.click();"
                        >`;
                    } else if (container.tagName === 'IMG') {
                        // Update existing image preview
                        container.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        }
        </script>
@endsection