<?php
require_once __DIR__ . '/../config/Database.php';

class MahasiswaController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Tambah data
    public function createData($nama, $nim, $prodi, $alamat = '', $gambar = '') {
        $sql = "INSERT INTO mahasiswa (nama, nim, prodi, alamat, gambar) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssss", $nama, $nim, $prodi, $alamat, $gambar);
        return $stmt->execute();
    }

    // Read data, optional search
    public function read($keyword = '') {
        if($keyword != '') {
            $sql = "SELECT * FROM mahasiswa WHERE nama LIKE ? OR nim LIKE ? OR prodi LIKE ?";
            $stmt = $this->db->prepare($sql);
            $search = "%$keyword%";
            $stmt->bind_param("sss", $search, $search, $search);
            $stmt->execute();
            return $stmt->get_result();
        } else {
            return $this->db->query("SELECT * FROM mahasiswa ORDER BY id DESC");
        }
    }

    // Read one (edit)
    public function readOne($id) {
        $sql = "SELECT * FROM mahasiswa WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Update
    public function update($id, $nama, $nim, $prodi, $alamat = '', $gambar = '') {
        $sql = "UPDATE mahasiswa SET nama=?, nim=?, prodi=?, alamat=?, gambar=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssssi", $nama, $nim, $prodi, $alamat, $gambar, $id);
        return $stmt->execute();
    }

    // Delete
    public function delete($id) {
        $sql = "DELETE FROM mahasiswa WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
