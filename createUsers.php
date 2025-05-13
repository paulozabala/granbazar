<?php
include './config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tdoc = $_POST["tdoc"] ?? '';
    $doc = $_POST["documento"] ?? '';
    $nombre = $_POST["nombre"] ?? '';
    $apellido = $_POST["apellido"] ?? '';
    $area = $_POST["area"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $telefono = $_POST["telefono"] ?? '';
    $username = $_POST["username"] ?? '';
    $tuser = $_POST["tuser"] ?? '';

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $conn = connection();
    crearUsuario($area, $tdoc, $doc, $nombre, $apellido, $email, $telefono, $username, $tuser, $hash, $conn );
    closeConnection($conn);
    // Evitar reenvío del formulario al recargar
    header("Location: dashboard.php");
    exit();
}
?>