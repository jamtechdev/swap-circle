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
                            <button id="swap-insuretech-sync-btn" type="button" class="btn btn-light border" title="InsureTech sync (verify, pull products, push sales)">
                                <i class="fas fa-sync-alt"></i>
                            </button>
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
                                            <td>{{ number_format((float)($item->custom_price ?? $item->price ?? 0), 2) }}
                                                @if(isset($item->custom_price) && $item->custom_price !== null && $item->custom_price !== '')
                                                    <br><small class="text-muted">Base: {{ number_format((float)($item->price ?? 0), 2) }}</small>
                                                @endif
                                            </td>
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
                                                                <form method="post" action="{{ url('admin/products_edit') }}">
                                                                    @csrf
                                                                    <input type="hidden" name="products_id" value="{{ $item->products_id }}">
                                                                    <div class="row">
                                                                        <div class="col-md-12 mb-3">
                                                                            <div class="form-group">
                                                                                <b>Name</b>
                                                                                <input type="text" class="form-control mt-1" value="{{ $item->name }}" readonly style="background:#f8f9fa;">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="form-group">
                                                                                <b>Type</b>
                                                                                <input type="text" class="form-control mt-1" value="{{ $item->type ?? 'A' }}" readonly style="background:#f8f9fa;">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="form-group">
                                                                                <b>Base Price (from Insurtech)</b>
                                                                                <input type="number" class="form-control mt-1" value="{{ $item->price ?? 0 }}" readonly style="background:#f8f9fa;">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 mb-3">
                                                                            <div class="form-group">
                                                                                <b>Custom Price</b> <small class="text-muted">(leave empty to use base price)</small>
                                                                                <input type="number" step="0.01" min="0" name="custom_price" class="form-control mt-1" value="{{ $item->custom_price ?? '' }}" placeholder="{{ $item->price ?? 0 }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 mb-3">
                                                                            <div class="form-group">
                                                                                <b>Description</b>
                                                                                <textarea rows="4" class="form-control mt-1" readonly style="background:#f8f9fa;">{{ $item->description }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-end mt-3">
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary ml-2">Save Price</button>
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
                                var msg = 'InsureTech sync OK.';
                                if (syncedProducts !== null) {
                                    msg += ' Products refreshed: ' + syncedProducts + '.';
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