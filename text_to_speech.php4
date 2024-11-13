<?php
require 'vendor/autoload.php';
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;

putenv('GOOGLE_APPLICATION_CREDENTIALS=/path/to/your/service-account-file.json');

session_start();
if (!isset($_SESSION['username'])) {
    die("Please log in to download the audio file.");
}

$data = json_decode(file_get_contents('php://input'), true);
$text = $data['text'];

$client = new TextToSpeechClient();
$input = new SynthesisInput();
$input->setText($text);

$voice = new VoiceSelectionParams();
$voice->setLanguageCode('en-US');
$voice->setSsmlGender(\Google\Cloud\TextToSpeech\V1\SsmlVoiceGender::NEUTRAL);

$audioConfig = new AudioConfig();
$audioConfig->setAudioEncoding(AudioEncoding::MP3);

$response = $client->synthesizeSpeech($input, $voice, $audioConfig);
$audioContent = $response->getAudioContent();

$file = 'audio/speech.mp3';
file_put_contents($file, $audioContent);

header('Content-Type: application/json');
echo json_encode(['audioFile' => $file]);

$client->close();
?>
