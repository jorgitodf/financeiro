<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Conta_model extends CI_Model
{
    protected $table = 'tb_conta';
    private $id_conta;
    private $codigo_agencia;
    private $digito_verificador_agencia;
    private $numero_conta;
    private $digito_verificador_conta;
    private $codigo_operacao;
    private $data_cadastro;
    private $fk_id_usuario;
    private $fk_cod_banco;
    private $fk_tipo_conta;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Banco_model');
        $this->load->model('TipoConta_model');
        $this->load->model('Usuario_model');
        $this->load->model('Extrato_model');
        $this->load->model('Categoria_model');
        $this->load->model('Agendamento_model');
        $this->load->helper("funcoes");
    }

    public function getTable() {
        return $this->table;
    }

    public function getIdConta()
    {
        return $this->id_conta;
    }
    public function setIdConta($id_conta)
    {
        $this->id_conta = $id_conta;
    }

    public function getEmail($email)
    {
        $usuario = $this->db->get_where($this->table, ['email' => $email]);
        return $usuario->custom_row_object(0, 'Usuario_model');
    }

    public function verificaConta(int $id):array
    {
        try {
            $sql = "SELECT c.id_conta AS id_conta, c.codigo_agencia AS cod_agencia, b.nome_banco AS nome_banco,
                c.digito_verificador_agencia AS dig_ver_agencia, tc.tipo_conta AS tipo_conta, c.numero_conta AS numero_conta,
                c.digito_verificador_conta AS dig_ver_conta, c.codigo_operacao AS cod_operacao, DATE_FORMAT(c.data_cadastro, '%d/%m/%Y') AS data_cadastro
                FROM $this->table AS c LEFT JOIN {$this->Banco_model->getTable()} AS b ON (c.fk_cod_banco = b.cod_banco)
                LEFT JOIN {$this->TipoConta_model->getTable()} AS tc ON (c.fk_tipo_conta = tc.id_tipo_conta) WHERE c.fk_id_usuario = ? ORDER BY c.id_conta DESC";
            return $this->db->query($sql, [$id])->result_array();
        } catch (Exception $e) {
            return array('status'=>'error', 'message' => $e->getMessage());
        }
    }

    public function getAtualSaldo(int $idUsuario, int $idConta)
    {
        try {
          $sql = "SELECT e.saldo AS saldo, c.numero_conta AS numero_conta, c.digito_verificador_conta AS digito,
          b.nome_banco AS nome_banco FROM {$this->Extrato_model->getTable()} e JOIN $this->table c ON (c.id_conta = e.fk_id_conta)
          JOIN {$this->Banco_model->getTable()} b ON (b.cod_banco = c.fk_cod_banco)
          WHERE c.fk_id_usuario = ? AND e.fk_id_conta = ? ORDER BY e.id_extrato DESC LIMIT 1";
          return $this->db->query($sql, [$idUsuario, $idConta])->row();
        } catch (Exception $e) {
            return array('status'=>'error', 'message' => $e->getMessage());
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

    public function getSaldoAtual(int $idConta): array
    {
        if (!empty($idConta)) {
            $dataAtual = date("Y-m-d");
            $dataMenor = date("Y-m-d", strtotime("-20 days", strtotime($dataAtual)));
            $sql = "SELECT ext.saldo as saldo, concat(con.numero_conta,'-',con.digito_verificador_conta) as
                  conta, ban.nome_banco as banco FROM {$this->Extrato_model->getTable()} as ext JOIN $this->table as con
                  ON (ext.fk_id_conta = con.id_conta) JOIN {$this->Banco_model->getTable()} as ban ON (con.fk_cod_banco = ban.cod_banco)
                  WHERE ext.data_movimentacao BETWEEN ? AND ? AND ext.fk_id_conta = ? ORDER BY ext.id_extrato DESC LIMIT 1";
            return $this->db->query($sql, [$dataMenor, $dataAtual, $idConta])->result_array();
        }
    }

    public function verificaPagamentoAgendado($idConta) {
        $dados = array();
        $dataPagamento = date("Y-m-d");
        $sql = "SELECT * FROM {$this->Agendamento_model->getTable()} WHERE data_pagamento = ?  AND pago = 'Não' ORDER BY data_pagamento";
        $dados = $this->db->query($sql, [$dataPagamento])->result_array();
        if (!empty($dados)) {
            foreach ($dados as $value) {
                $saldo = $this->getSaldoAtual($idConta);
                if ($saldo[0]['saldo'] < $value['valor']) {
                    return array('status'=>'error', 'message' => 'Saldo Insuficiente para realizar o Pagamento!');
                } elseif ($saldo[0]['saldo'] >= $value['valor'] && $value['pago'] == 'Não') {
                    $novoSaldo = $saldo[0]['saldo'] - $value['valor'];
                    $mes = verificaMes();

                    $sql1 = "INSERT INTO {$this->Extrato_model->getTable()} (data_movimentacao, mes, tipo_operacao,
                        movimentacao, quantidade, valor, saldo, fk_id_categoria, fk_id_conta, despesa_fixa) VALUES
                        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $this->db->query($sql1, [$value['data_pagamento'], $mes, 'Débito', $value['movimentacao'], 1,
                    $value['valor'], $novoSaldo, $value['fk_id_categoria'], $value['fk_id_conta'], 'S']);

                    $sql2 = "UPDATE {$this->Agendamento_model->getTable()} SET pago = 'Sim' WHERE id_pgto_agendado = ?";
                    $this->db->query($sql2, [$value['id_pgto_agendado']]);
                } else {
                    return array('status'=>'error', 'message' => 'Não há nenhum pagamento agendado para hoje!');
                }
            }
            return array('status'=>'success', 'message' => 'Pagamento(s) realizado(s) com Sucesso!!');
        } else {
            return array('status'=>'error', 'message' => 'Não há nenhum pagamento agendado para hoje!');
        }
    }


}
