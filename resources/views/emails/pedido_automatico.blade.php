<!DOCTYPE html>
<html>

<head>
    <title>Nuevo Pedido Automático</title>
</head>

<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #2c3e50;">Alerta de Stock Bajo (Estrategia PUSH)</h2>
    <p>Se ha generado un nuevo pedido de reposición automáticamente debido a que el stock del producto ha alcanzado el nivel mínimo.</p>

    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">Producto:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $producto->nombre }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">Stock Actual:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $producto->stock_actual }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">Stock Mínimo:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $producto->stock_minimo }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">Cantidad Pedida:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $pedido->cantidad }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">ID Pedido:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">#{{ $pedido->id }}</td>
        </tr>
    </table>

    <div style="margin-top: 30px; font-size: 0.9em; color: #7f8c8d;">
        <p>Este es un mensaje automático del sistema de gestión de inventario.</p>
    </div>
</body>

</html>