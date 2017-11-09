<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class TipoConta_model extends CI_Model
{
    protected $table = 'tb_tipo_conta';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getTable() {
        return $this->table;
    }

    public function getTiposContas():array
    {
        $sql = "SELECT * FROM $this->table ORDER BY tipo_conta";
        return $this->db->query($sql)->result_array();
    }

}
