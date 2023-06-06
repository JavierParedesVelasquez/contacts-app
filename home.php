<?php


require "config/conexion.php";

session_start();

if (!isset($_SESSION["user"])){
    header("Location: login.php");
    return;
}

$contacts = $conn->query("SELECT * FROM contacts WHERE user_id = {$_SESSION['user']['id_user']}");


?>

<?php require "partials/header.php" ?>
<!-- Main -->
<div class="container pt-4 p-3">
    <div class="row">

        <?php if ($contacts->rowCount() == 0) : ?>
            <div class="col-md-4 mx-auto">
                <div class="card card-body text-center">
                    <p>No contacts saved yet</p>
                    <a href="./add.php">Add One!</a>
                </div>
            </div>
        <?php endif ?>

        <!-- foreach es una estructura de control utilizada para iterar sobre los elementos de array u objetos, permite recorrer cada elemento de una colección y ejecutar un bloque de código para cada elemento. -->
        <!-- $element es una variable que tomará el valor de cada elemento del array en cada iteración. Dentro del bloque de código, puedes realizar las operaciones necesarias utilizando el valor de $element. -->
        <?php foreach ($contacts as $elements) : ?>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="card-title text-capitalize"><?= $elements["name_contacts"] ?></h3>
                        <p class="m-2"><?= $elements["phone_contacts"] ?></p>
                        <a href="edit.php?id=<?= $elements["id_contacts"] ?>" class="btn btn-secondary mb-2">Edit
                            Contact</a>
                        <a href="delete.php?id=<?= $elements["id_contacts"] ?>" class="btn btn-danger mb-2">Delete
                            Contact</a>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
<?php require "partials/footer.php" ?>
