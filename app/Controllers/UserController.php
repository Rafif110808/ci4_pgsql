<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class UserController extends Controller {

    // Menampilkan halaman utama CRUD
    public function index() {
        return view('user_view'); // memanggil view
    }

    // Mengambil semua data user, bisa dengan search
    public function fetch() {
        $model = new UserModel();
        $search = $this->request->getGet('search'); // ambil keyword search dari AJAX
        if($search){
            // Cari user berdasarkan name atau email yang mirip dengan keyword
            $data = $model->like('name', $search)
                          ->orLike('email', $search)
                          ->findAll();
        } else {
            $data = $model->findAll(); // ambil semua user
        }
        return $this->response->setJSON($data); // kirim data ke AJAX dalam format JSON
    }

    // Menyimpan data user baru
    public function store() {
        $model = new UserModel();
        $model->insert([
            'name' => $this->request->getPost('name'), // ambil input name
            'email' => $this->request->getPost('email') // ambil input email
        ]);
        return $this->response->setJSON(['status' => 'success']); // respon sukses ke AJAX
    }

    // Mengambil data user tertentu untuk edit
    public function edit($id) {
        $model = new UserModel();
        return $this->response->setJSON($model->find($id)); // kirim data user ke AJAX
    }

    // Mengupdate data user tertentu
    public function update($id) {
        $model = new UserModel();
        $model->update($id, [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email')
        ]);
        return $this->response->setJSON(['status' => 'success']); // respon sukses
    }

    // Menghapus data user tertentu
    public function delete($id) {
        $model = new UserModel();
        $model->delete($id);
        return $this->response->setJSON(['status' => 'success']); // respon sukses
    }
}
