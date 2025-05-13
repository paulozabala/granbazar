<?php
//inicia conexión
function connection()
{
    $servidor = 'localhost'; // El servidor que utilizaremos, en este caso será el localhost
    $usuario = 'root'; // El usuario que acabamos de crear en la base de datos
    $contrasenha = ''; // La contraseña del usuario que utilizaremos
    $DB = 'granbazarshopdb'; // El nombre de la base de datos

    $conn = new mysqli($servidor, $usuario, $contrasenha, $DB);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}


function closeConnection($conn)
{
    mysqli_close(mysql: $conn);
}

//CREATE
function crearUsuario($area, $tdoc, $doc, $nombre, $apellido, $email, $telefono, $username, $tuser, $password, $conn)
{
    $stmt = $conn->prepare("INSERT INTO usuario (Area, T_Documento,Doc_Usuario, Nombre, Apellido, Email, Telefono, Nombre_Usuario, Tipo_Usuario, Contraseña ) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssssss", $area, $tdoc, $doc, $nombre, $apellido, $email, $telefono, $username, $tuser, $password);
    $stmt->execute();
    return "<p>✅ Registro exitoso.</p>";
}

function crearCliente($tdoc, $doc, $nombre, $ciudad, $dir, $tel, $email, $password, $conn)
{
    $stmt = $conn->prepare("INSERT INTO cliente (T_Documento, Doc_Cliente, Nombre, Ciudad, Direccion, Telefono, Email, Contraseña) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $tdoc, $doc, $nombre, $ciudad, $dir, $tel, $email, $password);
    $stmt->execute();
    return "<p>✅ Registro exitoso.</p>";
}
//READ
function loginUsuario($email, $password, $conn)
{
    $stmt = $conn->prepare("SELECT Doc_Usuario, Nombre, Apellido, Contraseña FROM usuario WHERE Email= ? ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Salida de datos de cada fila

        while ($row = $result->fetch_assoc()) {

            if (password_verify($password, $row["Contraseña"])) {
                return [
                    "status" => "success",
                    "nombre" => $row["Nombre"],
                    "apellido" => $row["Apellido"],
                    "id" => $row["Doc_Usuario"]
                ];
            } else {
                return [
                    "status" => "fail",
                    "message" => "contraseña o usuario incorrectos",
                ];
            }
        }
    } else {
        return [
            "status" => "fail",
            "message" => "usuario No Registrado",
        ];
    }

}

function loginCliente($email, $password, $conn)
{
    $stmt = $conn->prepare("SELECT Doc_Cliente, Nombre, Contraseña FROM cliente WHERE Email= ? ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Salida de datos de cada fila

        while ($row = $result->fetch_assoc()) {

            if (password_verify($password, $row["Contraseña"])) {
                return [
                    "status" => "success",
                    "nombre" => $row["Nombre"],
                    "id" => $row["Doc_Cliente"]
                ];
            } else {
                return [
                    "status" => "fail",
                    "message" => "contraseña o usuario incorrectos",
                ];
            }
        }
    } else {
        return [
            "status" => "fail",
            "message" => "usuario No Registrado",
        ];
    }

}

//UPDATE
function clientUpdate($idnumber, $userInfo, $conn)
{
    //type idnumber as an integer
    $id = $idnumber;

    // Secure SQL Query using Prepared Statements
    $sql = "UPDATE cliente SET T_Documento=?, Doc_Cliente=?, Nombre=?, Email=?, Telefono=?, Contraseña=?, Direccion=?, Ciudad=?  WHERE Doc_Cliente=?";

    // Prepare statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters (s = string, i = integer)
        $stmt->bind_param("sssssssss", $userInfo["T_Documento"], $userInfo["Doc_Cliente"], $userInfo["Nombre"], $userInfo["Email"], $userInfo["Telefono"], $userInfo["Contraseña"], $userInfo["Direccion"], $userInfo["Ciudad"], $id);

        // Execute query
        if ($stmt->execute()) {
            echo ". Registro actualizado exitosamente";
        } else {
            echo "Error actualizando el registro: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

    // Close connection
    $conn->close();
}
function userUpdate($idnumber, $userInfo, $conn)
{
    //type idnumber as an integer
    $id = $idnumber;

    // Secure SQL Query using Prepared Statements
    $sql = "UPDATE usuario SET T_Documento=?, Doc_Usuario=?, Nombre=?, Apellido=?, Area=?, Email=?, Contraseña=?, Telefono=?, Nombre_Usuario=?, Tipo_Usuario=? WHERE Doc_Usuario=?";

    // Prepare statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters (s = string, i = integer)
        $stmt->bind_param("sssssssssss", $userInfo["tdoc"],$userInfo["doc"],$userInfo["nombre"],$userInfo["apellido"],$userInfo["area"],$userInfo["email"],$userInfo["password"], $userInfo["telefono"], $userInfo["username"], $userInfo["tuser"], $id);

        // Execute query
        if ($stmt->execute()) {
            echo ". Registro actualizado exitosamente";
        } else {
            echo "Error actualizando el registro: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

    // Close connection
    $conn->close();
}

?>