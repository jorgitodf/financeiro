<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/../../system/core/Model.php';

class Agendamento_model extends CI_Model
{
	protected $table = 'tb_pgto_agendado';
	private $id_pgto_agendado;
	private $data_pagamento;
	private $movimentacao;
	private $valor;
	private $pago;
	private $categoria;
	private $conta;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('Categoria_model');
		$this->conta = new Conta_model();
        $this->categoria = new Categoria_model();
		$this->load->helper("funcoes");
	}

	public function getTable() {
		return $this->table;
	}

	public function getIdPgtoAgendado()
    {
        return $this->id_pgto_agendado;
    }
    public function setIdPgtoAgendado($id_pgto_agendado)
    {
        $this->id_pgto_agendado = $id_pgto_agendado;
	}
	
	public function getDataPagamento()
    {
        return $this->data_pagamento;
    }
    public function setDataPagamento($data_pagamento)
    {
        $this->data_pagamento = $data_pagamento;
	}
	
	public function getMovimentacao()
    {
        return $this->movimentacao;
    }
    public function setMovimentacao($movimentacao)
    {
        $this->movimentacao = $movimentacao;
    }

	public function getValor()
    {
        return $this->valor;
    }
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

	public function getPago()
    {
        return $this->pago;
    }
    public function setPago($pago)
    {
        $this->pago = $pago;
	}
	
	public function getCategoria()
    {
        return $this->categoria;
    }

    public function getConta()
    {
        return $this->conta;
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
			JOIN {$this->Categoria_model->getTable()} as cat ON (pgto.fk_id_categoria = 
            cat.id_categoria) ORDER BY pgto.data_pagamento ASC LIMIT $offset, 15";
        return $this->db->query($sql, [$offset])->result_array();
	}
	
	public function getPagamentoAgendado($id) {
		$sql = "SELECT * FROM $this->table WHERE id_pgto_agendado = ?";
		return $this->db->query($sql, [$id])->result_array();
	}
	
	public function alterarPgtoAgendado($dados) {
		if (empty($dados['dt_pgto'])) {
			return array('status' => 'error', 'message' => 'Informe e Data do Pagamento!');
		} elseif (empty($dados['mov_pgto'])) {
			return array('status'=>'error', 'message'=>'Informe a Movimentação!');
		} elseif (empty($dados['valor_pgto'])) {
			return array('status'=>'error', 'message'=>'Informe o Valor da Movimentação!');
		} elseif (empty($dados['categoria_pgto'])) {
			return array('status'=>'error', 'message'=>'Informe a Categoria!');
		} else {
			$sql = "UPDATE $this->table SET data_pagamento = ?, movimentacao = ?, valor = ?, pago = ?, fk_id_categoria = ?, 
			fk_id_conta = ? WHERE id_pgto_agendado = ?";

			$result = $this->db->query($sql, ['data_pagamento'=>$dados['dt_pgto'] , 'movimentacao'=>$dados['mov_pgto'] , 
			'valor'=>formatarMoeda($dados['valor_pgto']) , 'pago'=> "Não", 'fk_id_categoria'=>$dados['categoria_pgto'] , 
			'fk_id_conta'=>$dados['idConta'] , 'id_pgto_agendado'=>$dados['idPgtoAgendado']]);

			if ($result === FALSE) {
				return array('status' => 'error', 'message' => 'Ocorreu algum erro na Alteração do Pagamento Agendado!');
			} else {
				return array('status' => 'success', 'message' => 'Pagamento Agendado Alterado com Sucesso!');
			}
		}
	}
	

	public function getContasAgendadas(int $idConta): array {
		$ano = date("Y");
		$mes = verificaMesNumerico();
        $sql = "SELECT pgag.id_pgto_agendado AS idpgag, pgag.data_pagamento AS data, pgag.movimentacao AS mov, 
			pgag.valor AS valor, pgag.pago AS pago FROM $this->table AS pgag WHERE pgag.fk_id_conta = ? 
			AND pgag.data_pagamento BETWEEN '{$ano}-{$mes}-01' AND '{$ano}-{$mes}-31' 
			ORDER BY data_pagamento ASC";
		return $this->db->query($sql, [$idConta])->result_array();
    }

}
