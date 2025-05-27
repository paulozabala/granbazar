<h1>Crear Producto</h1>

<form method="post" action="index.php?ruta=producto/guardar" enctype="multipart/form-data">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Descripción:</label><br>
    <input type="text" name="descripcion" required><br><br>

    <label>Precio:</label><br>
    <input type="text" name="precio" required><br><br>

    <label>Stock:</label><br>
    <input type="number" name="stock" required><br><br>

    <label>Categoría:</label><br>
    <select name="categoria" required>
        <option value="">Seleccione una categoría</option>
        <?php foreach ($categorias as $cat): ?>
            <option value="<?= $cat['Id_Categoria'] ?>"><?= htmlspecialchars($cat['Nombre']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Imagen:</label><br>
    <input type="file" name="imagen" accept="image/*"><br><br>

    <button type="submit">Guardar</button>
</form>
