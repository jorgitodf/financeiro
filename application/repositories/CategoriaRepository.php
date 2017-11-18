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
    }

    public function checkCategoria()
    {
        $resultado = $this->select("SELECT * FROM {$this->categoria->getTable()}");
        return $resultado->result_array();
    }

    public function getCategoriasDespesas():array
    {
        $resultado = $this->select("SELECT * FROM {$this->categoria->getTable()} WHERE id_categoria <> 38 
            ORDER BY nome_categoria");
        return $resultado->result_array();
    }

    
    public function getCategoriasReceitas():array
    {
        $resultado = $this->select("SELECT id_categoria, nome_categoria FROM {$this->categoria->getTable()} 
            WHERE tipo = 'R'");
        return $resultado->result_array();
    }
}    