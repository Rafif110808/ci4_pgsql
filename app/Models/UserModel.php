<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {
    // Nama tabel
    protected $table = 'users';

    // Primary key
    protected $primaryKey = 'id';

    // Field yang boleh diinput
    protected $allowedFields = ['name', 'email'];

    // Mengambil semua data
    public function getAllUsers() {
        return $this->findAll(); 
    }

    // Mencari data berdasarkan keyword
    public function searchUsers($keyword) {
        return $this->like('name', $keyword)
                    ->orLike('email', $keyword)
                    ->findAll();
    }
}
