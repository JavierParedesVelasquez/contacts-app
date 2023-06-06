<?php

require "config/conexion.php";
session_start();

if (!isset($_SESSION["user"])){
    header("Location: login.php");
    return;
}

$id = $_GET["id"]; // Obtiene el valor del parámetro "id" de la URL

$statement =  $conn->prepare("SELECT * FROM contacts WHERE id_contacts = :id LIMIT 1");
// Prepara una consulta SQL para seleccionar el registro de la tabla "contacts" donde el id_contacts coincida con el valor del parámetro ":id"

$statement->execute([':id' => $id]);
// Ejecuta la consulta SQL y asigna el valor de $id al marcador de posición ":id" en la consulta

if ($statement->rowCount() == 0) {
    // Si no se encuentra ningún registro, devuelve un código de respuesta HTTP 404 (no encontrado)
    http_response_code(404);
    echo ("HTTP 404 NOT FOUND");
    return;
}

$contacts = $statement->fetch(PDO::FETCH_ASSOC);


if($contacts["user_id"] !==$_SESSION["user"]["id_user"]){
    http_response_code(403);
    echo("HTTP 403 UNAUTHORIZED");
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

        $statement = $conn->prepare("UPDATE contacts SET name_contacts = :name, phone_contacts = :phone WHERE id_contacts = :id");
        $statement->execute([
            ":id" => $id,
            ":name" => $_POST["name"],
            ":phone" => $_POST["phone"],
        ]);


        // Mensaje Flash
        $_SESSION["flash"] = ["message"=>"Contact {$_POST['name_contacts']} updated.", "type" => "warning"];


        // Redirigir al usuario a la página "home.php" después de insertar los datos en la base de datos.
        header("Location: home.php");

        return;
    }
}

?>

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
                    <form method="POST" action="edit.php?id=<?= $contacts["id_contacts"] ?>">

                        <div class="mb-3 row">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>
                            <div class="col-md-6">
                                <input value=<?= $contacts["name_contacts"] ?> id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">Phone
                                Number</label>

                            <div class="col-md-6">
                                <input value=<?= $contacts["phone_contacts"] ?> id="phone" type="tel" class="form-control" name="phone" autocomplete="phone" autofocus>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Edit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require "partials/footer.php" ?>
