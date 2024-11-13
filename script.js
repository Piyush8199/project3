document.addEventListener("DOMContentLoaded", function () {
    // Check if user is logged in
    fetch('check_login.php')
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                document.getElementById('loginForm').style.display = 'none';
                document.getElementById('registerForm').style.display = 'none';
                document.getElementById('logoutForm').style.display = 'block';
                document.getElementById('downloadLink').style.display = 'block';
            } else {
                document.getElementById('loginForm').style.display = 'block';
                document.getElementById('registerForm').style.display = 'block';
                document.getElementById('logoutForm').style.display = 'none';
                document.getElementById('downloadLink').style.display = 'none';
            }
        });

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('register') === 'success') {
        alert('Registration successful. Please log in.');
    }

    const synth = window.speechSynthesis;
    const inputText = document.getElementById('inputText');
    const audioOutput = document.getElementById('audioOutput');
    const downloadLink = document.getElementById('downloadLink');

    document.getElementById('convertTextToSpeech').addEventListener('click', function () {
        const text = inputText.value;
        const utterance = new SpeechSynthesisUtterance(text);

        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const destination = audioContext.createMediaStreamDestination();
        const source = audioContext.createMediaStreamSource(destination.stream);
        source.connect(audioContext.destination);

        const mediaRecorder = new MediaRecorder(destination.stream);
        let chunks = [];

        mediaRecorder.ondataavailable = function(event) {
            chunks.push(event.data);
        };

        mediaRecorder.onstop = function() {
            const blob = new Blob(chunks, { type: 'audio/wav' });
            const url = URL.createObjectURL(blob);
            audioOutput.src = url;
            downloadLink.href = url;
            downloadLink.download = 'speech.wav';
            downloadLink.style.display = 'block';  // Show the download link
        };

        utterance.onstart = function() {
            mediaRecorder.start();
        };

        utterance.onend = function() {
            mediaRecorder.stop();
        };

        synth.speak(utterance);
    });
});
