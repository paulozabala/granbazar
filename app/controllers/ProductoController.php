<?php

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';

session_start();
class ProductoController {
    private $modelo;
    private $categoriaModel;

    public function __construct() {
        $this->modelo = new Producto();
        $this->categoriaModel = new Categoria();
    }

    public function index() {
        $productos = $this->modelo->obtenerTodos();
        include __DIR__ . '/../../views/producto/lista.php';
    }

    public function crear() {
        // Aquí corregimos: usamos obtenerTodos(), no obtenerTodas()
        $categorias = $this->categoriaModel->obtenerTodos();
        include __DIR__ . '/../../views/producto/crear.php';
    }

    public function guardar() {
        $nombreArchivo = null;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
            $directorio = 'resources/assets/producto_img/';
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }

            $nombreArchivo = uniqid() . '_' . basename($_FILES['imagen']['name']);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio . $nombreArchivo);
        }

        $this->modelo->crear(
            $_POST['nombre'],
            $_POST['descripcion'],
            $_POST['precio'],
            $_POST['stock'],
            $_POST['categoria'],
            $nombreArchivo,
            $_SESSION['user-id'] // Asumiendo que tienes la sesión del usuario
        );

        header('Location: index.php?ruta=producto/index');
        exit;
    }

    public function editar() {
        if (!isset($_GET['id'])) {
            echo "Error: No se proporcionó el ID del producto.";
            return;
        }
        $producto = $this->modelo->obtenerPorId($_GET['id']);
        if (!$producto) {
            echo "Producto no encontrado";
            return;
        }
        // También corregimos aquí:
        $categorias = $this->categoriaModel->obtenerTodos();
        include __DIR__ . '/../../views/producto/editar.php';
    }

    public function actualizar() {
        $productoActual = $this->modelo->obtenerPorId($_POST['id']);
        $nombreArchivo = $productoActual['Url'];

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
            $directorio = 'resources/assets/producto_img/';
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }

            $nombreArchivo = uniqid() . '_' . basename($_FILES['imagen']['name']);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio . $nombreArchivo);
        }

        $this->modelo->actualizar(
            $_POST['id'],
            $_POST['nombre'],
            $_POST['descripcion'],
            $_POST['precio'],
            $_POST['stock'],
            $_POST['categoria'],
            $nombreArchivo,
            $_SESSION['user-id'] // Asumiendo que tienes la sesión del usuario
        );

        header('Location: index.php?ruta=producto/index');
        exit;
    }

    public function eliminar() {
        $this->modelo->eliminar($_GET['id']);
        header('Location: index.php?ruta=producto/index');
        exit;
    }
}
