<h1>Editar Producto</h1>

<form method="post" action="index.php?ruta=producto/actualizar" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $producto['Id_Producto'] ?>">

    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="<?= htmlspecialchars($producto['Nombre']) ?>" required><br><br>

    <label>Descripción:</label><br>
    <input type="text" name="descripcion" value="<?= htmlspecialchars($producto['Descripcion']) ?>" required><br><br>

    <label>Precio:</label><br>
    <input type="text" name="precio" value="<?= $producto['Precio'] ?>" required><br><br>

    <label>Stock:</label><br>
    <input type="number" name="stock" value="<?= $producto['Cantidad_Stock'] ?>" required><br><br>

    <label>Categoría:</label><br>
    <select name="categoria" required>
        <option value="">Seleccione una categoría</option>
        <?php foreach ($categorias as $cat): ?>
            <option value="<?= $cat['Id_Categoria'] ?>" <?= $cat['Id_Categoria'] == $producto['Id_Categoria'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['Nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Imagen actual:</label><br>
    <?php if (!empty($producto['Url'])): ?>
        <img src="resources/assets/producto_img/<?= htmlspecialchars($producto['Url']) ?>" width="100"><br>
    <?php endif; ?>

    <label>Cambiar imagen:</label><br>
    <input type="file" name="imagen" accept="image/*"><br><br>

    <button type="submit">Actualizar</button>
</form>
