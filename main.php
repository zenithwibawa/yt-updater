<?php 

if (!file_exists(__DIR__ . '/vendor/autoload.php')){
    throw new Exception(sprintf('Please run "composer require google/apiclient:~2.0" in "%s"', __DIR__));
}

require_once __DIR__.'/vendor/autoload.php';
require_once('Telegram.php');

$status = false;

try{
    $api = json_decode(file_get_contents('api.json'));

    $client = new Google_Client();
    $client->setApplicationName('API Youtube Updater');
    $client->setScopes([
        'https://www.googleapis.com/auth/youtube.force-ssl',
    ]);
    $client->setAuthConfig('client_secret.json');
    $client->setIncludeGrantedScopes(true);
    $client->setAccessType('offline');
    $client->setApprovalPrompt('force');
    $client->refreshToken($api->refresh_token);
    
    $service = new Google_Service_YouTube($client);
    
    $view_count = $service->videos->listVideos('statistics', ['id' => $api->video_key])->items[0]->statistics->viewCount;
    $snippet = $service->videos->listVideos('snippet', ['id' => $api->video_key])->items[0]->snippet;
    
    $video = new Google_Service_YouTube_Video();
    $video->setId($api->video_key);
    
    $videoSnippet = new Google_Service_YouTube_VideoSnippet();
    $title = 'This video has been watched '.number_format($view_count).' times';
    $videoSnippet->setTitle($title);
    $videoSnippet->setCategoryId($snippet->categoryId);
    $videoSnippet->setDefaultLanguage($snippet->defaultLanguage);
    $videoSnippet->setDescription($snippet->description);
    $videoSnippet->setTags($snippet->tags);
    
    $video->setSnippet($videoSnippet);
    
    $service->videos->update('snippet', $video);
    
    $output = $title;
    $status = true;
}
catch (Google_Service_Exception $e){
    $output = $e->getMessage();
}
catch (Google_Exception $e){
    $output = $e->getMessage();
}

$bot = json_decode(file_get_contents('bot.json'));

date_default_timezone_set('Asia/Makassar');
$telegram = new Telegram('https://api.telegram.org/bot'.$bot->token.'/sendMessage', $bot->group_chat_id);
$telegram->sendMessage(date('d M Y H:i:s').chr(10).'[Title Update : '.($status === true ? 'TRUE' : 'FALSE').']'.chr(10).chr(10).'<pre>'.$output.'</pre>');