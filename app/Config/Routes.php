<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Reportes
$routes->get('/reporte/r1', 'ReporteController::index');
$routes->get('/reporte/r2', 'ReporteController::reporte2');
$routes->get('/reporte/r3', 'ReporteController::reporte3');


$routes->get('/filtros', 'FiltrosController::index');
$routes->post('/filtros/generarPDF', 'FiltrosController::generarPDF');