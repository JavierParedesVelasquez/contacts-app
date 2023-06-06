<?php

// Incluye el archivo de configuración de la conexión a la base de datos
require "config/conexion.php";

// Variable para almacenar mensajes de error
$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"]) || empty($_POST["password"])) {
      $error = "Please fill all the fileds.";
    } else if (strpos($_POST["email"], "@") === false) {
        $error = "Email format is incorrect.";
    } else {
      $statement = $conn->prepare("SELECT * FROM user WHERE email_user = :email LIMIT 1");
      $statement->bindParam(":email", $_POST["email"]);
      $statement->execute();

      if ($statement->rowCount() == 0) {
        $error = "Invalid credentials.";
      } else {
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if (password_hash($_POST["password"], PASSWORD_DEFAULT) === $user["pass_user"]) {
            $error = "Invalid credentials.";
        } else {
          session_start();

     

          $_SESSION["user"] = $user;

          header("Location: home.php");
        }
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
                <div class="card-header">Login</div>
                <div class="card-body">
                    <?php if ($error != null) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif ?>
                    <form method="POST" action="login.php">
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