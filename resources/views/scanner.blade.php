<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scanner QR & Código de Barras Melhorado</title>
  <style>
    body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
    #reader { width: 100%; max-width: 500px; margin: auto; position: relative; }
    #overlay {
      position: absolute;
      top: 0; left: 0; width: 100%; height: 100%;
      border: 4px dashed #00f;
      box-sizing: border-box;
      pointer-events: none;
    }
    #result { margin-top: 20px; font-size: 1.2em; word-break: break-word; }
    button { margin: 10px; padding: 10px 20px; font-size: 16px; cursor: pointer; }
    #tip { font-size: 0.9em; color: #555; margin-top: 5px; }
  </style>
</head>
<body>

  <h2>📷 Scanner QR & Código de Barras Melhorado</h2>

  <div id="reader">
    <div id="overlay"></div>
  </div>
  <div id="tip">📌 Posicione o código dentro do retângulo azul para captura mais rápida</div>
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
      try {
        cameras = await Html5Qrcode.getCameras();
        if (!cameras || cameras.length === 0) {
          resultDiv.innerHTML = "❌ Nenhuma câmera encontrada.";
          return;
        }

        scanner = new Html5Qrcode("reader");
        startCamera(currentCameraIndex);
      } catch (err) {
        resultDiv.innerHTML = "❌ Erro ao listar câmeras: " + err;
      }
    }

    function startCamera(index) {
      const cameraId = cameras[index].id;

      scanner.start(
        cameraId,
        {
          fps: 5, // reduz fps para dar mais tempo de processar
          qrbox: { width: 400, height: 150 }, // retângulo maior, horizontal, ideal para barras lineares
          formatsToSupport: [
            Html5QrcodeSupportedFormats.QR_CODE,
            Html5QrcodeSupportedFormats.CODE_39,
            Html5QrcodeSupportedFormats.CODE_128,
            Html5QrcodeSupportedFormats.EAN_13,
            Html5QrcodeSupportedFormats.EAN_8,
            Html5QrcodeSupportedFormats.UPC_A,
            Html5QrcodeSupportedFormats.UPC_E
          ],
          experimentalFeatures: { useBarCodeDetectorIfSupported: true } // tenta usar detector nativo se o browser suportar
        },
        async (decodedText, decodedResult) => {
          resultDiv.innerHTML = "🎉 Código lido: " + decodedText;

          // Envia para API gratuita para validar ou processar
          try {
            const response = await fetch(`https://world.openfoodfacts.org/api/v0/product/${decodedText}.json`);
            if (!response.ok) throw new Error("Produto não encontrado na API");
            const data = await response.json();
            if (data.status === 1) {
              resultDiv.innerHTML += `<br>✅ Produto: ${data.product.product_name}`;
            } else {
              resultDiv.innerHTML += `<br>⚠️ Produto não encontrado`;
            }
          } catch (err) {
            console.warn(err);
          }
        },
        (errorMessage) => {
          // Erros contínuos podem ser ignorados
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
