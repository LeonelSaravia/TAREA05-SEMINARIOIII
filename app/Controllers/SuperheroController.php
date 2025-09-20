<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;

class SuperheroController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper('url');
    }

    public function index()
    {
        return view('superhero_search');
    }

    public function search()
    {
        // Solo permitir solicitudes AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Solicitud no válida']);
        }

        $searchTerm = $this->request->getGet('term');

        if (empty($searchTerm) || strlen($searchTerm) < 2) {
            return $this->response->setJSON(['error' => 'Ingresa al menos 2 caracteres']);
        }

        try {
            $builder = $this->db->table('superhero s');
            $builder->select('s.id, s.superhero_name, s.full_name, p.publisher_name, a.alignment');
            $builder->join('publisher p', 's.publisher_id = p.id', 'left');
            $builder->join('alignment a', 's.alignment_id = a.id', 'left');
            $builder->like('s.superhero_name', $searchTerm);
            $builder->orLike('s.full_name', $searchTerm);
            $builder->limit(10);
            
            $query = $builder->get();
            $superheroes = $query->getResult();

            return $this->response->setJSON($superheroes);
            
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function getSuperheroPowers($id)
    {
        try {
            // Información del superhéroe
            $builder = $this->db->table('superhero s');
            $builder->select('s.*, p.publisher_name, a.alignment, g.gender, r.race');
            $builder->join('publisher p', 's.publisher_id = p.id', 'left');
            $builder->join('alignment a', 's.alignment_id = a.id', 'left');
            $builder->join('gender g', 's.gender_id = g.id', 'left');
            $builder->join('race r', 's.race_id = r.id', 'left');
            $builder->where('s.id', $id);
            
            $heroQuery = $builder->get();
            $superhero = $heroQuery->getRow();

            if (!$superhero) {
                return $this->response->setJSON(['error' => 'Superhéroe no encontrado']);
            }

            // Poderes del superhéroe
            $powersBuilder = $this->db->table('hero_power hp');
            $powersBuilder->select('sp.power_name');
            $powersBuilder->join('superpower sp', 'hp.power_id = sp.id');
            $powersBuilder->where('hp.hero_id', $id);
            $powersBuilder->orderBy('sp.power_name');
            
            $powersQuery = $powersBuilder->get();
            $powers = $powersQuery->getResult();

            return $this->response->setJSON([
                'superhero' => $superhero,
                'powers' => $powers
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function generatePowersPDF($id)
    {
        try {
            // Obtener información del superhéroe
            $builder = $this->db->table('superhero s');
            $builder->select('s.superhero_name, s.full_name, p.publisher_name, a.alignment');
            $builder->join('publisher p', 's.publisher_id = p.id', 'left');
            $builder->join('alignment a', 's.alignment_id = a.id', 'left');
            $builder->where('s.id', $id);
            
            $query = $builder->get();
            $superhero = $query->getRow();

            if (!$superhero) {
                throw new \Exception('Superhéroe no encontrado');
            }

            // Obtener poderes
            $powersBuilder = $this->db->table('hero_power hp');
            $powersBuilder->select('sp.power_name');
            $powersBuilder->join('superpower sp', 'hp.power_id = sp.id');
            $powersBuilder->where('hp.hero_id', $id);
            $powersBuilder->orderBy('sp.power_name');
            
            $powersQuery = $powersBuilder->get();
            $powers = $powersQuery->getResult();

            // Datos para la vista
            $data = [
                'superhero' => $superhero,
                'powers' => $powers,
                'title' => 'Poderes de ' . $superhero->superhero_name
            ];

            // Generar PDF
            $html = view('superhero_powers_pdf', $data);
            $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', [10, 10, 10, 10]);
            $html2pdf->writeHTML($html);
            
            // Descargar PDF
            $filename = 'poderes_' . url_title($superhero->superhero_name) . '.pdf';
            $html2pdf->output($filename, 'D');

        } catch (Html2PdfException $e) {
            echo "Error al generar PDF: " . $e->getMessage();
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}