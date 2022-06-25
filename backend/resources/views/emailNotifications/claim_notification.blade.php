<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>¡Saludos {{ $customerName }} {{ $customerLastName }}!</p>
    <p>
        @foreach($claimDate as $date)
        Se ha registrado correctamente tu solicitud, radicada con el número
        {{ $claimNumber }}, efectuada el
        {{ \Carbon\Carbon::parse($date->created_at)->format('d-m-Y')}}.
        Uno de nuestros asesores revisará tu caso y te dará respuesta
        en un plazo de 1 a 3 días hábiles.
        @endforeach
    </p>
    <p>
        Recuerda que cuentas con nuestros canales virtuales en caso de quejas o reclamos.
    </p>
    <p>Att: Equipo Webstore</p> 
</body>
</html>