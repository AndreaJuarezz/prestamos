@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent

<div class="row">
    <div class="form-group my-3">
        <h1>Puestos del empleado</h1>
    </div>
    <div>Empleado:{{$empleado->nombre}}</div>
    <div class="col"></div>
    <div class="col-auto">
        <a class="btn btn-primary" href='{{ url("/catalogos/empleados/{$empleado->id_empleado}/puestos/cambiar") }}'>Cambiar</a> 

    </div>
</div>

<table class="table" id="maintable">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">PUESTO</th>
            <th scope="col">FECHA INICIO</th>
            <th scope="col">FECHA FIN</th>
        </tr>
    </thead>
<tbody>
@foreach($puestos as $puesto)
    <tr>
        <td class="text-center">{{$puesto->id_det_emp_puesto}}</td>
        <td class="text-center">{{$puesto->puesto}}</td>
        <td class="text-center">{{$puesto->fecha_inicio}}</td>
        <td class="text-center">{{$puesto->fecha_fin}}</td>
    </tr>
@endforeach
</tbody>
</table>
<script>
//Se crea la instancia de datatable conesos usos paginacion y buscador
//let table=new DataTable("#maintable",{paging:true,searching:true});
</script>
@endsection