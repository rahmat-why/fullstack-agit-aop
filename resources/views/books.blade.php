@extends('layouts.app')

@section('title', 'Menu Books')

@section('content')
<div class="container mt-3">
    <h1>Menu Books</h1>
    <button class="btn btn-danger btn-sm mb-3" id="btnAdd">
        <i class="bi bi-plus-lg"></i> Add
    </button>

    <ul class="nav nav-tabs mb-3" id="booksTabs">
        <li class="nav-item">
            <a class="nav-link active" href="#" id="tabActive">Active</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" id="tabDeleted">Deleted</a>
        </li>
    </ul>

    <div id="activeTableWrapper">
        <table id="booksTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div id="deletedTableWrapper" style="display:none;">
        <table id="deletedBooksTable" class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Deleted At</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal for Add/Edit -->
<div class="modal fade" id="bookModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="bookForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add Book</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="bookId" />
            <div class="mb-3">
                <label for="bookTitle" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" id="bookTitle" class="form-control" required />
                <div class="invalid-feedback">Please enter the title.</div>
            </div>
            <div class="mb-3">
                <label for="bookAuthor" class="form-label">Author <span class="text-danger">*</span></label>
                <input type="text" id="bookAuthor" class="form-control" required />
                <div class="invalid-feedback">Please enter the author.</div>
            </div>
            <div class="mb-3">
                <label for="bookIsbn" class="form-label">ISBN <span class="text-danger">*</span></label>
                <input type="text" id="bookIsbn" class="form-control" required />
                <div class="invalid-feedback">Please enter the ISBN.</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Categories <span class="text-danger">*</span></label>
                <div id="categoryChecklist" style="max-height: 150px; overflow-y: auto; border: 1px solid #ced4da; border-radius: .25rem; padding: 0.5rem;"></div>
                <div class="invalid-feedback d-block" id="categoryError" style="display:none;"></div>
            </div>
            <div class="mb-3">
                <label for="bookDescription" class="form-label">Description</label>
                <textarea id="bookDescription" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger" id="saveBtn">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const modal = new bootstrap.Modal($('#bookModal')[0]);

    let table = $('#booksTable').DataTable({
        ajax: {
            url: '/books',
            dataSrc: 'data'
        },
        columns: [
            { data: 'id' },
            { data: 'title' },
            { data: 'authors' },
            { data: 'isbn' },
            { data: 'description', defaultContent: '-' },
            { 
                data: 'category_names',
                render: function(data) {
                    if (!data) return '-';
                    let categories = data.split(',');
                    return categories.map(cat => `<span class="badge bg-danger me-1">${cat.trim()}</span>`).join('');
                }
            },
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
                            <button class="btn btn-sm btn-secondary dropdown-toggle p-0" type="button" 
                                id="actionMenu${row.id}" data-bs-toggle="dropdown" aria-expanded="false" 
                                style="width: 32px; height: 32px; line-height: 1;">
                                &#x22EE;
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionMenu${row.id}">
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

    let deletedTable = $('#deletedBooksTable').DataTable({
        ajax: {
            url: '/books/deleted',
            dataSrc: 'data'
        },
        columns: [
            { data: 'id' },
            { data: 'title' },
            { data: 'authors' },
            { data: 'isbn' },
            { data: 'description', defaultContent: '-' },
            {
                data: 'category_names',
                render: function(data) {
                    if (!data) return '-';
                    let categories = data.split(',');
                    return categories.map(cat => `<span class="badge bg-danger me-1">${cat.trim()}</span>`).join('');
                }
            },
            { 
                data: 'deleted_at',
                render: function(data) {
                    return data ? new Date(data).toLocaleString() : '-';
                }
            }
        ]
    });

    $('#tabActive').click(function(e) {
        e.preventDefault();
        $('#booksTabs a').removeClass('active');
        $(this).addClass('active');
        $('#deletedTableWrapper').hide();
        $('#activeTableWrapper').show();
        table.ajax.reload();
    });

    $('#tabDeleted').click(function(e) {
        e.preventDefault();
        $('#booksTabs a').removeClass('active');
        $(this).addClass('active');
        $('#activeTableWrapper').hide();
        $('#deletedTableWrapper').show();
        deletedTable.ajax.reload();
    });

    $('#btnAdd').click(function() {
        $('#bookForm')[0].reset();
        $('#bookId').val('');
        $('#modalTitle').text('Add Book');
        $('#bookTitle, #bookAuthor').removeClass('is-invalid');
        loadCategories();
        modal.show();
    });

    $('#bookForm').submit(function(e) {
        e.preventDefault();

        let id = $('#bookId').val();
        let title = $('#bookTitle').val().trim();
        let author = $('#bookAuthor').val().trim();
        let isbn = $('#bookIsbn').val().trim();
        let description = $('#bookDescription').val().trim();

        let selectedCategories = [];
        $('#categoryChecklist input.category-checkbox:checked').each(function() {
            selectedCategories.push($(this).val());
        });

        // Validate fields
        let isValid = true;

        if (!title) {
            $('#bookTitle').addClass('is-invalid');
            isValid = false;
        } else {
            $('#bookTitle').removeClass('is-invalid');
        }

        if (!author) {
            $('#bookAuthor').addClass('is-invalid');
            isValid = false;
        } else {
            $('#bookAuthor').removeClass('is-invalid');
        }

        if (!isbn) {
            $('#bookIsbn').addClass('is-invalid');
            isValid = false;
        } else {
            $('#bookIsbn').removeClass('is-invalid');
        }

        if (selectedCategories.length === 0) {
            // Show some error message for categories, e.g. add a red border
            $('#categoryChecklist').addClass('border border-danger');
            isValid = false;
        } else {
            $('#categoryChecklist').removeClass('border border-danger');
        }

        if (!isValid) return;

        let ajaxUrl = '/books';
        let method = 'POST';

        if (id) {
            ajaxUrl = `/books/${id}`;
            method = 'PUT';
        }

        $.ajax({
            url: ajaxUrl,
            method: method,
            data: {
                title: title,
                authors: author,
                isbn: isbn,
                description: description,
                categories: selectedCategories
            },
            success: function() {
                modal.hide();
                table.ajax.reload(null, false);
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'Something went wrong!');
            }
        });
    });

    // Edit button click
    $('#booksTable tbody').on('click', '.btn-edit', function() {
        let id = $(this).data('id');
        $.get(`/books/${id}`, function(res) {
            $('#bookId').val(res.data.id);
            $('#bookTitle').val(res.data.title);
            $('#bookAuthor').val(res.data.authors);
            $('#bookIsbn').val(res.data.isbn);
            $('#bookDescription').val(res.data.description);
            $('#modalTitle').text('Edit Book');
            $('#bookTitle, #bookAuthor').removeClass('is-invalid');

            // Load categories and pre-check those selected for this book
            loadCategories(res.data.category_ids || []);

            modal.show();
        });
    });

    // Delete button click
    $('#booksTable tbody').on('click', '.btn-delete', function() {
        if (!confirm('Are you sure want to delete this book?')) return;
        let id = $(this).data('id');
        $.ajax({
            url: `/books/${id}`,
            method: 'DELETE',
            success: function() {
                table.ajax.reload(null, false);
            },
            error: function() {
                alert('Failed to delete book!');
            }
        });
    });

    function loadCategories(selectedIds = []) {
        $.get('/categories', function(res) {
            let container = $('#categoryChecklist');
            container.empty();

            if (res.data && res.data.length) {
                res.data.forEach(cat => {
                    let checked = selectedIds.includes(cat.id.toString()) ? 'checked' : '';
                    container.append(`
                        <div class="form-check">
                            <input class="form-check-input category-checkbox" type="checkbox" value="${cat.id}" id="cat${cat.id}" name="categories[]" ${checked}>
                            <label class="form-check-label" for="cat${cat.id}">
                                ${cat.name}
                            </label>
                        </div>
                    `);
                });
            } else {
                container.html('<small>No categories available.</small>');
            }
        });
    }
});
</script>
@endpush