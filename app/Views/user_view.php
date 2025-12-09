<!DOCTYPE html>
<html>
<head>
    <title>CRUD AJAX CI4 + PostgreSQL</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery untuk AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="p-4">

<div class="container">
    <h2 class="mb-4">CRUD Users</h2>

    <!-- Form input user -->
    <form id="userForm" class="mb-3">
        <input type="hidden" id="user_id" name="user_id"> <!-- hidden field untuk ID user -->
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" id="name" name="name" class="form-control" placeholder="Name" required>
            </div>
            <div class="col-md-4">
                <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Save</button>
            </div>
        </div>
    </form>

    <!-- Search input -->
    <div class="mb-3">
        <input type="text" id="search" class="form-control" placeholder="Search by Name or Email">
    </div>

    <!-- Tabel user -->
    <table class="table table-bordered table-striped" id="userTable">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th style="width:150px">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
// Fungsi untuk menampilkan data user
function fetchUsers(query='') {
    $.get("<?= site_url('user/fetch') ?>", {search: query}, function(data) {
        let rows = '';
        data.forEach(function(user) {
            rows += `<tr>
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>
                            <button class="btn btn-sm btn-warning me-1" onclick="editUser(${user.id})">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})">Delete</button>
                        </td>
                     </tr>`;
        });
        $('#userTable tbody').html(rows); // isi tbody dengan data user
    });
}

// Simpan data baru atau update data
$('#userForm').submit(function(e) {
    e.preventDefault();
    let id = $('#user_id').val(); // ambil ID jika edit
    let url = id ? "<?= site_url('user/update') ?>/" + id : "<?= site_url('user/store') ?>";
    $.post(url, $(this).serialize(), function() {
        $('#userForm')[0].reset(); // reset form
        $('#user_id').val(''); // hapus hidden ID
        fetchUsers($('#search').val()); // refresh tabel, tetap filter search
    });
});

// Ambil data user untuk edit
function editUser(id) {
    $.get("<?= site_url('user/edit') ?>/" + id, function(data) {
        $('#user_id').val(data.id);
        $('#name').val(data.name);
        $('#email').val(data.email);
    });
}

// Hapus user
function deleteUser(id) {
    if(confirm("Are you sure?")) {
        $.get("<?= site_url('user/delete') ?>/" + id, function() {
            fetchUsers($('#search').val()); // refresh tabel
        });
    }
}

// Event search input
$('#search').on('input', function() {
    fetchUsers($(this).val());
});

// Load data user saat halaman siap
$(document).ready(function() {
    fetchUsers();
});
</script>

</body>
</html>
