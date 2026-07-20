<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>

QR Code

</title>

<script>

function printPage(){

    window.print();

}

</script>

<style>

body{

    font-family:Arial;

    text-align:center;

    margin-top:60px;

}

.card{

    width:500px;

    margin:auto;

    border:2px solid black;

    padding:40px;

}

h1{

    margin-bottom:30px;

}

.floor{

    font-size:30px;

    margin-top:25px;

    font-weight:bold;

}

button{

    margin-top:40px;

    padding:12px 30px;

    font-size:18px;

}

@media print{

button{

display:none;

}

}

</style>

</head>

<body>

<div class="card">

    <p>
    
    <!-- Scan this QR code after climbing the stairs. -->
    YOU HAVE REACHED 
    
    </p>
<h1>{{ $floor->name }}</h1>
<h2>SCAN ME!</h2>


{!! QrCode::size(320)->generate($floor->id) !!}

<!-- <div class="floor">

Floor {{ $floor->id }}

</div> -->



</div>
<button onclick="printPage()">Print</button>

</body>

</html>