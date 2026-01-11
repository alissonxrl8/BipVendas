<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BipVendas Scanner</title>
<script src="https://unpkg.com/html5-qrcode@2.3.10/minified/html5-qrcode.min.js"></script>
<style>
body {
  font-family: Arial, sans-serif;
  margin: 0;
  background: black;
  color: white;
  text-align: center;
}
#reader {
  width: 100%;
  max-width: 400px;
  margin: 20px auto;
}
#box {
  margin-top: 10px;
  font-size: 20px;
}
</style>
</head>
<body>

<h2>BipVendas Scanner</h2>
<div id="reader"></div>
<div id="box">📷 Aguardando leitura...</div>

<script>
function onScanSuccess(decodedText, decodedResult) {
    document.getElementById('box').innerText = "📦 Código: " + decodedText;
    console.log("Lido:", decodedText);
    navigator.vibrate(200); // vibra no celular
}

function onScanFailure(error) {
    // ignora erros
}

let html5QrcodeScanner = new Html5Qrcode("reader");

Html5Qrcode.getCameras().then(cameras => {
    if (cameras && cameras.length) {
        let cameraId = cameras[0].id; // pega primeira câmera traseira
        for(let cam of cameras){
            if(cam.label.toLowerCase().includes("back") || cam.label.toLowerCase().includes("traseira")){
                cameraId = cam.id;
                break;
            }
        }
        html5QrcodeScanner.start(
            cameraId,
            { fps: 10, qrbox: 250 }, 
            onScanSuccess,
            onScanFailure
        );
    } else {
        alert("Nenhuma câmera encontrada!");
    }
}).catch(err => console.error(err));
</script>

</body>
</html>
