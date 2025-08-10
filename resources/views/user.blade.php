@extends('layouts.app')

@section('title', 'Menu Users')

@section('content')
<div class="container mt-3">
    <h1>Menu Users</h1>
    <button class="btn btn-danger btn-sm mb-3" id="btnAdd">
        <i class="bi bi-plus-lg"></i> Add User
    </button>

    <ul class="nav nav-tabs mb-3" id="usersTabs">
        <li class="nav-item">
            <a class="nav-link active" href="#" id="tabActive">Active</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" id="tabDeleted">Deleted</a>
        </li>
    </ul>

    <div id="activeTableWrapper">
        <table id="usersTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div id="deletedTableWrapper" style="display:none;">
        <table id="deletedUsersTable" class="table table-bordered table-striped table-sm">
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

<!-- Modal Add/Edit User -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="userForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="userId" />
            <div class="mb-3">
                <label for="userName" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" id="userName" class="form-control" required />
                <div class="invalid-feedback">Please enter the name.</div>
            </div>
            <div class="mb-3">
                <label for="userEmail" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" id="userEmail" class="form-control" required />
                <div class="invalid-feedback">Please enter a valid email.</div>
            </div>
            <div class="mb-3">
                <label for="userPhone" class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" id="userPhone" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="userAddress" class="form-label">Address <span class="text-danger">*</span></label>
                <textarea id="userAddress" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-3">
                <label for="userRole" class="form-label">Role <span class="text-danger">*</span></label>
                <select id="userRole" class="form-select" required>
                    <option value="">-- Select Role --</option>
                    <option value="librarian">Librarian</option>
                    <option value="member">Member</option>
                </select>
                <div class="invalid-feedback">Please select the role.</div>
            </div>
            <div class="mb-3">
                <label for="userPassword" class="form-label">Password <span class="text-danger" id="passwordRequired">*</span></label>
                <input type="password" id="userPassword" class="form-control" />
                <div class="invalid-feedback">Please enter the password.</div>
                <small id="passwordHelp" class="form-text text-muted">Leave empty to keep current password when editing.</small>
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
    const modal = new bootstrap.Modal($('#userModal')[0]);

    let table = $('#usersTable').DataTable({
        ajax: {
            url: '/users',
            dataSrc: 'data'
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'role' },
            { data: 'phone' },
            { data: 'address' },
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

    let deletedTable = $('#deletedUsersTable').DataTable({
        ajax: {
            url: '/users/deleted',
            dataSrc: 'data'
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'role' },
            { data: 'phone' },
            { data: 'address' },
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
        $('#usersTabs a').removeClass('active');
        $(this).addClass('active');
        $('#deletedTableWrapper').hide();
        $('#activeTableWrapper').show();
        table.ajax.reload();
    });

    $('#tabDeleted').click(function(e) {
        e.preventDefault();
        $('#usersTabs a').removeClass('active');
        $(this).addClass('active');
        $('#activeTableWrapper').hide();
        $('#deletedTableWrapper').show();
        deletedTable.ajax.reload();
    });

    $('#btnAdd').click(function() {
        $('#userForm')[0].reset();
        $('#userId').val('');
        $('#modalTitle').text('Add User');
        $('#userName, #userEmail, #userRole, #userPassword').removeClass('is-invalid');
        $('#userPassword').attr('required', true);
        $('#passwordRequired').show();
        modal.show();
    });

    $('#userForm').submit(function(e) {
        e.preventDefault();

        let id = $('#userId').val();
        let name = $('#userName').val().trim();
        let email = $('#userEmail').val().trim();
        let role = $('#userRole').val().trim();
        let password = $('#userPassword').val();
        let phone = $('#userPhone').val().trim();
        let address = $('#userAddress').val().trim();

        if (!name) {
            $('#userName').addClass('is-invalid');
            return;
        } else {
            $('#userName').removeClass('is-invalid');
        }

        if (!email) {
            $('#userEmail').addClass('is-invalid');
            return;
        } else {
            $('#userEmail').removeClass('is-invalid');
        }

        if (!role) {
            $('#userRole').addClass('is-invalid');
            return;
        } else {
            $('#userRole').removeClass('is-invalid');
        }

        if (!id && !password) {
            $('#userPassword').addClass('is-invalid');
            return;
        } else {
            $('#userPassword').removeClass('is-invalid');
        }

        let ajaxUrl = '/users';
        let method = 'POST';

        if (id) {
            ajaxUrl = `/users/${id}`;
            method = 'PUT';
        }

        $.ajax({
            url: ajaxUrl,
            method: method,
            data: { name, email, role, password, phone, address },
            success: function() {
                modal.hide();
                table.ajax.reload(null, false);
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'Something went wrong!');
            }
        });
    });

    $('#usersTable tbody').on('click', '.btn-edit', function() {
        let id = $(this).data('id');
        $.get(`/users/${id}`, function(res) {
            $('#userId').val(res.data.id);
            $('#userName').val(res.data.name);
            $('#userEmail').val(res.data.email);
            $('#userRole').val(res.data.role);
            $('#userPassword').val('');
            $('#userPhone').val(res.data.phone);
            $('#userAddress').val(res.data.address);
            $('#modalTitle').text('Edit User');
            $('#userName, #userEmail, #userRole, #userPassword').removeClass('is-invalid');
            $('#userPassword').attr('required', false);
            $('#passwordRequired').hide();
            modal.show();
        });
    });

    $('#usersTable tbody').on('click', '.btn-delete', function() {
        if (!confirm('Are you sure want to delete this user?')) return;
        let id = $(this).data('id');
        $.ajax({
            url: `/users/${id}`,
            method: 'DELETE',
            success: function() {
                table.ajax.reload(null, false);
            },
            error: function() {
                alert('Failed to delete user!');
            }
        });
    });
});
</script>
@endpush