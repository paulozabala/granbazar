<?php
require_once '../../config/db.php';
session_start();

$id = $_GET['id'];

if ($id != 0) {
   $_SESSION['carrito'][] = $id;
header("Location: index.php");
}
