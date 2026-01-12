<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Scanner Grátis Web</title>
<style>
body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
video { width: 100%; max-width: 500px; border: 1px solid #ccc; }
#result { margin-top: 20px; font-size: 1.2em; word-break: break-word; }
button { margin: 10px; padding: 10px 20px; font-size: 16px; cursor: pointer; }
</style>
</head>
<body>

<h2>📷 Scanner de Código de Barras Grátis</h2>
<video id="video" autoplay></video>
<div id="result">Nenhum código detectado ainda.</div>
<button id="stopButton">Parar Scanner</button>

<!-- ZXing JS -->
<script type="module">
import { BrowserMultiFormatReader } from "https://cdn.jsdelivr.net/npm/@zxing/library@0.19.1/esm/index.js";

const codeReader = new BrowserMultiFormatReader();
const video = document.getElementById("video");
const resultDiv = document.getElementById("result");
const stopButton = document.getElementById("stopButton");

let scanning = true;
let stream;

// Inicia câmera traseira
async function startScanner() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
        video.srcObject = stream;

        codeReader.decodeFromVideoDevice(null, video, (result, err) => {
            if (result) {
                resultDiv.innerHTML = "🎉 Código lido: " + result.getText();
                stopScanner();
            }
        });
    } catch (err) {
        resultDiv.innerHTML = "❌ Erro ao acessar câmera: " + err;
    }
}

// Para scanner
function stopScanner() {
    if (stream) stream.getTracks().forEach(track => track.stop());
    scanning = false;
    resultDiv.innerHTML += "<br>Scanner parado.";
}

stopButton.addEventListener("click", stopScanner);

startScanner();
</script>

</body>
</html>
