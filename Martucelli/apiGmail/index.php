<?php
require_once 'vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setClientId('419389398290-33o40p57963o77v66ld4d274jb9o0061.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/Sisifo/apiGmail/index.php'); // Ajusta según tu configuración
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Obtener perfil del usuario
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $_SESSION['user'] = $google_account_info;

    // Redirigir al usuario a la página de inicio
    header('Location: index.php');
    exit();
}

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    echo '<h3>Bienvenido, ' . $user->name . ' (' . $user->email . ')</h3>';
    echo '<a href="logout.php">Cerrar sesión</a>';

    ?>
    <meta http-equiv="refresh" content="5;url=../php/interfazUsuario(SESSION).php" />
    <?php

} else {
    $login_url = $client->createAuthUrl();
    echo '<a href="' . htmlspecialchars($login_url) . '">Iniciar sesión con Google</a>';
}