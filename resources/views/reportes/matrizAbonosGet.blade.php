@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent

<div class="row my-4">
    <div class="col">
        <h1>Resumen de Abonos Cobrados</h1>
    </div>
</div>

<form class="card p-4 my-4" action="{{ url('/reportes/matriz-abonos') }}" method="get">
    <div class="row">
        <div class="col form-group">
            <label for="txtFecha">Fecha inicio</label>
            <input class="form-control" type="date" name="fecha_inicio" id="txtFecha" value="{{ $fecha_inicio ?? '' }}">
        </div>
        <div class="col form-group">
            <label for="txtFecha">Fecha fin</label>
            <input class="form-control" type="date" name="fecha_fin" id="txtFecha" value="{{ $fecha_fin ?? '' }}">
        </div>
    </div>
    <div class="row col-auto">
        <button type="submit" class="btn-primary btn">Filtrar</button>
    </div>
</form>

<table class="table" id="maintable">
    <thead>
        <tr>
            <th>ID</th>
            <th>NOMBRE</th>
            @foreach($fechas as $fecha)
                <th>{{ $fecha }}</th>
            @endforeach
            <th>COBRADO</th>
        </tr>
    </thead>
    <tbody>
        @foreach($abonosGrouped as $id_prestamo => $abonos)
        <tr>
            <td>{{ $id_prestamo }}</td>
            <td>{{ $abonos->first()['nombre'] }}</td>
            @foreach($fechas as $fecha)
                <td class="text-end">
                    {{ number_format($abonos->where('fecha', $fecha)->sum('monto_cobrado'), 2) }}
                </td>
            @endforeach
            <td class="text-end">
                {{ number_format($abonos->sum('monto_cobrado'), 2) }}
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td class="text-end" colspan="2">TOTAL</td>
            @foreach($fechas as $fecha)
                <td class="text-end">
                    {{ number_format($abonosGrouped->flatten(1)->where('fecha', $fecha)->sum('monto_cobrado'), 2) }}
                </td>
            @endforeach
            <td class="text-end">
                {{ number_format($abonosGrouped->flatten(1)->sum('monto_cobrado'), 2) }}
            </td>
        </tr>
    </tfoot>
</table>
@endsection