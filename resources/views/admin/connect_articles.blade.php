@extends('layout.admin.list_master')

@section('titleBar')
<span>Connect Articles</span>
@endsection

@section('content')
    <div class="modal fade" id="exampleModalAddConnectArticle" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add connect article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="addconnectCategory" class="font-weight-bold">Category</label>
                        <select class="form-control" id="addconnectCategory"></select>
                    </div>
                    <div class="form-group">
                        <label for="add_article_title" class="font-weight-bold">Title</label>
                        <input type="text" id="add_article_title" class="form-control title input" required>
                    </div>
                    <div class="form-group">
                        <label for="add_article_description" class="font-weight-bold">Description</label>
                        <textarea rows="4" id="add_article_description" class="form-control description input"></textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label for="image" class="font-weight-bold">Image</label>
                        <input type="file" id="image" class="form-control image input" accept="image/*">
                        <textarea id="image_string" class="input d-none"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary add_connect_article">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editConnectArticleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit connect article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="connect_articles_id">
                    <div class="form-group">
                        <label for="editconnectCategory" class="font-weight-bold">Category</label>
                        <select class="form-control" id="editconnectCategory"></select>
                    </div>
                    <div class="form-group">
                        <label for="title" class="font-weight-bold">Title</label>
                        <input type="text" id="title" class="form-control input" required>
                    </div>
                    <div class="form-group">
                        <label for="description" class="font-weight-bold">Description</label>
                        <textarea rows="4" id="description" class="form-control input"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_image" class="font-weight-bold">Replace image</label>
                        <input type="file" id="edit_image" class="form-control input" accept="image/*">
                        <textarea id="edit_image_string" class="input d-none"></textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold d-block">Current image</label>
                        <img src="" id="image_preview" alt="Current article image" class="admin-connect-thumb admin-connect-thumb--wide">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="edit_connect_article">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewConnectArticleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Article details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body admin-connect-view" id="ConnectArticleViewModal"></div>
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
                    <p class="admin-page-lead mb-0">Community articles and insights shown on the user Connect page.</p>
                </div>
                <div class="admin-filter-cta">
                    <a href="{{ url('admin/connect_categories') }}" class="btn btn-outline-primary btn-sm mr-2">Manage categories</a>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalAddConnectArticle">@include('partials.admin.icon-plus') Add article</button>
                </div>
            </div>

            <div class="card admin-table-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped display w-100">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Title</th>
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

    function resolveImageUrl(path) {
        if (!path) return placeholderImage;
        if (/^https?:\/\//i.test(path)) return path;
        return assetBase.replace(/\/$/, '') + '/' + String(path).replace(/^\//, '');
    }

    function escapeHtml(value) {
        return $('<div>').text(value ?? '').html();
    }

    function truncate(text, limit) {
        const value = String(text || '');
        return value.length > limit ? value.substring(0, limit) + '…' : value;
    }

    function statusBadge(status) {
        return window.adminStatusBadge(status);
    }

    function buildActions(item) {
        let html = '<div class="admin-action-group">';
        html += '<button type="button" class="btn btn-secondary view_connect_article" value="' + item.connect_articles_id + '" title="View"><i class="fa fa-eye"></i></button>';
        html += '<button type="button" class="btn btn-info edit_connect_article" value="' + item.connect_articles_id + '" title="Edit"><i class="fa fa-edit"></i></button>';

        if (item.status === 'Active') {
            html += '<button type="button" class="btn btn-warning update_data" value="' + item.connect_articles_id + '" data-info="Inactive" title="Deactivate"><i class="fa fa-times"></i></button>';
        } else if (item.status === 'Inactive' || item.status === 'Deleted') {
            html += '<button type="button" class="btn btn-success update_data" value="' + item.connect_articles_id + '" data-info="Active" title="Activate"><i class="fa fa-check"></i></button>';
        } else if (item.status === 'Pending') {
            html += '<button type="button" class="btn btn-success update_data" value="' + item.connect_articles_id + '" data-info="Active" title="Approve"><i class="fa fa-check"></i></button>';
            html += '<button type="button" class="btn btn-warning update_data" value="' + item.connect_articles_id + '" data-info="Inactive" title="Reject"><i class="fa fa-times"></i></button>';
        }

        if (item.status !== 'Deleted') {
            html += '<button type="button" class="btn btn-danger delete_data" value="' + item.connect_articles_id + '" title="Delete"><i class="fa fa-trash"></i></button>';
        }

        html += '</div>';
        return html;
    }

    function loadCategories() {
        return $.get('/admin/connect_categories_fetch').done(function (response) {
            const options = (response.connectCategories || [])
                .filter(function (item) { return item.status === 'Active'; })
                .map(function (item) {
                    return '<option value="' + item.connect_categories_id + '">' + escapeHtml(item.name) + '</option>';
                }).join('');
            $('#addconnectCategory, #editconnectCategory').html(options);
        });
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

    $('#image').on('change', function () { previewImage(this, '#image_string'); });
    $('#edit_image').on('change', function () { previewImage(this, '#edit_image_string'); });

    loadCategories();

    const table = $('#example').DataTable({
        processing: true,
        destroy: true,
        ajax: {
            url: '/admin/connect_articles_fetch',
            type: 'GET',
            dataSrc: function (json) {
                return json.connectArticles || [];
            }
        },
        columns: [
            { data: null, render: (d, t, r, m) => m.row + 1 },
            {
                data: 'title',
                render: function (value) {
                    const safe = escapeHtml(value);
                    return '<span class="admin-table-note" title="' + safe + '">' + escapeHtml(truncate(value, 48)) + '</span>';
                }
            },
            {
                data: 'description',
                render: function (value) {
                    const safe = escapeHtml(value || '-');
                    return '<span class="admin-table-note" title="' + safe + '">' + escapeHtml(truncate(value || '-', 56)) + '</span>';
                }
            },
            {
                data: 'image',
                orderable: false,
                searchable: false,
                render: function (image) {
                    const src = resolveImageUrl(image);
                    return '<img src="' + escapeHtml(src) + '" alt="" class="admin-connect-thumb admin-connect-thumb--wide zoomable-image" loading="lazy" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\';">';
                }
            },
            { data: 'status', render: (status) => statusBadge(status) },
            { data: null, orderable: false, searchable: false, render: (item) => buildActions(item) }
        ]
    });

    const reload = () => table.ajax.reload(null, false);

    $(document).on('click', '.edit_connect_article', function () {
        const id = $(this).val();
        loadCategories().always(function () {
            $('#editConnectArticleModal').modal('show');
            $.get('/admin/connect_article_edit/' + id, function (response) {
                if (response.status !== 'success') {
                    toastr.error(response.message);
                    return;
                }
                $('#title').val(response.data.title);
                $('#description').val(response.data.description);
                $('#connect_articles_id').val(response.data.connect_articles_id);
                $('#editconnectCategory').val(response.data.connect_categories_id);
                $('#image_preview').attr('src', resolveImageUrl(response.data.image));
            });
        });
    });

    $(document).on('click', '.delete_data', function () {
        if (!confirm('Delete this article?')) return;
        $.post('/admin/connect_article_delete', { connect_articles_id: $(this).val() }, function (response) {
            response.status === 'success' ? toastr.success(response.message) : toastr.error(response.message);
            reload();
        });
    });

    $(document).on('click', '.update_data', function () {
        $.post('/admin/connect_article_update', {
            connect_articles_id: $(this).val(),
            status: $(this).data('info')
        }, function (response) {
            response.status === 'success' ? toastr.success(response.message) : toastr.error(response.message);
            reload();
        });
    });

    $(document).on('click', '.view_connect_article', function () {
        const id = $(this).val();
        $('#viewConnectArticleModal').modal('show');
        $.get('/admin/connect_article_edit/' + id, function (response) {
            if (response.status !== 'success') {
                toastr.error(response.message);
                return;
            }
            const imageHtml = response.data.image
                ? '<p><strong>Image</strong><img src="' + escapeHtml(resolveImageUrl(response.data.image)) + '" alt="Article image" class="admin-connect-thumb admin-connect-thumb--wide zoomable-image"></p>'
                : '';
            $('#ConnectArticleViewModal').html(
                '<p><strong>Title</strong><span>' + escapeHtml(response.data.title) + '</span></p>' +
                '<p><strong>Description</strong><span>' + escapeHtml(response.data.description || '-') + '</span></p>' +
                '<p><strong>Status</strong>' + statusBadge(response.data.status) + '</p>' +
                imageHtml
            );
        });
    });

    $(document).on('click', '.zoomable-image', function () {
        const src = $(this).attr('src');
        if (!src || src === placeholderImage) return;
        $('#zoomedImage').attr('src', src);
        $('#imageZoomModal').modal('show');
    });

    $('.add_connect_article').on('click', function () {
        $.post('/admin/connect_article_add_data', {
            title: $('#add_article_title').val(),
            description: $('#add_article_description').val(),
            connect_categories_id: $('#addconnectCategory').val(),
            image: $('#image_string').val()
        }, function (response) {
            if (response.status === 'success') {
                toastr.success(response.message);
                $('#exampleModalAddConnectArticle').modal('hide');
                $('.input').val('');
                reload();
            } else {
                toastr.error(response.message);
            }
        });
    });

    $('#edit_connect_article').on('click', function () {
        $.post('/admin/connect_article_edit_data', {
            connect_articles_id: $('#connect_articles_id').val(),
            title: $('#title').val(),
            description: $('#description').val(),
            connect_categories_id: $('#editconnectCategory').val(),
            image: $('#edit_image_string').val()
        }, function (response) {
            if (response.status === 'success') {
                toastr.success(response.message);
                $('#editConnectArticleModal').modal('hide');
                reload();
            } else {
                toastr.error(response.message);
            }
        });
    });
});
</script>
@endsection
