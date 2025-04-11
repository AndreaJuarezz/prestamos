<?php

namespace App\Http\Controllers;
use DateTime;
use App\Models\Puesto;
use App\Models\Prestamo;
use App\Models\Empleado;
use App\Models\Det_emp_puesto;
use App\Models\Abono;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;



class MovimientosController extends Controller
{
    public function prestamosGet(): View
    {
        $prestamos = Prestamo::join("empleado","prestamo.fk_id_empleado","=","empleado.id_empleado")->get();
        return view("movimientos/prestamosGet", [
            "prestamos" => $prestamos,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Prestamos" => url('/movimientos/prestamos')
            ]
        ]);
    }

    public function prestamosAgregarGet() {
        $haceunanno = (new DateTime("-1 year"))->format("Y-m-d");
        $empleados = Empleado::where("fecha_ingreso", "<", $haceunanno)->get()->all();
        $fecha_actual = SupportCarbon::now();
        $prestamosvigentes = Prestamo::where("fecha_ini_desc", "<", $fecha_actual) ->where("fecha_fin_desc", ">", $fecha_actual) ->get();
        $empleados = array_column($empleados, null, "id_empleado");
        $prestamosvigentes = array_column($prestamosvigentes->toArray(), "id_empleado");
        $empleados = array_diff_key($empleados, $prestamosvigentes);
    
        return view("movimientos/prestamosAgregarGet", [
            "empleados" => $empleados,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Prestamos" => url("/movimientos/prestamos"),
                "Agregar" => url("/movimientos/prestamos/agregar"),
            ]
        ]);
    }

    public function prestamosAgregarPost(Request $request)
{
        $id_empleado=$request->input("id_empleado");
        $monto=$request->input("monto");
        $puesto=Puesto::join("det_emp_puesto", "puesto.id_puesto", "=", "det_emp_puesto.id_puesto")
            ->where("det_emp_puesto.id_empleado","=",$id_empleado)
            ->whereNull("det_emp_puesto.fecha_fin")->first();
        $sueldox6=$puesto->sueldo*6;
        if ($monto>$sueldox6){
            return view("/error",["error"=>"La solicitud excede el monto permitido"]);
        }
        $fecha_solicitud=$request->input("fecha_solicitud");
        $plazo=$request->input("plazo");
        $fecha_aprob=$request->input("fecha_aprob");
        $tasa_mensual=$request->input("tasa_mensual");
        $pago_fijo_cap=$request->input("pago_fijo_cap");
        $fecha_ini_desc=$request->input("fecha_ini_desc");
        $fecha_fin_desc=$request->input("fecha_fin_desc");
        $saldo=$request->input("saldo");
        $estado=$request->input("estado");
        $prestamo=new Prestamo([
            "fk_id_empleado"=>$id_empleado,
            "fecha_solicitud"=>$fecha_solicitud,
            "monto"=>$monto,
            "plazo"=>$plazo,
            "fecha_aprob"=>$fecha_aprob,
            "tasa_mensual"=>$tasa_mensual,
            "pago_fijo_cap"=>$pago_fijo_cap,
            "fecha_ini_desc"=>$fecha_ini_desc,
            "fecha_fin_desc"=>$fecha_fin_desc,
            "saldo"=>$saldo,
            "estado"=>$estado,
        ]);
        $prestamo->save();
        return redirect("/movimientos/prestamos"); // redirige al listado de prestamos
    }

    public function abonosGet($id_prestamo): View
    {
        $abonos= Abono::where("fk_id_prestamo",$id_prestamo)->get()->all();
    // Obtener el prestamo con su relacion de empleado
    $prestamo = Prestamo::join("empleado", "empleado.id_empleado","=","prestamo.fk_id_empleado")
        ->where("id_prestamo", $id_prestamo)->first();
    
    return view('movimientos/abonosGet', [
        'abonos' => $abonos,
        'prestamo' => $prestamo,
        "breadcrumbs" => [
            "Inicio" => url("/"),
            "Prestamos" => url("/movimientos/prestamos"),
            "Abonos" => url("/movimientos/prestamos/abonos"),
        ]
    ]);
    }

    public function abonosAgregarGet($id_prestamo): View|RedirectResponse
{
    $prestamo = Prestamo::join("empleado", "empleado.id_empleado", "=", "prestamo.fk_id_empleado")
        ->where("id_prestamo", $id_prestamo)
        ->select("prestamo.*", "empleado.nombre")
        ->first();

    if (!$prestamo) {
        return redirect()->back()->with('error', 'Préstamo no encontrado.');
    }

    $abonos = Abono::where("fk_id_prestamo", $id_prestamo)->get();
    $num_abono = count($abonos) + 1;

    $ultimo_abono = Abono::where("fk_id_prestamo", $id_prestamo)
        ->orderBy("fecha", "desc")
        ->first();

    $saldo_actual = $ultimo_abono ? $ultimo_abono->saldo_actual : $prestamo->saldo_actual;

    // Calculamos los valores necesarios para la vista
    $monto_interes = $saldo_actual * ($prestamo->tasa_mensual / 100);
    $monto_cobrado = $prestamo->pago_fijo_cap + $monto_interes;
    $saldo_pendiente = $saldo_actual - $prestamo->pago_fijo_cap;

    if ($saldo_pendiente < 0) {
        $pago_fijo_cap = $prestamo->pago_fijo_cap + $saldo_pendiente;
        $saldo_pendiente = 0;
    } else {
        $pago_fijo_cap = $prestamo->pago_fijo_cap;
    }

    return view('movimientos/abonosAgregarGet', [
        'prestamo' => $prestamo,
        'num_abono' => $num_abono,
        'pago_fijo_cap' => $pago_fijo_cap, 
        'monto_interes' => $monto_interes,
        'monto_cobrado' => $monto_cobrado,
        'saldo_pendiente' => $saldo_pendiente,
        'breadcrumbs' => [
            "Inicio" => url("/"),
            "Prestamos" => url("/movimientos/prestamos"),
            "Abonos" => url("/prestamos/{$prestamo->id_prestamo}/abonos"),
            "Agregar" => url("/prestamos/{$prestamo->id_prestamo}/abonos/agregar"),
        ]
    ]);
}

