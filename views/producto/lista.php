<h1>Productos</h1>
<a href="index.php?ruta=producto/crear">Agregar Producto</a>

<table border="1">
    <tr>
        <th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio</th>
        <th>Stock</th><th>Categoría</th><th>Imagen</th><th>Acciones</th>
    </tr>
    <?php foreach ($productos as $p): ?>
    <tr style="text-align: center;">
        <td><?= $p['Id_Producto'] ?></td>
        <td><?= $p['Nombre'] ?></td>
        <td><?= $p['Descripcion'] ?></td>
        <td>$<?= $p['Precio'] ?></td>
        <td><?= $p['Cantidad_Stock'] ?></td>
        <td><?= $p['Id_Categoria'] ?></td>
        <td>
            <?php if (!empty($p['Url'])): ?>
                <img src="resources/assets/producto_img/<?= htmlspecialchars($p['Url']) ?>" width="80">
            <?php else: ?>
                Sin imagen
            <?php endif; ?>
        </td>
        <td>
            <a href="index.php?ruta=producto/editar&id=<?= $p['Id_Producto'] ?>">Editar</a>
            <a href="index.php?ruta=producto/eliminar&id=<?= $p['Id_Producto'] ?>" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
