<?php
require_once '../config/db.php';
require_once 'search.php';

$conn = connection();
session_start();

$categoria_id_url = null;
if (isset($_GET['categoria_id']) && ctype_digit((string)$_GET['categoria_id']) && $_GET['categoria_id'] > 0) {
    $categoria_id_url = intval($_GET['categoria_id']);
}

$termino_busqueda_url = null;
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $termino_busqueda_url = trim($_GET['search']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_submit_button'])) {
    $termino_busqueda_post = isset($_POST['search']) ? trim($_POST['search']) : null;
    $categoria_id_form_post = null;
    if (isset($_POST['categoria_id_hidden']) && ctype_digit((string)$_POST['categoria_id_hidden']) && $_POST['categoria_id_hidden'] > 0) {
        $categoria_id_form_post = intval($_POST['categoria_id_hidden']);
    }

    $redirect_params = [];
    if (!empty($termino_busqueda_post)) {
        $redirect_params['search'] = $termino_busqueda_post;
    }
    
    if ($categoria_id_form_post !== null) {
        $redirect_params['categoria_id'] = $categoria_id_form_post;
    } elseif ($categoria_id_url !== null) { 
        $redirect_params['categoria_id'] = $categoria_id_url;
    }

    if (!empty($redirect_params)) {
        header('Location: index.php?' . http_build_query($redirect_params));
        exit;
    } else {
        header('Location: index.php');
        exit;
    }
}

$categoria_actual_para_query = $categoria_id_url;
$termino_actual_para_query = $termino_busqueda_url;

