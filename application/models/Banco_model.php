<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Banco_model extends CI_Model
{
    protected $table = 'tb_banco';

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }

        public function getTable() {
            return $this->table;
        }

        public function getBancos():array
        {
            $sql = "SELECT * FROM $this->table ORDER BY nome_banco";
            return $this->db->query($sql)->result_array();
        }

}
