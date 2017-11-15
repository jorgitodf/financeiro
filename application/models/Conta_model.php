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
        $this->load->model('banco_model');
        $this->load->model('tipoconta_model');
        $this->load->model('usuario_model');
        $this->load->model('extrato_model');
        $this->load->model('categoria_model');
        $this->load->model('agendamento_model');
        $this->load->helper("funcoes");
        date_default_timezone_set('America/Sao_Paulo');
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
                FROM $this->table AS c LEFT JOIN {$this->banco_model->getTable()} AS b ON (c.fk_cod_banco = b.cod_banco)
                LEFT JOIN {$this->tipoconta_model->getTable()} AS tc ON (c.fk_tipo_conta = tc.id_tipo_conta) WHERE c.fk_id_usuario = ? ORDER BY c.id_conta DESC";
            return $this->db->query($sql, [$id])->result_array();
        } catch (Exception $e) {
            return array('status'=>'error', 'message' => $e->getMessage());
        }
    }

    public function getAtualSaldo(int $idUsuario, int $idConta)
    {
        try {
            $sql = "SELECT e.saldo AS saldo, c.numero_conta AS numero_conta, c.digito_verificador_conta AS digito,
            b.nome_banco AS nome_banco FROM {$this->extrato_model->getTable()} e JOIN $this->table c ON (c.id_conta = e.fk_id_conta)
            JOIN {$this->banco_model->getTable()} b ON (b.cod_banco = c.fk_cod_banco)
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
                  conta, ban.nome_banco as banco FROM {$this->extrato_model->getTable()} as ext JOIN $this->table as con
                  ON (ext.fk_id_conta = con.id_conta) JOIN {$this->banco_model->getTable()} as ban ON (con.fk_cod_banco = ban.cod_banco)
                  WHERE ext.data_movimentacao BETWEEN ? AND ? AND ext.fk_id_conta = ? ORDER BY ext.id_extrato DESC LIMIT 1";
            return $this->db->query($sql, [$dataMenor, $dataAtual, $idConta])->result_array();
        }
    }

    public function getTokenUsuario(int $idUsuario)
    {
        $sql = "SELECT token FROM {$this->usuario_model->getTable()} WHERE id_usuario = ?";
        $result = $this->db->query($sql, [$idUsuario])->row();
        if ($result->token == NULL || empty($result->token)) {
            return $this->usuario_model->insertTokenUsuario($idUsuario);
        } else {
            return $this->db->query($sql, [$idUsuario])->row();
        }
    }

    public function debitarValor($dados)
    {
        $saldo = $this->getSaldoAtual($dados['idConta']);
        if ($dados['valor'] <= $saldo[0]['saldo']) {
            $novoSaldo = $saldo[0]['saldo'] - $dados['valor'];
            $mes = verificaMes();
            $checkCategoria = $this->categoria_model->checkCategoria();
                foreach ($checkCategoria as $linha) {
                    if (($linha['id_categoria'] == $dados['fk_id_categoria']) && ($linha['despesa_fixa'] == 'S')) {
                        $despFixa = 'S';
                    }  elseif (($linha['id_categoria'] == $dados['fk_id_categoria']) && ($linha['despesa_fixa'] == 'N')) {
                        $despFixa = 'N';
                    }
                }

            $this->db->trans_begin();
            $sql = "INSERT INTO tb_extrato (data_movimentacao, mes, tipo_operacao, movimentacao, quantidade, valor, saldo,
              fk_id_categoria, fk_id_conta, despesa_fixa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, [$dados['data_movimentacao'], $mes, 'Débito', $dados['movimentacao'], 1, $dados['valor'],
              $novoSaldo, $dados['fk_id_categoria'], $dados['idConta'], $despFixa]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('status'=>'error', 'message' => 'Ops! Ocorreu algum erro na realização da transação...');
            } else {
                $this->db->trans_commit();
                return array('status'=>'success', 'message' => 'Débito realizado com Sucesso!');
            }
        } else {
            return array('status'=>'error', 'message' => 'Saldo Insuficiente para realizar essa Operação!');
        }
    }

    public function creditarValor($dados)
    {
        if (!empty($dados)) {
            $saldo = $this->getSaldoAtual($dados['idConta']);
            $dados['valor'] <= $saldo[0]['saldo'];
            $novoSaldo = $saldo[0]['saldo'] + $dados['valor'];
            $mes = verificaMes();
            $checkCategoria = $this->categoria_model->checkCategoria();
                foreach ($checkCategoria as $linha) {
                    if (($linha['id_categoria'] == $dados['fk_id_categoria']) && ($linha['despesa_fixa'] == 'S')) {
                        $despFixa = 'S';
                    }  elseif (($linha['id_categoria'] == $dados['fk_id_categoria']) && ($linha['despesa_fixa'] == 'N')) {
                        $despFixa = 'N';
                    }
                }

            $this->db->trans_begin();
            $sql = "INSERT INTO tb_extrato (data_movimentacao, mes, tipo_operacao, movimentacao, quantidade, valor, saldo,
              fk_id_categoria, fk_id_conta, despesa_fixa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, [$dados['data_movimentacao'], $mes, 'Crédito', $dados['movimentacao'], 1, $dados['valor'],
              $novoSaldo, $dados['fk_id_categoria'], $dados['idConta'], $despFixa]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('status'=>'error', 'message' => 'Ops! Ocorreu algum erro na realização da transação...');
            } else {
                $this->db->trans_commit();
                return array('status'=>'success', 'message' => 'Crédito realizado com Sucesso!');
            }
        } else {
            return array('status'=>'error', 'message' => 'ERRO: Possui dados vazios.');
        }
    }


    public function verificaPagamentoAgendado($idConta) {
        $dados = array();
        $dataPagamento = date("Y-m-d");
        $sql = "SELECT * FROM {$this->agendamento_model->getTable()} WHERE data_pagamento = ?  AND pago = 'Não' ORDER BY data_pagamento";
        $dados = $this->db->query($sql, [$dataPagamento])->result_array();
        if (!empty($dados)) {
            foreach ($dados as $value) {
                $saldo = $this->getSaldoAtual($idConta);
                if ($saldo[0]['saldo'] < $value['valor']) {
                    return array('status'=>'error', 'message' => 'Saldo Insuficiente para realizar o Pagamento!');
                } elseif ($saldo[0]['saldo'] >= $value['valor'] && $value['pago'] == 'Não') {
                    $novoSaldo = $saldo[0]['saldo'] - $value['valor'];
                    $mes = verificaMes();
                    
                    $sql1 = "INSERT INTO {$this->extrato_model->getTable()} (data_movimentacao, mes, tipo_operacao, 
                        movimentacao, quantidade, valor, saldo, fk_id_categoria, fk_id_conta, despesa_fixa) VALUES 
                        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $this->db->query($sql1, [$value['data_pagamento'], $mes, 'Débito', $value['movimentacao'], 1, 
                    $value['valor'], $novoSaldo, $value['fk_id_categoria'], $value['fk_id_conta'], 'S']);

                    $sql2 = "UPDATE {$this->agendamento_model->getTable()} SET pago = 'Sim' WHERE id_pgto_agendado = ?";
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
