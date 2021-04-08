<?php
require 'autoload.php';

use PodcastCrawler\PodcastCrawler;
use PodcastCrawler\Provider\Itunes;

$podcast_domain = ['youtube', 'apple', 'spotify'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $url_input = $_REQUEST['url'];

  $podcastLinks = [];

  if (strpos($url_input, $podcast_domain[0]) !== false) { //Get by script tag === youtube URL
    $podcastLinks[] = getYouTubeVideoDownloadLink($url_input);
  } else if (strpos($url_input, $podcast_domain[1]) !== false) { //Get by script tag === Apple URL
    $podcastLinks = getAppleDownloadUrls($url_input);
  } else if (strpos($url_input, $podcast_domain[2]) !== false) { //Get by script tag === Apple URL
    $podcastLinks = getSpotifyDownloadUrls($url_input);
    header('Content-Type: application/json');
    echo json_encode($podcastLinks);
    return;
  } else {
    $html = file_get_contents($url_input);

    //Instantiate the DOMDocument class.
    $htmlDom = new DOMDocument;

    // //Parse the HTML of the page using DOMDocument::loadHTML
    @$htmlDom->loadHTML($html);
    //Get by audio tag
    $audios = $htmlDom->getElementsByTagName('audio');
    foreach ($audios as $audio) {
      $podcastLinks[] = $audio->nodeValue;
    }
  }


  $podcastLinks = array_values(array_unique($podcastLinks));
  header('Content-Type: application/json');
  echo json_encode($podcastLinks);
}

// Spotify
function getSpotifyDownloadUrls($url_input)
{
  $podcastLinks = [];
  $code_input = $_REQUEST['code'];
  if (!$code_input) {
    return $podcastLinks;
  }

  $types = ['show'];

  $path = parse_url($url_input)['path'];
  $id = explode("/", $path);
  $type = $id[1];
  $id = end($id);

  $session = new SpotifyWebAPI\Session(
    $_ENV['SPOTIFY_CLIENT_ID'],
    $_ENV['SPOTIFY_CLIENT_SECRET'],
    $_ENV['SPOTIFY_CALL_BACK_URL'],
  );


  // Request a access token using the code from Spotify
  $session->requestAccessToken($code_input);

  $accessToken = $session->getAccessToken();

  $api = new SpotifyWebAPI\SpotifyWebAPI();
  $api->setAccessToken($accessToken);

  switch ($type) {
    case $types[0]:
      $datas = $api->getShow($id);
      if(isset($datas) && isset($datas->episodes) && isset($datas->episodes->items) && count($datas->episodes->items) > 0){
        foreach ($datas->episodes->items as $value) {
          $podcastLinks[] = $value->audio_preview_url;
        }
      }
      break;
    
    default:
      break;
  }

  return $podcastLinks;
}

// Apple
function getAppleDownloadUrls($url_input)
{
  $podcastLinks = [];
  $path = parse_url($url_input)['path'];
  $id = explode("/", $path);
  $id = end($id);
  $PodcastCrawler = new PodcastCrawler(new Itunes);
  $get_by_id = $PodcastCrawler->limit(1)->get($id);
  $find_podcasts = $PodcastCrawler->find($get_by_id['podcasts'][0]['rss']);
  if (isset($find_podcasts) && $find_podcasts['episodes_total'] > 0) {
    foreach ($find_podcasts['episodes'] as $value) {
      $podcastLinks[] = $value['mp3'];
    }
  }
  return $podcastLinks;
}

// Youtube 
function extractYoutubeVideoId($video_url)
{
  //parse the url 
  $parsed_url = parse_url($video_url);
  if (isset($parsed_url["query"])) {
    $query_string = $parsed_url["query"];
    //parse the string separated by '&' to array 
    parse_str($query_string, $query_arr);
    if (isset($query_arr["v"])) {
      return $query_arr["v"];
    }
  }
}

function getYouTubeVideoDownloadLink($url)
{
  parse_str(file_get_contents("http://youtube.com/get_video_info?video_id=" . extractYoutubeVideoId($url)), $data); //decode the data

  $videoData = json_decode($data['player_response'], true);
  $streamingData = $videoData['streamingData'];
  $streamingDataFormats = $streamingData['formats'];

  return $streamingDataFormats[1]['url'];
}
