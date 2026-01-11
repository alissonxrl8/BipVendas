<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>BipVendas Scanner</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body {
  margin: 0;
  background: black;
  font-family: Arial, sans-serif;
  color: white;
  text-align: center;
}
video {
  width: 100vw;
  height: 100vh;
  object-fit: cover;
}
#box {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  background: rgba(0,0,0,0.6);
  padding: 15px 25px;
  border-radius: 10px;
  font-size: 18px;
}
</style>
</head>
<body>

<div id="box">📷 Aguardando leitura...</div>
<video id="video" autoplay></video>

<script>
const video = document.getElementById("video");
const box = document.getElementById("box");

async function start() {
  const stream = await navigator.mediaDevices.getUserMedia({
    video: { facingMode: "environment" }
  });

  video.srcObject = stream;

  const barcodeDetector = new BarcodeDetector({
    formats: ["ean_13", "code_128", "qr_code", "upc_a"]
  });

  setInterval(async () => {
    try {
      const barcodes = await barcodeDetector.detect(video);
      if (barcodes.length > 0) {
        const code = barcodes[0].rawValue;
        box.innerText = "📦 Código: " + code;

        navigator.vibrate(200);

        // aqui você envia pro Laravel depois
        console.log("LIDO:", code);
      }
    } catch (e) {}
  }, 300);
}

start();
</script>

</body>
</html>
