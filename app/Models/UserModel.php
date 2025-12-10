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

    // Return type
    protected $returnType = 'array';

    // Validation rules
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Name is required',
            'min_length' => 'Name must be at least 3 characters',
            'max_length' => 'Name cannot exceed 100 characters'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please provide a valid email address',
            'is_unique' => 'This email is already registered'
        ]
    ];

    // Timestamps otomatis (DIMATIKAN karena kolom belum ada di database)
    protected $useTimestamps = false;

    /**
     * Mengambil semua data users
     * Diurutkan berdasarkan ID descending (data terbaru di atas)
     */
    public function getAllUsers() {
        return $this->orderBy('id', 'DESC')->findAll();
    }

    /**
     * Mencari data berdasarkan keyword
     * Search di kolom name dan email
     * Case insensitive
     */
    public function searchUsers($keyword) {
        return $this->groupStart()
                    ->like('LOWER(name)', strtolower($keyword))
                    ->orLike('LOWER(email)', strtolower($keyword))
                    ->groupEnd()
                    ->orderBy('id', 'DESC')
                    ->findAll();
    }

    /**
     * Get user by email
     */
    public function getUserByEmail($email) {
        return $this->where('email', $email)->first();
    }

    /**
     * Check if email exists (untuk validation)
     */
    public function emailExists($email, $excludeId = null) {
        $builder = $this->where('email', $email);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Get total users count
     */
    public function getTotalUsers() {
        return $this->countAll();
    }

    /**
     * Get paginated users
     */
    public function getPaginatedUsers($perPage = 10, $page = 1) {
        return $this->orderBy('id', 'DESC')
                    ->paginate($perPage, 'default', $page);
    }
}