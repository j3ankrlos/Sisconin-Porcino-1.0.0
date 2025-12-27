<?php

namespace App\Helpers;

use Carbon\Carbon;

class PicDateHelper
{
    /**
     * El punto de inicio para los cálculos de fecha PIC.
     */
    const INICIO_VUELA = '1971-09-27';

    /**
     * Calcula la "Vuelta" a partir de una fecha.
     * Basado en la función VBA Vuelta(fecha).
     */
    public static function getVuelta($fecha)
    {
        $diferencia = self::getDiferenciaDias($fecha);
        if ($diferencia < 0) return "00";
        
        // El VBA usa Left(DiferenciaDias, 2), lo que implica los primeros dos dígitos 
        // de un número que usualmente tiene 5 dígitos (desde 1999 en adelante).
        return substr((string)$diferencia, 0, 2);
    }

    /**
     * Calcula el "PIC" a partir de una fecha.
     * Basado en la función VBA FPic(fecha).
     */
    public static function getPic($fecha)
    {
        $diferencia = self::getDiferenciaDias($fecha);
        if ($diferencia < 0) return "000";

        // El VBA usa Mid(CStr(DiferenciaDias), 3, 6), que son los dígitos restantes.
        $pic = substr((string)$diferencia, 2);
        
        // Aseguramos que tenga 3 dígitos con ceros a la izquierda si es necesario
        return str_pad($pic, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Genera el formato completo "Vuelta-PIC" (ej: 19-808).
     */
    public static function format($fecha)
    {
        if (!$fecha) return null;
        return self::getVuelta($fecha) . '-' . self::getPic($fecha);
    }

    /**
     * Convierte un valor PIC y Vuelta de regreso a una fecha real.
     * Basado en la función VBA FCalendario(Pic, Vuelta).
     */
    public static function picToDate($pic, $vuelta)
    {
        $inicio = Carbon::parse(self::INICIO_VUELA);
        $totalDias = ($vuelta * 1000) + (int)$pic;
        
        return $inicio->addDays($totalDays);
    }

    /**
     * Método auxiliar para obtener la diferencia en días.
     */
    private static function getDiferenciaDias($fecha)
    {
        try {
            $fechaObj = Carbon::parse($fecha);
            $inicio = Carbon::parse(self::INICIO_VUELA);
            return $inicio->diffInDays($fechaObj, false);
        } catch (\Exception $e) {
            return 0;
        }
    }
}
