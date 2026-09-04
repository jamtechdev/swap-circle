@extends('layout.admin.list_master')

@section('titleBar')
<span>Rate API</span>
@endsection

@section('content')
    {{-- Add Rate API --}}
    <div class="modal fade" id="exampleModalAddRateApi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Rate API</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="add_rate_api_name" class="font-weight-bold">Name</label>
                        <input type="text" id="add_rate_api_name" name="catname" class="form-control catname input" required>
                        <span class="error_msg text-danger" id="name_error"></span>
                    </div>
                    <div class="form-group mb-0">
                        <label for="add_rate_api_url" class="font-weight-bold">URL</label>
                        <input type="url" id="add_rate_api_url" name="url" class="form-control url input" required placeholder="https://">
                        <span class="error_msg text-danger" id="url_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary add_rate_api">Save</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Rate API --}}
    <div class="modal fade" id="editRateApiModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Rate API</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="font-weight-bold">Name</label>
                        <input type="text" name="name" id="name" class="form-control input" required>
                    </div>
                    <div class="form-group mb-0">
                        <label for="url" class="font-weight-bold">URL</label>
                        <input type="url" name="url" id="url" class="form-control input" required>
                    </div>
                    <input type="hidden" class="input" id="rate_api_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="edit_rate_api">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    {{-- View Rate API --}}
    <div class="modal fade" id="viewRateApiModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rate API details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="RateApiViewModal"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="container-fluid">
            <div class="admin-filter-bar">
                <div class="admin-filter-actions">
                    <p class="admin-page-lead mb-0">Manage external exchange rate providers used across the platform.</p>
                </div>
                <div class="admin-filter-cta">
                    <a href="{{ url('admin/currency_rate') }}" class="btn btn-outline-primary btn-sm mr-2">View live rates</a>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalAddRateApi">
                        @include('partials.admin.icon-plus') Add Rate API
                    </button>
                </div>
            </div>

            <div class="card admin-table-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped display w-100">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Name</th>
                                    <th>URL</th>
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
    function statusBadge(status) {
        return window.adminStatusBadge(status);
    }

    function escapeHtml(value) {
        return $('<div>').text(value ?? '').html();
    }

    const table = $('#example').DataTable({
        processing: true,
        destroy: true,
        ajax: {
            url: '/admin/rate_api_fetch',
            type: 'GET',
            dataSrc: function (json) {
                return json.rate_api || [];
            }
        },
        columns: [
            { data: null, render: (d, t, r, m) => m.row + 1 },
            { data: 'name', render: (value) => escapeHtml(value) },
            {
                data: 'url',
                render: function (value) {
                    const safe = escapeHtml(value);
                    return '<span class="admin-rate-url" title="' + safe + '">' + safe + '</span>';
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

                    html += '<button type="button" class="btn btn-secondary view_rate_api" value="' + item.rate_api_id + '" title="View"><i class="fa fa-eye"></i></button>';
                    html += '<button type="button" class="btn btn-info edit_rate_api" value="' + item.rate_api_id + '" title="Edit"><i class="fa fa-edit"></i></button>';

                    if (item.status === 'Active') {
                        html += '<button type="button" class="btn btn-warning update_data" data-id="' + item.rate_api_id + '" data-s="Inactive" title="Deactivate"><i class="fa fa-times"></i></button>';
                    } else if (item.status === 'Inactive') {
                        html += '<button type="button" class="btn btn-success update_data" data-id="' + item.rate_api_id + '" data-s="Active" title="Activate"><i class="fa fa-check"></i></button>';
                    }

                    if (item.status !== 'Deleted') {
                        html += '<button type="button" class="btn btn-danger delete_data" data-id="' + item.rate_api_id + '" title="Delete"><i class="fa fa-trash"></i></button>';
                    }

                    html += '</div>';
                    return html;
                }
            }
        ]
    });

    const reload = () => table.ajax.reload(null, false);

    $(document).on('click', '.edit_rate_api', function () {
        const id = $(this).val();
        $('#editRateApiModal').modal('show');

        $.get('/admin/rate_api_edit/' + id, function (r) {
            $('#name').val(r.data.name);
            $('#url').val(r.data.url);
            $('#rate_api_id').val(r.data.rate_api_id);
        });
    });

    $(document).on('click', '.update_data', function () {
        $.post('/admin/rate_api_update', {
            rate_api_id: $(this).data('id'),
            status: $(this).data('s')
        }, reload);
    });

    $(document).on('click', '.delete_data', function () {
        if (!confirm('Delete this rate API provider?')) {
            return;
        }
        $.post('/admin/rate_api_delete', {
            rate_api_id: $(this).data('id')
        }, reload);
    });

    $(document).on('click', '.view_rate_api', function () {
        const id = $(this).val();
        $('#viewRateApiModal').modal('show');

        $.get('/admin/rate_api_edit/' + id, function (r) {
            $('#RateApiViewModal').html(
                '<div class="admin-rate-view">' +
                    '<p><strong>Name</strong><span>' + escapeHtml(r.data.name) + '</span></p>' +
                    '<p><strong>URL</strong><span>' + escapeHtml(r.data.url) + '</span></p>' +
                    '<p><strong>Status</strong>' + statusBadge(r.data.status) + '</p>' +
                '</div>'
            );
        });
    });

    $(document).on('click', '.add_rate_api', function () {
        const name = $('.catname').val().trim();
        const url = $('.url').val().trim();

        if (!name || !url) {
            toastr.error('Please enter both name and URL.');
            return;
        }

        $.post('/admin/rate_api_add_data', { name: name, url: url }, function () {
            reload();
            $('#exampleModalAddRateApi').modal('hide');
            $('.input').val('');
            toastr.success('Rate API added successfully.');
        });
    });

    $('#edit_rate_api').on('click', function () {
        $.post('/admin/rate_api_edit_data', {
            rate_api_id: $('#rate_api_id').val(),
            name: $('#name').val(),
            url: $('#url').val()
        }, function () {
            reload();
            $('#editRateApiModal').modal('hide');
            toastr.success('Rate API updated successfully.');
        });
    });
});
</script>
@endsection
