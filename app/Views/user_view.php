<!DOCTYPE html>
<html>
<head>
    <title>CRUD AJAX CI4 + PostgreSQL</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="p-4">

<div class="container">
    <h2 class="mb-4">CRUD Users</h2>

    <!-- Form input -->
    <form id="userForm" class="mb-3">
        <input type="hidden" id="user_id" name="user_id">
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

    <!-- Search -->
    <input type="text" id="search" class="form-control mb-3" placeholder="Search by Name or Email">

    <!-- Tabel -->
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
// Mengambil data dari controller
function fetchUsers(query='') {
    $.get("<?= site_url('user/fetch') ?>", {search: query}, function(data) {
        let rows = '';

        // Isi tabel
        data.forEach(function(user) {
            rows += `<tr>
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>
                            <button class="btn btn-warning btn-sm me-1" onclick="editUser(${user.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">Delete</button>
                        </td>
                     </tr>`;
        });

        $('#userTable tbody').html(rows);
    });
}

// Simpan atau update
$('#userForm').submit(function(e) {
    e.preventDefault();

    let id = $('#user_id').val();
    let url = id 
        ? "<?= site_url('user/update') ?>/" + id 
        : "<?= site_url('user/store') ?>";

    $.post(url, $(this).serialize(), function() {
        $('#userForm')[0].reset();
        $('#user_id').val('');
        fetchUsers($('#search').val());
    });
});

// Ambil data untuk edit
function editUser(id) {
    $.get("<?= site_url('user/edit') ?>/" + id, function(data) {
        $('#user_id').val(data.id); 
        $('#name').val(data.name);
        $('#email').val(data.email);
    });
}

// Hapus
function deleteUser(id) {
    if(confirm("Hapus data ini?")) {
        $.get("<?= site_url('user/delete') ?>/" + id, function() {
            fetchUsers($('#search').val());
        });
    }
}

// Search realtime
$('#search').on('input', function() {
    fetchUsers($(this).val());
});

// Load awal
$(document).ready(function() {
    fetchUsers();
});
</script>

</body>
</html>
