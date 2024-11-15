<?php
session_start();
if (!isset($_SESSION['username'])) {
    die("Please log in to download the audio file.");
}

$file = 'audio/speech.mp3';
if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
} else {
    die("File not found.");
}
?>
