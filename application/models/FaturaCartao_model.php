<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class FaturaCartao_model extends CI_Model
{
	protected $table = 'tb_fatura_cartao';

	public function __construct()
	{
		parent::__construct();
        $this->load->database();
        $this->load->model('cartaocredito_model');
        $this->load->model('banco_model');
        $this->load->model('bandeiracartao_model');
		$this->load->helper("funcoes");
		date_default_timezone_set('America/Sao_Paulo');
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
            FROM $this->table as fat JOIN {$this->cartaocredito_model->getTable()} as car ON (fat.fk_id_cartao_credito = 
            car.id_cartao_credito) JOIN {$this->banco_model->getTable()} as ban ON (ban.cod_banco = car.fk_cod_banco) 
            JOIN {$this->bandeiracartao_model->getTable()} as  band ON (band.id_bandeira_cartao = car.fk_id_bandeira_cartao) 
            WHERE fat.pago = 'N'";
        return $this->db->query($sql)->result_array();
    }

    public function getFaturaCartaoAbertaById(int $idFatura, int $idUser)
    {   
        $sql = "SELECT fat.id_fatura_cartao as id, car.numero_cartao as num,
            DATE_FORMAT(fat.data_vencimento_fatura,'%d/%m/%Y') as data, band.bandeira as bandeira,
            ban.nome_banco as nome, fat.encargos as encargos, fat.protecao_premiada as protecao, fat.anuidade as anuidade,
            fat.restante_fatura_anterior as restante, fat.valor_total_fatura as valor_total, 
            car.id_cartao_credito as idCartao FROM $this->table as fat JOIN {$this->cartaocredito_model->getTable()} 
            as car ON (fat.fk_id_cartao_credito = car.id_cartao_credito) JOIN {$this->banco_model->getTable()} as ban ON
            (ban.cod_banco = car.fk_cod_banco) JOIN {$this->bandeiracartao_model->getTable()} band ON (band.id_bandeira_cartao =
            car.fk_id_bandeira_cartao) WHERE fat.pago = 'N' AND fat.id_fatura_cartao = ? AND car.fk_id_usuario = ?";
        return $this->db->query($sql, [$idFatura, $idUser])->row();
    }

    public function verificaFaturaById(int $idFatura)
    {   
        $sql = "SELECT id_fatura_cartao FROM tb_fatura_cartao WHERE id_fatura_cartao = ? AND pago = 'N'";
        return $this->db->query($sql, [$idFatura])->row();
    }

}
