<?php

namespace App\Services;

use App\Models\Impuesto;

class ImpuestoService
{
    /**
     * Calcula el desglose de impuestos para un subtotal dado.
     * Suma todos los impuestos activos de la base de datos.
     *
     * @param float $subtotal  Monto base antes de impuestos
     * @return array {
     *   subtotal: float,
     *   tasa_total: float,        // Porcentaje total efectivo (suma de activos)
     *   impuesto_monto: float,    // Monto total de impuesto
     *   total: float,
     *   impuestos_aplicados: array // Detalle de cada impuesto aplicado
     * }
     */
    public function calcularImpuestos(float $subtotal): array
    {
        $impuestosActivos = Impuesto::where('activo', true)->get();

        $tasaTotal = 0;
        $impuestosAplicados = [];

        foreach ($impuestosActivos as $impuesto) {
            $porcentaje = (float) $impuesto->porcentaje;
            $monto = round($subtotal * ($porcentaje / 100), 2);

            $tasaTotal += $porcentaje;
            $impuestosAplicados[] = [
                'id'          => $impuesto->id,
                'nombre'      => $impuesto->nombre,
                'codigo'      => $impuesto->codigo,
                'porcentaje'  => $porcentaje,
                'monto'       => $monto,
            ];
        }

        $impuestoMonto = round($subtotal * ($tasaTotal / 100), 2);
        $total         = round($subtotal + $impuestoMonto, 2);

        return [
            'subtotal'            => round($subtotal, 2),
            'tasa_total'          => $tasaTotal,
            'impuesto_monto'      => $impuestoMonto,
            'total'               => $total,
            'impuestos_aplicados' => $impuestosAplicados,
        ];
    }

    /**
     * Retorna solo la tasa efectiva total (suma de porcentajes activos).
     */
    public function tasaEfectiva(): float
    {
        return (float) Impuesto::where('activo', true)->sum('porcentaje');
    }
}
