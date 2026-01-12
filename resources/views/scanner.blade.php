<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scanner de Código de Barras - API</title>
  <style>
    body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
    video { width: 100%; max-width: 500px; border: 1px solid #ccc; }
    #result { margin-top: 20px; font-size: 1.2em; word-break: break-word; }
    button { margin: 10px; padding: 10px 20px; font-size: 16px; cursor: pointer; }
  </style>
</head>
<body>

<h2>📷 Scanner de Código de Barras via API</h2>

<video id="video" autoplay></video>
<div id="result">Nenhum código detectado ainda.</div>

<button id="stopButton">Parar Scanner</button>

<script>
const video = document.getElementById("video");
const resultDiv = document.getElementById("result");
const stopButton = document.getElementById("stopButton");

let stream;
let scanning = true;

// Aqui você vai usar a API da Dynamsoft
// Crie uma conta trial e pegue sua chave: https://www.dynamsoft.com/Products/barcode-recognition-cloud.aspx
const API_KEY = "SUA_API_KEY_AQUI";

// Abre a câmera traseira
async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
        video.srcObject = stream;
        scanLoop();
    } catch (err) {
        resultDiv.innerHTML = "❌ Erro ao acessar câmera: " + err;
    }
}

// Captura frames e envia para API
async function scanLoop() {
    if (!scanning) return;

    const canvas = document.createElement("canvas");
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext("2d");
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataUrl = canvas.toDataURL("image/jpeg");

    try {
        const res = await fetch("https://api.dynamsoft.com/barcode/recognition", {
            method: "POST",
            headers: {
                "apikey": API_KEY,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                image: dataUrl
            })
        });
        const data = await res.json();
        if (data && data.barcodes && data.barcodes.length > 0) {
            resultDiv.innerHTML = "🎉 Código lido: " + data.barcodes[0].text;
            scanning = false; // para após ler
            stopCamera();
            return;
        }
    } catch (err) {
        console.log("Erro API:", err);
    }

    // Próximo frame em 200ms
    setTimeout(scanLoop, 200);
}

// Para câmera
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
