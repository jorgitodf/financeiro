<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/DefaultRepository.php';
require_once __DIR__ . '/../validacoes/Validacoes.php';
require_once __DIR__ . '/../models/Agendamento_model.php';

class AgendamentoRepository extends DefaultRepository
{
    private $validacoes;

    public function __construct()
    {
        $this->validacoes = new Validacoes();
        $this->load->model('Agendamento_model');
        $this->load->helper("funcoes");
    }

    public function cadastrarPgtoAgendado(Agendamento_model $Agendamento_model)
	{
        $resultValidacao = $this->validacoes->validarAgendamento($Agendamento_model);
        if ($resultValidacao->getErros() == true) {
            return array('status'=>'error', 'message'=>$resultValidacao->getErros());
        } else {
            $values = ["data_pagamento"=>$Agendamento_model->getDataPagamento(), 
            "movimentacao"=>$Agendamento_model->getMovimentacao(), 
            "valor"=>formatarMoeda($Agendamento_model->getValor()), "pago"=>$Agendamento_model->getPago(), 
            "fk_id_categoria"=>$Agendamento_model->getCategoria()->getIdCategoria(),
            "fk_id_conta"=>$Agendamento_model->getConta()->getIdConta()];
            $resultado = $this->insert($this->Agendamento_model->getTable(), $values);
            if ($resultado['status'] == 'success') {
                return array('status' => 'success', 'message' => 'Agendamento Realizado com Sucesso!');
            } else {
                return array('status' => 'error', 'message' => $resultado['message']);
            }
        }
	}

}    