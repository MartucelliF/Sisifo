<?php
require 'vendor/autoload.php';

use Google\Client;
use Google\Service\Gmail;

function getClient()
{
    $client = new Client();
    $client->setApplicationName('Gmail API PHP Quickstart');
    $client->setScopes(Gmail::GMAIL_SEND);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    $redirect_uri = 'http://localhost/apiGmail/index.php';
    $client->setRedirectUri($redirect_uri);

    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    } else {
        if (isset($_GET['code'])) {
            $authCode = $_GET['code'];
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            if (!isset($accessToken['error'])) {
                $client->setAccessToken($accessToken);
                if (!file_exists(dirname($tokenPath))) {
                    mkdir(dirname($tokenPath), 0700, true);
                }
                file_put_contents($tokenPath, json_encode($client->getAccessToken()));
            } else {
                throw new Exception('Error fetching access token: ' . json_encode($accessToken));
            }
        } else {
            $authUrl = $client->createAuthUrl();
            header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
            exit();
        }
    }

    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            $accessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            if (!isset($accessToken['error'])) {
                $client->setAccessToken($accessToken);
                file_put_contents($tokenPath, json_encode($client->getAccessToken()));
            } else {
                throw new Exception('Error refreshing access token: ' . json_encode($accessToken));
            }
        } else {
            $authUrl = $client->createAuthUrl();
            header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
            exit();
        }
    }

    // No imprime ningún mensaje aquí
    return $client;
}
?>
