<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    @foreach($content as $cusName)
    @foreach($totalValue as $total)
    <p>¡Saludos {{ $cusName->f_name }} {{ $cusName->f_lastname }}!</p>
    <p>
        Hemos validado tu compra satisfactoriamente, registrada con el número 
        {{ $saleNumber }},
        efectuada el {{ \Carbon\Carbon::parse($total->created_at)->format('d-m-Y')}},
        a las {{ \Carbon\Carbon::parse($total->created_at)->isoFormat('hh:mm: A')}}.
        A continuación, te presentamos el detalle de tu compra:
    </p>

    <p>
        @foreach($saleDetail as $product)
        <br>
        Nombre de producto: {{ $product->name }}.
        <br>
        Referencia:         {{ $product->reference }}.
        <br>
        Marca:              {{ $product->brand }}.
        <br>
        Modelo:             {{ $product->model }}.
        <br>
        Precio unitario:    {{ $product->unitary_value }}.
        <br>
        Cantidad afquirida: {{ $product->amount }}.
        <br>
        Subtotal:           ${{ $product->total_value }} pesos.
        <br>
        @endforeach
    </p>
    <p>Total: ${{ $total->total_value }} pesos</p>
    <p>
        Recuerda que cuentas con nuestros canales virtuales en caso de quejas o reclamos,
        gracias por confiar en nosotros para tus compras.
    </p>
    <p>Att: Equipo Webstore</p>
    @endforeach
    @endforeach
    
</body>
</html>