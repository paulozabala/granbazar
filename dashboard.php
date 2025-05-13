<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios</title>
    <script>

        document.addEventListener("DOMContentLoaded", function () {
            fetch("/granbazar/app/routes/web.php?action=listUsers")
                .then(response => response.json())
                .then(data => {
                    let tableBody = document.getElementById("userTableBody");

                    data.forEach(user => {
                        let row = `<tr>
                <td>${user.T_Documento}</td>
                <td>${user.Doc_Usuario}</td>
                <td>${user.Nombre}</td>
                <td>${user.Apellido}</td>
                <td>${user.Area}</td>
                <td>${user.Email}</td>
                <td>${user.Telefono}</td>
                <td>${user.Nombre_Usuario}</td>
                <td>${user.Tipo_Usuario}</td>
                <td><button class="actualize-btn" data-id="${user.Doc_Usuario}">Actualizar</button></td>
                <td><button class="delete-btn" data-id="${user.Doc_Usuario}">Eliminar</button></td>
            </tr>`;
                        tableBody.innerHTML += row;
                    });
                })
                .then(() => {
                    // Add event listeners after rows are added
                    document.getElementById("userTableBody").addEventListener("click", function (event) {
                        let target = event.target;

                        if (target.classList.contains("actualize-btn")) {
                            let userId = target.getAttribute("data-id");
                            console.log("Actualizar usuario con ID:", userId);
                            window.location.href = `/granbazar/updateUser.html?id=${userId}`;
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
                    fetch(`/granbazar/app/routes/web.php?action=deleteWorker&id=${userId}`, { method: "POST" })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status == 'success') {
                                alert("Usuario eliminado correctamente.");
                                window.location.replace('https://localhost/granbazar/dashboard.php'); // Refresh table
                            } else {
                                alert("Error eliminando usuario.");
                            }
                        });
                }
            }

        });
    </script>
    <link rel="icon" href="./resources/assets/tabIcon.svg" type="image/svg">
    <style>
        th, td{
            text-align: center;
        }
    </style>
</head>

<body>
    <?php
    session_start();

    if (empty($_SESSION["user"])) {
        echo "
                    <h5>Usuario no autorizado</h5></br>
                    <a href='./index.html'>Iniciar sesión</a>
                ";
    }

    echo "<h3>Bienvenido sr(@) " . $_SESSION["user-id"] . ". " . $_SESSION["user"] . "</h3>" .
        "<div style='display:flex; flex-direction:row; justify-content:around; width:1/3px; gap:20px;'>
        <a href='./createUsers.html'>Crear Usuario</a>" . "" . "<a href='./logout.php'>Cerrar sesión</a> </div>";
    ?>

    <h2>Listado de Usuarios</h2>
    <table border="1">
        <thead>
            <tr>
                <th>T_Doc</th>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Area</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Nom_Usuario</th>
                <th>T_Usuario</th>
                <th colspan="2">Acción</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            <!-- Data will be inserted here -->
        </tbody>
    </table>
</body>

</html>