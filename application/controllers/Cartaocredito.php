<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cartaocredito extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Banco_model');
		$this->load->model('TipoConta_model');
		$this->load->model('Conta_model');
		$this->load->model('CartaoCredito_model');
		$this->load->model('BandeiraCartao_model');
		$this->load->model('Extrato_model');
		$this->load->model('FaturaCartao_model');
		$this->load->model('DespesaCartao_model');
		$this->load->helper('funcoes');
	}

	public function cadastrar($id = null)
	{
		if (is_numeric($id) && !empty($id) && $id > 0 && $this->Conta_model->verificaConta($id) == true) {
			$dados["title"] = "Cadastro de Cartão de Crédito";
			$dados["view"] = "cartao/v_index";
			$dados["idConta"] = $id;
			$dados["conta"] = $this->Conta_model->getAtualSaldo($this->session->userdata('id'), $this->session->userdata('idConta'));
			$dados["bancos"] = $this->Banco_model->getBancos();
			$dados["bandeiras"] = $this->BandeiraCartao_model->getBandeirasCartoes();
			$this->load->view("v_template", $dados);
		} else if ($this->input->post()) {
			$idUsuario = $this->input->post('idUsuario');
			$num_cartao = $this->input->post('num_cartao');
			$data_validade = $this->input->post('data_validade');
			$bandeira = $this->input->post('bandeira');
			$banco = $this->input->post('banco');

			$dados = ['numero_cartao'=>$num_cartao, 'data_validade'=>$data_validade, 'fk_id_bandeira_cartao'=>$bandeira,
				'fk_id_usuario'=>$idUsuario, 'fk_cod_banco'=>$banco];

			$return = $this->CartaoCredito_model->cadastrarCartaoCredito($dados);
			if ($return['status'] == 'success') {
				$json = array('status'=>'success', 'message'=>$return['message']);
			}  else {
				$json = array('status'=>'error', 'message'=>$return['message']);
			}
			return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
		} else {
			$this->load->view("v_template", pageNotFound());
		}
	}

	public function comprar($id = null)
	{
		if (is_numeric($id) && !empty($id) && $id > 0 && $this->Conta_model->verificaConta($id) == true) {
			$dados["title"] = "Lançar Compra Cartão de Crédito";
			$dados["view"] = "cartao/v_lancar_compra";
			$dados["idConta"] = $id;
			$dados["conta"] = $this->Conta_model->getAtualSaldo($this->session->userdata('id'), $this->session->userdata('idConta'));
			$dados["idUser"] = $this->session->userdata('id');
			$dados["cartoes"] = $this->CartaoCredito_model->getCartoesCreditosByUser($this->session->userdata('id'));
			//$dados["bandeiras"] = $this->BandeiraCartao_model->getBandeirasCartoes();
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

			$return = $this->DespesaCartao_model->salvarDespesaCartaoCredito($dados);
			if ($return['status'] == 'success') {
				$json = array('status'=>'success', 'message'=>$return['message']);
			}  else {
				$json = array('status'=>'error', 'message'=>$return['message']);
			}
			return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
		} else {
			$this->load->view("v_template", pageNotFound());
		}
	}

	public function faturar($id = null) 
	{
        if (is_numeric($id) && !empty($id) && $id > 0 && $this->Conta_model->verificaConta($id) == true) {
			$dados["title"] = "Criar Fatura Cartão de Crédito";
			$dados["view"] = "cartao/v_criar_fatura";
			$dados["idConta"] = $id;
			$dados['cartoes'] = $this->CartaoCredito_model->getCartoesCreditosByUser($this->session->userdata('id'));
			$this->load->view("v_template", $dados);
		} else if ($this->input->post()) {
			$cartao = $this->input->post('cartao');
			$data_vencimento = $this->input->post('data_vencimento');
			$dados = ['data_vencimento'=>$data_vencimento, 'cartao'=>$cartao];  
			$return = $this->FaturaCartao_model->cadastrarFaturaCartao($dados);
			if ($return['status'] == 'success') {
				$json = array('status'=>'success', 'message'=>$return['message']);
			}  else {
				$json = array('status'=>'error', 'message'=>$return['message']);
			}
			return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
        } else {
            $this->load->view("v_template", pageNotFound());
        }
	}
	
	function fecharFatura($id = null) 
	{
		if (is_numeric($id) && !empty($id) && $id > 0 && $this->Conta_model->verificaConta($id) == true) {
			$dados["title"] = "Fechar Fatura Cartão de Crédito";
			$dados["view"] = "cartao/v_fechar_fatura";
			$dados["idConta"] = $id;
			$dados['faturas'] = $this->FaturaCartao_model->getCartoesFaturaAberta();
			$this->load->view("v_template", $dados);
		} else {
			$this->load->view("v_template", pageNotFound());
		}
	}

	function pagarFatura() 
	{
		if ($this->input->post()) {
			$id_fatura_cartao = $this->input->post('id_fatura_cartao');
			if (empty($id_fatura_cartao)) {
				$json = array('status' => 'error', 'message' => 'Selecione um Cartão de Crédito!');
			} else {
				$base_url = 'http';
				if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") $base_url .= "s";
				$base_url .= "://";
				if ($_SERVER["SERVER_PORT"] != "80") $base_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
				else $base_url .= $_SERVER["SERVER_NAME"];
				$base_url."/";
				$json = array('status' => 'success', 'id_fatura_cartao'=> $id_fatura_cartao, 'base_url'=>$base_url);
			}
			return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
		} else {
			$this->load->view("v_template", pageNotFound());
		}
	}

	function faturaPagar($id_fatura_cartao = null)
	{
		if (is_numeric($id_fatura_cartao) && !empty($id_fatura_cartao) && $id_fatura_cartao > 0 && $this->FaturaCartao_model->verificaFaturaById($id_fatura_cartao) == true) {
			$dados['fatura'] = $this->FaturaCartao_model->getFaturaCartaoAbertaById($id_fatura_cartao, $this->session->userdata('id'));
			$dados['itensfatura'] = $this->DespesaCartao_model->getItensDespesaFaturaByIdFaturaCartao($dados['fatura']->idCartao, formataData($dados['fatura']->data));
			$dados["title"] = "Pagar Fatura Cartão de Crédito";
			$dados["view"] = "cartao/v_pagar_fatura";
			$dados["idConta"] = $this->session->userdata('idConta');
			$this->load->view("v_template", $dados);
		} else if ($this->input->post()) {
			$id_cartao_fat = $this->input->post('id_cartao_fat');
			$id_cartao_cre = $this->input->post('id_cartao_cre');
			$itens_desp = $this->input->post('itens_desp');
			$encargos = $this->input->post('encargos');
			$protecao_prem = $this->input->post('protecao_prem');
			$iof = $this->input->post('iof');
			$anuidade = $this->input->post('anuidade');
			$restante = $this->input->post('restante');
			$juros = $this->input->post('juros_fat');
			$valor_total = $this->input->post('valor_total');
			$valor_pago = $this->input->post('valor_pagar');
			$idConta = $this->session->userdata('idConta');

			$dados = ['itens_desp'=>$itens_desp, 'encargos'=>$encargos,'iof'=>$iof,'anuidade'=>$anuidade,'protecao'=>$protecao_prem,
			'juros'=>$juros,'restante'=>$restante,'totalgeral'=>$valor_total,'valor_pagar'=>$valor_pago];

			$return = $this->FaturaCartao_model->pagarFatura($dados, $id_cartao_fat, $idConta, $id_cartao_cre);

			if ($return['status'] == 'success') {
				$json = array('status'=>'success', 'message'=>$return['message']);
			}  else {
				$json = array('status'=>'error', 'message'=>$return['message']);
			}
			return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
		} else {
			$this->load->view("v_template", pageNotFound());
		}
	}

	
	public function getRestanteFaturaAnterior() {
		if ($this->input->post()) {
			$id_cartao_cre = (int) $this->input->post('id_cartao_cre');
			$resFatAnt = $this->FaturaCartao_model->getValorFaturaMesAnterior($id_cartao_cre);
			if (!empty($resFatAnt)) {
				$valorTotal = (float) $resFatAnt->valtotal;
				$valorPago = (float) $resFatAnt->valpgo;
				$res = $valorTotal - $valorPago;
				$json = array('status'=>'success', 'message'=>number_format($res, 2, '.', '.' ));
			} else {
				$json = array('status'=>'error', 'message'=>'Valor da Fatura Anterior Indisponível!');
			}
			return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
		}
	}


}
