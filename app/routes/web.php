<?php
include_once "../controllers/UserController.php";

$controller = new UserController();


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_GET["action"]) && $_GET["action"] === "deleteUser") {
    if (isset($_GET["id"])) {
        $id = intval($_GET["id"]);
        $controller->deleteUser($id); // Call the function
    } else {
        echo "Error: No ID provided.";
    }
} else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_GET["action"]) && $_GET["action"] === "deleteWorker") {
    if (isset($_GET["id"])) {
        $id = intval($_GET["id"]);
        $controller->deleteWorker($id); // Call the function
    } else {
        echo "Error: No ID provided.";
    }
} else {
    if ($_GET["action"] === "listUsers") {
        $controller->listUsers();
    } else if ($_GET["action"] === "listUser") {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $controller->listUser($id); // Call the function
        } else {
            echo "Error: No ID provided.";
        }
    }
    //echo "Error: Invalid request method.";
}


?>