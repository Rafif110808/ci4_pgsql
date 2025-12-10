<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class UserController extends Controller {

    protected $userModel;

    // Konstruktor. Dipanggil otomatis.
    // Mempermudah pemanggilan Model.
    public function __construct() {
        $this->userModel = new UserModel();
        
        // Load helper untuk form validation
        helper(['form', 'url']);
    }

    // Menampilkan halaman utama
    public function index() {
        return view('user_view');
    }

    // Mengambil data untuk tabel (dengan search)
    public function fetch() {
        try {
            $search = $this->request->getGet('search'); // ambil keyword dari AJAX

            // Jika ada search, panggil method search di Model
            if($search && trim($search) !== ''){
                $data = $this->userModel->searchUsers($search);
            } else {
                $data = $this->userModel->getAllUsers();
            }

            // Return data dengan header JSON
            return $this->response
                        ->setJSON($data)
                        ->setStatusCode(200);
                        
        } catch (\Exception $e) {
            return $this->response
                        ->setJSON(['error' => $e->getMessage()])
                        ->setStatusCode(500);
        }
    }

    // Menyimpan data baru
    public function store() {
        try {
            // Validasi input
            $validation = \Config\Services::validation();
            
            $validation->setRules([
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response
                            ->setJSON([
                                'status' => 'error',
                                'message' => $validation->getErrors()
                            ])
                            ->setStatusCode(400);
            }

            // Insert data
            $this->userModel->insert([
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email')
            ]);

            return $this->response
                        ->setJSON([
                            'status' => 'success',
                            'message' => 'Data saved successfully'
                        ])
                        ->setStatusCode(201);
                        
        } catch (\Exception $e) {
            return $this->response
                        ->setJSON([
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ])
                        ->setStatusCode(500);
        }
    }

    // Mengambil data user berdasarkan ID untuk edit
    public function edit($id) {
        try {
            $user = $this->userModel->find($id);
            
            if (!$user) {
                return $this->response
                            ->setJSON([
                                'status' => 'error',
                                'message' => 'User not found'
                            ])
                            ->setStatusCode(404);
            }

            return $this->response
                        ->setJSON($user)
                        ->setStatusCode(200);
                        
        } catch (\Exception $e) {
            return $this->response
                        ->setJSON([
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ])
                        ->setStatusCode(500);
        }
    }

    // Mengupdate data tertentu
    public function update($id) {
        try {
            // Cek apakah user ada
            $user = $this->userModel->find($id);
            if (!$user) {
                return $this->response
                            ->setJSON([
                                'status' => 'error',
                                'message' => 'User not found'
                            ])
                            ->setStatusCode(404);
            }

            // Validasi input
            $validation = \Config\Services::validation();
            
            $validation->setRules([
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => "required|valid_email|is_unique[users.email,id,{$id}]"
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response
                            ->setJSON([
                                'status' => 'error',
                                'message' => $validation->getErrors()
                            ])
                            ->setStatusCode(400);
            }

            // Update data
            $this->userModel->update($id, [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email')
            ]);

            return $this->response
                        ->setJSON([
                            'status' => 'success',
                            'message' => 'Data updated successfully'
                        ])
                        ->setStatusCode(200);
                        
        } catch (\Exception $e) {
            return $this->response
                        ->setJSON([
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ])
                        ->setStatusCode(500);
        }
    }

    // Menghapus data tertentu
    public function delete($id) {
        try {
            // Cek apakah user ada
            $user = $this->userModel->find($id);
            if (!$user) {
                return $this->response
                            ->setJSON([
                                'status' => 'error',
                                'message' => 'User not found'
                            ])
                            ->setStatusCode(404);
            }

            // Hapus data
            $this->userModel->delete($id);

            return $this->response
                        ->setJSON([
                            'status' => 'success',
                            'message' => 'Data deleted successfully'
                        ])
                        ->setStatusCode(200);
                        
        } catch (\Exception $e) {
            return $this->response
                        ->setJSON([
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ])
                        ->setStatusCode(500);
        }
    }
}