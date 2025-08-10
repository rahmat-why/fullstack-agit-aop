@extends('layouts.app')

@section('title', 'Menu Loans')

@section('content')
<div class="container mt-3">
    <h1>Menu Loans</h1>
    <button class="btn btn-danger btn-sm mb-3" id="btnAdd">
        <i class="bi bi-plus-lg"></i> Add Loan
    </button>

    <table id="loansTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Librarian</th>
                <th>Member</th>
                <th>Book</th>
                <th>Loan Date</th>
                <th>Return Date</th>
                <th>Note</th>
                <th style="width:120px;">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Modal Add/Edit Loan -->
<div class="modal fade" id="loanModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="loanForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add Loan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="loanId" />
            <div class="mb-3">
                <label for="loanLibrarian" class="form-label">Librarian <span class="text-danger">*</span></label>
                <select id="loanLibrarian" class="form-select" required></select>
                <div class="invalid-feedback">Please select a librarian.</div>
            </div>
            <div class="mb-3">
                <label for="loanMember" class="form-label">Member <span class="text-danger">*</span></label>
                <select id="loanMember" class="form-select" required></select>
                <div class="invalid-feedback">Please select a member.</div>
            </div>
            <div class="mb-3">
                <label for="loanBook" class="form-label">Book <span class="text-danger">*</span></label>
                <select id="loanBook" class="form-select" required></select>
                <div class="invalid-feedback">Please select a book.</div>
            </div>
            <div class="mb-3">
                <label for="loanDate" class="form-label">Loan Date <span class="text-danger">*</span></label>
                <input type="date" id="loanDate" class="form-control" required />
                <div class="invalid-feedback">Please enter the loan date.</div>
            </div>
            <div class="mb-3">
                <label for="returnDate" class="form-label">Return Date <span class="text-danger">*</span></label>
                <input type="date" id="returnDate" class="form-control" required />
                <div class="invalid-feedback">Return date must be after or same as loan date.</div>
            </div>
            <div class="mb-3">
                <label for="loanNote" class="form-label">Note</label>
                <textarea id="loanNote" class="form-control" rows="3"></textarea>
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
    const modal = new bootstrap.Modal($('#loanModal')[0]);

    let table = $('#loansTable').DataTable({
        ajax: {
            url: '/loans',
            dataSrc: 'data'
        },
        columns: [
            { data: 'id' },
            { data: 'librarian_name', title: 'Librarian' },
            { data: 'member_name', title: 'Member' },
            { data: 'book_title', title: 'Book' },
            {
                data: 'loan_at',
                title: 'Loan Date',
                render: function(data) {
                    if (!data) return '-';
                    const d = new Date(data);
                    return d.toLocaleString();
                }
            },
            {
                data: 'returned_at',
                title: 'Return Date',
                render: function(data) {
                    if (!data) return '-';
                    const d = new Date(data);
                    return d.toLocaleString();
                }
            },
            { data: 'note', title: 'Note' },
            {
                data: null,
                render: function(data, type, row) {
                    return `
                        <div class="dropdown">
                        <button class="btn btn-sm btn-secondary dropdown-toggle p-0" type="button" id="actionMenu${row.id}" data-bs-toggle="dropdown" aria-expanded="false" style="width: 32px; height: 32px; line-height: 1;">
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

    function loadUsers(role, selectId, selectedId = null) {
        $.get('/loans/users', function(res) {
            let select = $(selectId);
            select.empty();
            select.append('<option value="">-- Select --</option>');
            res.data
                .filter(user => user.role === role)
                .forEach(user => {
                    let selected = (user.id == selectedId) ? 'selected' : '';
                    select.append(`<option value="${user.id}" ${selected}>${user.name}</option>`);
                });
        });
    }

    function loadBooks(selectedId = null) {
        $.get('/loans/books', function(res) {
            let select = $('#loanBook');
            select.empty();
            select.append('<option value="">-- Select Book --</option>');
            res.data.forEach(book => {
                let selected = (book.id == selectedId) ? 'selected' : '';
                select.append(`<option value="${book.id}" ${selected}>${book.title}</option>`);
            });
        });
    }

    $('#btnAdd').click(function() {
        $('#loanForm')[0].reset();
        $('#loanId').val('');
        $('#modalTitle').text('Add Loan');
        $('#loanLibrarian, #loanMember, #loanBook, #loanDate, #returnDate').removeClass('is-invalid');
        loadUsers('librarian', '#loanLibrarian');
        loadUsers('member', '#loanMember');
        loadBooks();
        modal.show();
    });

    $('#loanForm').submit(function(e) {
        e.preventDefault();

        let id = $('#loanId').val();
        let librarian_id = $('#loanLibrarian').val();
        let member_id = $('#loanMember').val();
        let book_id = $('#loanBook').val();
        let loan_at = $('#loanDate').val();
        let returned_at = $('#returnDate').val();
        let note = $('#loanNote').val().trim();

        let valid = true;

        if (!librarian_id) {
            $('#loanLibrarian').addClass('is-invalid');
            valid = false;
        } else {
            $('#loanLibrarian').removeClass('is-invalid');
        }

        if (!member_id) {
            $('#loanMember').addClass('is-invalid');
            valid = false;
        } else {
            $('#loanMember').removeClass('is-invalid');
        }

        if (!book_id) {
            $('#loanBook').addClass('is-invalid');
            valid = false;
        } else {
            $('#loanBook').removeClass('is-invalid');
        }

        if (!loan_at) {
            $('#loanDate').addClass('is-invalid');
            valid = false;
        } else {
            $('#loanDate').removeClass('is-invalid');
        }

        if (returned_at) {
            if (returned_at < loan_at) {
                $('#returnDate').addClass('is-invalid');
                valid = false;
            } else {
                $('#returnDate').removeClass('is-invalid');
            }
        } else {
            $('#returnDate').removeClass('is-invalid');
        }

        if (!valid) return;

        let ajaxUrl = '/loans';
        let method = 'POST';
        if (id) {
            ajaxUrl = `/loans/${id}`;
            method = 'PUT';
        }

        $.ajax({
            url: ajaxUrl,
            method: method,
            data: { librarian_id, member_id, book_id, loan_at, returned_at, note },
            success: function() {
                modal.hide();
                table.ajax.reload(null, false);
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'Something went wrong!');
            }
        });
    });

    $('#loansTable tbody').on('click', '.btn-edit', function() {
        let id = $(this).data('id');
        $.get(`/loans/${id}`, function(res) {
            $('#loanId').val(res.data.id);
            $('#loanDate').val(res.data.loan_at);
            $('#returnDate').val(res.data.returned_at);
            $('#loanNote').val(res.data.note || '');
            $('#modalTitle').text('Edit Loan');
            $('#loanLibrarian, #loanMember, #loanBook, #loanDate, #returnDate').removeClass('is-invalid');

            loadUsers('librarian', '#loanLibrarian', res.data.librarian_id);
            loadUsers('member', '#loanMember', res.data.member_id);
            loadBooks(res.data.book_id);

            modal.show();
        });
    });

    $('#loansTable tbody').on('click', '.btn-delete', function() {
        if (!confirm('Are you sure want to delete this loan?')) return;
        let id = $(this).data('id');
        $.ajax({
            url: `/loans/${id}`,
            method: 'DELETE',
            success: function() {
                table.ajax.reload(null, false);
            },
            error: function() {
                alert('Failed to delete loan!');
            }
        });
    });
});
</script>
@endpush