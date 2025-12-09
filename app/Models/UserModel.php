<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {
    // Nama tabel di database
    protected $table = 'users';

    // Primary key tabel
    protected $primaryKey = 'id';

    // Field yang boleh diisi/update melalui Model
    protected $allowedFields = ['name', 'email'];
}
