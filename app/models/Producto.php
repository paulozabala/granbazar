<?php
require_once __DIR__ . '/../../config/db.php';

class Producto {
    private $conn;

    public function __construct() {
        $this->conn = connection();
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM Producto";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM Producto WHERE Id_Producto = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function crear($nombre, $descripcion, $precio, $stock, $categoria, $imagen, $usuario) {
        $stmt = $this->conn->prepare("INSERT INTO Producto (Nombre, Descripcion, Precio, Cantidad_Stock, Id_Categoria, Url, Doc_Usuario) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdisss", $nombre, $descripcion, $precio, $stock, $categoria, $imagen, $usuario);
        return $stmt->execute();
    }

    public function actualizar($id, $nombre, $descripcion, $precio, $stock, $categoria, $imagen, $usuario) {
        $stmt = $this->conn->prepare("UPDATE Producto SET Nombre=?, Descripcion=?, Precio=?, Cantidad_Stock=?, Id_Categoria=?, Url=?, Doc_Usuario=? WHERE Id_Producto=?");
        $stmt->bind_param("ssdisssi", $nombre, $descripcion, $precio, $stock, $categoria, $imagen, $usuario, $id );
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM Producto WHERE Id_Producto=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
