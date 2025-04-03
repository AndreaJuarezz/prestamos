<?php

namespace App\Http\Controllers;
use App\Models\Abono;
use App\Models\Prestamo;
use DateTime;
use Francerz\PowerData\Index;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportesController extends Controller
{
    public function indexGet(Request $request)
    {
        return view("reportes.indexGet",[
            "breadcrumbs"=>[
                "Inicio"=>url("/"),
                "Reportes"=>url("/reportes/prestamos-activos")
            ]
        ]);
    }

    public function prestamosActivosGet(Request $request)
    {
    $fecha = Carbon::now()->format("Y-m-d"); // Carbon Fecha actual en formato de texto
    $fecha = $request->query("fecha", $fecha);

    $prestamos = Prestamo::join("empleado", "empleado.id_empleado", "=", "prestamo.fk_id_empleado")
        ->leftJoin("abono", "abono.fk_id_prestamo", "=", "prestamo.id_prestamo")
        ->select("prestamo.id_prestamo", "empleado.nombre", "prestamo.monto")
        ->selectRaw("SUM(abono.monto_capital) AS total_capital")
        ->selectRaw("SUM(abono.monto_interes) AS total_interes")
        ->selectRaw("SUM(abono.monto_cobrado) AS total_cobrado")
        ->groupBy("prestamo.id_prestamo", "empleado.nombre", "prestamo.monto")
        ->where("prestamo.fecha_ini_desc", "<=", $fecha)
        ->where("prestamo.fecha_fin_desc", ">=", $fecha)
        ->get()->all();

    // var_dump($prestamos);

    return view("/reportes/prestamosActivosGet", [
        "fecha" => $fecha,
        "prestamos" => $prestamos,
        "breadcrumbs" => [
            "Inicio" => url("/"),
            "Reportes" => url("/reportes/prestamos-activos")
        ]
    ]);
    }

    public function matrizAbonosGet(Request $request)
    {
        $fecha_inicio = $request->query('fecha_inicio', Carbon::now()->startOfYear()->format('Y-01-01'));
        $fecha_fin = $request->query('fecha_fin', Carbon::now()->endOfYear()->format('Y-12-31'));
    
        $query = Abono::join("prestamo", "prestamo.id_prestamo", "=", "abono.fk_id_prestamo")
            ->join("empleado", "empleado.id_empleado", "=", "prestamo.fk_id_empleado")
            ->select("prestamo.id_prestamo", "empleado.nombre", "abono.monto_cobrado", "abono.fecha")
            ->orderBy("abono.fecha")
            ->where("abono.fecha", ">=", $fecha_inicio)
            ->where("abono.fecha", "<=", $fecha_fin);
    
        $abonos = $query->get()->toArray();
    
        // Agrupar los abonos por id_prestamo
        $abonosGrouped = collect($abonos)->groupBy('id_prestamo');
        $fechas = collect($abonos)->pluck('fecha')->unique()->sort()->values(); // Fechas únicas ordenadas
    
        return view("reportes.matrizAbonosGet", [
            "abonosGrouped" => $abonosGrouped,
            "fechas" => $fechas,
            "fecha_inicio" => $fecha_inicio,
            "fecha_fin" => $fecha_fin,
            "breadcrumbs" => [] // Si tienes breadcrumbs
        ]);
    }
}
