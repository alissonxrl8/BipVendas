<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scanner QR & Barcode - Traseira</title>
  <style>
    body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
    #reader { width: 100%; max-width: 500px; margin: auto; }
    #result { margin-top: 20px; font-size: 1.2em; word-break: break-word; }
    button { margin-top: 10px; padding: 10px 20px; font-size: 16px; }
  </style>
</head>
<body>

  <h2>📷 Scanner de QR & Código de Barras</h2>

  <div id="reader"></div>
  <div id="result">Nenhum código detectado ainda.</div>
  <button id="stopButton">Parar Scanner</button>

  <!-- Biblioteca html5-qrcode -->
  <script src="https://unpkg.com/html5-qrcode"></script>

  <script>
    const resultDiv = document.getElementById("result");
    const stopButton = document.getElementById("stopButton");

    let scanner;

    async function startScanner() {
      // Obtém lista de câmeras disponíveis
      const devices = await Html5Qrcode.getCameras();
      let cameraId = null;

      if(devices && devices.length) {
        // Tenta escolher a traseira (environment) ou a primeira câmera disponível
        const rearCamera = devices.find(device => /back|rear|environment/i.test(device.label));
        cameraId = rearCamera ? rearCamera.id : devices[0].id;
      }

      scanner = new Html5Qrcode("reader");

      scanner.start(
        cameraId,
        {
          fps: 10,
          qrbox: { width: 250, height: 250 },
          supportedScanTypes: [ Html5QrcodeScanType.SCAN_TYPE_CAMERA ] // padrão
        },
        (decodedText, decodedResult) => {
          resultDiv.innerHTML = "🎉 Código lido: " + decodedText;
        },
        (errorMessage) => {
          // console.log("Scan error:", errorMessage);
        }
      ).catch(err => {
        resultDiv.innerHTML = "❌ Erro ao acessar a câmera: " + err;
      });
    }

    // Inicia scanner automaticamente
    startScanner();

    // Botão para parar o scanner
    stopButton.addEventListener("click", () => {
      if(scanner) {
        scanner.stop().then(() => {
          resultDiv.innerHTML = "Scanner parado.";
        }).catch(err => console.error(err));
      }
    });
  </script>
</body>
</html>
