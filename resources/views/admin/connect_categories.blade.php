@extends('layout.admin.list_master')

@section('titleBar')
<span>Connect Categories</span>
@endsection

@section('content')
    <div class="modal fade" id="exampleModalAddConnectCategory" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add connect category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="add_category_name" class="font-weight-bold">Name</label>
                        <input type="text" id="add_category_name" class="form-control catname input" required>
                    </div>
                    <div class="form-group mb-0">
                        <label for="icon" class="font-weight-bold">Icon</label>
                        <input type="file" id="icon" class="form-control icon input" accept="image/*">
                        <textarea id="icon_string" class="input d-none"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary add_connect_category">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editConnectCateyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit connect category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="connect_categories_id">
                    <div class="form-group">
                        <label for="name" class="font-weight-bold">Name</label>
                        <input type="text" id="name" class="form-control input" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_icon" class="font-weight-bold">Replace icon</label>
                        <input type="file" id="edit_icon" class="form-control icon input" accept="image/*">
                        <textarea id="edit_icon_string" class="input d-none"></textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold d-block">Current icon</label>
                        <img src="" id="icon_preview" alt="Current icon" class="admin-connect-thumb">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="edit_connect_category">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewConnectCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Category details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body admin-connect-view">
                    <p><strong>Name</strong><span id="cc_name">-</span></p>
                    <p><strong>Status</strong><span id="cc_status_wrap">-</span></p>
                    <p class="image-row d-none"><strong>Icon</strong><img id="cc_icon" src="" alt="Category icon" class="admin-connect-thumb zoomable-image"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageZoomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content admin-zoom-modal">
                <div class="modal-header border-0">
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img id="zoomedImage" src="" alt="Zoomed image" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="container-fluid">
            <div class="admin-filter-bar">
                <div class="admin-filter-actions">
                    <p class="admin-page-lead mb-0">Categories shown on the Connect page for filtering community articles.</p>
                </div>
                <div class="admin-filter-cta">
                    <a href="{{ url('admin/connect_articles') }}" class="btn btn-outline-primary btn-sm mr-2">Manage articles</a>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalAddConnectCategory">Add category</button>
                </div>
            </div>

            <div class="card admin-table-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped display w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Icon</th>
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

    function resolveImageUrl(path) {
        if (!path) return placeholderImage;
        if (/^https?:\/\//i.test(path)) return path;
        return assetBase.replace(/\/$/, '') + '/' + String(path).replace(/^\//, '');
    }

    function escapeHtml(value) {
        return $('<div>').text(value ?? '').html();
    }

    function statusBadge(status) {
        return window.adminStatusBadge(status);
    }

    function buildActions(item) {
        let html = '<div class="admin-action-group">';
        html += '<button type="button" class="btn btn-secondary view_connect_category" value="' + item.connect_categories_id + '" title="View"><i class="fa fa-eye"></i></button>';
        html += '<button type="button" class="btn btn-info edit_connect_category" value="' + item.connect_categories_id + '" title="Edit"><i class="fa fa-edit"></i></button>';

        if (item.status === 'Active') {
            html += '<button type="button" class="btn btn-warning update_data" value="' + item.connect_categories_id + '" data-info="Inactive" title="Deactivate"><i class="fa fa-times"></i></button>';
        } else if (item.status === 'Inactive' || item.status === 'Deleted') {
            html += '<button type="button" class="btn btn-success update_data" value="' + item.connect_categories_id + '" data-info="Active" title="Activate"><i class="fa fa-check"></i></button>';
        } else if (item.status === 'Pending') {
            html += '<button type="button" class="btn btn-success update_data" value="' + item.connect_categories_id + '" data-info="Active" title="Approve"><i class="fa fa-check"></i></button>';
            html += '<button type="button" class="btn btn-warning update_data" value="' + item.connect_categories_id + '" data-info="Inactive" title="Reject"><i class="fa fa-times"></i></button>';
        }

        if (item.status !== 'Deleted') {
            html += '<button type="button" class="btn btn-danger delete_data" value="' + item.connect_categories_id + '" title="Delete"><i class="fa fa-trash"></i></button>';
        }

        html += '</div>';
        return html;
    }

    function previewImage(input, targetSelector) {
        const file = input.files && input.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function () {
            $(targetSelector).val(String(reader.result).replace(/^data:(.*,)?/, ''));
        };
        reader.readAsDataURL(file);
    }

    $('#icon').on('change', function () { previewImage(this, '#icon_string'); });
    $('#edit_icon').on('change', function () { previewImage(this, '#edit_icon_string'); });

    const table = $('#example').DataTable({
        processing: true,
        destroy: true,
        ajax: {
            url: '/admin/connect_categories_fetch',
            type: 'GET',
            dataSrc: function (json) {
                return json.connectCategories || [];
            }
        },
        columns: [
            { data: null, render: (d, t, r, m) => m.row + 1 },
            { data: 'name', render: (value) => escapeHtml(value) },
            {
                data: 'icon',
                orderable: false,
                searchable: false,
                render: function (icon) {
                    const src = resolveImageUrl(icon);
                    return '<img src="' + escapeHtml(src) + '" alt="" class="admin-connect-thumb" loading="lazy" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\';">';
                }
            },
            { data: 'status', render: (status) => statusBadge(status) },
            { data: null, orderable: false, searchable: false, render: (item) => buildActions(item) }
        ]
    });

    const reload = () => table.ajax.reload(null, false);

    $(document).on('click', '.edit_connect_category', function () {
        const id = $(this).val();
        $('#editConnectCateyModal').modal('show');
        $.get('/admin/connect_category_edit/' + id, function (response) {
            if (response.status !== 'success') {
                toastr.error(response.message);
                return;
            }
            $('#name').val(response.data.name);
            $('#connect_categories_id').val(response.data.connect_categories_id);
            $('#icon_preview').attr('src', resolveImageUrl(response.data.icon));
        });
    });

    $(document).on('click', '.delete_data', function () {
        if (!confirm('Delete this category?')) return;
        $.post('/admin/connect_category_delete', { connect_categories_id: $(this).val() }, function (response) {
            response.status === 'success' ? toastr.success(response.message) : toastr.error(response.message);
            reload();
        });
    });

    $(document).on('click', '.update_data', function () {
        $.post('/admin/connect_category_update', {
            connect_categories_id: $(this).val(),
            status: $(this).data('info')
        }, function (response) {
            response.status === 'success' ? toastr.success(response.message) : toastr.error(response.message);
            reload();
        });
    });

    $(document).on('click', '.view_connect_category', function () {
        const id = $(this).val();
        $('#viewConnectCategoryModal').modal('show');
        $.get('/admin/connect_category_edit/' + id, function (response) {
            if (response.status !== 'success') {
                toastr.error(response.message);
                return;
            }
            $('#cc_name').text(response.data.name || '-');
            $('#cc_status_wrap').html(statusBadge(response.data.status));
            if (response.data.icon) {
                $('.image-row').removeClass('d-none');
                $('#cc_icon').attr('src', resolveImageUrl(response.data.icon));
            } else {
                $('.image-row').addClass('d-none');
            }
        });
    });

    $(document).on('click', '.zoomable-image', function () {
        const src = $(this).attr('src');
        if (!src || src === placeholderImage) return;
        $('#zoomedImage').attr('src', src);
        $('#imageZoomModal').modal('show');
    });

    $('.add_connect_category').on('click', function () {
        $.post('/admin/connect_category_add_data', {
            name: $('#add_category_name').val(),
            icon_image: $('#icon_string').val()
        }, function (response) {
            if (response.status === 'success') {
                toastr.success(response.message);
                $('#exampleModalAddConnectCategory').modal('hide');
                $('.input').val('');
                reload();
            } else {
                toastr.error(response.message);
            }
        });
    });

    $('#edit_connect_category').on('click', function () {
        $.post('/admin/connect_category_edit_data', {
            connect_categories_id: $('#connect_categories_id').val(),
            name: $('#name').val(),
            icon_image: $('#edit_icon_string').val()
        }, function (response) {
            if (response.status === 'success') {
                toastr.success(response.message);
                $('#editConnectCateyModal').modal('hide');
                reload();
            } else {
                toastr.error(response.message);
            }
        });
    });
});
</script>
@endsection
