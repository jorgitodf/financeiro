<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/../validacoes/Validacoes.php';
require_once __DIR__ . '/../repositories/UsuarioRepository.php';
require_once __DIR__ . '/../repositories/CategoriaRepository.php';
require_once __DIR__ . '/../repositories/ContaRepository.php';
require_once __DIR__ . '/../repositories/AgendamentoRepository.php';

class Agendamento extends CI_Controller
{
	private $categoria;
	private $usuario;
	private $conta;
	private $agendamento;

	public function __construct()
	{
		parent::__construct();
		$this->categoria = new CategoriaRepository();
		$this->usuario = new UsuarioRepository();
		$this->conta = new ContaRepository();
		$this->agendamento = new AgendamentoRepository();
		$this->load->model('banco_model');
		$this->load->model('tipoconta_model');
		$this->load->model('conta_model');
		$this->load->model('categoria_model');
		$this->load->model('agendamento_model');
		$this->load->helper('url');
		$this->load->helper('funcoes');
		date_default_timezone_set('America/Sao_Paulo');
	}

	public function listar()
	{
		$dados["title"] = "Listagem de Pagamentos Agendados";
		$dados['pgtos_count']  = $this->agendamento_model->getCount();
		$dados['p_count'] = ceil($dados['pgtos_count'] / 15);
		$offset = 0;
		$data['p'] = 1;
		
		if ($this->input->get()) {
			$p = $this->input->get('p');
			if (is_numeric($p) && !empty($p) && $p !== false && $p <= $dados['p_count'] || $p == 0) {
				$data['p'] = $p;
				if($data['p'] == 0) {
					$data['p'] = 1;
				} 
			} else {
				redirect('/agendamento/page-not-found', 'refresh');
			}
		}
		
		$offset = (15 * ($data['p'] - 1));
		$dados['pgto_agendados'] = $this->agendamento_model->getAllPgamentosAgendados($offset);
		$dados["conta"] = $this->conta_model->getAtualSaldo($this->session->userdata('id'), $this->session->userdata('idConta'));
		$dados["view"] = "agendamento/v_listagem_agendamentos";
		$this->load->view("v_template", $dados);
    }
    
    public function agendar($id = null)
	{
		if (is_numeric($id) && !empty($id) && $id > 0 && $this->conta_model->verificaConta($id) == true) {
			$dados["title"] = "Agendar Pagamento";
			$dados["idConta"] = $id;
			$dados["conta"] = $this->conta->getAtualSaldo($this->session->userdata('id'), $this->session->userdata('idConta'));
			$dados['categorias'] = $this->categoria->getCategoriasDespesas();
			$dados["token"] = $this->usuario->getTokenUsuario($this->session->userdata('id'), $id);
			$dados["view"] = "agendamento/v_agendamento";
            $this->load->view("v_template", $dados);
		} else if ($this->input->post()) {
			$this->agendamento_model->setDataPagamento($this->input->post('data_pgto'));
			$this->agendamento_model->setMovimentacao($this->input->post('movimentacao'));
			$this->agendamento_model->setValor($this->input->post('valor'));
			$this->agendamento_model->setPago('Não');
			$this->agendamento_model->getCategoria()->setIdCategoria($this->input->post('nome_categoria'));
			$this->agendamento_model->getConta()->setIdConta($this->input->post('idConta'));
			if ($this->usuario->getTokenByUserById($this->input->post('token'), $this->session->userdata('id')) == false) {
				$json = array('status'=>'error', 'message'=>'Essa operação não pode ser realizada!');
			} else {
				$retorno = $this->agendamento->cadastrarPgtoAgendado($this->agendamento_model);
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

	public function alterar($id = null) {
        if (is_numeric($id) && !empty($id) && $id > 0) {
			$dados["title"] = "Alterar Pagamento Agendado";
			$dados["idUser"] = $this->session->userdata('id');
			$dados["idConta"] = $this->session->userdata('idConta');
			$dados['categorias'] = $this->categoria->getCategoriasDespesas();
			$dados['pagamento'] = $this->agendamento_model->getPagamentoAgendado($id);
			$dados["conta"] = $this->conta_model->getAtualSaldo($this->session->userdata('id'), $this->session->userdata('idConta'));
			$dados["view"] = "agendamento/v_alterar_pgto_agendado";
			$this->load->view("v_template", $dados);
		} elseif ($this->input->post()) {
            $idConta = $this->input->post('idConta');
            $idPgtoAgendado = $this->input->post('idPgtoAgendado');
            $dtPgto = $this->input->post('data_pgto');
            $movPgto = $this->input->post('mov_pgto');
            $categoriaPgto = $this->input->post('categoria_pgto');
            $valorPgto = $this->input->post('valor_pgto');
			
			$dados = ['idConta'=>$idConta, 'idPgtoAgendado'=>$idPgtoAgendado, 'dt_pgto'=>$dtPgto, 'mov_pgto'=>$movPgto,
			'categoria_pgto'=>$categoriaPgto, 'valor_pgto'=>$valorPgto];

			$return = $this->agendamento_model->alterarPgtoAgendado($dados);
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

	public function pageNotFound()
	{
		$this->load->view("v_template", pageNotFound());
	}
}
