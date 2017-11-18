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
			$data_pgto = date('Y-m-08', strtotime("+1 month"));
			$data_fechamento_fatura = date("Y-m-d", strtotime("-11 days", strtotime($data_pgto)));
			if ($dados['data_compra'] <= $data_fechamento_fatura) {
				$data_pagamento = $data_pgto;
			} elseif ($dados['data_compra'] > $data_fechamento_fatura) {
				$data_pagamento = date('Y-m-d', strtotime("+1 month", strtotime($data_pgto)));
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
					$data[$i]['data_compra'] = $dados['data_compra'];
					$data[$i]['valor_parcela'] = formatarMoeda($dados['valor_parcela']) / $qtd_parcelas;
					$data[$i]['parcela'] = $i < 10 ? "0".$i."/".$dados['parcela'] : $i."/".$dados['parcela'];
					$data[$i]['data_pagamento'] = date('Y-m-d', strtotime("+{$i} month", strtotime($data_pagamento)));
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
        $sql = "SELECT id_item_despesa_fatura AS id_item_desp, despesa, DATE_FORMAT(data_compra,'%d/%m/%Y') 
		AS data_compra, valor_compra, parcela FROM $this->table 
		WHERE data_pagamento = ? AND fk_id_cartao_credito = ? ORDER BY data_compra ASC";
		return $this->db->query($sql, [$data_pagamento, $idFatura])->result_array();
    }

}
