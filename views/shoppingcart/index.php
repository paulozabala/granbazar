<?php
require_once '../../config/db.php';
session_start();

$carrito = $_SESSION['carrito'] ?? [];

/* foreach ($carrito as $key => $value) {
    echo $key . " => " . $value . "<br>";
} */

if (empty($carrito)) {
    echo "El carrito está vacío.<br><a href='../index.php'>Volver</a>";
    exit;
}

foreach ($carrito as $key => $value) {
    if ($value == 0) {
        unset($carrito[$key]);
    }
}

$ids = implode(',', array_map('intval', array_values($carrito)));
/* echo "ids tiene: " . $ids; */
$conn = connection();
$result = $conn->query("SELECT * FROM producto WHERE Id_Producto IN ($ids)");
$total = 0;
$productList = [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShoppingCart</title>
    <link rel="icon" href="./resources/assets/tabIcon.svg" type="image/svg">
    <style>
        .title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            width: 100%;
        }

        .bg {
            background-color: rgb(212, 205, 205);
            height: 100vh;
            width: 100vw;
        }
    </style>
</head>

<body class="bg">
    <?php while ($row = $result->fetch_assoc()):

        $id = intval($row['Id_Producto']);
        if ($id) {
            $productList[] = [
                "id" => $id,
                "name" => $row['Nombre'],
                "price" => $row['Precio'],
                "quantity" => 1,
            ];
        }
        ?>
    <?php endwhile; ?>
    <div style="display: flex; flex-direction:row; justify-content: center; width: 100vw;">
        <div
            style="display: flex; flex-direction: row; justify-content:center; align-items:center; width: 85%; height: 95vh; border-radius:25px; background-color: white; ">
            <div
                style="display: flex; flex-direction: row; justify-content: start; align-items: center; width: 100%; height: 95vh;">
                <div
                    style="display: flex; flex-direction: column; align-items: center; gap: 0px; width:100%; height: 95vh;">
                    <div style="display: flex; flex-direction: row; justify-content: space-around; width: 100%;">
                        <a style="display: flex; justify-content: center; align-items: center; "
                            href="../index.php"><img style="max-width: 40px; height: 40px; border-radius: 50%; background-color: #E8E8E8;" src="../../resources/assets/arrow-left-orange.png" alt="" /></a>
                        <h2 style="color:#FF6600; text-align: center;">Tu Carro</h2>
                    </div>
                      
                    <div>
                          <?php
                         foreach ($productList as $product) {
                            echo "<div>";
                            echo "<div >" . $product['name'] . "\n";
                            echo "</div>";

                         }


                         ?>
                    </div>
                    <a href="resetCar.php"
                        onclick="return confirm('¿Estás seguro que deseas vaciar el carrito?')">Vaciar
                        carrito</a>
                </div>
            </div>
            <div style="display:flex; justify-content: center; width: 100%; height: 95vh; border-left: 2px solid grey !important; ">
                <div
                    style="display:flex; flex-direction: column; justify-content: start; align-items: start; height: 100px; gap:0px;">
                    <?php
                    $total = 0;


                    //count the number of times each product ID appears in the cart
                    $conteo = array_count_values($carrito);
                    /*foreach ($conteo as $productoId => $cantidad) {
                          echo "Producto ID $productoId se repite $cantidad veces<br>";
                      }*/

                    echo "<h2 style='color:#FF6600; text-align: center; width:100%'>Resumen de pedido</h2>";
                    foreach ($productList as $product) {
                        $cantidad = $conteo[$product['id']];
                        $subtotal = $product['price'] * $cantidad;

                        $unitPrice = $product['price'] / $product['quantity'];

                        $total += $subtotal;

                        echo "<div style='text-decoration: none !important;'>" . $product['name'] . "\n";
                        echo "$ " . number_format($unitPrice, 2) . "\n";
                        echo "x " . $cantidad . "\n";
                        echo "= " . number_format($subtotal, 2) . "<a href='addProduct.php?id={$product['id']}'> [+] </a><a href='deleteUnit.php?id={$product['id']}'> [-] </a>\n</div>";
                    }
                    echo "<span style='font-weight:bold;'>Total: " . number_format($total, 2) . "\n </span>";
                    echo "<div style='display:flex; justify-content:center; width:100%'><button type='button' style='background:linear-gradient(220deg, #FF6600 15%, rgb(255, 255, 255) 85%); border-radius:7px; min-height:40px; width:50%; border:none; '> PAGAR </button> </div>";
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>