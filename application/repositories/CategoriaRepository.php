<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/DefaultRepository.php';
require_once __DIR__ . '/../models/Categoria_model.php';

class CategoriaRepository extends DefaultRepository
{
    protected $categoria;

    public function __construct()
    {
        $this->categoria = new Categoria_model();
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function checkCategoria()
    {
        $resultado = $this->select("SELECT * FROM {$this->extrato->getTable()}");
        return $resultado->result_array();
    }

 
}    