<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Categoria_model extends CI_Model
{
    protected $table = 'tb_categoria';
    private $id_categoria;
    private $nome_categoria;
    private $despesa_fixa;
    private $tipo;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getTable() {
        return $this->table;
    }
    
    public function getIdCategoria()
    {
        return $this->id_categoria;
    }
    public function setIdCategoria($id_categoria)
    {
        $this->id_categoria = $id_categoria;
    }


    public function getCategoriasDespesas():array
    {
        $sql = "SELECT * FROM $this->table WHERE id_categoria <> 38 ORDER BY nome_categoria";
        return $this->db->query($sql)->result_array();
    }

    public function getCategoriasReceitas():array
    {
        $sql = "SELECT id_categoria, nome_categoria FROM $this->table WHERE tipo = 'R'";
        return $this->db->query($sql)->result_array();
    }

    public function checkCategoria()
    {
        $sql = "SELECT * FROM $this->table";
        return $this->db->query($sql)->result_array();
    }

}
