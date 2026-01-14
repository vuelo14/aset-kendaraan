<?php
namespace Models; use Core\Database;

class Vehicle {
    public static function all($filters=[]){
        $pdo = Database::getInstance()->pdo();
        $sql = 'SELECT * FROM vehicles WHERE 1=1'; $params = [];
        if(!empty($filters['jenis'])){ $sql .= ' AND jenis=?'; $params[] = $filters['jenis']; }
        if(!empty($filters['status_kendaraan'])){ $sql .= ' AND status_kendaraan=?'; $params[] = $filters['status_kendaraan']; }
        if(!empty($filters['status_pajak'])){ $sql .= ' AND pajak_status=?'; $params[] = $filters['status_pajak']; }
        if(!empty($filters['penanggung'])){ $sql .= ' AND current_responsible LIKE ?'; $params[] = '%' + $filters['penanggung'] + '%'; }
        $sql .= ' ORDER BY id DESC';
        $stmt = $pdo->prepare($sql); $stmt->execute($params); return $stmt->fetchAll();
    }
    public static function find($id){ $pdo = Database::getInstance()->pdo(); $stmt=$pdo->prepare('SELECT * FROM vehicles WHERE id=?'); $stmt->execute([$id]); return $stmt->fetch(); }
    public static function create($data){ $pdo = Database::getInstance()->pdo(); $stmt=$pdo->prepare('INSERT INTO vehicles (plat, merk, tipe, tahun, jenis, status_penggunaan, status_kendaraan, foto_path, current_responsible) VALUES (?,?,?,?,?,?,?,?,?)'); $stmt->execute([$data['plat'],$data['merk'],$data['tipe'],$data['tahun'],$data['jenis'],$data['status_penggunaan'],$data['status_kendaraan'],$data['foto_path'],$data['current_responsible']]); return $pdo->lastInsertId(); }
    public static function update($id,$data){ $pdo = Database::getInstance()->pdo(); $stmt=$pdo->prepare('UPDATE vehicles SET plat=?, merk=?, tipe=?, tahun=?, jenis=?, status_penggunaan=?, status_kendaraan=?, foto_path=?, current_responsible=? WHERE id=?'); $stmt->execute([$data['plat'],$data['merk'],$data['tipe'],$data['tahun'],$data['jenis'],$data['status_penggunaan'],$data['status_kendaraan'],$data['foto_path'],$data['current_responsible'],$id]); }
    public static function delete($id){ $pdo = Database::getInstance()->pdo(); $stmt=$pdo->prepare('DELETE FROM vehicles WHERE id=?'); $stmt->execute([$id]); }
}