public function abonosAgregarPost(Request $request)
{
    dd($request->all());

    $id_prestamo = $request->input("id_prestamo");
    $num_abono = $request->input("num_abono");
    $fecha = $request->input("fecha");
    $monto_capital = $request->input("monto_capital");
    $monto_interes = $request->input("monto_interes");
    $monto_cobrado = $request->input("monto_cobrado");
    $saldo_pendiente = $request->input("saldo_pendiente");

    // Validar que todos los datos están presentes
    if (!$id_prestamo || !$num_abono || !$fecha || !$monto_capital || !$monto_interes || !$monto_cobrado || !$saldo_pendiente) {
        return redirect()->back()->with('error', 'Todos los campos son obligatorios.');
        
    }

    $abono = new Abono([
        "fk_id_prestamo" => $id_prestamo,
        "num_abono" => $num_abono,
        "fecha" => $fecha,
        "monto_capital" => $monto_capital,
        "monto_interes" => $monto_interes,
        "monto_cobrado" => $monto_cobrado,
        "saldo_actual" => $saldo_pendiente,
    ]);

    $abono->save();

    // Actualizar el saldo del préstamo
    $prestamo = Prestamo::find($id_prestamo);
    $prestamo->saldo_actual = $saldo_pendiente;
    if ($saldo_pendiente < 0.01) {
        $prestamo->estado = 1; // Marcar como pagado si el saldo llega a 0
    }
    $prestamo->save();

    // Redirigir de vuelta a la lista de abonos con un mensaje de éxito
    return redirect(url("/prestamos/{$id_prestamo}/abonos"))->with('success', 'Abono guardado exitosamente.');

}

    public function empleadosPrestamosGet(Request $request, $id_empleado): View
    {
        // Buscar el empleado por ID
        $empleado = Empleado::find($id_empleado);
    
        // Validar si el empleado existe
        if (!$empleado) {
            abort(404, "Empleado no encontrado");
        }
    
        // Obtener todos los préstamos del empleado
        $prestamos = Prestamo::where('fk_id_empleado', $id_empleado)->get();
    
        // Retornar la vista con los datos necesarios
        return view('movimientos/empleadosPrestamosGet', [
            'empleado' => $empleado,
            'prestamos' => $prestamos,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Prestamos' => url('/movimientos/prestamos'),
            ],
        ]);
    }
    

}
 
