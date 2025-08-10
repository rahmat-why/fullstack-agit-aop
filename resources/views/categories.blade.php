@extends('layouts.app')

@section('title', 'Menu Categories')

@section('content')
<div class="container mt-3">
    <h1>Menu Categories</h1>
    <button class="btn btn-danger btn-sm mb-3" id="btnAdd">
        <i class="bi bi-plus-lg"></i> Add Category
    </button>
    
    <ul class="nav nav-tabs mb-3" id="categoryTabs">
        <li class="nav-item">
            <a class="nav-link active" href="#" id="tabActive">Active</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" id="tabDeleted">Deleted</a>
        </li>
    </ul>

    <div id="activeTableWrapper">
        <table id="categoriesTable" class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th style="width:110px;">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div id="deletedTableWrapper" style="display:none;">
        <table id="deletedCategoriesTable" class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th>Name</th>
                    <th>Deleted At</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="categoryForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="categoryId" />
          <div class="mb-3">
            <label for="categoryName" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" id="categoryName" class="form-control" required />
            <div class="invalid-feedback">Please enter a category name.</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-sm" id="saveBtn">
            <i class="bi bi-save"></i> Save
          </button>
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

<script>
$(document).ready(function() {
    const modal = new bootstrap.Modal($('#categoryModal')[0]);

    let table = $('#categoriesTable').DataTable({
        ajax: {
            url: '/categories',
            dataSrc: 'data'
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { 
                data: 'created_at',
                render: function(data) {
                    return data ? new Date(data).toLocaleString() : '-';
                }
            },
            { 
                data: 'updated_at',
                render: function(data) {
                    return data ? new Date(data).toLocaleString() : '-';
                }
            },
            { 
                data: null,
                render: function(data, type, row) {
                    return `
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton${row.id}" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Actions">
                        &#x22EE;
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton${row.id}">
                        <li><a class="dropdown-item btn-edit" href="#" data-id="${row.id}">Edit</a></li>
                        <li><a class="dropdown-item btn-delete" href="#" data-id="${row.id}">Delete</a></li>
                    </ul>
                    </div>
                    `;
                },
                orderable: false,
                searchable: false
            }
        ]
    });

    let deletedTable = $('#deletedCategoriesTable').DataTable({
        ajax: { url: '/categories/deleted', dataSrc: 'data' },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { 
                data: 'deleted_at',
                render: data => data ? new Date(data).toLocaleString() : '-'
            }
        ]
    });

    $('#tabActive').click(function(e) {
        e.preventDefault();
        $('#categoryTabs a').removeClass('active');
        $(this).addClass('active');
        $('#deletedTableWrapper').hide();
        $('#activeTableWrapper').show();
        table.ajax.reload();
    });

    $('#tabDeleted').click(function(e) {
        e.preventDefault();
        $('#categoryTabs a').removeClass('active');
        $(this).addClass('active');
        $('#activeTableWrapper').hide();
        $('#deletedTableWrapper').show();
        deletedTable.ajax.reload();
    });

    $('#btnAdd').click(function() {
        $('#categoryForm')[0].reset();
        $('#categoryId').val('');
        $('#modalTitle').text('Add Category');
        $('#categoryName').removeClass('is-invalid');
        modal.show();
    });

    $('#categoryForm').submit(function(e) {
        e.preventDefault();

        let id = $('#categoryId').val();
        let name = $('#categoryName').val().trim();

        if (!name) {
            $('#categoryName').addClass('is-invalid');
            return;
        } else {
            $('#categoryName').removeClass('is-invalid');
        }

        let ajaxUrl = '/categories';
        let method = 'POST';

        if (id) {
            ajaxUrl = `/categories/${id}`;
            method = 'PUT';
        }

        $.ajax({
            url: ajaxUrl,
            method: method,
            data: { name: name },
            success: function() {
                modal.hide();
                table.ajax.reload(null, false);
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'Something went wrong!');
            }
        });
    });

    $('#categoriesTable tbody').on('click', '.btn-edit', function() {
        let id = $(this).data('id');
        $.get(`/categories/${id}`, function(res) {
            $('#categoryId').val(res.data.id);
            $('#categoryName').val(res.data.name);
            $('#modalTitle').text('Edit Category');
            $('#categoryName').removeClass('is-invalid');
            modal.show();
        });
    });

    $('#categoriesTable tbody').on('click', '.btn-delete', function() {
        if (!confirm('Are you sure want to delete this category?')) return;
        let id = $(this).data('id');
        $.ajax({
            url: `/categories/${id}`,
            method: 'DELETE',
            success: function() {
                table.ajax.reload(null, false);
            },
            error: function() {
                alert('Failed to delete category!');
            }
        });
    });
});
</script>
@endpush
