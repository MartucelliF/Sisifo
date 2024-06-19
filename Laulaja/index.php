<?php
require 'vendor/autoload.php';

use Google\Client;
use Google\Service\Calendar;

function getClient()
{
    $client = new Client();
    $client->setApplicationName('Google Calendar API PHP Quickstart');
    $client->setScopes(Calendar::CALENDAR_READONLY);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    $redirect_uri = 'http://localhost/EJEMPLO/index.php'; // Asegúrate de que esto coincida con la URI registrada en Google Cloud Console
    $client->setRedirectUri($redirect_uri);

    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // Si el token ha expirado o no existe, se inicia el flujo de autorización
    if ($client->isAccessTokenExpired() || !$client->getAccessToken()) {
        if (isset($_GET['code'])) {
            $authCode = $_GET['code'];
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }

            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
            header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
            exit();
        } else {
            // No hay token válido y no se ha iniciado sesión, redirige a Google para la autorización
            $authUrl = $client->createAuthUrl();
            header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
            exit();
        }
    }

    return $client;
}

$client = getClient();
$service = new Calendar($client);

$calendarId = 'primary';
$optParams = array(
    'maxResults' => 10,
    'orderBy' => 'startTime',
    'singleEvents' => true,
    'timeMin' => date('c'),
);
$results = $service->events->listEvents($calendarId, $optParams);

if (count($results->getItems()) == 0) {
    print "No upcoming events found.\n";
} else {
    print "Upcoming events:\n";
    foreach ($results->getItems() as $event) {
        $start = $event->start->dateTime;
        if (empty($start)) {
            $start = $event->start->date;
        }
        printf("%s (%s)\n", $event->getSummary(), $start);
    }
}
