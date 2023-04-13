<?php include 'config/database.php'; ?>

<?php

session_start(); // start the session

// check if the session ID is already set
if (!isset($_SESSION['initiated'])) {

    // generate a new session ID
    session_regenerate_id(true); // true parameter deletes the old session ID

    // set the session as initiated
    $_SESSION['initiated'] = true;
}

$currentPage = htmlspecialchars($_SERVER['PHP_SELF']);


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <title>Projectpedia</title>
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar navbar-expand-sm navbar-light bg-light mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Projectpedia</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === "/php-learning/index.php" ? "text-decoration-underline" : "" ?>" href="index.php">Projects</a>
                    </li>
                    <?php
                    if (isset($_SESSION['userId'])) {
                        echo '<li class="nav-item"> <a class="nav-link ' . ($currentPage === "/Projectpedia/addProject.php" ? "text-decoration-underline" : "") . '" href="addProject.php">Add Project</a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="#" onclick="logout()">Log Out</a> </li>';
                    } else {
                        if ($currentPage == "/Projectpedia/login.php" || $currentPage == "/Projectpedia/register.php") {
                            echo '<li class="nav-item text-decoration-underline"><a class="nav-link"href="login.php">Login/Register</a></li>';
                        } else {
                            echo '<li class="nav-item"><a class="nav-link" href="login.php">Login/Register</a></li>';
                        }
                    } ?>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container d-flex flex-column align-items-center">