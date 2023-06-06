<?php

require "config/conexion.php";
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    return;
}
$error = null;

// Variables super global
/**
 * $_SERVER : esta variable tiene informacion sobre la peticion http 
 *  $_POST  : Basicamente contiene la informacion del POST
 */
//  este código verifica si se realizó una solicitud POST y, en caso afirmativo, imprime el contenido de los datos POST en el navegador y finaliza la ejecución del script. Esto puede ser útil para depurar y verificar los datos enviados a través de una solicitud POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Verifica si la solicitud HTTP realizada por el servidor es de tipo POST.
    if (empty($_POST["name"]) || empty($_POST["phone"])) { // Verifica si los campos "name" y "phone" están vacíos en el formulario enviado.
        $error = "Please fill all the fields"; // Asigna un mensaje de error si algún campo está vacío.
    } else if (strlen($_POST["phone"]) < 9) { // Verifica si la longitud del campo "phone" es menor a 9 caracteres.
        $error = "Phone number must be at least 9 characters"; // Asigna un mensaje de error si la longitud del número de teléfono es menor a 9.
    } else {
        $name = $_POST["name"]; // Obtiene el valor del campo "name" del formulario enviado.
        $phone = $_POST["phone"]; // Obtiene el valor del campo "phone" del formulario enviado.

        $statement = $conn->prepare("INSERT INTO contacts (user_id, name_contacts, phone_contacts) VALUES ({$_SESSION['user']['id_user']}, :name, :phone)"); // Prepara una consulta SQL para insertar los valores del formulario en la tabla "contacts".
        // VALIDAR ENTRADA DE LOS USUARIOS A LA BD
        // hemos puesto valores limpios, que no se pueden hacer inyecciones SQL, porque la funcion bindParam lo analiza, y le quita todas las cosas que le puedan hacer un ataque a la DB
        // Basicamente las inyecciones SQL desde el usuario ya no funcionan
        // Moraleja: nunca pongas en la base de datos lo que te manda un usuario, y tienes que validar siempre los datos
        $statement->bindParam(":name", $_POST["name"]);
        $statement->bindParam(":phone", $_POST["phone"]);
        $statement->execute(); // Ejecuta la consulta SQL para insertar los valores en la base de datos.



        // Mensaje Flash
        $_SESSION["flash"] = ["message"=>"Contact {$_POST['name_contacts']} added.", "type" => "primary"];




        // Redirigir al usuario a la página "home.php" después de insertar los datos en la base de datos.
        header("Location: home.php");

        return;//esto hace que funcione el flash message
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
                <div class="card-header">Add New Contact</div>
                <div class="card-body">
                    <?php if ($error != null) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif ?>
                    <form method="POST" action="add.php">
                        <div class="mb-3 row">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">Phone
                                Number</label>

                            <div class="col-md-6">
                                <input id="phone" type="tel" class="form-control" name="phone" autocomplete="phone" autofocus>
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