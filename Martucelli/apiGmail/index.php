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
    // Si hay un código de autorización, intercámbialo por un token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Obtener perfil del usuario
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $_SESSION['user'] = $google_account_info;

    // Redirigir a la página principal después de iniciar sesión
    header('Location: index.php'); // O redirige a otra página si es necesario
    exit();
}
if (!isset($_GET['recibedatos']) || $_GET['recibedatos'] == 1) {

    $nombre_usuario = isset($_GET['nombre_usuario']) ? $_GET['nombre_usuario'] : '';
    $correo_usuario = isset($_GET['correo_usuario']) ? $_GET['correo_usuario'] : '';

    if (isset($_SESSION['user'])) {
        // Si ya hay una sesión activa, muestra la información del usuario
        $user = $_SESSION['user'];
        echo '<h3>Bienvenido, ' . htmlspecialchars($user->name) . ' (' . htmlspecialchars($user->email) . ')</h3>';
        
        $apiGmailSESION = 1;

        $redirect_url = "../php/interfazUsuario.php?paso=0&iniciosesionConsulta=1&consultalistaTareas=0&nombre_usuario=" . urlencode($nombre_usuario) . "&correo_usuario=" . urlencode($correo_usuario). "&apiGmailSESION=" . urlencode($apiGmailSESION) . "#redirigirSesion";
        
        ?>
        <meta http-equiv="refresh" content="5;url=<?php echo $redirect_url; ?>" />   <script>
                console.log("[GMAIL] Nombre usuario: <?php echo htmlspecialchars($user->name); ?>");
                console.log("[GMAIL] Correo usuario: <?php echo htmlspecialchars($user->email); ?>");
            </script>
        <?php
    } else {
        // Redirige al usuario a la página de inicio de sesión de Google
        $login_url = $client->createAuthUrl();
        header('Location: ' . $login_url);
        exit();
    }
}

