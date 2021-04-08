<?php 
require 'autoload.php';

$session = new SpotifyWebAPI\Session(
  $_ENV['SPOTIFY_CLIENT_ID'],
  $_ENV['SPOTIFY_CLIENT_SECRET'],
  $_ENV['SPOTIFY_CALL_BACK_URL'],
);

$state = $session->generateState();
$options = [
    'scope' => [
        'playlist-read-private',
        'user-read-private',
    ],
    'state' => $state,
];

header('Location: ' . $session->getAuthorizeUrl($options));
die();