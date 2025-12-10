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
// Mengambil data dari controller menggunakan $.ajax()
function fetchUsers(query = '') {
    $.ajax({
        url: "<?= site_url('user/fetch') ?>",
        type: "GET",
        data: {
            search: query
        },
        dataType: "json",
        success: function(data) {
            let rows = '';

            // Isi tabel
            if (data.length > 0) {
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
            } else {
                rows = `<tr><td colspan="4" class="text-center">No data found</td></tr>`;
            }

            $('#userTable tbody').html(rows);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching users:', error);
            alert('Error loading data');
        }
    });
}

// Simpan atau update menggunakan $.ajax()
$('#userForm').submit(function(e) {
    e.preventDefault();

    let id = $('#user_id').val();
    let url = id 
        ? "<?= site_url('user/update') ?>/" + id 
        : "<?= site_url('user/store') ?>";

    $.ajax({
        url: url,
        type: "POST",
        data: {
            name: $('#name').val(),
            email: $('#email').val()
        },
        dataType: "json",
        success: function(result) {
            console.log('Response:', result); // Debug
            
            if (result.status === 'success') {
                // Reset form
                $('#userForm')[0].reset();
                $('#user_id').val('');
                
                // Reload data
                fetchUsers($('#search').val());
                
                alert(result.message || (id ? 'Data updated successfully' : 'Data saved successfully'));
            } else {
                // Tampilkan error
                let errorMsg = 'Error: ';
                if (typeof result.message === 'object') {
                    errorMsg += Object.values(result.message).join(', ');
                } else {
                    errorMsg += result.message;
                }
                alert(errorMsg);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error saving data:', error);
            alert('Error saving data. Check console for details.');
        }
    });
});

// Ambil data untuk edit menggunakan $.ajax()
function editUser(id) {
    $.ajax({
        url: "<?= site_url('user/edit') ?>/" + id,
        type: "GET",
        dataType: "json",
        success: function(data) {
            $('#user_id').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);
        },
        error: function(xhr, status, error) {
            console.error('Error loading user data:', error);
            alert('Error loading user data');
        }
    });
}

// Hapus menggunakan $.ajax()
function deleteUser(id) {
    if(confirm("Hapus data ini?")) {
        $.ajax({
            url: "<?= site_url('user/delete') ?>/" + id,
            type: "GET",
            dataType: "json",
            success: function(result) {
                if (result.status === 'success') {
                    fetchUsers($('#search').val());
                    alert('Data deleted successfully');
                } else {
                    alert(result.message || 'Error deleting data');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error deleting data:', error);
                alert('Error deleting data');
            }
        });
    }
}

// Search realtime dengan debounce
let searchTimeout;
$('#search').on('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetchUsers($(this).val());
    }, 300); // Delay 300ms untuk mengurangi request
});

// Load awal
$(document).ready(function() {
    fetchUsers();
});
</script>

</body>
</html>