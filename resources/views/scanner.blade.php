<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Scanner Rápido Web (WASM)</title>
<style>
  body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
  video { width: 100%; max-width: 500px; border: 1px solid #ccc; }
  #result { margin-top: 20px; font-size: 1.2em; word-break: break-word; }
  button { margin: 10px; padding: 10px 20px; font-size: 16px; cursor: pointer; }
</style>
</head>
<body>

<h2>📷 Scanner de Código de Barras – WebAssembly</h2>
<video id="video" autoplay></video>
<div id="result">Nenhum código detectado ainda.</div>
<button id="stopButton">Parar Scanner</button>

<!-- SDK Dynamsoft Barcode Reader WebAssembly -->
<script src="https://cdn.jsdelivr.net/npm/dynamsoft-javascript-barcode@10.3.4/dist/dbr.js"></script>

<script>
const video = document.getElementById("video");
const resultDiv = document.getElementById("result");
const stopButton = document.getElementById("stopButton");

let stream;
let scanning = true;

async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
        video.srcObject = stream;
        initScanner();
    } catch (err) {
        resultDiv.innerHTML = "❌ Erro ao acessar câmera: " + err;
    }
}

async function initScanner() {
    // Cria instância do leitor
    const reader = await Dynamsoft.BarcodeReader.createInstance();

    // Loop de leitura contínua
    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");

    async function scanLoop() {
        if (!scanning) return;

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        try {
            const results = await reader.decode(canvas);
            if (results && results.length > 0) {
                resultDiv.innerHTML = "🎉 Código lido: " + results[0].barcodeText;
                scanning = false;
                stopCamera();
                return;
            }
        } catch (err) {
            // nenhum código detectado no frame, ignora
        }

        requestAnimationFrame(scanLoop);
    }

    scanLoop();
}

function stopCamera() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
    resultDiv.innerHTML += "<br>Scanner parado.";
    scanning = false;
}

stopButton.addEventListener("click", stopCamera);

startCamera();
</script>

</body>
</html>
