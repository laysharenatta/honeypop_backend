<?php

namespace App\Services;

use App\Models\Impuesto;

class TaxService
{
    /**
     * Obtiene todos los impuestos activos (no eliminados).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActiveImpuestos()
    {
        return Impuesto::where('activo', true)->get();
    }

    /**
     * Calcula la tasa total de impuestos (suma de porcentajes activos).
     *
     * @return float  Ejemplo: 16.00 para 16%
     */
    public static function getTotalRate(): float
    {
        return (float) Impuesto::where('activo', true)->sum('porcentaje');
    }

    /**
     * Calcula el desglose de impuestos para un subtotal dado.
     *
     * @param float $subtotal  El monto antes de impuestos
     * @return array{
     *   subtotal: float,
     *   impuesto_porcentaje: float,
     *   impuesto_monto: float,
     *   total: float,
     *   impuestos_aplicados: array
     * }
     */
    public static function calcular(float $subtotal): array
    {
        $impuestos = self::getActiveImpuestos();

        $tasaTotal = 0;
        $impuestosAplicados = [];

        foreach ($impuestos as $impuesto) {
            $montoImpuesto = round($subtotal * ($impuesto->porcentaje / 100), 2);
            $tasaTotal += $impuesto->porcentaje;

            $impuestosAplicados[] = [
                'id' => $impuesto->id,
                'nombre' => $impuesto->nombre,
                'codigo' => $impuesto->codigo,
                'porcentaje' => (float) $impuesto->porcentaje,
                'monto' => $montoImpuesto,
            ];
        }

        $impuestoMontoTotal = round($subtotal * ($tasaTotal / 100), 2);
        $total = round($subtotal + $impuestoMontoTotal, 2);

        return [
            'subtotal' => round($subtotal, 2),
            'impuesto_porcentaje' => round($tasaTotal, 2),
            'impuesto_monto' => $impuestoMontoTotal,
            'total' => $total,
            'impuestos_aplicados' => $impuestosAplicados,
        ];
    }
}
