<?php
require_once "./config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tdoc = $_POST['tdoc'] ?? '';
    $doc = $_POST['documento'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $dir = $_POST['direccion'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';

    if (isset($_GET['id'])) {
        $id = $_GET['id']; // Get the value of 'id' from the URL
        echo "el ID del usuario es: " . htmlspecialchars($id);

        $conn = connection();
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $userInfo = [
            "T_Documento" => $tdoc,
            "Doc_Cliente" => $doc,
            "Nombre" => $nombre,
            "Email" => $email,
            "Telefono" => $telefono,
            "Contraseña" => $hash,
            "Direccion" => $dir,
            "Ciudad" => $ciudad
        ];

        if (!empty($doc)) {
            clientUpdate($id, $userInfo, $conn);

            echo "</br>" . "<a href='./dashboardClientes.php?action='inicieSesion'>Volver al Dashboard</a>";
        }

    } else {
        echo "No se encontró usuario a actualizar.";
    }



}



?>