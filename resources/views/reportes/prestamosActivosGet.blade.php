@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent

<div class="row my-4">
    <div class="col">
        <h1>Préstamos Activos</h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">NOMBRE</th>
                        <th class="text-center">PRESTADO</th>
                        <th class="text-center">CAPITAL</th>
                        <th class="text-center">INTERÉS</th>
                        <th class="text-center">COBRADO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prestamos as $prestamo)
                    <tr>
                        <td class="text-center">{{ $prestamo['id_prestamo'] }}</td>
                        <td>{{ $prestamo['nombre'] }}</td>
                        <td class="text-end">{{ number_format($prestamo['monto'], 2) }}</td>
                        <td class="text-end">{{ number_format($prestamo['total_capital'], 2) }}</td>
                        <td class="text-end">{{ number_format($prestamo['total_interes'], 2) }}</td>
                        <td class="text-end">{{ number_format($prestamo['total_cobrado'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold">
                        <td colspan="2" class="text-end">TOTAL</td>
                        <td class="text-end">{{ number_format(array_sum(array_column($prestamos, 'monto')), 2) }}</td>
                        <td class="text-end">{{ number_format(array_sum(array_column($prestamos, 'total_capital')), 2) }}</td>
                        <td class="text-end">{{ number_format(array_sum(array_column($prestamos, 'total_interes')), 2) }}</td>
                        <td class="text-end">{{ number_format(array_sum(array_column($prestamos, 'total_cobrado')), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection