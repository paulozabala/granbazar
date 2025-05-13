<?php
    require_once "./config/db.php";

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

        if (isset($_GET['id'])) {
            $id = $_GET['id']; // Get the value of 'id' from the URL
            echo "el ID del usuario es: " . htmlspecialchars($id);

            $conn = connection();
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $userInfo = [
                "tdoc" => $tdoc,
                "doc" => $doc,
                "nombre" => $nombre,
                "apellido" => $apellido,
                "area" => $area,
                "email" => $email,
                "telefono" => $telefono,
                "password" => $hash,
                "username" => $username,
                "tuser" => $tuser
            ];

            userUpdate($id, $userInfo, $conn);

            echo "</br>"."<a href='./dashboard.php'>Volver al Dashboard</a>";


        } else {
            echo "No se encontró usuario a actualizar.";
        }



    }



?>