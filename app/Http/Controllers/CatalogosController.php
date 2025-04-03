<?php

namespace App\Http\Controllers;
use App\Models\Puesto;
use App\Models\Empleado;
use App\Models\Prestamo;
use App\Models\Det_emp_puesto;
use DateTime;


use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogosController extends Controller
{
    public function home():View
    {
        return view('home',["breadcrumbs"=>[]]);
    }

    public function puestosGet():View
    {
        $puestos= puesto::all();
        return view('catalogos/puestosGet',[
            'puestos' => $puestos,
            "breadcrumbs"=>[
                "Inicio"=>url("/"),
                "Puestos"=>url("/catalogos/puestos")
            ]
            ]);
    }
    
    public function empleadosGet():View
    {
       $empleados = Empleado::all();
        return view('catalogos/empleadosGet', [
            'empleados' => $empleados,
            "breadcrumbs" => [
                "inicio" => url("/"),
                "Empleados" => url("/empleados")
            ]
            ]);
    }
    public function empleadosAgregarGet():View
    {
        $puestos=Puesto::all();  //obtenemos todos los puestos para mostrarlos en el select
        return view('catalogos/empleadosAgregarGet', [
            "puestos"=>$puestos,
            "breadcrumbs" => [
                "inicio" => url("/"),
                "Empleados" => url("/empleados"),
                "Agregar" => url("/empleados/agregar")
            ]
        ]);
    }
    public function empleadosAgregarPost(Request $request) {
        $nombre=$request->input("nombre");
        $fecha_ingreso=$request->input("fecha_ingreso");
        $activo=$request->input("activo");
        $empleado=new Empleado([
            "nombre"=>strtoupper($nombre),
            "fecha_ingreso"=>$fecha_ingreso,
            "activo"=>$activo
        ]);
        $empleado->save();
        $puesto=new det_emp_puesto([
            "id_empleado"=>$empleado->id_empleado,
            "id_puesto"=>$request->input("puesto"),
            "fecha_inicio"=>$fecha_ingreso
        ]);
        $puesto->save();
        return redirect("/empleados"); //redirige al listado de empleados
    }


    public function puestosAgregarGet():View
    {
        return view('catalogos/puestosAgregarGet', [
            "breadcrumbs" => [
                "inicio" => url("/"),
                "Puestos" => url("/catalogos/puestos"),
                "Agregar" => url("/catalogos/puestos/agregar")
            ]
        ]);
    }
    public function puestosAgregarPost(Request $request) {
        $nombre=$request->input("nombre");
        $sueldo=$request->input("sueldo");
        $puesto=new Puesto([
            "nombre"=>strtoupper($nombre),
            "sueldo"=>$sueldo
        ]);
        $puesto->save();
        return redirect("/catalogos/puestos/agregar");
    } 


public function empleadosPuestosGet(Request $request, $id_empleado)
{
    $puestos=Det_emp_puesto::join("puesto","puesto.id_puesto","=","det_emp_puesto.id_puesto")//
    ->select("det_emp_puesto.*","puesto.nombre as puesto","puesto.sueldo")
    ->where("det_emp_puesto.id_empleado","=",$id_empleado)
    ->get();
    $empleado=Empleado::find($id_empleado);
    return view('/catalogos/empleadosPuestosGet', [
        "puestos"=>$puestos,"empleado"=>$empleado,
        "breadcrumbs" => [
            "inicio" => url("/"),
            "Empleados" => url("/empleados"),
            "Puestos" => url("/catalogos/empleados/{id}/puestos")
        ]
    ]);
        
}
public function empleadosPuestosCambiarGet(Request $request,$id_empleado):View {
    $empleado=Empleado::find($id_empleado);
    $puestos=Puesto::all();
    return view('/catalogos/empleadosPuestosCambiarGet', [
        "puestos"=>$puestos,
        "empleado"=>$empleado,
        "breadcrumbs" => [
            "inicio" => url("/"),
            "Empleados" => url("/empleados"),
            "Puestos" => url("/catalogos/empleados/{$id_empleado}/puestos"),
            "Cambiar" => url("/catalogos/empleados/{$id_empleado}/puestos/cambiar")
        ]
        ]);
    }
public function empleadosPuestosCambiarPost(Request $request, $id_empleado)
    {
        $fecha_inicio = $request->input("fecha_inicio");
        $fecha_fin = (new DateTime($fecha_inicio))->modify("-1 day");
        $anterior = Det_emp_puesto::where("id_empleado", $id_empleado)
            ->whereNull("fecha_fin")
            ->update(["fecha_fin" => $fecha_fin->format("Y-m-d")]);
    
        /** Agregar el nuevo puesto */
        $puesto = new Det_emp_puesto([
            "id_empleado" => $id_empleado,
            "id_puesto" => $request->input("puesto"), /** Viene el dato del formulario */
            "fecha_inicio" => $fecha_inicio,
        ]);
        $puesto->save();
    
        return redirect("/empleados/{$id_empleado}/puestos");
    }
        
    
}