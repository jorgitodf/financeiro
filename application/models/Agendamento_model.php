<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Agendamento_model extends CI_Model
{
	protected $table = 'tb_pgto_agendado';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('categoria_model');
		$this->load->helper("funcoes");
		date_default_timezone_set('America/Sao_Paulo');
	}

	public function getTable() {
		return $this->table;
	}

	public function cadastrarPgtoAgendado($dados)
	{
		if (empty($dados['data_pagamento'])) {
			return array('status' => 'error', 'message' => 'Informe e Data do Pagamento!');
		} elseif (empty($dados['movimentacao'])) {
			return array('status'=>'error', 'message'=>'Informe a Movimentação!');
		} elseif (empty($dados['valor'])) {
			return array('status'=>'error', 'message'=>'Informe o Valor da Movimentação!');
		} elseif (empty($dados['categoria'])) {
			return array('status'=>'error', 'message'=>'Informe a Categoria!');
		} else {
			$this->db->trans_begin();
			$sql = "INSERT INTO $this->table (data_pagamento, movimentacao, valor, pago, fk_id_categoria, fk_id_conta) 
					VALUES (?, ?, ?, ?, ?, ?)";

			$this->db->query($sql, [$dados['data_pagamento'], $dados['movimentacao'], formatarMoeda($dados['valor']),
				"Não", $dados['categoria'], $dados['idConta']]);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return array('status' => 'error', 'message' => 'Ocorreu algum erro no Cadastro do Agendamento de Pagamento!');
			} else {
				$this->db->trans_commit();
				return array('status' => 'success', 'message' => 'Agendamento de Pagamento Cadastrado com Sucesso!');
			}
		}
	}

	public function getCount() {
		$r = 0;
		$sql = "SELECT COUNT(*) as c FROM $this->table";
		$row = $this->db->query($sql)->result_array();
        $r = $row[0]['c'];
        return $r;
    }

	public function getAllPgamentosAgendados($offset) {
        $sql = "SELECT pgto.id_pgto_agendado as id, DATE_FORMAT(pgto.data_pagamento,'%d/%m/%Y') as data_pg, 
            lower(movimentacao) as mov, Replace(concat('R$ ', Format(pgto.valor, 2)),'.',',') as valor, 
			pgto.pago as pg, cat.nome_categoria as categoria FROM $this->table as pgto 
			JOIN {$this->categoria_model->getTable()} as cat ON (pgto.fk_id_categoria = 
            cat.id_categoria) ORDER BY pgto.data_pagamento ASC LIMIT $offset, 15";
        return $this->db->query($sql, [$offset])->result_array();
    }

}
