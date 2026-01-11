<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scanner QR & Código de Barras - Traseira</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      padding: 20px;
      background: #f5f5f5;
    }
    #reader {
      width: 100%;
      max-width: 500px;
      margin: 20px auto;
      border: 2px solid #333;
      border-radius: 10px;
      overflow: hidden;
    }
    #result {
      margin-top: 20px;
      font-size: 1.2em;
      word-break: break-word;
      color: #222;
    }
    button {
      margin: 10px;
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 5px;
      border: none;
      background: #007bff;
      color: white;
    }
    button:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>

  <h2>📷 Scanner QR & Código de Barras</h2>

  <div id="reader"></div>
  <div id="result">Nenhum código detectado ainda.</div>
  
  <button id="switchCameraBtn">🔄 Trocar Câmera</button>
  <button id="stopButton">⏹ Parar Scanner</button>

  <!-- Biblioteca html5-qrcode -->
  <script src="https://unpkg.com/html5-qrcode"></script>

  <script>
    const resultDiv = document.getElementById("result");
    const switchCameraBtn = document.getElementById("switchCameraBtn");
    const stopButton = document.getElementById("stopButton");

    let scanner;
    let cameras = [];
    let currentCameraIndex = 0;

    async function initScanner() {
      try {
        cameras = await Html5Qrcode.getCameras();
        if (!cameras || cameras.length === 0) {
          resultDiv.innerHTML = "❌ Nenhuma câmera encontrada.";
          return;
        }

        scanner = new Html5Qrcode("reader");
        // Tenta iniciar sempre com a câmera traseira
        currentCameraIndex = cameras.findIndex(cam => cam.label.toLowerCase().includes("back")) || 0;
        startCamera(currentCameraIndex);
      } catch (err) {
        resultDiv.innerHTML = "❌ Erro ao acessar câmeras: " + err;
      }
    }

    function startCamera(index) {
      const cameraId = cameras[index].id;

      scanner.start(
        cameraId,
        {
          fps: 30,        // mais frames para leitura rápida
          qrbox: false,   // sem recorte, melhor para código de barras
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
          // Pode ignorar erros de leitura contínua
        }
      ).catch(err => {
        resultDiv.innerHTML = "❌ Erro ao iniciar câmera: " + err;
      });
    }

    switchCameraBtn.addEventListener("click", async () => {
      if (!scanner || cameras.length <= 1) return;
      await scanner.stop();
      currentCameraIndex = (currentCameraIndex + 1) % cameras.length;
      startCamera(currentCameraIndex);
    });

    stopButton.addEventListener("click", async () => {
      if (!scanner) return;
      await scanner.stop();
      resultDiv.innerHTML = "Scanner parado.";
    });

    initScanner();
  </script>

</body>
</html>
