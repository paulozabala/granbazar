<?php

// Si no existe parámetro ruta, mostrar index.html directamente
if (empty($_GET['ruta'])) {
    // Mostrar archivo index.html (suponiendo que está en la misma carpeta)
    readfile(__DIR__ . '/index.html');
    exit; // Terminar el script aquí para no cargar web.php
}

// Si hay ruta, limpiar y definir $_GET['ruta']
$ruta = $_GET['ruta'] ?? '';
$_GET['ruta'] = ltrim($ruta, '/'); // Quitar barra inicial si la hay

// Ahora cargar el enrutador
require_once __DIR__ . '/app/routes/webi.php';
