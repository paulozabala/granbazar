<?php
include_once "../../config/db.php";

class User
{
    public static function getAllUsers()
    {
        $conn = connection();
        $query = "SELECT * FROM usuario";
        $result = $conn->query($query);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        $conn->close();
        return $users;
    }

    public static function getUserById($id)
    {
        $conn = connection();
        //$sql = "SELECT * FROM cliente WHERE Doc_Cliente = ? ";
        $stmt = $conn->prepare("SELECT * FROM cliente WHERE Doc_Cliente = ? ");
        $stmt->bind_param("s", $id);
        // Execute query
        if ($stmt->execute()) {

            $result = $stmt->get_result();
            $user = [];

            if ($result->num_rows > 0) {
                // Salida de datos de cada fila

                while ($row = $result->fetch_assoc()) {
                    $user[] = $row;
                }
                $conn->close();
                return $user;
            }
        } else {
            echo "Error buscando el registro: " . $stmt->error;
        }

        // Close statement
        $stmt->close();

    }

    public static function deleteUserById($idnumber)
    {

        $conn = connection();

        // Set id as integer
        $id = $idnumber;

        // Secure SQL Query using Prepared Statements
        $sql = "DELETE FROM cliente WHERE Doc_Cliente = ?";

        // Prepare statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameter (i = integer)
            $stmt->bind_param("s", $id);

            // Execute query
            if ($stmt->execute()) {
                $data = [
                    "status" => "success",
                    "message" => "Registro eliminado correctamente"
                ];
                
                // Configurar el encabezado para indicar contenido JSON
                header('Content-Type: application/json');
                
                // Enviar la respuesta JSON
                echo json_encode($data);
            } else {
                echo "Error eliminando el registro: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $conn->error;
        }

        // Close connection
        $conn->close();
    }

    public static function deleteWorkerById($idnumber)
    {

        $conn = connection();

        // Set id as integer
        $id = $idnumber;

        // Secure SQL Query using Prepared Statements
        $sql = "DELETE FROM usuario WHERE Doc_Usuario = ?";

        // Prepare statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameter (i = integer)
            $stmt->bind_param("s", $id);

            // Execute query
            if ($stmt->execute()) {
                $data = [
                    "status" => "success",
                    "message" => "Registro eliminado correctamente"
                ];
                
                // Configurar el encabezado para indicar contenido JSON
                header('Content-Type: application/json');
                
                // Enviar la respuesta JSON
                echo json_encode($data);
            } else {
                return [
                    "status" => "fail",
                    "message" => "Error eliminando el registro: " . $stmt->error
                ];
            }
        } else {
            echo "Error en la preparación de la consulta: " . $conn->error;
        }

        $stmt->close();
        // Close connection
        $conn->close();
    }
}
?>