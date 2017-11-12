<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class CartaoCredito_model extends CI_Model
{
	protected $table = 'tb_cartao_credito';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('banco_model');
		$this->load->model('bandeiracartao_model');
		$this->load->helper("funcoes");
		date_default_timezone_set('America/Sao_Paulo');
	}

	public function getTable() {
		return $this->table;
	}

	public function cadastrarCartaoCredito($dados)
	{
		if (empty($dados['numero_cartao'])) {
			return array('status' => 'error', 'message' => 'Preencha o Número do Cartão de Crédito!');
		} elseif ($this->consultaCartaoCredito($dados['numero_cartao']) != null) {
			return array('status'=>'error', 'message'=>'O Número do Cartão de Crédito já está Cadastrado!');
		} elseif (empty($dados['data_validade'])) {
			return array('status'=>'error', 'message'=>'Preencha a Data de Validade do Cartão de Crédito!');
		} elseif (empty($dados['fk_id_bandeira_cartao'])) {
			return array('status'=>'error', 'message'=>'Preencha a Bandeira do Cartão de Crédito!');
		} elseif (empty($dados['fk_cod_banco'])) {
			return array('status'=>'error', 'message'=>'Preencha o Banco do do Cartão de Crédito!');
		} else {
			$this->db->trans_begin();
			$sql = "INSERT INTO $this->table (numero_cartao, data_validade, fk_id_bandeira_cartao, fk_id_usuario, fk_cod_banco) 
					VALUES (?, ?, ?, ?, ?)";

			$this->db->query($sql, [removePontos($dados['numero_cartao']), $dados['data_validade'], $dados['fk_id_bandeira_cartao'],
				$dados['fk_id_usuario'], $dados['fk_cod_banco']]);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return array('status' => 'error', 'message' => 'Ops! Ocorreu algum erro no Cadastro do Cartão de Crédito!');
			} else {
				$this->db->trans_commit();
				return array('status' => 'success', 'message' => 'Cartão de Crédito Cadastrado com Sucesso!');
			}
		}
	}

	public function consultaCartaoCredito($num_cartao)
	{
		$sql = "SELECT numero_cartao FROM $this->table WHERE numero_cartao = ?";
		if (empty($this->db->query($sql, [removePontos($num_cartao)])->result_array()[0]['numero_cartao'])) {
			return null;
		} else {
			return $this->db->query($sql, [removePontos($num_cartao)])->result_array()[0]['numero_cartao'];
		}
	}

	public function getCartoesCreditosByUser(int $idUser): array
	{
		$sql = "SELECT cc.id_cartao_credito AS id_cartao, cc.numero_cartao AS numero, bc.bandeira, b.nome_banco 
			FROM $this->table cc JOIN {$this->bandeiracartao_model->getTable()} bc ON (bc.id_bandeira_cartao = cc.fk_id_bandeira_cartao)
			JOIN {$this->banco_model->getTable()} b ON (b.cod_banco = cc.fk_cod_banco) WHERE cc.fk_id_usuario = ? AND cc.ativo = 'S' 
			ORDER BY b.nome_banco ASC";

		if (empty($this->db->query($sql, [$idUser])->result_array())) {
			return false;
		} else {
			return $this->db->query($sql, [$idUser])->result_array();
		}
	}

	public function salvarCompraCartaoCredito($dados)
	{
		if (empty($dados['id_cartao'])) {
			return array('status' => 'error', 'message' => 'Informe o Cartão de Crédito!');
		} elseif (empty($dados['data_compra'])) {
			return array('status' => 'error', 'message' => 'Preencha a Data da Compra!');
		} elseif (empty($dados['despesa'])) {
			return array('status'=>'error', 'message'=>'Preencha a Descrição da Compra!');
		} elseif (empty($dados['valor_parcela'])) {
			return array('status'=>'error', 'message'=>'Preencha o Valor da Compra!');
		} else {
			$data_pagamento = date('Y-m-08', strtotime("+1 month"));
			$data_fechamento_fatura = date("Y-m-d", strtotime("-10 days", strtotime($data_pagamento)));
			if ($dados['data_compra'] < $data_fechamento_fatura) {
				$data_pagamento = date('Y-m-08', strtotime("+1 month"));
			} elseif ($dados['data_compra'] >= $data_fechamento_fatura) {
				$data_pagamento = date('Y-m-08', strtotime("+2 month"));
			}

			$data = [];		
			$mes = date("m");
			if ($parcela == 01) {
				$data[0]['data_compra'] = $dados['data_compra'];
				$data[0]['valor_parcela'] = number_format(formatarMoeda($dados['valor_parcela']) / $parcela, 2);
				$data[0]['parcela'] = "01/".$dados['parcela'];
				$data[0]['data_pagamento'] = $data_pagamento;
				$data[0]['despesa'] = $dados['despesa'];
				$data[0]['id_cartao'] = $dados['id_cartao'];
			} else {
				for($i=1; $i <= $dados['valor_parcela']; $i++) {
					$data[$i]['data_compra'] = $dados['data_compra'];
					$data[$i]['valor_parcela'] = number_format(formatarMoeda($dados['valor_parcela']) / $parcela, 2);
					$data[$i]['parcela'] = $i < 10 ? "0".$i."/".$dados['valor_parcela'] : $i."/".$dados['valor_parcela'];
					$data[$i]['data_pagamento'] = $data_pagamento;
					$data[$i]['despesa'] = $dados['despesa'];
					$data[$i]['id_cartao'] = $dados['id_cartao'];
				}	
			}

			$this->db->trans_begin();
			$sql = "INSERT INTO $this->table (numero_cartao, data_validade, fk_id_bandeira_cartao, fk_id_usuario, fk_cod_banco) 
					VALUES (?, ?, ?, ?, ?)";

			$this->db->query($sql, [removePontos($dados['numero_cartao']), $dados['data_validade'], $dados['fk_id_bandeira_cartao'],
				$dados['fk_id_usuario'], $dados['fk_cod_banco']]);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return array('status' => 'error', 'message' => 'Ops! Ocorreu algum erro no Cadastro do Cartão de Crédito!');
			} else {
				$this->db->trans_commit();
				return array('status' => 'success', 'message' => 'Cartão de Crédito Cadastrado com Sucesso!');
			}
		}
	}

}
