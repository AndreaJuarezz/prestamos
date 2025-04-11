<?php
use App\Http\Controllers\CatalogosController;
use App\Http\Controllers\MovimientosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReportesController;

Route::get('/', function () {
    return view('home',["breadcrumbs"=>[]]);
});
Route::get ("/catalogos/puestos", [CatalogosController::class, "puestosGet"]);
Route::get ("/empleados", [CatalogosController::class, "empleadosGet"]);

Route::get ("/catalogos/puestos/agregar", [CatalogosController::class, "puestosAgregarGet"]);
Route::post ("/catalogos/puestos/agregar", [CatalogosController::class, "puestosAgregarPost"]);

Route::get("/empleados/agregar", [CatalogosController::class, "empleadosAgregarGet"]);
Route::post("/empleados/agregar", [CatalogosController::class, "empleadosAgregarPost"]);

Route::get("/catalogos/empleados/{id_empleado}/puestos", [CatalogosController::class, 'empleadosPuestosGet'])->where("id", "[0-9]+");
Route::get("/catalogos/empleados/{id_empleado}/puestos/cambiar", [CatalogosController::class, 'empleadosPuestosCambiarGet'])->where("id_empleado", "[0-9]+");
Route::post("/catalogos/empleados/{id_empleado}/puestos/cambiar", [CatalogosController::class, 'empleadosPuestosCambiarPost'])->where("id_empleado", "[0-9]+");



//movimientos controller
Route::get("/movimientos/prestamos",[MovimientosController::class, "prestamosGet"]);
Route::get("/movimientos/prestamos/agregar",[MovimientosController::class, "prestamosAgregarGet"]);
Route::post("/movimientos/prestamos/agregar",[MovimientosController::class, "prestamosAgregarPost"]);
Route::get("/movimientos/prestamos/{prest}/abonos",[MovimientosController::class, "abonosGet"])->where("prest","\\d+");
Route::get('/prestamos/{id}/abonos/agregar', [MovimientosController::class, 'abonosAgregarGet'])->name('abonos.agregar.get');

Route::post("/prestamos/{id}/abonos/agregar", [MovimientosController::class, "abonosAgregarPost"])->where("id", "\\d+");
Route::get('/movimientos/empleados/{id_empleado}', [MovimientosController::class, 'empleadosPrestamosGet']);


//reportes
Route::get("/reportes",[ReportesController::class,"indexGet"]);
Route::get("/reportes/prestamos-activos",[ReportesController::class,"prestamosActivosGet"]);
Route::get("/reportes/matriz-abonos",[ReportesController::class,"matrizAbonosGet"]);

//login controller y register
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);