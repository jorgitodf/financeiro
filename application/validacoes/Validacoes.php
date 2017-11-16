<?php
 
require_once __DIR__ . '/../models/Extrato_model.php';
require_once __DIR__ . '/ResultadoValidacoes.php';
 
class Validacoes 
{
    private $resultadoValidacao;
    
    public function __construct()
    {
        $this->resultadoValidacao = new ResultadoValidacoes();
    }

    public function validarDebito(Extrato_model $extrato_model)
    {
        if (empty($extrato_model->getDataMovimentacao())) {
            $this->resultadoValidacao->addErro("Informe a Data da Operação");
        } elseif (empty($extrato_model->getMovimentacao())) {
            $this->resultadoValidacao->addErro("Informe a Movimentação");
        } elseif (empty($extrato_model->getCategoria()->getIdCategoria()) || $extrato_model->getCategoria()->getIdCategoria() == null) {
            $this->resultadoValidacao->addErro("Informe a Categoria");
        } elseif (empty($extrato_model->getValor() || $extrato_model->getValor() == "R$ 0,00")) {
            $this->resultadoValidacao->addErro("Informe o Valor");
        }
        return $this->resultadoValidacao;
    }

    public function validarCredito(Extrato_model $extrato_model)
    {
        if (empty($extrato_model->getDataMovimentacao()))
        {
            $this->resultadoValidacao->addErro("Informe a Data da Operação");
        } elseif (empty($extrato_model->getMovimentacao()))
        {
            $this->resultadoValidacao->addErro("Informe a Movimentação");
        } elseif (empty($extrato_model->getCategoria()->getIdCategoria()) || $extrato_model->getCategoria()->getIdCategoria() == null)
        {
            $this->resultadoValidacao->addErro("Informe a Categoria");
        } elseif (empty($extrato_model->getValor() || $extrato_model->getValor() == "R$ 0,00"))
        {
            $this->resultadoValidacao->addErro("Informe o Valor");
        }
        return $this->resultadoValidacao;
    }
    
    public function validarAgendamento(Agendamento_model $agendamento_model)
    {
        if (empty($agendamento_model->getDataPagamento())) {
            $this->resultadoValidacao->addErro("Informe a Data do Pagamento");
        } elseif (empty($agendamento_model->getMovimentacao())) {
            $this->resultadoValidacao->addErro("Informe a Movimentação");
        } elseif (empty($agendamento_model->getCategoria()->getIdCategoria()) || $agendamento_model->getCategoria()->getIdCategoria() == null) {
            $this->resultadoValidacao->addErro("Informe a Categoria");
        } elseif (empty($agendamento_model->getValor() || $agendamento_model->getValor() == "R$ 0,00")) {
            $this->resultadoValidacao->addErro("Informe o Valor");
        }
        return $this->resultadoValidacao;
    }
}