<?php
session_start();

$id = $_GET['id'];

if (isset($_SESSION['carrito'])) {
    // Buscar el índice donde está el valor
    
    $index = array_search($id, $_SESSION['carrito']);
    
    if ($index !== false) {
        unset($_SESSION['carrito'][$index]); // Elimina el elemento
        $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexa
    }
}

header("Location: index.php");
