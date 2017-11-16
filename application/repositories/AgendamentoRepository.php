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
        $this->load->model('agendamento_model');
        $this->load->helper("funcoes");
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function cadastrarPgtoAgendado(Agendamento_model $agendamento_model)
	{
        $resultValidacao = $this->validacoes->validarAgendamento($agendamento_model);
        if ($resultValidacao->getErros() == true) {
            return array('status'=>'error', 'message'=>$resultValidacao->getErros());
        } else {
            $values = ["data_pagamento"=>$agendamento_model->getDataPagamento(), 
            "movimentacao"=>$agendamento_model->getMovimentacao(), 
            "valor"=>formatarMoeda($agendamento_model->getValor()), "pago"=>$agendamento_model->getPago(), 
            "fk_id_categoria"=>$agendamento_model->getCategoria()->getIdCategoria(),
            "fk_id_conta"=>$agendamento_model->getConta()->getIdConta()];
            $resultado = $this->insert($this->agendamento_model->getTable(), $values);
            if ($resultado['status'] == 'success') {
                return array('status' => 'success', 'message' => 'Agendamento Realizado com Sucesso!');
            } else {
                return array('status' => 'error', 'message' => $resultado['message']);
            }
        }
	}

}    