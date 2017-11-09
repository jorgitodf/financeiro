<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CartaoCredito extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('banco_model');
		$this->load->model('tipoconta_model');
		$this->load->model('conta_model');
		$this->load->model('cartaocredito_model');
		$this->load->model('bandeiracartao_model');
		$this->load->model('extrato_model');
		$this->load->model('despesacartao_model');
		$this->load->helper('funcoes');
		date_default_timezone_set('America/Sao_Paulo');
	}

	public function cadastrar($id = null)
	{
		if (is_numeric($id) && !empty($id) && $id > 0 && $this->conta_model->verificaConta($id) == true) {
			$dados["title"] = "Cadastro de Cartão de Crédito";
			$dados["view"] = "cartao/v_index";
			$dados["idConta"] = $id;
			$dados["bancos"] = $this->banco_model->getBancos();
			$dados["bandeiras"] = $this->bandeiracartao_model->getBandeirasCartoes();
			$this->load->view("v_template", $dados);
		} else if ($this->input->post()) {
			$idUsuario = $this->input->post('idUsuario');
			$num_cartao = $this->input->post('num_cartao');
			$data_validade = $this->input->post('data_validade');
			$bandeira = $this->input->post('bandeira');
			$banco = $this->input->post('banco');

			$dados = ['numero_cartao'=>$num_cartao, 'data_validade'=>$data_validade, 'fk_id_bandeira_cartao'=>$bandeira,
				'fk_id_usuario'=>$idUsuario, 'fk_cod_banco'=>$banco];

			$return = $this->cartaocredito_model->cadastrarCartaoCredito($dados);
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

	public function comprar($id = null)
	{
		if (is_numeric($id) && !empty($id) && $id > 0 && $this->conta_model->verificaConta($id) == true) {
			$dados["title"] = "Lançar Compra Cartão de Crédito";
			$dados["view"] = "cartao/v_lancar_compra";
			$dados["idConta"] = $id;
			$dados["idUser"] = $this->session->userdata('id');
			$dados["cartoes"] = $this->cartaocredito_model->getCartoesCreditosByUser($this->session->userdata('id'));
			//$dados["bandeiras"] = $this->bandeiracartao_model->getBandeirasCartoes();
			$this->load->view("v_template", $dados);
		} else if ($this->input->post()) {
			$idUser = $this->input->post('idUser');
			$cartao = $this->input->post('cartao');
			$data_compra = $this->input->post('data_compra');
			$despesa = $this->input->post('descricao');
			$valor_compra = $this->input->post('valor_compra');
			$parcela = $this->input->post('parcela');

			$dados = ['data_compra'=>$data_compra, 'valor_parcela'=>$valor_compra, 'parcela'=>$parcela, 
			'despesa'=>$despesa, 'id_cartao'=>$cartao];  

			$return = $this->despesacartao_model->salvarDespesaCartaoCredito($dados);
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
