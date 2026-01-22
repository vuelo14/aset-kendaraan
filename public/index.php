<?php
session_start();
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Autoload.php';

use Core\Router;

$router = new Router();

$router->get('/', 'DashboardController@index');
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@doLogin');
$router->get('/logout', 'AuthController@logout');

$router->get('/vehicles', 'VehicleController@index');
$router->get('/vehicles/create', 'VehicleController@create');
$router->post('/vehicles/store', 'VehicleController@store');
$router->get('/vehicles/edit', 'VehicleController@edit');
$router->post('/vehicles/update', 'VehicleController@update');
$router->post('/vehicles/delete', 'VehicleController@delete');
$router->get('/vehicles/show', 'VehicleController@show');

$router->get('/maintenance', 'MaintenanceController@index');
$router->post('/maintenance/store', 'MaintenanceController@store');
$router->get('/maintenance/byVehicle', 'MaintenanceController@byVehicle');

$router->get('/tax', 'TaxController@index');
$router->post('/tax/store', 'TaxController@store');
$router->get('/tax/byVehicle', 'TaxController@byVehicle');

$router->get('/schedule/maintenance', 'ScheduleController@maintenance');
$router->post('/schedule/maintenance/store', 'ScheduleController@storeMaintenance');
$router->get('/schedule/tax', 'ScheduleController@tax');
$router->post('/schedule/tax/store', 'ScheduleController@storeTax');

$router->get('/users', 'UserController@index');
$router->post('/users/store', 'UserController@store');

$router->get('/import', 'ImportExportController@index');
$router->post('/import/csv', 'ImportExportController@importCSV');
$router->get('/export/vehicles/csv', 'ImportExportController@exportVehiclesCSV');
$router->get('/export/vehicles/pdf', 'ImportExportController@exportVehiclesPDF');

$router->get('/backup', 'BackupController@index');
$router->get('/backup/run', 'BackupController@run');
$router->post('/restore/upload', 'BackupController@restore');

$router->get('/usage', 'UsageController@index');
$router->post('/usage/store', 'UsageController@store');
$router->get('/usage/byVehicle', 'UsageController@byVehicle');
$router->get('/usage/create', 'UsageController@create');



$router->dispatch();
