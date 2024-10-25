<!DOCTYPE html>
<html>

<head>
    <title>Cotización</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            /* Tamaño del título principal */
            margin: 0;
        }

        .header h4 {
            font-size: 12px;
            /* Tamaño del número de cotización */
            margin: 0;
            color: gray;
        }

        .header .logo {
            font-size: 12px;
            text-align: left;
            color: gray;
        }

        .header .date-time {
            font-size: 12px;
            text-align: right;
            color: gray;
        }

        .info-cotizacion {
            width: 100%;
            margin-bottom: 20px;
            font-size: 12px;
            /* Tamaño de la fuente para la info */
        }

        .info-cotizacion td {
            padding: 1px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
            /* Tamaño de la fuente en la tabla */
        }

        .details-table th,
        .details-table td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        .details-table th {
            background-color: #f2f2f2;
        }

        .totals {
            width: 100%;
            margin-top: 20px;
            font-size: 14px;
            /* Tamaño de la fuente para totales */
        }

        .totals td {
            padding: 5px;
        }

        .right {
            text-align: right;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            padding: 10px 0;
            background-color: white;            
            border-top: 1px solid black;
        }

        .footer p {
            margin: 1px 0;
            font-size: 8px;
        }


        p {
            font-size: 12px;
            margin-top: 20px;
            line-height: 1.5;
        }
    </style>
</head>

<body>

    <div class="header">

        <div class="date-time">
            <p>{{ now()->format('d/m/Y H:i') }}</p>
        </div>
        <div class="logo">
            <img src="{{ storage_path('app/public/fondologin.jpg') }}" alt="Logo" width="100"> <!-- Logo -->
        </div>
        <div>
            <h1>Cotización</h1>
            <h4>{{ $numero }}</h4>
        </div>

    </div>

    <table class="info-cotizacion">
        <tr>
            <td><strong>Cliente:</strong>{{ $ruc }} {{ $cliente }}</td>
        </tr>
        <tr>
            <td><strong>Dirección:</strong> {{ $direccion }}</td>
        </tr>
        <tr>
            <td><strong>Moneda:</strong> {{ $moneda }}</td>
            <td></td>
        </tr>
    </table>
    <!-- Agregar el párrafo personalizado -->
    <p>
        Estimado cliente por medio de la presente reciban nuestro cordial saludo y asimismo queremos poner a su
        disposicion los siguientes productos...
    </p>
    <table class="details-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Código</th>
                <th>Producto</th>
                <th>UM</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalles as $detalle)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detalle->CodProducto }}</td>
                    <td>{{ $detalle->Descripcion }}</td>
                    <td>{{ $detalle->umedida }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>{{ $detalle->precio }}</td>
                    <td>{{ $detalle->TotalPro }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td class="right"><strong>Subtotal </strong></td>
            <td class="right"><strong> {{ $simbolomoneda }}</strong></td>
            <td class="right"> {{ number_format($subtotal, 2) }}</td>
        </tr>
        <tr>
            <td class="right"><strong>IGV (18%) </strong></td>
            <td class="right"><strong> {{ $simbolomoneda }}</strong></td>
            <td class="right"> {{ number_format($igv, 2) }}</td>
        </tr>
        <tr>
            <td class="right"><strong>Total</strong></td>
            <td class="right"><strong> {{ $simbolomoneda }}</strong></td>
            <td class="right"> {{ number_format($total, 2) }}</td>
        </tr>
    </table>
    <div class="footer">
        <p> {{ $Direccion }} </p>
        <p> {{ $Url_Web }}</p>
        <p> {{ $Email }}</p>
    </div>
</body>

</html>