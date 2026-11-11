# Sistema de Préstamos para Empleados

Sistema web para llevar el control de los préstamos que una empresa otorga a sus empleados. El monto que se puede prestar depende del sueldo de cada empleado, y solo se permite un préstamo activo a la vez.

Proyecto académico de Ingeniería Informática, Tecnológico Nacional de México, Campus Colima. Desplegado en Railway (abril 2025).

## Qué hace

**Gestión de empleados**
- Registrar empleados nuevos.
- Dar de baja empleados.
- Modificar el sueldo de un empleado.

**Gestión de puestos**
- Crear y consultar puestos.
- Asignar un puesto a un empleado y cambiarlo cuando haga falta.

**Préstamos**
- Solicitar un préstamo: el monto máximo depende del sueldo del empleado.
- Un solo préstamo activo por empleado.
- Cálculo de los pagos mensuales.
- Historial por empleado: préstamo activo y préstamos anteriores.

**Reportes**
- Reportes de préstamos otorgados.

## Tecnologías

- Laravel (PHP y plantillas Blade)
- JavaScript
- CSS
- Railway (despliegue)

## Cómo ejecutarlo

> Ajusta estos pasos si tu configuración es distinta.

```bash
# 1. Clona el repositorio
git clone https://github.com/AndreaJuarezz/prestamos.git
cd prestamos

# 2. Instala las dependencias
composer install
npm install

# 3. Configura las variables de entorno
cp .env.example .env
php artisan key:generate
# Edita .env con los datos de tu base de datos

# 4. Crea las tablas
php artisan migrate

# 5. Inicia el servidor
php artisan serve
```

## Autora

**Andrea Guadalupe Juárez Nava**
[github.com/AndreaJuarezz](https://github.com/AndreaJuarezz)
