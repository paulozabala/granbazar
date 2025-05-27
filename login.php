<?php
require_once "./config/db.php";

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"]; // El usuario que acabamos de crear en la base de datos
    $password = $_POST["password"]; // La contraseña del usuario que utilizaremos

    $conn = connection();

    $logged = loginCliente($email, $password, $conn);
    if($logged["status"] == "success"){
        echo "<p>✅ Ingreso exitoso.</p>";
        //create session variables
        $_SESSION["user"] = $logged["nombre"];
        $_SESSION["user-id"] = $logged["id"];

        //redirect user dashboard
        header("Location: views/index.php");


        // avoid form resending and close connection
        closeConnection($conn);
        exit();
       
    } else {
        echo $logged["message"]."</br>";
        echo "<a href='./index.html'>Ingresar</a>";
    }
}
?>