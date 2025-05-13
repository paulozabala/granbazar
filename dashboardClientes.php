<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script>

        document.addEventListener("DOMContentLoaded", function () {
            // Get the current URL
            const url = new URL(window.location.href);

            // Use URLSearchParams to extract the query parameters
            const params = new URLSearchParams(url.search);

            // Get the value of the "id" parameter
            const id = params.get('id');


            fetch(`/granbazar/app/routes/web.php?action=listUser&id=${id}`, { method: "POST" })
                .then(response => response.json())
                .then(data => {
                    let tableBody = document.getElementById("userTableBody");

                    if (data) {
                        data.forEach(user => {
                            let row = `<tr>
                            <td>${user.T_Documento}</td>
                            <td>${user.Doc_Cliente}</td>
                            <td>${user.Nombre}</td>
                            <td>${user.Email}</td>
                            <td>${user.Telefono}</td>
                            <td>${user.Ciudad}</td>
                            <td>${user.Direccion}</td>
                            <td><button class="actualize-btn" data-id="${user.Doc_Cliente}">Actualizar</button></td>
                            <td><button class="delete-btn" data-id="${user.Doc_Cliente}">Eliminar</button></td>
                        </tr>`;
                            tableBody.innerHTML += row;
                        });
                    }
                }).then(() => {
                    // Add event listeners after rows are added
                    document.getElementById("userTableBody").addEventListener("click", function (event) {
                        let target = event.target;

                        if (target.classList.contains("actualize-btn")) {
                            let userId = target.getAttribute("data-id");
                            console.log("Actualizar usuario con ID:", userId);
                            window.location.href = `/granbazar/updateClient.html?id=${userId}`;
                        }

                        if (target.classList.contains("delete-btn")) {
                            let userId = target.getAttribute("data-id");
                            console.log("Eliminar usuario con ID:", userId);
                            // Call a function to handle user deletion
                            deleteUser(userId);
                        }
                    });
                });

            // Function to delete user
            function deleteUser(userId) {
                if (confirm("¿Seguro que deseas eliminar este usuario?")) {
                    fetch(`/granbazar/app/routes/web.php?action=deleteUser&id=${userId}`, { method: "POST" })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status == 'success') {
                                alert("Usuario eliminado correctamente.");
                                window.location.replace('https://localhost/granbazar/dashboardClientes.php'); // Refresh table
                            } else {
                                alert("Error eliminando usuario.");
                            }
                        });
                }
            }
        });
    </script>
    <link rel="icon" href="./resources/assets/tabIcon.svg" type="image/svg">
</head>

<body>
    <?php
    session_start();

    if (empty($_SESSION["user"])) {
        echo "
                    <h5>Usuario no autorizado</h5></br>
                    <a href='./index.html'>Iniciar sesión</a>
                ";
    } else {

        echo "<h3>Bienvenido sr(@) " . $_SESSION["user-id"] . ". " . $_SESSION["user"] . "</h3>" .
            "<div style='display:flex; flex-direction:row; justify-content:around; width:1/3px; gap:20px;'>"
            . "" . "<a href='./logout.php'>Cerrar sesión</a> </div>";
    }
    ?>

    <h2>Información del Cliente</h2>
    <table border="1">
        <thead>
            <tr>
                <th>T_ID</th>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Ciudad</th>
                <th>Dirección</th>
                <th colspan="2">Acción</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            <!-- Data will be inserted here -->
        </tbody>
    </table>
</body>

</html>