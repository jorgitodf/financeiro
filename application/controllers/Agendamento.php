<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agendamento extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('banco_model');
		$this->load->model('tipoconta_model');
		$this->load->model('conta_model');
		$this->load->model('categoria_model');
		$this->load->model('agendamento_model');
		date_default_timezone_set('America/Sao_Paulo');
	}

	public function listar()
	{
		$dados["title"] = "Listagem de Pagamentos Agendados";
		$dados["view"] = "agendamento/v_listagem_agendamentos";
		//$dados["bancos"] = $this->banco_model->getBancos();
		//$dados["tipoConta"] = $this->tipoconta_model->getTiposContas();
		$this->load->view("v_template", $dados);
    }
    
    public function agendar($id = null)
	{
		if (is_numeric($id) && !empty($id) && $id > 0 && $this->conta_model->verificaConta($id) == true) {
			$dados["title"] = "Agendar Pagamento";
			$dados["idConta"] = $id;
			$dados['categorias'] = $this->categoria_model->getCategoriasDespesas();
			$dados["view"] = "agendamento/v_agendamento";
            $this->load->view("v_template", $dados);
		} else if ($this->input->post()) {
			$idConta = $this->input->post('idConta');
			$data_pgto = $this->input->post('data_pgto');
			$movimentacao = $this->input->post('movimentacao');
			$nome_categoria = $this->input->post('nome_categoria');
			$valor = $this->input->post('valor');
			$newValor = str_replace('R$ ', '', str_replace(',', '.', str_replace('.', '', $valor)));

            $dados = ['data_pagamento'=>$data_pgto, 'movimentacao'=>$movimentacao, 'valor'=>$valor, 
                'categoria'=>$nome_categoria, 'idConta'=>$idConta];

            $return = $this->agendamento_model->cadastrarPgtoAgendado($dados);
            if ($return['status'] == 'success') {
                $json = array('status'=>'success', 'message'=>$return['message']);
            }  else {
                $json = array('status'=>'error', 'message'=>$return['message']);
            }

			return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
		} else {
			$dados["title"] = "Page Not Found: 404";
			$dados["message"] = "Erro 404: A página requisita não existe...";
			$dados["view"] = "errors/error_404";
			$this->load->view("v_template", $dados);
		}
	}

}
