<?php
require 'vendor/autoload.php';
require 'index.php';

use Google\Service\Gmail;
use Google\Service\Gmail\Message;

$client = getClient();
$service = new Gmail($client);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];

    $userId = 'me';
    $subject = 'Verificación de Correo Electrónico';
    $messageText = "Hola $nombre $apellido, estamos verificando tu correo electrónico.";

    $rawMessageString = "From: Your Name <youremail@gmail.com>\r\n";
    $rawMessageString .= "To: $email\r\n";
    $rawMessageString .= "Subject: $subject\r\n\r\n";
    $rawMessageString .= $messageText;

    $rawMessage = strtr(base64_encode($rawMessageString), array('+' => '-', '/' => '_'));

    $message = new Message();
    $message->setRaw($rawMessage);

    try {
        $service->users_messages->send($userId, $message);
        echo "El correo ha sido enviado a $email. Por favor verifica tu bandeja de entrada.";
    } catch (Exception $e) {
        echo 'Hubo un error al enviar el correo: ' . $e->getMessage();
    }
}
?>
