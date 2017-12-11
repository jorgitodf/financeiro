<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/DefaultRepository.php';
require_once __DIR__ . '/../models/Conta_model.php';

class ContaRepository extends DefaultRepository
{
    protected $conta_model;
    protected $extrato_model;
    protected $banco_model;
    protected $tipoConta_model;

    public function __construct()
    {
        $this->conta_model = new Conta_model();
        $this->extrato_model = new Extrato_model();
        $this->banco_model = new Banco_model();
        $this->tipoConta_model = new TipoConta_model();
        $this->load->helper("funcoes");
    }

    public function getAtualSaldo(int $idUsuario, int $idConta)
    {
        if (!empty($idUsuario) && !empty($idConta)) {
            $values = [$idConta, $idConta];
            $resultado = $this->selectWhereId("SELECT e.saldo AS saldo, c.numero_conta AS numero_conta, ".
            "c.digito_verificador_conta AS digito, b.nome_banco AS nome_banco ".
            "FROM {$this->extrato_model->getTable()} e JOIN {$this->conta_model->getTable()} c ON (c.id_conta = e.fk_id_conta) ".
            "JOIN {$this->banco_model->getTable()} b ON (b.cod_banco = c.fk_cod_banco) ".
            "WHERE c.fk_id_usuario = ? AND e.fk_id_conta = ? ORDER BY e.id_extrato DESC LIMIT 1", $values);
            return $resultado->row();
        }
    }

    public function verificaConta(int $id)
    {
        if (!empty($id)) {
            $values = [$id];
            $resultado = $this->selectWhereId("SELECT c.id_conta AS id_conta, c.codigo_agencia AS cod_agencia, ".
                "b.nome_banco AS nome_banco, c.digito_verificador_agencia AS dig_ver_agencia, tc.tipo_conta AS tipo_conta, " .
                "c.numero_conta AS numero_conta, c.digito_verificador_conta AS dig_ver_conta, c.codigo_operacao AS cod_operacao, " .
                "DATE_FORMAT(c.data_cadastro, '%d/%m/%Y') AS data_cadastro FROM {$this->conta_model->getTable()} AS c " .
                "LEFT JOIN {$this->banco_model->getTable()} AS b ON (c.fk_cod_banco = b.cod_banco)" .
                "LEFT JOIN {$this->tipoConta_model->getTable()} AS tc ON (c.fk_tipo_conta = tc.id_tipo_conta) " .
                "WHERE c.fk_id_usuario = ? ORDER BY c.id_conta DESC", $values);
            if (!empty($resultado->result_array())) {
                return $resultado->result_array();
            }
            return false;
        }
    }
}    