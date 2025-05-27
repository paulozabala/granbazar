<?php
require_once '../config/db.php';
require_once 'search.php';

$conn = connection();
// 2. Fetch products
$result = searchProducts($conn);
session_start();
?>

<!DOCTYPE html5>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Gran Bazar</title>
    <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" href="../resources/css/storeStyles.css">
</head>

<body>
    <header class="header">
        <div class="logo-container">
            <div class="menu-icon" id="menu-toggle"></div>
            <div class="logo">El Gran <span>Bazar</span></div>
        </div>

        <div class="search-container">
            <div class="search-box">
                <?php
                echo "<input type='text' name='search' class='search-input' placeholder='Buscar productos...'>";
                echo "<button class='search-button' action='search'>&#128269;</button>";
                if (isset($_POST['search'])) {
                    $searchFlag = true;
                    $name = $_POST['search'];
                    $result = searchProductbyName($conn, $name);
                }
                ?>
            </div>
        </div>

        <div class="icons-container">
            <div class="icon-box" id="notifications-icon">
                <div class="icon">&#128276;</div>
                <div>Notificaciones</div>
            </div>
            <div class="icon-box" id="cart-icon">
                <div class="icon">&#128722;</div>
                <a style='color:white; text-decoration:none;' href='/granbazar/views/shoppingcart/index.php'>Carrito</a>
            </div>
            <div class="icon-box" id="register-icon">
                <div class="icon">&#9997;</div>
                <?php
                if (empty($_SESSION["user-id"])){
                    echo "Perfil Desconocido";
                }else{
                    echo "<a style='color:white; text-decoration:none;' href='/granbazar/dashboardClientes.php?id=".$_SESSION['user-id']."'>Editar Perfil</a>";
                }
                ?>
            </div>
            <div class="icon-box" id="">
                <a class="icon" >&#128100;</a>
                <div>
                    <?php
                        if (empty($_SESSION["user"])){
                            echo "<a style='text-align:center; color:#FF5722; font-weight:bold; font-size:14px; text-decoration:none; margin-left=4px; padding:4px;' href='../index.html'>Iniciar Sesión</a>"; 
                        }else{
                            echo "<div><span>Hola, </span><span>".$_SESSION["user"]."</span></div>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </header>

    <div class="categories">
        <button class="category-button">Ofertas</button>
        <button class="category-button">Lácteos</button>
        <button class="category-button">Frutas y verduras</button>
        <button class="category-button">Despensa</button>
        <button class="category-button">Refrigerados</button>
        <button class="category-button">Dulcería</button>
    </div>

    <div class="side-menu" id="side-menu">
        <div class="menu-header"></div>
        <div class="menu-title">Categorías</div>
        <div class="menu-item">
            Frutas y verduras
            <span>&#10095;</span>
        </div>
        <div class="menu-item">
            Cárnicos
            <span>&#10095;</span>
        </div>
        <div class="menu-item">
            Licores
            <span>&#10095;</span>
        </div>
        <div class="menu-item">
            Aseo
            <span>&#10095;</span>
        </div>
        <div class="menu-item">
            Granos
            <span>&#10095;</span>
        </div>
        <div class="menu-item">
            Lácteos y quesos
            <span>&#10095;</span>
        </div>
        <div class="menu-item">
            Panadería
            <span>&#10095;</span>
        </div>
        <div class="menu-item">
            Gaseosas
            <span>&#10095;</span>
        </div>
        <div class="menu-item">
            Cosméticos
            <span>&#10095;</span>
        </div>
        <div class="menu-item">
            Snack
            <span>&#10095;</span>
        </div>
        <div class="menu-item">
            Energizantes
            <span>&#10095;</span>
        </div>
        <div class="menu-item">
            Otras categorías
            <span>&#10095;</span>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <main class="main-content">
        <div class="products-grid">
            <?php
            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {
                    $id = intval($row['Id_Producto']);
                    $route = "../resources/assets/producto_img/".$row['Url'];
                    

                    $nombreJS = json_encode($row['Nombre']); // ✅ seguro para JS
                    echo "<div class='product-card'>";
                    echo "<div class='product-image'>";
                    echo "<img src='$route' alt='" . htmlspecialchars($row['Nombre']) . "' style='width:100%; height:100%; object-fit:contain;' />";
                    echo "</div>";
                    echo "<div class='product-price'>" . number_format($row['Precio'], 0) . "</div>";
                    echo "<div class='product-name'>" . htmlspecialchars($row['Nombre']) . "</div>";
                    echo "<button class='add-button' >";
                    echo "<span class='cart-icon'>&#128722;</span>";
                    echo "<a  style='font-size:16px; text-decoration:none; color:white;' onclick='addToCart($nombreJS, {$row['Precio']}, $id)'>Agregar</a>";
                    echo "</button>";
                    echo "</div>";
                }
            } else {
                echo "<p>No products found.</p>";
            }
            $conn->close();
            ?>
        </div>
    </main>

    <div class="toast" id="toast">Producto agregado al carrito</div>

    <!-- Enlace al archivo JavaScript -->
    <script src="../js/script.js"></script>
</body>

</html>