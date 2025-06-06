<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ secure_asset('https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css') }}">


    <!--importar los archivos js de bootstrap-->
    <script src="{{ secure_asset('https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js') }}"></script>
    <!-- importar librerías de estilos y javascript de datatables para manipular tablas desde el
    navegador del usuario-->
    <link href={{ secure_asset('DataTables/datatables.min.css')}} rel="stylesheet"/>
    <script src={{ secure_asset('DataTables/datatables.min.js')}}></script>
    <link href={{secure_asset("assets/style.css")}} rel="stylesheet" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamos Coliman</title>
</head>
<body>
    <div class="row">
        <div class="col-2">
            @component("components.sidebar")
            @endcomponent
</div>
    <div class="col-10">
        <div class="container">
            @section("content")
            @show
    </div>
    </div>
    </div>
    </body>
</html>
