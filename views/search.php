<?php
// search.php

/**
 * Obtiene productos de la base de datos, aplicando filtros opcionales.
 *
 * @param mysqli $conn La conexión a la base de datos.
 * @param int|null $categoria_id ID de la categoría para filtrar (opcional).
 * @param string|null $termino_busqueda Término para buscar en Nombre y Descripción (opcional).
 * @return mysqli_result|false El resultado de la consulta o false en error.
 */
function searchProducts($conn, $categoria_id = null, $termino_busqueda = null) {
    // Verificar que la conexión sea válida
    if (!$conn || !($conn instanceof mysqli) || $conn->connect_error) {
        error_log("Error de conexión en searchProducts: Conexión no válida o no es un objeto mysqli.");
        if ($conn && $conn->connect_error) {
            error_log("Detalle del error de conexión: " . $conn->connect_error);
        }
        return false; // Retornar false si la conexión no es válida
    }

    // Base de la consulta SQL
    $sql = "SELECT Id_Producto, Nombre, Descripcion, Precio, Cantidad_Stock, Url, Id_Categoria, Doc_Usuario
            FROM producto";

    $params = []; // Para los parámetros de bind_param
    $types = "";  // Para los tipos de los parámetros (s: string, i: integer)
    $conditions = []; // Para las cláusulas WHERE

    // Añadir condición por categoría si se proporciona un ID válido
    if ($categoria_id !== null && filter_var($categoria_id, FILTER_VALIDATE_INT) && $categoria_id > 0) {
        $conditions[] = "Id_Categoria = ?";
        $params[] = $categoria_id;
        $types .= "i"; // 'i' para entero
    }

    // Añadir condición por término de búsqueda si se proporciona
    if ($termino_busqueda !== null && is_string($termino_busqueda) && !empty(trim($termino_busqueda))) {
        $sanitized_termino = trim($termino_busqueda); // Usar el término ya sanitizado en index.php
        $conditions[] = "(Nombre LIKE ? OR Descripcion LIKE ?)"; // Puedes añadir más campos si quieres buscar en ellos
        $searchTermWildcard = "%" . $sanitized_termino . "%";
        $params[] = $searchTermWildcard; // Para Nombre
        $params[] = $searchTermWildcard; // Para Descripcion
        $types .= "ss"; // 's' para string, dos veces
    }

    // Si hay condiciones, añadirlas a la consulta SQL
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    // Añadir un orden (opcional, pero bueno para consistencia)
    $sql .= " ORDER BY Nombre ASC";

    // --- DEBUGGING OUTPUT (Comenta o elimina esto en producción) ---
    /*
    echo "<!-- DEBUG SEARCH.PHP: ";
    echo "SQL: " . htmlspecialchars($sql) . " --- ";
    echo "Params: "; var_dump($params);
    echo "Types: "; var_dump($types);
    echo " -->";
    */

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Error preparando la consulta de productos: " . $conn->error . " --- SQL: " . $sql);
        return false;
    }

    // Si hay parámetros, enlazarlos a la sentencia preparada
    if (!empty($params)) {
        if (!$stmt->bind_param($types, ...$params)) { // Operador splat '...' para pasar el array
            error_log("Error en bind_param: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }

    // Ejecutar la consulta
    if (!$stmt->execute()) {
        error_log("Error ejecutando la consulta de productos: " . $stmt->error);
        $stmt->close();
        return false;
    }

    // Obtener el resultado
    $result = $stmt->get_result();

    // Es importante cerrar el statement después de obtener el resultado si ya no se va a usar
    // $stmt->close(); // De hecho, get_result() transfiere el resultado, por lo que $stmt puede cerrarse.

    return $result;
}

// La función searchProductbyName original ya no se usa activamente por index.php
// si la búsqueda principal se integra en searchProducts. Se puede mantener o eliminar.
/*
function searchProductbyName($conn, $name){
    // ... (código original de esta función) ...
}
*/
?>