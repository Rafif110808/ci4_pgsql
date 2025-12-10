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
    }

    // Menampilkan halaman utama
    public function index() {
        return view('user_view');
    }

    // Mengambil data untuk tabel (dengan search)
    public function fetch() {
        $search = $this->request->getGet('search'); // ambil keyword dari AJAX

        // Jika ada search, panggil method search di Model
        if($search){
            $data = $this->userModel->searchUsers($search);
        } else {
            $data = $this->userModel->getAllUsers();
        }

        return $this->response->setJSON($data);
    }

    // Menyimpan data baru
    public function store() {
        $this->userModel->insert([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email')
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }

    // Mengambil data user berdasarkan ID untuk edit
    public function edit($id) {
        return $this->response->setJSON(
            $this->userModel->find($id)
        );
    }

    // Mengupdate data tertentu
    public function update($id) {
        $this->userModel->update($id, [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email')
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }

    // Menghapus data tertentu
    public function delete($id) {
        $this->userModel->delete($id);

        return $this->response->setJSON(['status' => 'success']);
    }
}
