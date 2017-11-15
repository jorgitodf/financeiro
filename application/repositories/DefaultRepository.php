<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/../../system/core/Model.php';

class DefaultRepository extends CI_MODEL
{
   
    public function __construct()
    {
        $this->load->database();
    }

    public function conection($sql, $values = null)
    {
        return $this->db->query($sql, $values);
    }

    public function select($sql) 
    {
        if (!empty($sql)) {
            return $this->db->query($sql);
        }
    }

    public function selectWhereId($sql, $values) 
    {
        if (!empty($sql) && !empty($values)) {
            return $this->db->query($sql, $values);
        }
    }

    public function insert($table, $values) 
    {
        if (!empty($table) && !empty($values)) { 
            $this->db->insert($table, $values);
            if ($this->db->affected_rows() > 0) {
                return array('status' => 'success', 'message' => true);
            } else {
                return array('status' => 'error', 'message' => $this->db->error()["message"]);
            }
        }    
    }

}   