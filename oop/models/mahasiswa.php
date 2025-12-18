<?php
class Mahasiswa {
    private $conn;
    private $table = "mahasiswa";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($nama, $nim, $prodi, $gambar='') {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (nama,nim,prodi,gambar) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $nama, $nim, $prodi, $gambar);
        return $stmt->execute();
    }

    public function read($keyword='') {
        if($keyword != ''){
            $keyword = $this->conn->real_escape_string($keyword);
            $sql = "SELECT * FROM {$this->table} 
                    WHERE nim LIKE '%$keyword%' OR nama LIKE '%$keyword%' OR prodi LIKE '%$keyword%' 
                    ORDER BY id DESC";
            return $this->conn->query($sql);
        }
        return $this->conn->query("SELECT * FROM {$this->table} ORDER BY id DESC");
    }

    public function readOne($id){
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($id, $nama, $nim, $prodi, $gambar=''){
        if($gambar != ''){
            $stmt = $this->conn->prepare("UPDATE {$this->table} SET nama=?, nim=?, prodi=?, gambar=? WHERE id=?");
            $stmt->bind_param("ssssi", $nama, $nim, $prodi, $gambar, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE {$this->table} SET nama=?, nim=?, prodi=? WHERE id=?");
            $stmt->bind_param("sssi", $nama, $nim, $prodi, $id);
        }
        return $stmt->execute();
    }

    public function delete($id){
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>