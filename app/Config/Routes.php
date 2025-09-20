<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Reportes
$routes->get('/reporte/r1', 'ReporteController::index');
$routes->get('/reporte/r2', 'ReporteController::reporte2');
$routes->get('/reporte/r3', 'ReporteController::reporte3');

// Filtros
$routes->get('/filtros', 'FiltrosController::index');
$routes->post('/filtros/generarPDF', 'FiltrosController::generarPDF');

$routes->get('/test', 'TestController::index');

// Superhéroes - Nuevas rutas
$routes->get('superhero', 'SuperheroController::index');
$routes->get('superhero/search', 'SuperheroController::search');
$routes->get('superhero/powers/(:num)', 'SuperheroController::getSuperheroPowers/$1');
$routes->get('superhero/generate-pdf/(:num)', 'SuperheroController::generatePowersPDF/$1');

