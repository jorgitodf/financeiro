<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class DespesaCartao_model extends CI_Model
{
	protected $table = 'tb_despesa_cartao';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('Banco_model');
		$this->load->model('BandeiraCartao_model');
		$this->load->helper("funcoes");
	}

	public function getTable() {
		return $this->table;
	}

	public function salvarDespesaCartaoCredito($dados)
	{
		if (empty($dados['id_cartao'])) {
			return array('status' => 'error', 'message' => 'Informe o Cartão de Crédito!');
		} elseif (empty($dados['data_compra'])) {
			return array('status' => 'error', 'message' => 'Preencha a Data da Compra!');
		} elseif (empty($dados['despesa'])) {
			return array('status'=>'error', 'message'=>'Preencha a Descrição da Compra!');
		} elseif (empty($dados['valor_parcela'])) {
			return array('status'=>'error', 'message'=>'Preencha o Valor da Compra!');
		} elseif (empty($dados['parcela'])) {
			return array('status'=>'error', 'message'=>'Informe a quantidade de Parcela(s)!');
		} else {

			$dia_compra = date('d', strtotime($dados['data_compra']));
			$mes_compra = date('m', strtotime($dados['data_compra']));
			$ano_compra = date('Y', strtotime($dados['data_compra']));

			if ($dados['id_cartao'] == 1 && $dia_compra <= 26) {
				$data_pagamento = date('Y-m-d', strtotime("+1 month", strtotime("{$ano_compra}-{$mes_compra}-08")));
			} else if ($dados['id_cartao'] == 1 && $dia_compra > 26) {
				$data_pagamento = date('Y-m-d', strtotime("+2 month", strtotime("{$ano_compra}-{$mes_compra}-08")));
			} else if ($dados['id_cartao'] == 2 && $dia_compra <= 25) {
				$data_pagamento = date('Y-m-d', strtotime("+1 month", strtotime("{$ano_compra}-{$mes_compra}-08")));
			} else if ($dados['id_cartao'] == 2 && $dia_compra > 25) {
				$data_pagamento = date('Y-m-d', strtotime("+2 month", strtotime("{$ano_compra}-{$mes_compra}-08")));
			} else if ($dados['id_cartao'] == 3 && ($dia_compra >= 1 && $dia_compra <= 2)) {
				$data_pagamento = date('Y-m-09');
			} else if ($dados['id_cartao'] == 3 && ($dia_compra > 2 && $dia_compra <= 31)) {
				$data_pagamento = date('Y-m-d', strtotime("+1 month", strtotime("{$ano_compra}-{$mes_compra}-09")));
			}
			
			$data = [];		
			
			if ($dados['parcela'] == 01) {
				$data[0]['data_compra'] = $dados['data_compra'];
				$data[0]['valor_parcela'] = number_format(formatarMoeda($dados['valor_parcela']) / $dados['parcela'], 2);
				$data[0]['parcela'] = "01/".$dados['parcela'];
				$data[0]['data_pagamento'] = $data_pagamento;
				$data[0]['despesa'] = $dados['despesa'];
				$data[0]['id_cartao'] = $dados['id_cartao'];
			} else {
				$qtd_parcelas = (int) $dados['parcela'];
				for($i=1; $i <= $dados['parcela']; $i++) {
					$m = $i-1;
					$n = $i+1;
					$data[$i]['data_compra'] = $dados['data_compra'];
					$data[$i]['valor_parcela'] = formatarMoeda($dados['valor_parcela']) / $qtd_parcelas;
					$data[$i]['parcela'] = $i < 10 ? "0".$i."/".$dados['parcela'] : $i."/".$dados['parcela'];

					if ($dados['id_cartao'] == 1 && $dia_compra <= 26) {
						$data[$i]['data_pagamento'] = date('Y-m-d', strtotime("+{$n} month", strtotime("{$ano_compra}-{$mes_compra}-08")));
					} else if ($dados['id_cartao'] == 1 && $dia_compra > 26) {
						$data[$i]['data_pagamento'] = date('Y-m-d', strtotime("+{$n} month", strtotime("{$ano_compra}-{$mes_compra}-08")));
					} else if ($dados['id_cartao'] == 2 && $dia_compra <= 24) {
						$data[$i]['data_pagamento'] = date('Y-m-d', strtotime("+{$n} month", strtotime("{$ano_compra}-{$mes_compra}-08")));
					} else if ($dados['id_cartao'] == 2 && $dia_compra > 24) {
						$data[$i]['data_pagamento'] = date('Y-m-d', strtotime("+{$n} month", strtotime("{$ano_compra}-{$mes_compra}-08")));
					} else if ($dados['id_cartao'] == 3 && ($dia_compra >= 1 && $dia_compra <= 2)) {
						$data[$i]['data_pagamento'] = date('Y-m-d', strtotime("+{$n} month", strtotime("{$ano_compra}-{$mes_compra}-09")));
					} else if ($dados['id_cartao'] == 3 && ($dia_compra > 2 && $dia_compra <= 31)) {
						$data[$i]['data_pagamento'] = date('Y-m-d', strtotime("+{$n} month", strtotime("{$ano_compra}-{$mes_compra}-09")));
					}
					
					$data[$i]['despesa'] = $dados['despesa'];
					$data[$i]['id_cartao'] = $dados['id_cartao'];
					
				}	
			}
			$this->db->trans_begin();

			foreach($data as $linha) {
				$sql = "INSERT INTO $this->table (despesa, data_compra, valor_compra, parcela, data_pagamento, fk_id_cartao_credito) 
				VALUES (?, ?, ?, ?, ?, ?)";

				$this->db->query($sql, [$linha['despesa'], $linha['data_compra'], $linha['valor_parcela'], $linha['parcela'], 
					$linha['data_pagamento'], $linha['id_cartao']]);
			}

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return array('status' => 'error', 'message' => 'Ops! Ocorreu algum erro no Cadastro do Cartão de Crédito!');
			} else {
				$this->db->trans_commit();
				return array('status' => 'success', 'message' => 'Despesa Cadastrada com Sucesso!');
			}

		}
	}

	public function getItensDespesaFaturaByIdFaturaCartao(int $idFatura, string $data_pagamento):array
    {
        $sql = "SELECT id_item_despesa_fatura AS id_item_desp, despesa, data_compra
		AS data_compra, valor_compra, parcela FROM $this->table 
		WHERE data_pagamento = ? AND fk_id_cartao_credito = ? ORDER BY data_compra ASC";
		return $this->db->query($sql, [$data_pagamento, $idFatura])->result_array();
    }

}
