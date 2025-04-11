@extends("components.layout")

@section("content")
    @component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
    @endcomponent

    <div class="row">
        <div class="form-group my-3">
            <h1>Préstamos del empleado</h1>
        </div>
    </div>

    {{-- Verifica si el empleado existe --}}
    @if($empleado)
        <div class="card p-4">
            <div class="row">
                <div class="col-2">Empleado:</div>
                <div class="col">{{ $empleado->nombre }}</div>
            </div>
        </div>

        <table class="table" id="maintable">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">MONTO</th>
                    <th scope="col">FECHA INICIO</th>
                    <th scope="col">FECHA FIN</th>
                    <th scope="col">ESTADO</th>
                    <th scope="col">DETALLES</th>
                </tr>
            </thead>
            <tbody>
                {{-- Bucle que recorre todos los préstamos del empleado --}}
                @foreach($prestamos as $prestamo)
                    <tr>
                        <td class="text-center">{{ $prestamo->id_prestamo }}</td>
                        <td class="text-center">${{ number_format($prestamo->monto, 2) }}</td>
                        <td class="text-center">{{ $prestamo->fecha_ini_desc }}</td>
                        <td class="text-center">{{ $prestamo->fecha_fin_desc }}</td>
                        <td class="text-center">{{ $prestamo->estado }}</td>
                        <td class="text-center">
                        <a href="{{ url('/movimientos/prestamos/' . $prestamo->id_prestamo . '/abonos') }}" >Detalles</a>


                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger">
            El empleado no existe o no se encontró en la base de datos.
        </div>
    @endif

    @push('scripts')
        <script>
            let table = new DataTable("#maintable", {
                paging: true,
                searching: true
            });
        </script>
    @endpush
@endsection
