@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp
<!DOCTYPE html>
<html>

<head>
    <title>Comprobante electronico - {{ $empresa }}-09-{{ $serie }}-{{ $numero }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header .logo {
            font-size: 12px;
            text-align: left;
            color: gray;
        }

        .info-box {
            border: 1px solid #000;
            padding: 10px;
            border-radius: 10px;

            text-align: center;
            position: absolute;
            top: 10px;
            right: 1px;
        }

        .info-box p {
            margin: 5px 0;
        }

        .info-box-large {
            border: 1px solid #000;
            border-radius: 10px;
            width: 100%;
            box-sizing: border-box;
            top: 130px;
        }

        .info-box-large table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-box-large td {
            padding: 2px 10px;
            font-size: 10px;
        }

        .info-box-large td:first-child {
            width: 60%;
            /* Ajusta este valor según tus necesidades */
        }

        .info-box-large td:last-child {
            width: 50%;
            /* Ajusta este valor según tus necesidades */
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10px;
            margin-top: 20px;
        }

        .details-table th,
        .details-table td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;

        }

        .details-table th {
            background-color: #f2f2f2;
        }

        .details-table tbody td {
            height: 1px;
            /* Altura mínima */
            padding: 1px 2px;
            /* Espaciado dentro de las celdas */
        }

        .details-table th:nth-child(1),
        .details-table td:nth-child(1) {
            width: 5%;
            /* Ancho de la primera columna */
        }

        .details-table th:nth-child(2),
        .details-table td:nth-child(2) {
            width: 10%;
            /* Ancho de la segunda columna */
        }

        .details-table th:nth-child(3),
        .details-table td:nth-child(3) {
            width: 40%;
            /* Ancho de la tercera columna */
        }

        .details-table th:nth-child(4),
        .details-table td:nth-child(4) {
            width: 5%;
            /* Ancho de la cuarta columna */
        }

        .details-table th:nth-child(5),
        .details-table td:nth-child(5) {
            width: 10%;
            /* Ancho de la quinta columna */
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            padding: 10px 0;
            background-color: white;
        }

        .footer p {
            margin: 1px 0;
            font-size: 8px;
        }

        .qr-code {
            text-align: right;
        }

        .qr-code img {
            width: 70px;
            height: 70px;
        }
    </style>
</head>

<body>
    <div class="info-box">
        <strong>RUC: {{ $empresa }}</strong>
        <p><strong>Guia de remision electronica</strong> </p>
        <strong>Nº {{ $serie }} - {{ $numero }} </strong>
    </div>
    <table width="100%" style="border-spacing: 10px;">
        <tr>
            <!-- Columna del logo -->
            <td valign="top" width="20%" style="padding-right: 10px;">
                <img src="{{ storage_path('app/public/fondologin.jpg') }}" alt="Logo" width="100">
            </td>

            <!-- Columna del nombre y datos de la empresa -->
            <td valign="top" width="80%" style="text-align: left;">
                <h3 style="margin: 0;"> {{ $razonsocialempresa }}</h3>
                <br>
                <p style="margin: 0;">
                    {{ $dir }}<br>
                    {{ $ubicacion }}<br>
                    {{ $telefono }}
                </p>
            </td>
        </tr>
    </table>

    <!-- Cuadro con la dirección de entrega y otros datos -->
    <div class="info-box-large">
        <table>
            <tr>
                <td><strong>Fecha de emisión: </strong>{{ $fecha }}</td>
            </tr>
            <tr>
                <td><strong>Destinatario: </strong> {{ $cliente }}</td>
                <td><strong>Orden de compra: </strong> {{ $ordencompra }}</td>
            </tr>
        </table>
        <table>
            <tr>
                <td><strong>Ruc: </strong> {{ $ruc }}</td>
                <td><strong>Documento relacionado: </strong> </td>
            </tr>
            <tr>
                <td><strong>Direccion: </strong> {{ $direccion }}</td>
            </tr>
        </table>
        <table>
            <tr>
                <td><strong>Ciudad: </strong> {{ $ciudad }}</td>
            </tr>
        </table>
        <hr>
        <table>
            <tr>
                <td><strong>Motivo de traslado: </strong>{{ $motivo }}</td>
                <td><strong>Modalidad de traslado: </strong> {{ $ruc }}</td>
            </tr>
            <tr>
                <td><strong>Fecha de traslado: </strong>{{ $fechatraslado }}</td>
                <td><strong>Peso bruto: </strong>{{ $peso }}</td>
            </tr>
        </table>
        <hr>
        <table>
            <tr>
                <td><strong>DATOS DEL TRANSPORTISTA</strong></td>
            </tr>
            <tr>
                <td><strong>Razón social: </strong> {{ $razonsocialtransporte }}</td>
                <td><strong>Ruc del transportista: </strong> {{ $ructransporte }}</td>
            </tr>
            <tr>
                <td><strong>Nombre del conductor: </strong> {{ $nombreconductor }} {{ $apellidoconductor }}</td>
                <td><strong>DNI del conductor: </strong> {{ $dni }}</td>
            </tr>
            <tr>
                <td><strong>Placa del vehiculo:</strong> {{ $placa }}</td>
                <td><strong>Licencia del conductor:</strong> {{ $licencia }}</td>
            </tr>
        </table>
        <hr>
        <table>
            <tr>
                <td><strong>PUNTO DE PARTIDA</strong></td>
                <td><strong>PUNTO DE LLEGADA </strong></td>
            </tr>
            <tr>
                <td>{{ $direccionpartida }}</td>
                <td>{{ $direccionllegada }}</td>
            </tr>
        </table>
    </div>

    <!-- Agregar el párrafo personalizado -->

    <table class="details-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Código</th>
                <th>Producto</th>
                <th>UM</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalles as $detalle)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detalle->Producto }}</td>
                    <td>{{ $detalle->Descripcion }}</td>
                    <td>{{ $detalle->umedida }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table class="totals">
        <tr>
            <td>Recibido en la fecha: </td>
        </tr>
        <tr>
            <td>Apellidos y Nombres:</td>
        </tr>
        <tr>
            <td>Tipo y Nº documento:</td>
        </tr>
        <tr>
            <td>Firma:</td>
        </tr>
    </table>
    <div class="qr-code">
        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
    </div>
    <div class="footer">
        <p>Representacion impresa de Guia de remision electronica </p>
    </div>

</body>

</html>