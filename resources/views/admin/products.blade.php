@extends('layout.admin.list_master')
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
                                <label><b>Base Price (User-side)</b></label>
                                <input type="number" step="0.01" min="0" name="price" class="form-control mt-1" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                <label><b>Description</b></label>
                                <textarea rows="5" name="description" class="form-control mt-1" required></textarea>
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

    <!-- Edit Rate Api -->
    <div class="modal fade" id="editRateApiModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            @section('titleBar')
            <span class="ml-2">Edit Rate Api</span>
            @endsection 
                <div class="modal-header">
                    <h5 class="modal-title">Edit Rate Api</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="basic-form">

                        <div class="row col-md-12"> 
                            <div class="form-group col-md-12">
                                <b>Name</b>
                                <b><input  type="text" name="name" id="name" class="form-control input" required></b>
                            </div>
                        </div>
                        <div class="row col-md-12"> 
                            <div class="form-group col-md-12">
                                <b>URL</b>
                                <b><input  type="text" name="url" id="url" class="form-control input" required></b>
                            </div>
                        </div>
                        <input type="hidden" class="input" id="rate_api_id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="edit_rate_api">Edit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Rate Api -->

    <!-- View Rate Api -->
    <div class="modal fade" id="viewRateApiModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            @section('titleBar')
            <span class="ml-2">View Rate Api</span>
            @endsection 
                <div class="modal-header">
                    <h5 class="modal-title">View Rate Api</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div id="RateApiViewModal">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Rate Api -->

    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles mb-n5">
				<ol class="breadcrumb">
                    @section('titleBar')
                    <span class="ml-2">Manage Products</span>
                    @endsection
				</ol>
            </div>
            <!-- row -->

            <div class="row">
                <div class="col-12">
                    

                    <div class="card">
                        <div class="card-body">                                    
                        <legend style="float: right;">
                            <button id="swap-sync-btn-products" type="button" class="btn btn-info mr-2">Pull Admin Products</button>
                            <button id="swap-sync-btn-transactions" type="button" class="btn btn-primary">Sync Product Transactions</button>
                        </legend>
                            <div class="table-responsive">
                                <table id="example" class="table dt-responsive nowrap display min-w850">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Base Price</th>
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
                                            <td>{{ number_format((float)($item->price ?? 0), 2) }}</td>
                                            <td>
                                                @if($item->image)
                                                    <img src="{{ asset($item->image) }}" alt="Image 1" style="width:100px; height: 100px;">
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->description)
                                                    {!! wordwrap(e($item->description), 60, '<br>') !!}
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
                                                <span class="btn btn-light" style="cursor: default; border: 1px solid #ced4da; color: #6c757d;">
                                                    Managed by Admin
                                                </span>
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-primary ml-1 swap-sync-single-product"
                                                    data-product-id="{{ $item->products_id }}"
                                                >
                                                    Sync Sales
                                                </button>
                                            <!-- modal start -->
                                            <div class="modal fade" id="modal_edit{{ $item->products_id }}">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        @section('titleBar')
                                                        <span class="ml-2">Manage Products</span>
                                                        @endsection 
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Product Details</h5>
                                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="basic-form">
                                                                <form method="post" action="{{ url('admin/products_edit') }}" enctype="multipart/form-data">
                                                                    <div class="row">
                                                                        <input type="hidden" name="products_id" value="{{ $item->products_id }}" readonly>
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
                                                                                    <option value="B" {{ ($item->type ?? '') == 'B' ? 'selected' : '' }}>B</option>
                                                                                    <option value="C" {{ ($item->type ?? '') == 'C' ? 'selected' : '' }}>C</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="form-group">
                                                                                <b>Base Price (User-side)</b>
                                                                                <input type="number" step="0.01" min="0" name="price" class="form-control mt-1" value="{{ $item->price ?? 0 }}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 mb-3"> 
                                                                            <div class="form-group">
                                                                                <b>Description</b>
                                                                                <textarea rows="6" name="description" class="form-control mt-1" required>{{ $item->description }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 mb-3"> 
                                                                            <div class="form-group">
                                                                                <b>Image</b> <br>

                                                                                @if($item->image)
                                                                                    {{-- Show existing image --}}
                                                                                    <img 
                                                                                        class="previewImage" 
                                                                                        src="{{ asset($item->image) }}" 
                                                                                        alt="Product Image" 
                                                                                        style="width: 170px; height: 150px; object-fit: cover; cursor: pointer; border-radius: 8px; border: 1px solid #ccc;"
                                                                                        onclick="this.nextElementSibling.click();"
                                                                                    >
                                                                                @else
                                                                                    {{-- Upload box if no image --}}
                                                                                    <div 
                                                                                        class="uploadBox"
                                                                                        onclick="this.nextElementSibling.click();"
                                                                                        style="width: 170px; height: 150px; border: 2px dashed #aaa; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #777;"
                                                                                    >
                                                                                        <small>Click to Upload Image</small>
                                                                                    </div>
                                                                                @endif

                                                                                {{-- Hidden file input --}}
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
                                                                    <div class="d-flex justify-content-end mt-3">
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary ml-2">Save</button>
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
    <script src="{{ asset('users/assets/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('users/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('users/assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('users/assets/js/jquery.ui.min.js') }}"></script>
    <script src="{{ asset('users/assets/js/jquery.additional.methods.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var syncBtn = document.getElementById('swap-sync-btn-products');
            var syncTransactionsBtn = document.getElementById('swap-sync-btn-transactions');

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

            if (syncBtn) {
                syncBtn.addEventListener('click', function () {
                    syncBtn.disabled = true;
                    var originalText = syncBtn.innerText;
                    syncBtn.innerText = 'Syncing...';

                    postJson('/api/insuretech/pull-products')
                        .then(function (data) {
                            if (data && data.ok) {
                                alert('Admin products synced to Swap. Total synced: ' + (data.synced_products || 0));
                                window.location.reload();
                                return;
                            }
                            alert('Sync failed. Please check configuration.');
                        })
                        .catch(function () {
                            alert('Sync failed due to network or server error.');
                        })
                        .finally(function () {
                            syncBtn.disabled = false;
                            syncBtn.innerText = originalText;
                        });
                });
            }

            if (syncTransactionsBtn) {
                syncTransactionsBtn.addEventListener('click', function () {
                    syncTransactionsBtn.disabled = true;
                    var originalText = syncTransactionsBtn.innerText;
                    syncTransactionsBtn.innerText = 'Syncing Sales...';

                    postJson('/api/insuretech/sync-all', { limit: 200 })
                        .then(function (data) {
                            if (data && data.ok) {
                                alert('Synced product sales. Success: ' + (data.success_count || 0) + ', Failed: ' + (data.failed_count || 0));
                                window.location.reload();
                                return;
                            }
                            alert('Sales sync failed. Please check logs/config.');
                        })
                        .catch(function () {
                            alert('Sales sync failed due to network or server error.');
                        })
                        .finally(function () {
                            syncTransactionsBtn.disabled = false;
                            syncTransactionsBtn.innerText = originalText;
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

                    postJson('/api/insuretech/sync-all', { product_id: productId, limit: 200 })
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