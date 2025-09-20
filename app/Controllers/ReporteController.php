<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use Exception;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
// use CodeIgniter\Config
class ReporteController extends BaseController
{   
    protected $db;
    public function __construct(){
        $this->db =  \Config\Database::connect(); //Cargar la base de datos
    }
    public function index()
    {
        $html = view('reportes/reporte1'); //Datos convertir aen pdf
        $html2pdf = new Html2Pdf(); //Libreria
        $html2pdf->writeHTML($html); //Contenido

        //Definir el tipo d arhivo que debera renderizar la vista (navegador)
        $this->response->setHeader('Content-Type', 'application/pdf');
        $html2pdf->output();
    }
    public function reporte2()
    {
        $data = [
            'area'=> 'Finanzas',
            'autor'=> 'Eduardo CL',
            'productos'=> [
                ['id'=>1, 'descripcion'=>'Monitor', 'precio'=>100],
                ['id'=>2, 'descripcion'=>'Impresora', 'precio'=>200],
                ['id'=>3, 'descripcion'=>'Web Cam', 'precio'=>100],
                ['id'=>4, 'descripcion'=>'Teclado', 'precio'=>250],
            ],
            'estilos'=> view('reportes/style'),
        ]; // Datos para la vista

        $html = view('reportes/reporte2', $data); //Datos convertir aen pdf

        try {
            $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', [10, 10, 10, 10]); //Libreria
            $html2pdf->writeHTML($html); //Contenido
            $this->response->setHeader('Content-Type', 'application/pdf');
            $html2pdf->output('Reporte-Finanzas.pdf');
        } catch (Html2PdfException $e) {
           $html2pdf -> clean();
           $formatter = new ExceptionFormatter($e);
           echo $formatter->getMessage();
        }
    }   

    public function reporte3()
    {   
        $query = "
        SELECT 
            SH.id,
            SH.superhero_name,
            SH.full_name,
            PB.publisher_name,
            AL.alignment
            FROM superhero SH
            LEFT JOIN publisher PB ON SH.publisher_id = PB.id
            LEFT JOIN alignment AL ON SH.alignment_id = AL.id
            ORDER BY 4  ASC
            LIMIT 100";
        $rows = $this->db->query($query);
        $data = [
            'row' => $rows->getResultArray(),
            'estilos'=> view('reportes/style'),
        ]; // Datos para la vista

        $html = view('reportes/reporte3', $data); //Datos convertir aen pdf

        try {
            $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', [10, 10, 10, 10]); //Libreria
            $html2pdf->writeHTML($html); //Contenido
            $this->response->setHeader('Content-Type', 'application/pdf');
            $html2pdf->output('Reporte-superheroes.pdf');
        } catch (Html2PdfException $e) {
           $html2pdf -> clean();
           $formatter = new ExceptionFormatter($e);
           echo $formatter->getMessage();
        }
    }   
}