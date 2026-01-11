<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>BipVendas Scanner</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<script src="https://unpkg.com/html5-qrcode"></script>

<style>
body{
    margin:0;
    font-family: Arial, Helvetica, sans-serif;
    background:#0f172a;
    color:white;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    height:100vh;
}
h2{
    margin-bottom:10px;
}
#reader{
    width:320px;
    height:320px; /* precisa de altura */
    max-width:90vw;
    border-radius:16px;
    overflow:hidden;
    border:3px solid #22c55e;
    box-shadow:0 0 20px #22c55e55;
}
#result{
    margin-top:15px;
    font-size:18px;
    color:#22c55e;
    word-break:break-all;
    text-align:center;
}
button{
    margin-top:15px;
    padding:10px 18px;
    border:none;
    border-radius:10px;
    background:#22c55e;
    color:black;
    font-size:16px;
    font-weight:bold;
}
</style>
</head>
<body>

<h2>📷 BipVendas – Scanner</h2>

<div id="reader"></div>
<div id="result">Aguardando leitura…</div>
<button id="switchCam">Trocar câmera</button>

<script>
const result = document.getElementById("result");
const switchBtn = document.getElementById("switchCam");

let html5QrCode = new Html5Qrcode("reader");
let cameras = [];
let currentCameraIndex = 0;

function onScanSuccess(decodedText) {
    result.innerText = "Código: " + decodedText;

    // Envia pro Laravel (se quiser)
    fetch("/scan", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ code: decodedText })
    });
}

function startCamera(index){
    html5QrCode.stop().catch(()=>{}); // para câmera atual
    html5QrCode.start(
        cameras[index].id,
        {
            fps: 12,
            qrbox: { width: 250, height: 250 },
            experimentalFeatures: {
                useBarCodeDetectorIfSupported: true
            }
        },
        onScanSuccess
    );
}

Html5Qrcode.getCameras().then(devices => {
    if (devices.length === 0) {
        alert("Nenhuma câmera encontrada");
        return;
    }

    cameras = devices;

    // 🔹 Usa a última câmera (traseira na maioria dos celulares)
    currentCameraIndex = devices.length - 1;

    startCamera(currentCameraIndex);
});

switchBtn.onclick = () => {
    currentCameraIndex++;
    if (currentCameraIndex >= cameras.length) currentCameraIndex = 0;
    startCamera(currentCameraIndex);
};
</script>

</body>
</html>
