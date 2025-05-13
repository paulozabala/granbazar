<?php
include './config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tdoc = $_POST['tdoc'] ?? '';
    $doc = $_POST['documento'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $dir = $_POST['direccion'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $conn = connection();
    crearCliente($tdoc, $doc, $nombre, $ciudad, $dir, $telefono, $email, $hash, $conn);
    closeConnection($conn);
    // Evitar reenvío del formulario al recargar
    header("Location: index.html?registro=exitoso");
    exit();
}
?>