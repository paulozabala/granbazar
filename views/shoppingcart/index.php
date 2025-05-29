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

//count the number of times each product ID appears in the cart
$conteo = array_count_values($carrito);
 /*foreach ($conteo as $productoId => $cantidad) {
                          echo "Producto ID $productoId se repite $cantidad veces<br>";
                      }*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShoppingCart</title>
    <link rel="icon" href="./resources/assets/tabIcon.svg" type="image/svg">
    <style>
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
            style="display: flex; flex-direction: row; justify-content:space-around; align-items:center; width: 86%; border-radius:25px; background-color: white; height: 95vh;">
            <div style="display: flex; flex-direction: column; align-items: center; width:100%; height: 95vh;">
                <div style="display: flex; flex-direction: row; justify-content: space-around; width: 100%;">
                    <a style="display: flex; justify-content: center; align-items: center; " href="../index.php"><img
                            style="max-width: 40px; height: 40px; border-radius: 50%; background-color: #E8E8E8;"
                            src="../../resources/assets/arrow-left-orange.png" alt="" /></a>
                    <h1 style="color:#FF6600; text-align: center; ">Tu Carro</h1>
                </div>

                <div style="flex flex-direction: row; justify-content:center;">
                    <div
                        style="flex flex-direction: column; justify-content: center; align-items: center; font-size:20px; width: 100%; overflow-y: auto;">
                        <?php
                        foreach ($result as $product) {
                            echo "<div style='display:flex; justify-content:start; align-items:center;'><img src='../../resources/assets/producto_img/{$product['Url']}' style='width:53px; height:53px; border-radius:50%; background-color:#FF6600; margin-right: 6px; '/>";
                            echo "<div style='display:flex; flex-direction:column; justify-content:center; '>{$product['Nombre']} <span style='font-size:14px;'>$ {$product['Precio']}</span></div> <div style='display:flex; flex-direction:row; align-items:center;  background-color:#efefef; border-radius:30px; width:90px; height: 30px; margin-left:20px; '><div style='display:flex; flex-direction:row; justify-content:space-around; items-align:center; width:100%'><a href='addProduct.php?id={$product['Id_Producto']}' style='width:15px; height:15px; color:#FF6600;text-align:center; text-decoration:none;'> + </a><span >{$conteo[$product['Id_Producto']]}</span><a href='deleteUnit.php?id={$product['Id_Producto']}'style='width:15px; height:15px; color:#FF6600; text-align:center; text-decoration:none;'> - </a></div></div></div>" . "<br>";
                        }

                        ?>
                    </div>
                </div>
                <a href="resetCar.php" style="font-size:20px;" onclick="return confirm('¿Estás seguro que deseas vaciar el carrito?')">Vaciar
                    carrito</a>
            </div>

            <div style="display:flex; justify-content: center; width:100%;  border-left: 2px solid grey !important;  height: 95vh;">
                <div
                    style="display:flex; flex-direction: column; justify-content: start; align-items: start; height: 100px; width: 100%;">
                    <?php
                    $total = 0;

                    echo "<h1 style='color:#FF6600; text-align: center; width:100%'>Resumen de pedido</h1>";
                    foreach ($productList as $product) {
                        $cantidad = $conteo[$product['id']];
                        $subtotal = $product['price'] * $cantidad;

                        $unitPrice = $product['price'] / $product['quantity'];

                        $total += $subtotal;

                        echo "<div style='display:flex; flex-direction:row; justify-content:space-around; font-size:20px; text-decoration: none !important; width:100%; margin-bottom:30px;'>" . "<div style='display:flex; flex-direction:row; justify-content:start;'>". $product['name'] . "\n";
                       /*  echo "$ " . number_format($unitPrice, 2) . "\n"; */
                        echo "x " . $cantidad . " </div>";
                        echo "<div >".number_format($subtotal, 2)."</div>" . "\n</div>";
                    }
                    echo "<span style='text-align:center; font-size:20px; font-weight:bold; width:100%; margin-top:100px;'>Total: " . number_format($total, 2) . "\n </span>";
                    echo "<div style='display:flex; justify-content:center; width:100%; margin-top:30px;'><button type='button' style='background:linear-gradient(220deg, #FF6600 15%, rgb(255, 255, 255) 85%); border-radius:7px; min-height:40px; width:50%; border:none; font-size:20px; font-weight:bold;'> Pagar </button> </div>";
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>