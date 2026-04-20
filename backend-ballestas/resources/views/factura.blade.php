<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura Pedido #{{ $pedido->id }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; font-size: 14px; }
        .header { width: 100%; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #d32f2f; }
        .empresa { float: right; text-align: right; font-size: 12px; color: #666; }
        .detalles-pedido { margin-bottom: 30px; padding: 15px; background-color: #f8f9fa; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border-bottom: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #343a40; color: white; }
        .total-row { font-weight: bold; font-size: 18px; color: #d32f2f; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td class="logo">BALLESTAS BENI</td>
            <td class="empresa">
                Polígono Industrial La Portalada<br>
                C. de la Nevera, 26006 Logroño, La Rioja<br>
                CIF: B-12345678<br>
                info@ballestasbeni.com
            </td>
        </tr>
    </table>

    <div class="detalles-pedido">
        <h3>Factura de Compra</h3>
        <p><strong>Número de Pedido:</strong> #000{{ $pedido->id }}</p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') }}</p>
        <p><strong>Estado:</strong> {{ $pedido->estado }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th class="text-right">Precio Unit.</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedido->lineas as $linea)
            <tr>
                <td>{{ $linea->producto ? $linea->producto->nombre : 'Producto Eliminado' }}</td>
                <td>{{ $linea->cantidad }}</td>
                <td class="text-right">{{ number_format($linea->precio_unitario, 2, ',', '.') }} €</td>
                <td class="text-right">{{ number_format($linea->cantidad * $linea->precio_unitario, 2, ',', '.') }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width: 50%; float: right; margin-top: 20px;">
        <tr>
            <td><strong>Base Imponible:</strong></td>
            <td class="text-right">{{ number_format($pedido->total / 1.21, 2, ',', '.') }} €</td>
        </tr>
        <tr>
            <td><strong>IVA (21%):</strong></td>
            <td class="text-right">{{ number_format($pedido->total - ($pedido->total / 1.21), 2, ',', '.') }} €</td>
        </tr>
        <tr class="total-row">
            <td>TOTAL:</td>
            <td class="text-right">{{ number_format($pedido->total, 2, ',', '.') }} €</td>
        </tr>
    </table>

</body>
</html>