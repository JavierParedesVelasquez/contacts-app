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
    echo("HTTP 404 NOT FOUND");
    return;
}
// Asi se saca el array del usuario este
$contacts = $statement->fetch(PDO::FETCH_ASSOC);

if($contacts["user_id"] !==$_SESSION["user"]["id_user"]){
    http_response_code(403);
    echo("HTTP 403 UNAUTHORIZED");
    return;
}

$conn->prepare("DELETE FROM contacts WHERE id_contacts = :id")->execute([':id' => $id]);
// Prepara y ejecuta una consulta SQL para eliminar el registro de la tabla "contacts" donde el id_contacts coincida con el valor del parámetro ":id"

// Mensaje Flashs
$_SESSION["flash"] = ["message"=>"Contact {$contacts['name_contacts']} deleted.", "type" => "danger"];


// Redirige al usuario a la página "home.php" después de eliminar el registro
header("Location: home.php");

?>
