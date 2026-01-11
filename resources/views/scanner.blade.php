<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Scanner Avançado QR & Código de Barras</title>
<style>
  body { font-family: Arial, sans-serif; text-align: center; padding: 10px; background: #f4f4f4; }
  #reader { width: 100%; max-width: 600px; margin: auto; border: 2px solid #333; border-radius: 10px; overflow: hidden; }
  #result { margin-top: 15px; font-size: 1.2em; word-break: break-word; }
  button { margin: 5px; padding: 10px 20px; font-size: 16px; cursor: pointer; }
</style>
</head>
<body>

<h2>📷 Scanner Avançado QR & Código de Barras</h2>

<div id="reader"></div>
<div id="result">Nenhum código detectado ainda.</div>

<button id="switchCameraBtn">🔄 Trocar Câmera</button>
<button id="toggleFlashBtn">💡 Lanterna</button>
<button id="stopButton">⏹️ Parar Scanner</button>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
const resultDiv = document.getElementById("result");
const switchCameraBtn = document.getElementById("switchCameraBtn");
const toggleFlashBtn = document.getElementById("toggleFlashBtn");
const stopButton = document.getElementById("stopButton");

let scanner;
let cameras = [];
let currentCameraIndex = 0;
let flashOn = false;

// Inicializa scanner
async function initScanner() {
  cameras = await Html5Qrcode.getCameras();
  if (!cameras || cameras.length === 0) {
    resultDiv.innerHTML = "❌ Nenhuma câmera encontrada.";
    return;
  }
  scanner = new Html5Qrcode("reader");
  startCamera(currentCameraIndex);
}

function startCamera(index) {
  const cameraId = cameras[index].id;
  scanner.start(
    { deviceId: { exact: cameraId } },
    {
      fps: 20,                 // FPS mais alto
      qrbox: false,            // usa toda a tela para facilitar leitura
      aspectRatio: 1.5,        // ajuda a caber códigos longos
      videoConstraints: {
        facingMode: "environment", // força câmera traseira
        focusMode: "continuous"    // tenta foco automático contínuo
      },
      formatsToSupport: [
        Html5QrcodeSupportedFormats.QR_CODE,
        Html5QrcodeSupportedFormats.CODE_39,
        Html5QrcodeSupportedFormats.CODE_128,
        Html5QrcodeSupportedFormats.EAN_13,
        Html5QrcodeSupportedFormats.EAN_8,
        Html5QrcodeSupportedFormats.UPC_A,
        Html5QrcodeSupportedFormats.UPC_E
      ]
    },
    (decodedText, decodedResult) => {
      resultDiv.innerHTML = "🎉 Código lido: " + decodedText;
    },
    (errorMessage) => {
      // erros contínuos podem ser ignorados
    }
  ).catch(err => {
    resultDiv.innerHTML = "❌ Erro ao iniciar câmera: " + err;
  });
}

// Trocar câmera
switchCameraBtn.addEventListener("click", async () => {
  if (!scanner || cameras.length <= 1) return;
  await scanner.stop();
  currentCameraIndex = (currentCameraIndex + 1) % cameras.length;
  flashOn = false; // reset flash
  startCamera(currentCameraIndex);
});

// Parar scanner
stopButton.addEventListener("click", async () => {
  if (!scanner) return;
  await scanner.stop();
  resultDiv.innerHTML = "Scanner parado.";
});

// Acender/Apagar lanterna (quando suportado)
toggleFlashBtn.addEventListener("click", async () => {
  if (!scanner) return;
  const track = scanner.getRunningTrack();
  if (track && track.getCapabilities && track.getCapabilities().torch) {
    flashOn = !flashOn;
    await track.applyConstraints({ advanced: [{ torch: flashOn }] });
  } else {
    alert("Lanterna não suportada neste dispositivo.");
  }
});

// Inicializa ao carregar
initScanner();
</script>

</body>
</html>
