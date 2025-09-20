<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
class FiltrosController extends Controller
{   protected $db;
    public function __construct(){
        $this->db =  \Config\Database::connect(); //Cargar la base de datos
    }
    public function index()
    {    
       $query = "
        SELECT 
            PB.publisher_name
            FROM publisher PB
            ORDER BY PB.publisher_name ASC";
      $result = $this->db->query($query);
      $data['publishers'] = $result->getResult();

      return view('filtros', $data);
    }

    public function generarPDF()
    {
        try {
            // Obtener datos del formulario
            $editorial = $this->request->getPost('publisher');
            
            // Debug: verificar que se recibe el dato
            if (empty($editorial)) {
                throw new \Exception('No se seleccionó ningún publisher');
            }
            
            // Consultar superhéroes según el publisher seleccionado
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
                WHERE PB.publisher_name = ?
                ORDER BY SH.superhero_name ASC
                LIMIT 100";
            
            $result = $this->db->query($query, [$editorial]);
            $superheroes = $result->getResult();
            
            // Debug: verificar cantidad de resultados
            $count = count($superheroes);
            
            // Preparar datos para la vista
            $data = [
                'editorial' => $editorial,
                'superheroes' => $superheroes,
                'count' => $count,
                'estilos' => view('reportes/style'),
            ];
            
            // Generar HTML usando la vista
            $html = view('Reportes/reportePublisher', $data);
            
            // Generar el PDF usando Html2Pdf
            $html2pdf = new Html2Pdf('P', 'A4', 'es');
            $html2pdf->writeHTML($html);
            $this->response->setHeader('Content-Type', 'application/pdf');
            $html2pdf->output('superheroes_' . preg_replace('/[^a-zA-Z0-9]/', '_', $editorial) . '.pdf');
            
        } catch (Html2PdfException $e) {
            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        } catch (\Exception $e) {
            echo '<h2>Error:</h2><p>' . $e->getMessage() . '</p>';
            echo '<p><a href="/filtros">Volver al formulario</a></p>';
        }
    }
    
}