$result = searchProducts($conn, $categoria_actual_para_query, $termino_actual_para_query);
?>
<!DOCTYPE html5>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Gran Bazar</title>
    <link rel="stylesheet" href="../resources/css/storeStyles.css">
    <style>
        .category-button.active,
        .side-menu .menu-item a.active {
            background-color: #e64a19; 
            color: white !important; 
            font-weight: bold;
        }
         .side-menu .menu-item.active-menu-item > a {
            font-weight: bold;
            color: #FF5722 !important;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <div class="menu-icon" id="menu-toggle"></div>
            <div class="logo">El Gran <span>Bazar</span></div>
        </div>
        <div class="search-container">
            <div class="search-box">
                <form method="POST" action="index.php" style="display: flex; width: 100%;">
                <?php
                $valor_busqueda_input = isset($termino_actual_para_query) ? htmlspecialchars($termino_actual_para_query) : '';
                echo "<input type='text' name='search' class='search-input' placeholder='Buscar productos...' value='" . $valor_busqueda_input . "'>";
                if ($categoria_actual_para_query !== null) {
                    echo "<input type='hidden' name='categoria_id_hidden' value='" . htmlspecialchars($categoria_actual_para_query) . "'>";
                }
                ?>
                <button type='submit' name='search_submit_button' class='search-button'>🔍</button>
                </form>
            </div>
        </div>
        <div class="icons-container">
            <div class="icon-box" id="notifications-icon">
                <div class="icon">🔔</div>
                <div>Notificaciones</div>
            </div>
            <div class="icon-box" id="cart-icon">
                <div class="icon">🛒</div>
                <a style='color:white; text-decoration:none;' href='/granbazar/views/shoppingcart/index.php'>Carrito</a>
            </div>
            <div class="icon-box" id="register-icon">
                <div class="icon">✍</div>
                <?php
                if (empty($_SESSION["user-id"])){ echo "Perfil Desconocido"; }
                else{ echo "<a style='color:white; text-decoration:none;' href='/granbazar/dashboardClientes.php?id=".$_SESSION['user-id']."'>Editar Perfil</a>"; }
                ?>
            </div>
            <div class="icon-box" id="">
                <a class="icon" >👤</a>
                <div>
                    <?php
                    if (empty($_SESSION["user"])){ echo "<a style='text-align:center; color:#FF5722; font-weight:bold; font-size:14px; text-decoration:none; margin-left=4px; padding:4px;' href='../index.html'>Iniciar Sesión</a>"; }
                    else{ echo "<div><span>Hola, </span><span>".$_SESSION["user"]."</span></div>"; }
                    ?>
                </div>
            </div>
        </div>
    </header>

    <div class="categories">
        <a href="index.php?categoria_id=2"><button class="category-button <?php echo ($categoria_actual_para_query == 2 ? 'active' : ''); ?>">Lácteos</button></a>
        <a href="index.php?categoria_id=1"><button class="category-button <?php echo ($categoria_actual_para_query == 1 ? 'active' : ''); ?>">Frutas y verduras</button></a>
        <a href="index.php?categoria_id=7"><button class="category-button <?php echo ($categoria_actual_para_query == 7 ? 'active' : ''); ?>">Despensa</button></a>
        <a href="index.php?categoria_id=6"><button class="category-button <?php echo ($categoria_actual_para_query == 6 ? 'active' : ''); ?>">Dulcería</button></a>
        <a href="index.php"><button class="category-button <?php echo ($categoria_actual_para_query === null && $termino_actual_para_query === null ? 'active' : ''); ?>">Todas</button></a>
    </div>

    <div class="side-menu" id="side-menu">
        <div class="menu-header"></div>
        <div class="menu-title">Categorías</div>
        <div class="menu-item <?php echo ($categoria_actual_para_query == 1 ? 'active-menu-item' : ''); ?>"><a href="index.php?categoria_id=1" class="<?php echo ($categoria_actual_para_query == 1 ? 'active' : ''); ?>">Frutas y verduras</a><span>❯</span></div>
        <div class="menu-item <?php echo ($categoria_actual_para_query == 3 ? 'active-menu-item' : ''); ?>"><a href="index.php?categoria_id=3" class="<?php echo ($categoria_actual_para_query == 3 ? 'active' : ''); ?>">Cárnicos</a><span>❯</span></div>
        <div class="menu-item <?php echo ($categoria_actual_para_query == 5 ? 'active-menu-item' : ''); ?>"><a href="index.php?categoria_id=5" class="<?php echo ($categoria_actual_para_query == 5 ? 'active' : ''); ?>">Aseo</a><span>❯</span></div>
        <div class="menu-item <?php echo ($categoria_actual_para_query == 7 ? 'active-menu-item' : ''); ?>"><a href="index.php?categoria_id=7" class="<?php echo ($categoria_actual_para_query == 7 ? 'active' : ''); ?>">Granos</a><span>❯</span></div>
        <div class="menu-item <?php echo ($categoria_actual_para_query == 2 ? 'active-menu-item' : ''); ?>"><a href="index.php?categoria_id=2" class="<?php echo ($categoria_actual_para_query == 2 ? 'active' : ''); ?>">Lácteos y quesos</a><span>❯</span></div>
        <div class="menu-item <?php echo ($categoria_actual_para_query == 4 ? 'active-menu-item' : ''); ?>"><a href="index.php?categoria_id=4" class="<?php echo ($categoria_actual_para_query == 4 ? 'active' : ''); ?>">Panadería</a><span>❯</span></div>
        <div class="menu-item <?php echo ($categoria_actual_para_query === null && $termino_actual_para_query === null ? 'active-menu-item' : ''); ?>"><a href="index.php" class="<?php echo ($categoria_actual_para_query === null && $termino_actual_para_query === null ? 'active' : ''); ?>">Todas las categorías</a></div>
    </div>

    <div class="overlay" id="overlay"></div>

    <main class="main-content">
        <div class="products-grid">
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = intval($row['Id_Producto']);
                    $route = "../resources/assets/producto_img/" . rawurlencode($row['Url']);
                    $nombreJS = json_encode($row['Nombre']);
                    echo "<div class='product-card'>";
                    echo "<div class='product-image'><img src='".htmlspecialchars($route)."' alt='" . htmlspecialchars($row['Nombre']) . "' style='width:100%; height:100%; object-fit:contain;' /></div>";
                    echo "<div class='product-price'>" . number_format($row['Precio'], 0) . "</div>";
                    echo "<div class='product-name'>" . htmlspecialchars($row['Nombre']) . "</div>";
                    echo "<button class='add-button' ><span class='cart-icon'>🛒</span><a  style='font-size:16px; text-decoration:none; color:white;' onclick='addToCart($nombreJS, " . floatval($row['Precio']) . ", $id)'>Agregar</a></button>";
                    echo "</div>";
                }
            } else {
                echo "<p style='text-align:center; padding:20px;'>No se encontraron productos con los criterios seleccionados.</p>";
            }
            if ($conn) { $conn->close(); }
            ?>
        </div>
    </main>

    <div class="toast" id="toast">Producto agregado al carrito</div>
    <script src="../js/script.js"></script>
</body>
</html>