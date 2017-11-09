<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class BandeiraCartao_model extends CI_Model
{
	protected $table = 'tb_bandeira_cartao';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getTable() {
		return $this->table;
	}

	public function getBandeirasCartoes():array
	{
		$sql = "SELECT * FROM $this->table ORDER BY bandeira";
		return $this->db->query($sql)->result_array();
	}


}
