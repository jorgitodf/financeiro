<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/../../system/core/Model.php';

class Extrato_model extends CI_Model
{
    protected $table = 'tb_extrato';
    private $id_extrato;
    private $data_movimentacao;
    private $mes;
    private $tipo_operacao;
    private $movimentacao;
    private $quantidade;
    private $valor;
    private $saldo;
    private $categoria;
    private $conta;
    private $despesa_fixa;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Categoria_model');
        $this->load->model('Conta_model');
        $this->conta = new Conta_model();
        $this->categoria = new Categoria_model();
    }

    public function getTable() {
        return $this->table;
    }

    public function getIdExtrato()
    {
        return $this->id_extrato;
    }
    public function setIdExtrato($id_extratoid)
    {
        $this->id_extrato = $id_extrato;
    }

    public function getDataMovimentacao()
    {
        return $this->data_movimentacao;
    }
    public function setDataMovimentacao($data_movimentacao)
    {
        $this->data_movimentacao = $data_movimentacao;
    }

    public function getMes()
    {
        return $this->mes;
    }
    public function setMes($mes)
    {
        $this->mes = $mes;
    }

    public function getTipoOperacao()
    {
        return $this->tipo_operacao;
    }
    public function setTipoOperacao($tipo_operacao)
    {
        $this->tipo_operacao = $tipo_operacao;
    }

    public function getMovimentacao()
    {
        return $this->movimentacao;
    }
    public function setMovimentacao($movimentacao)
    {
        $this->movimentacao = $movimentacao;
    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    public function getValor()
    {
        return $this->valor;
    }
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function getSaldo()
    {
        return $this->saldo;
    }
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;
    }

    public function getDespesaFixa()
    {
        return $this->despesa_fixa;
    }
    public function setDespesaFixa($despesa_fixa)
    {
        $this->despesa_fixa = $despesa_fixa;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function getConta()
    {
        return $this->conta;
    }
}
