<?php

// Incluye el archivo de configuración de la conexión a la base de datos
require "config/conexion.php";

// Variable para almacenar mensajes de error
$error = null;

// Verifica si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica si alguno de los campos está vacío
    if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"])) {
        $error = 'Please fill all the fields.';
    } else if (strpos($_POST["email"], "@") === false) {
        // Verifica si el formato del correo electrónico es incorrecto
        $error = 'Email format is incorrect.';
    } else {
        // Prepara una consulta para verificar si el correo electrónico ya está registrado en la base de datos
        $statement = $conn->prepare("SELECT * FROM user WHERE email_user = :email");
        $statement->bindParam(":email", $_POST["email"]);
        $statement->execute();

        // Verifica si hay algún resultado en la consulta
        if ($statement->rowCount() > 0) {
            $error = 'This email is taken.';
        } else {
            // Prepara una consulta para insertar un nuevo usuario en la base de datos
            $insertStatement = $conn->prepare("INSERT INTO user (name_user, email_user, pass_user) VALUES (:name, :email, :password)");

            // Ejecuta la consulta con los valores proporcionados
            $insertStatement->execute([
                ":name" => $_POST["name"],
                ":email" => $_POST["email"],
                ":password" => password_hash($_POST["password"], PASSWORD_BCRYPT) // Hash del password
            ]);

            $statement = $conn->prepare("SELECT * FROM user WHERE email_user = :email LIMIT 1");
            $statement->bindParam(":email", $_POST["email"]);
            $statement->execute();

            $user = $statement->fetch(PDO::FETCH_ASSOC);

            session_start();
            $_SESSION["user"] = $user;

            // Redirecciona al usuario a la página "home.php"
            header("Location: home.php");
        }
    }
}

?>


<!doctype html>
<html lang="es">

<?php require "partials/header.php" ?>
<!-- Formulario -->
<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Register</div>
                <div class="card-body">
                    <?php if ($error != null) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif ?>
                    <form method="POST" action="register.php">
                        <div class="mb-3 row">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" autocomplete="email" autofocus>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" autocomplete="password" autofocus>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</main>

<?php require "partials/footer.php" ?>