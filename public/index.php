<?php
session_start();
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';
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
$router->get('/maintenance/edit', 'MaintenanceController@edit');
$router->post('/maintenance/update', 'MaintenanceController@update');
$router->post('/maintenance/delete', 'MaintenanceController@delete');
$router->get('/maintenance/byVehicle', 'MaintenanceController@byVehicle');
$router->get('/maintenance/details', 'MaintenanceController@details');
$router->post('/maintenance/details/store', 'MaintenanceController@storeDetail');
$router->post('/maintenance/details/delete', 'MaintenanceController@deleteDetail');
$router->get('/maintenance/nota', 'MaintenanceController@nota');

$router->get('/komponen', 'KomponenController@index');
$router->post('/komponen/store', 'KomponenController@store');
$router->get('/komponen/edit', 'KomponenController@edit');
$router->post('/komponen/update', 'KomponenController@update');
$router->post('/komponen/delete', 'KomponenController@delete');

$router->get('/budget', 'BudgetController@index');
$router->get('/budget/edit', 'BudgetController@edit');
$router->post('/budget/update', 'BudgetController@update');


$router->get('/tax', 'TaxController@index');
$router->post('/tax/store', 'TaxController@store');
$router->get('/tax/edit', 'TaxController@edit');
$router->post('/tax/update', 'TaxController@update');
$router->post('/tax/delete', 'TaxController@delete');
$router->get('/tax/byVehicle', 'TaxController@byVehicle');

$router->get('/schedule/maintenance', 'ScheduleController@maintenance');
$router->post('/schedule/maintenance/store', 'ScheduleController@storeMaintenance');
$router->get('/schedule/maintenance/edit', 'ScheduleController@editMaintenance');
$router->post('/schedule/maintenance/update', 'ScheduleController@updateMaintenance');
$router->post('/schedule/maintenance/delete', 'ScheduleController@deleteMaintenance');

$router->get('/schedule/tax', 'ScheduleController@tax');
$router->post('/schedule/tax/store', 'ScheduleController@storeTax');
$router->get('/schedule/tax/edit', 'ScheduleController@editTax');
$router->post('/schedule/tax/update', 'ScheduleController@updateTax');
$router->post('/schedule/tax/delete', 'ScheduleController@deleteTax');
$router->post('/schedule/tax/complete', 'ScheduleController@completeTax');
$router->get('/schedule/tax/export', 'ScheduleController@exportTaxPdf');

$router->get('/users', 'UserController@index');
$router->post('/users/store', 'UserController@store');
$router->get('/users/edit', 'UserController@edit');
$router->post('/users/update', 'UserController@update');
$router->post('/users/delete', 'UserController@delete');

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
$router->get('/usage/edit', 'UsageController@edit');
$router->post('/usage/update', 'UsageController@update');
$router->post('/usage/delete', 'UsageController@delete');
$router->get('/logs', 'LogController@index');


$router->dispatch();
