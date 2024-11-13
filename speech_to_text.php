<?php
require 'vendor/autoload.php';
use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\AudioEncoding;

putenv('GOOGLE_APPLICATION_CREDENTIALS=/path/to/your/service-account-file.json');

session_start();
if (!isset($_SESSION['username'])) {
    die("Please log in to download the audio file.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $audioFile = $_FILES['audio']['tmp_name'];
    $audioData = file_get_contents($audioFile);

    $client = new SpeechClient();

    $config = new RecognitionConfig();
    $config->setEncoding(AudioEncoding::LINEAR16);
    $config->setSampleRateHertz(16000);
    $config->setLanguageCode('en-US');

    $audio = new RecognitionAudio();
    $audio->setContent($audioData);

    $response = $client->recognize($config, $audio);
    $transcription = '';
    foreach ($response->getResults() as $result) {
        $transcription .= $result->getAlternatives()[0]->getTranscript();
    }

    header('Content-Type: application/json');
    echo json_encode(['text' => $transcription]);

    $client->close();
}
?>
