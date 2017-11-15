<?php
 
require_once __DIR__ . '/../models/Extrato_model.php';
require_once __DIR__ . '/ResultadoValidacoes.php';
 
class DebitoValidator 
{
    private $resultadoValidacao;
    
    public function __construct()
    {
        $this->resultadoValidacao = new ResultadoValidacoes();
    }

    public function validar(Extrato_model $extrato_model)
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
}