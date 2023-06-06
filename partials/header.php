<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Titulo Pagina -->
    <title>Contacts App</title>
    <!-- Bootstrap CDN - CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- Enlazando CSS  -->
    <link rel="stylesheet" href="static/css/index.css">
</head>

<body>
    <!-- Navbar -->
    <?php require "navbar.php" ?>


    <?php if (isset($_SESSION["flash"])) : ?>
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
            </symbol>
        </svg>

        <div class="container mt-4">
            <?php if (isset($_SESSION["flash"])) : ?>
                <?php $alertType = $_SESSION["flash"]["type"]; ?>
                <div class="alert alert-<?php echo $alertType; ?> d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                        <use xlink:href="#check-circle-fill" />
                    </svg>
                    <div class="ml-2">
                        <?= $_SESSION["flash"]["message"] ?>
                    </div>
                </div>
                <?php unset($_SESSION["flash"]) ?>
            <?php endif ?>
        </div>
        <?php unset($_SESSION["flash"]) ?>
    <?php endif ?>
    <main>
        <!-- Content Here -->