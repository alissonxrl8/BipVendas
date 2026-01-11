<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Scanner</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

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
#reader{
    width:300px;
    border-radius:12px;
    overflow:hidden;
    border:3px solid #22c55e;
}
#result{
    margin-top:15px;
    font-size:18px;
    color:#22c55e;
    word-break:break-all;
}
</style>
</head>
<body>

<h2>📷 Scanner de QR & Código de Barras</h2>

<div id="reader"></div>

<div id="result">Aguardando leitura…</div>

<script>
const result = document.getElementById("result");

function onScanSuccess(decodedText) {
    result.innerText = "Código: " + decodedText;

    fetch("/scan", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            code: decodedText
        })
    });
}

const html5QrCode = new Html5Qrcode("reader");

Html5Qrcode.getCameras().then(devices => {
    if (devices && devices.length) {
        html5QrCode.start(
            devices[0].id,
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            onScanSuccess
        );
    }
});
</script>

</body>
</html>
