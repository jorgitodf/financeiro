<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Extrato_model extends CI_Model
{
    protected $table = 'tb_extrato';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('categoria_model');
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function getTable() {
        return $this->table;
    }

    public function getExtratoAtual($idConta) {
        if (!empty($idConta)) {
            $ano = date("Y");
            $mes = date("m");
            $dataMovimentacaoInicial = "{$ano}-{$mes}-01";
            $dataMovimentacaoFinal = "{$ano}-{$mes}-31";

            $sql = "SELECT ext.data_movimentacao AS data_movimentacao, ext.movimentacao AS mov, cat.nome_categoria AS
                  cat, ext.tipo_operacao AS op, ext.valor AS val, ext.saldo AS sal, ext.despesa_fixa AS dp FROM
                  $this->table AS ext LEFT JOIN {$this->categoria_model->getTable()} AS cat ON (ext.fk_id_categoria = cat.id_categoria) WHERE
                  ext.fk_id_conta = ? AND ext.data_movimentacao BETWEEN ? AND ?";
            return $this->db->query($sql, [$idConta, $dataMovimentacaoInicial, $dataMovimentacaoFinal])->result_array();
        }
    }

    public function createConta(array $dados)
    {
        try {
            $sql = "INSERT INTO $this->table (codigo_agencia, digito_verificador_agencia, numero_conta, digito_verificador_conta,
              codigo_operacao, data_cadastro, fk_id_usuario, fk_cod_banco, fk_tipo_conta) VALUES (?,?,?,?,?,?,?,?,?)";
              $this->db->query($sql, [$dados['codigo_agencia'],
              !empty($dados['digito_verificador_agencia']) ? $dados['digito_verificador_agencia'] : null, $dados['numero_conta'],
              $dados['digito_verificador_conta'], !empty($dados['codigo_operacao']) ? $dados['codigo_operacao'] : null, $dados['data_cadastro'],
              $dados['fk_id_usuario'], $dados['fk_cod_banco'], $dados['fk_tipo_conta']]);
            return array('status'=>'success', 'message' => 'Conta Cadastrada com Sucesso!');
        } catch (Exception $e) {
            return array('status'=>'error', 'message' => $e->getMessage());
        }
    }

}
