<?php
// Incluir el controlador de usuarios
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/ProductoController.php';
require_once __DIR__ . '/../controllers/CategoriaController.php';



// Resto del código...


// Crear una instancia del controlador de usuarios
$userController = new UserController();

// Manejar las acciones relacionadas con los usuarios
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_GET["action"])) {
        if ($_GET["action"] === "deleteUser") {
            if (isset($_GET["id"])) {
                $id = intval($_GET["id"]);
                $userController->deleteUser($id); // Llamar a la función para eliminar usuario
            } else {
                echo "Error: No se proporcionó el ID del usuario.";
            }
        } elseif ($_GET["action"] === "deleteWorker") {
            if (isset($_GET["id"])) {
                $id = intval($_GET["id"]);
                $userController->deleteWorker($id); // Llamar a la función para eliminar trabajador
            } else {
                echo "Error: No se proporcionó el ID del trabajador.";
            }
        }
    }
} else {
    if (isset($_GET["action"])) { // Asegúrate de que $_GET['action'] esté definido
        if ($_GET["action"] === "listUsers") {
            $userController->listUsers();
        } else if ($_GET["action"] === "listUser") {
            if (isset($_GET["id"])) {
                $id = $_GET["id"];
                $userController->listUser($id); // Llamar a la función para obtener un usuario
            } else {
                echo "Error: No se proporcionó el ID del usuario.";
            }
        }
    }
    //  else {
    //     echo "Error: Método de solicitud no válido."; // Este mensaje podría ser demasiado general
    // }
}


//Ruteo principal
$ruta = $_GET['ruta'] ?? 'producto/index';
list($recurso, $accion) = explode('/', $ruta) + [null, null];
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($recurso) {
    case 'producto':
        $controller = new ProductoController();
        switch ($accion) {
            case 'index':
            case null:
                $controller->index();
                break;
            case 'crear':
                $controller->crear();
                break;
            case 'guardar':
                if ($metodo === 'POST') {
                    $controller->guardar();
                } else {
                    echo "Método no permitido";
                }
                break;
            case 'editar':
                $controller->editar();
                break;
            case 'actualizar':
                if ($metodo === 'POST') {
                    $controller->actualizar();
                } else {
                    echo "Método no permitido";
                }
                break;
            case 'eliminar':
                $controller->eliminar();
                break;
            default:
                echo "Ruta de producto no válida";
                break;
        }
        break;

        case 'categoria': 
            $controller = new CategoriaController();
            switch ($accion) {
                case 'index':
                case null:
                    $controller->index();
                    break;
                case 'crear':
                    $controller->crear();
                    break;
                case 'editar':
                    if (isset($_GET['id'])) {
                        $controller->editar($_GET['id']);
                    } else {
                        echo "Falta el ID para editar";
                    }
                    break;
                case 'eliminar':
                    if (isset($_GET['id'])) {
                        $controller->eliminar($_GET['id']);
                    } else {
                        echo "Falta el ID para eliminar";
                    }
                    break;
                default:
                    echo "Ruta de categoría no válida";
                    break;
            }
            break;
    
        default:
            echo "Recurso no reconocido";
            break;
    }