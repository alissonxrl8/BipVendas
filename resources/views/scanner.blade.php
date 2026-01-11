<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scanner QR & Barcode - Troca de Câmera</title>
  <style>
    body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
    #reader { width: 100%; max-width: 500px; margin: auto; }
    #result { margin-top: 20px; font-size: 1.2em; word-break: break-word; }
    button { margin: 10px; padding: 10px 20px; font-size: 16px; cursor: pointer; }
  </style>
</head>
<body>

  <h2>📷 Scanner QR & Código de Barras</h2>

  <div id="reader"></div>
  <div id="result">Nenhum código detectado ainda.</div>
  
  <button id="switchCameraBtn">Trocar Câmera</button>
  <button id="stopButton">Parar Scanner</button>

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
      // Obtém todas as câmeras disponíveis
      cameras = await Html5Qrcode.getCameras();
      if (!cameras || cameras.length === 0) {
        resultDiv.innerHTML = "❌ Nenhuma câmera encontrada.";
        return;
      }

      // Inicializa scanner
      scanner = new Html5Qrcode("reader");
      startCamera(currentCameraIndex);
    }

    function startCamera(index) {
      const cameraId = cameras[index].id;
      scanner.start(
        cameraId,
        { fps: 10, qrbox: { width: 250, height: 250 } },
        (decodedText, decodedResult) => {
          resultDiv.innerHTML = "🎉 Código lido: " + decodedText;
        },
        (errorMessage) => {
          // ignorar erros de leitura contínua
        }
      ).catch(err => {
        resultDiv.innerHTML = "❌ Erro ao iniciar câmera: " + err;
      });
    }

    // Alternar câmera
    switchCameraBtn.addEventListener("click", async () => {
      if (!scanner || cameras.length <= 1) return;
      await scanner.stop();
      currentCameraIndex = (currentCameraIndex + 1) % cameras.length;
      startCamera(currentCameraIndex);
    });

    // Parar scanner
    stopButton.addEventListener("click", async () => {
      if (!scanner) return;
      await scanner.stop();
      resultDiv.innerHTML = "Scanner parado.";
    });

    // Inicializa scanner ao carregar página
    initScanner();
  </script>

</body>
</html>
