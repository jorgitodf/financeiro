<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/../validacoes/Validacoes.php';
require_once __DIR__ . '/../repositories/ExtratoRepository.php';
require_once __DIR__ . '/../repositories/UsuarioRepository.php';
require_once __DIR__ . '/../repositories/CategoriaRepository.php';

class Conta extends CI_Controller
{
	private $validacoes;
	private $extrato;
	private $usuario;
	private $categoria;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Extrato_model');
		$this->load->model('Categoria_model');
		$this->load->model('Banco_model');
		$this->validacoes = new Validacoes();
		$this->extrato = new ExtratoRepository();
		$this->usuario = new UsuarioRepository();
		$this->categoria = new CategoriaRepository();
		$this->load->model('TipoConta_model');
		$this->load->model('Conta_model');
		$this->load->model('Agendamento_model');
		$this->load->helper('funcoes');
		$this->load->helper('construct');
	}

	public function index()
	{
		$dados["title"] = "Cadastro de Conta";
		$dados["view"] = "conta/v_index";
		$dados["bancos"] = $this->Banco_model->getBancos();
		$dados["tipoConta"] = $this->TipoConta_model->getTiposContas();
		$this->load->view("v_template", $dados);
	}

	public function cadastrar()
	{
		if ($this->input->post()) {
			$nome_banco = $this->input->post('nome_banco');
			$cod_agencia = $this->input->post('cod_agencia');
			$dig_agencia = $this->input->post('dig_agencia');
			$num_conta = $this->input->post('num_conta');
			$dig_conta = $this->input->post('dig_conta');
			$cod_operacao = $this->input->post('cod_operacao');
			$tipo_conta = $this->input->post('tipo_conta');

			if (empty($nome_banco)) {
				$json = array('status'=>'error', 'message'=>'Selecione o Banco');
			} elseif (empty($cod_agencia)) {
				$json = array('status'=>'error', 'message'=>'Informe o Código da Agência');
			} elseif (empty($num_conta)) {
				$json = array('status'=>'error', 'message'=>'Informe o Número da Conta');
			} elseif (empty($dig_conta)) {
				$json = array('status'=>'error', 'message'=>'Informe o Dígito da Conta');
			} elseif (empty($tipo_conta)) {
				$json = array('status'=>'error', 'message'=>'Informe o Tipo de Conta');
			} else {
				$dados = ['codigo_agencia'=>$cod_agencia, 'digito_verificador_agencia'=>$dig_agencia, 'numero_conta'=>$num_conta,
					'digito_verificador_conta'=>$dig_conta, 'codigo_operacao'=>$cod_operacao, 'data_cadastro'=>date('Y-m-d H:m:s'),
					'fk_id_usuario'=>$this->session->userdata('id'), 'fk_cod_banco'=>$nome_banco, 'fk_tipo_conta'=>$tipo_conta];
				$return = $this->Conta_model->createConta($dados);
				if ($return['status'] == 'success') {
					$json = array('status'=>'success', 'message'=>$return['message']);
				}  else {
					$json = array('status'=>'error', 'message'=>$return['message']);
				}
			}
			return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
		}
	}

	public function acessar($id = null)
	{
		if (is_numeric($id) && $id > 0 && $this->Conta_model->verificaConta($id) == true) {
			$dados["title"] = "Página Principal";
			$dados["view"] = "conta/v_home_conta";
			$dados["idConta"] = $id;
			$dados["conta"] = $this->Conta_model->getAtualSaldo($this->session->userdata('id'), $id);
			$this->session->set_userdata(['idConta' => $id]);
			$this->load->view("v_template", $dados);
		} else {
			$this->load->view("v_template", pageNotFound());
		}
	}

	public function extrato($id)
	{
		if (is_numeric($id) && $id > 0 && $this->Conta_model->verificaConta($id) == true) {
			if ($this->extrato->getExtratoAtual($id) == false) {
				$dados['message'] = "Ainda não existe movimentação neste mês!";
			} else {
				$dados['extrato'] = $this->extrato->getExtratoAtual($id);
				foreach ($dados['extrato'] as $item) {
					$data_final = $item['data_movimentacao'];
				}
				$dataAtual = date("m");
				$data_inicial = "2016-{$dataAtual}-01";
				$dados['data_inicial'] = $data_inicial;
				$dados['data_final'] = $data_final;
			}
			$dados["title"] = "Extrato";
			$dados["idConta"] = $id;
			$dados["conta"] = $this->Conta_model->getAtualSaldo($this->session->userdata('id'), $id);
			$dados["view"] = "extrato/v_extrato";
			$this->load->view("v_template", $dados);
		} else {
			$this->load->view("v_template", pageNotFound());
		}
	}

	public function debitar($id = null)
	{
		if (is_numeric($id) && !empty($id) && $id > 0 && $this->Conta_model->verificaConta($id) == true) {
			$dados["title"] = "Débito";
			$dados["idConta"] = $id;
			$dados['categorias'] = $this->categoria->getCategoriasDespesas();
			$dados["conta"] = $this->Conta_model->getAtualSaldo($this->session->userdata('id'), $id);
			$dados["token"] = $this->usuario->getTokenUsuario($this->session->userdata('id'), $id);
			$dados["view"] = "conta/v_debito";
			$this->load->view("v_template", $dados);
		} else if ($this->input->post()) {
			$this->Extrato_model->setDataMovimentacao($this->input->post('data_debito'));
			$this->Extrato_model->setMes(verificaMes());
			$this->Extrato_model->setTipoOperacao('Débito');
			$this->Extrato_model->setMovimentacao($this->input->post('movimentacao'));
			$this->Extrato_model->setQuantidade(1);
			$this->Extrato_model->setValor($this->input->post('valor'));
			$this->Extrato_model->getConta()->setIdConta($this->input->post('idConta'));
			$this->Extrato_model->getCategoria()->setIdCategoria($this->input->post('nome_categoria'));
			$resultadoValidacao = $this->validacoes->validarDebito($this->Extrato_model);
			if ($this->usuario->getTokenByUserById($this->input->post('token'), $this->session->userdata('id')) == false) {
				$json = array('status'=>'error', 'message'=>'Essa operação não pode ser realizada!');
			} elseif ($resultadoValidacao->getErros() == true) {
				$json = array('status'=>'error', 'message'=>$resultadoValidacao->getErros());
			} else {
				$retorno = $this->extrato->debitar($this->Extrato_model);
				if ($retorno['status'] == 'success') {
					$json = array('status'=>'success', 'message'=>$retorno['message']);
				} else {
					$json = array('status'=>'error', 'message'=>$retorno['message']);
				}
			}
			return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
		} else {
			$this->load->view("v_template", pageNotFound());
		}
	}

	public function creditar($id = null)
	{
		if (is_numeric($id) && $id > 0 && $this->Conta_model->verificaConta($id) == true) {
			$dados["title"] = "Crédito";
			$dados["idConta"] = $id;
			$dados["conta"] = $this->Conta_model->getAtualSaldo($this->session->userdata('id'), $id);
			$dados['categorias'] = $this->categoria->getCategoriasReceitas();
			$dados["token"] = $this->usuario->getTokenUsuario($this->session->userdata('id'), $id);
			$dados["view"] = "conta/v_credito";
			$this->load->view("v_template", $dados);
		} else if ($this->input->post()) {
			$this->Extrato_model->setDataMovimentacao($this->input->post('data_credito'));
			$this->Extrato_model->setMes(verificaMes());
			$this->Extrato_model->setTipoOperacao('Crédito');
			$this->Extrato_model->setMovimentacao($this->input->post('movimentacao'));
			$this->Extrato_model->setQuantidade(1);
			$this->Extrato_model->setValor($this->input->post('valor'));
			$this->Extrato_model->getConta()->setIdConta($this->input->post('idConta'));
			$this->Extrato_model->getCategoria()->setIdCategoria($this->input->post('nome_categoria'));
			$resultadoValidacaoCredito = $this->validacoes->validarCredito($this->Extrato_model);
			if ($this->usuario->getTokenByUserById($this->input->post('token'), $this->session->userdata('id')) == false) {
				$json = array('status'=>'error', 'message'=>'Essa operação não pode ser realizada!');
			} elseif ($resultadoValidacaoCredito->getErros() == true) {
				$json = array('status'=>'error', 'message'=>$resultadoValidacaoCredito->getErros());
			} else {
				$retorno = $this->extrato->creditar($this->Extrato_model);
				if ($retorno['status'] == 'success') {
					$json = array('status'=>'success', 'message'=>$retorno['message']);
				} else {
					$json = array('status'=>'error', 'message'=>$retorno['message']);
				}
			}
			return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
		} else {
			$this->load->view("v_template", pageNotFound());
		}
	}

	public function getSaldo()
	{
		$saldo_post = $this->input->post('saldo_nav');
		$saldo = $this->Conta_model->getAtualSaldo($this->session->userdata('id'), $this->session->userdata('idConta'));
		if (empty($saldo)) {
			$json = array('status'=>'error', 'message'=>'Ops... Sem Saldo');
		}  else {
			$json = array('status'=>'success', 'message'=>number_format($saldo->saldo, 2, ',', '.'));
		}
		return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
	}

	public function montarTabela()
	{
		$idConta = $this->input->post('idConta');
		$ano = date("Y");
		$mes = verificaMes();
		$contas_agendadas = $this->Agendamento_model->getContasAgendadas($idConta);
		$tabela = monta_tabela_pagto_agendado($mes, $ano, $contas_agendadas);
		$json = array('status'=>'success', 'message'=>'Não há nenhum pagamento agendado pagamento hoje!', 'tabela'=>$tabela);
		return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
	}
	
	public function pagar()
	{
		if ($this->input->post()) {
			$idConta = $this->input->post('idConta');
			$ano = date("Y");
			$mes = verificaMes();
			$return = $this->Conta_model->verificaPagamentoAgendado($idConta);
			if ($return['status'] == 'success') {
				$contas_agendadas = $this->Agendamento_model->getContasAgendadas($idConta);
				$tabela = monta_tabela_pagto_agendado($mes, $ano, $contas_agendadas);
				$json = array('status'=>'success', 'message'=>$return['message'], 'tabela'=>$tabela);
			} else {
				$contas_agendadas = $this->Agendamento_model->getContasAgendadas($idConta);
				$tabela = monta_tabela_pagto_agendado($mes, $ano, $contas_agendadas);
				$json = array('status'=>'error', 'message'=>$return['message'], 'tabela'=>$tabela);
			}
			return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
		}
	}

}
