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
		$this->load->model('faturacartao_model');
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
			$dados["conta"] = $this->conta_model->getAtualSaldo($this->session->userdata('id'), $this->session->userdata('idConta'));
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
			$this->load->view("v_template", pageNotFound());
		}
	}

	public function comprar($id = null)
	{
		if (is_numeric($id) && !empty($id) && $id > 0 && $this->conta_model->verificaConta($id) == true) {
			$dados["title"] = "Lançar Compra Cartão de Crédito";
			$dados["view"] = "cartao/v_lancar_compra";
			$dados["idConta"] = $id;
			$dados["conta"] = $this->conta_model->getAtualSaldo($this->session->userdata('id'), $this->session->userdata('idConta'));
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
			$this->load->view("v_template", pageNotFound());
		}
	}

	public function faturar($id = null) 
	{
        if (is_numeric($id) && !empty($id) && $id > 0 && $this->conta_model->verificaConta($id) == true) {
			$dados["title"] = "Criar Fatura Cartão de Crédito";
			$dados["view"] = "cartao/v_criar_fatura";
			$dados["idConta"] = $id;
			$dados['cartoes'] = $this->cartaocredito_model->getCartoesCreditosByUser($this->session->userdata('id'));
			$this->load->view("v_template", $dados);
		} else if ($this->input->post()) {
			$cartao = $this->input->post('cartao');
			$data_vencimento = $this->input->post('data_vencimento');
			$dados = ['data_vencimento'=>$data_vencimento, 'cartao'=>$cartao];  
			$return = $this->faturacartao_model->cadastrarFaturaCartao($dados);
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
		if (is_numeric($id) && !empty($id) && $id > 0 && $this->conta_model->verificaConta($id) == true) {
			$dados["title"] = "Fechar Fatura Cartão de Crédito";
			$dados["view"] = "cartao/v_fechar_fatura";
			$dados["idConta"] = $id;
			$dados['faturas'] = $this->faturacartao_model->getCartoesFaturaAberta();
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
				$json = array('status' => 'success', 'id_fatura_cartao'=> $id_fatura_cartao);
			}
			return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
		} else {
			$this->load->view("v_template", pageNotFound());
		}
	}

	function faturaPagar($id_fatura_cartao = null)
	{
		if (is_numeric($id_fatura_cartao) && !empty($id_fatura_cartao) && $id_fatura_cartao > 0 && $this->faturacartao_model->verificaFaturaById($id_fatura_cartao) == true) {
			$dados['fatura'] = $this->faturacartao_model->getFaturaCartaoAbertaById($id_fatura_cartao, $this->session->userdata('id'));
			$dados['itensfatura'] = $this->despesacartao_model->getItensDespesaFaturaByIdFaturaCartao($dados['fatura']->idCartao, formataData($dados['fatura']->data));
			$dados["title"] = "Pagar Fatura Cartão de Crédito";
			$dados["view"] = "cartao/v_pagar_fatura";
			$dados["idConta"] = $this->session->userdata('idConta');
			$this->load->view("v_template", $dados);
		} else if ($this->input->post()) {
			$id_cartao_fat = $this->input->post('id_cartao_fat');
			$encargos = $this->input->post('encargos');
			$protecao_prem = $this->input->post('protecao_prem');
			$iof = $this->input->post('iof');
			$anuidade = $this->input->post('anuidade');
			$restante = $this->input->post('restante');
			$juros = $this->input->post('juros_fat');
			$valor_total = $this->input->post('valor_total');
			$valor_pago = $this->input->post('valor_pagar');

			$dados = ['encargos'=>$encargos,'iof'=>$iof,'anuidade'=>$anuidade,'protecao'=>$protecao_prem,
			'juros'=>$juros,'restante'=>$restante,'totalgeral'=>$valor_total,'valor_pagar'=>$valor_pago];

			$return = $this->faturacartao_model->pagarFatura($dados, $id_cartao_fat);

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
			$resFatAnt = $this->faturacartao_model->getValorFaturaMesAnterior($id_cartao_cre);
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
