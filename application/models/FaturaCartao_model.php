<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class FaturaCartao_model extends CI_Model
{
	protected $table = 'tb_fatura_cartao';

	public function __construct()
	{
		parent::__construct();
        $this->load->database();
        $this->load->model('CartaoCredito_model');
        $this->load->model('Banco_model');
        $this->load->model('BandeiraCartao_model');
        $this->load->model('ItensFaturaDespesas_model');
        $this->load->model('Agendamento_model');
		$this->load->helper("funcoes");
	}

	public function getTable() {
		return $this->table;
	}

	
	public function cadastrarFaturaCartao($dados) 
	{
		if (empty($dados['cartao'])) {
			return array('status' => 'error', 'message' => 'Informe o Cartão de Crédito!');
		} elseif (empty($dados['data_vencimento'])) {
			return array('status'=>'error', 'message'=>'Informe a Data de Vencimento da Fatura!');
		} else {
            $anoMes = transformaAnoMes($dados['data_vencimento']);
			$this->db->trans_begin();
			$sql = "INSERT INTO $this->table (data_vencimento_fatura, pago, fk_id_cartao_credito, ano_mes_ref) 
                VALUES (?, ?, ?, ?)";

			$this->db->query($sql, [$dados['data_vencimento'], "N", $dados['cartao'], $anoMes]);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return array('status' => 'error', 'message' => 'Ops! Ocorreu algum erro no Cadastro da Fatura do Cartão!');
			} else {
				$this->db->trans_commit();
				return array('status' => 'success', 'message' => 'Fatura Cadastrada com Sucesso!');
			}
		}
    }

    public function getCartoesFaturaAberta(): array
    {
        $sql = "SELECT fat.id_fatura_cartao as id, DATE_FORMAT(fat.data_vencimento_fatura,'%d/%m/%Y') as data,
            car.numero_cartao as num, band.bandeira as bandeira, ban.nome_banco as nome, fat.fk_id_cartao_credito as cardid
            FROM $this->table as fat JOIN {$this->CartaoCredito_model->getTable()} as car ON (fat.fk_id_cartao_credito = 
            car.id_cartao_credito) JOIN {$this->Banco_model->getTable()} as ban ON (ban.cod_banco = car.fk_cod_banco) 
            JOIN {$this->BandeiraCartao_model->getTable()} as  band ON (band.id_bandeira_cartao = car.fk_id_bandeira_cartao) 
            WHERE fat.pago = 'N'";
        return $this->db->query($sql)->result_array();
    }

    public function getFaturaCartaoAbertaById(int $idFatura, int $idUser)
    {   
        $sql = "SELECT fat.id_fatura_cartao as id, car.numero_cartao as num,
            DATE_FORMAT(fat.data_vencimento_fatura,'%d/%m/%Y') as data, band.bandeira as bandeira,
            ban.nome_banco as nome, fat.encargos as encargos, fat.protecao_premiada as protecao, fat.anuidade as anuidade,
            fat.restante_fatura_anterior as restante, fat.valor_total_fatura as valor_total, 
            car.id_cartao_credito as idCartao FROM $this->table as fat JOIN {$this->CartaoCredito_model->getTable()} 
            as car ON (fat.fk_id_cartao_credito = car.id_cartao_credito) JOIN {$this->Banco_model->getTable()} as ban ON
            (ban.cod_banco = car.fk_cod_banco) JOIN {$this->BandeiraCartao_model->getTable()} band ON (band.id_bandeira_cartao =
            car.fk_id_bandeira_cartao) WHERE fat.pago = 'N' AND fat.id_fatura_cartao = ? AND car.fk_id_usuario = ?";
        return $this->db->query($sql, [$idFatura, $idUser])->row();
    }

    public function verificaFaturaById(int $idFatura)
    {   
        $sql = "SELECT id_fatura_cartao FROM tb_fatura_cartao WHERE id_fatura_cartao = ? AND pago = 'N'";
        return $this->db->query($sql, [$idFatura])->row();
    }

    public function getValorFaturaMesAnterior($idCart) {
        $mes = verificaMesNumerico();

        $ano = date("Y");
        $anoMes = "$ano-$mes";

        $sql = "SELECT valor_total_fatura as valtotal, valor_pago as valpgo FROM tb_fatura_cartao
                WHERE fk_id_cartao_credito = ? AND ano_mes_ref = ?";
        return $this->db->query($sql, [$idCart, $anoMes])->row();
    }

    
    public function pagarFatura(array $dados, int $id_cartao_fat = null, int $idConta = null) {
        $data = date('Y-m-08');
        $dataAtual = date('Y-m-d');
        if ($dataAtual > $data) {
            $data_vencimento_fatura = date('Y-m-d', strtotime("+1 month", strtotime($data)));
        }
        $data_vencimento_fatura = $data;
        $data_fechamento_fatura = date("Y-m-d", strtotime("-12 days", strtotime($data_vencimento_fatura)));
        $data_pagamento_fatura = date("Y-m-d");
        if ($data_pagamento_fatura < $data_fechamento_fatura) {
            return array('status' => 'error', 'message' => 'Nâo é possível realizar o Pagamento da Fatura!');
        } elseif (empty($dados['totalgeral']) || $dados['totalgeral'] == "" || $dados['totalgeral'] == null) {
			return array('status' => 'error', 'message' => 'Informe o Valor do Total da Fatura!');
		} elseif (empty($dados['valor_pagar']) || $dados['valor_pagar'] == "" || $dados['valor_pagar'] == null) {
			return array('status' => 'error', 'message' => 'Informe o Valor a Pagar!');
		} elseif ($dados['valor_pagar'] > $dados['totalgeral']) {
			return array('status'=>'error', 'message'=>'Valor do Pagamento superior ao Valor do Pagamento!');
		} else {

            $this->db->trans_begin();

                $sql = "UPDATE $this->table SET encargos = ?, protecao_premiada = ?, iof = ?, 
                anuidade = ?, restante_fatura_anterior = ?, pago = ?, juros = ?, valor_total_fatura = ?,
                valor_pago = ? WHERE id_fatura_cartao = ?";
                $result = $this->db->query($sql, ['encargos'=>formatarMoeda($dados['encargos']),
                'protecao_premiada'=>formatarMoeda($dados['protecao']), 'iof'=>formatarMoeda($dados['iof']),
                'anuidade'=>formatarMoeda($dados['anuidade']), 
                'restante_fatura_anterior'=>formatarMoeda($dados['totalgeral'])-formatarMoeda($dados['valor_pagar']),
                'pago'=>'S', 'juros'=>formatarMoeda($dados['juros']), 'valor_total_fatura'=>formatarMoeda($dados['totalgeral']),
                'valor_pago'=>formatarMoeda($dados['valor_pagar']), $id_cartao_fat]);

                foreach($dados['itens_desp'] as $linha) {
                    $sql = "INSERT INTO {$this->ItensFaturaDespesas_model->getTable()} (fk_id_item_despesa_fatura, fk_id_fatura_cartao) 
                        VALUES (?, ?)";
                    $this->db->query($sql, [$linha, $id_cartao_fat]);
                }
                
                $sql = "INSERT INTO {$this->Agendamento_model->getTable()} (data_pagamento, movimentacao, valor, pago, fk_id_categoria, fk_id_conta) ".
                        "VALUES (?,?,?,?,?,?)";
                $this->db->query($sql, [$data_vencimento_fatura, $this->getCartaoCreditoByNome($id_cartao_fat), formatarMoeda($dados['valor_pagar']),
                    'Não', 6, $idConta]); 

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('status' => 'error', 'message' => 'Houve um Erro no Pagamento!');
            } else {
                $this->db->trans_commit();
                return array('status' => 'success', 'message' => 'Pagamento realizado com Sucesso!');
            }

        }
    }
    
	public function getCartaoCreditoByNome($id)
	{
		$sql = "SELECT 
				CASE c.id_cartao_credito 
					WHEN 1 THEN 'CARTÃO VOTORANTIM'
					WHEN 2 THEN 'CARTÃO CEF'
				END AS cartao 
			FROM {$this->getTable()} f
			JOIN {$this->CartaoCredito_model->getTable()} c ON (c.id_cartao_credito = f.fk_id_cartao_credito)
			WHERE f.id_fatura_cartao = ?";
		return $this->db->query($sql, [$id])->result_array()[0]['cartao'];
	}

}
