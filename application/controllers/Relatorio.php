<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/../repositories/ContaRepository.php';
require_once __DIR__ . '/../repositories/UsuarioRepository.php';
require_once __DIR__ . '/../validacoes/Validacoes.php';
require_once __DIR__ . '/../repositories/ExtratoRepository.php';
require_once __DIR__ . '/../repositories/CategoriaRepository.php';

class Relatorio extends CI_Controller
{
    private $conta;
    private $usuario;
    private $validacoes;
    private $extrato;
    private $dados_relatorio;
    private $categoria;

    public function __construct()
    {
        parent::__construct();
        $this->conta = new ContaRepository();
        $this->usuario = new UsuarioRepository();
        $this->validacoes = new Validacoes();
        $this->extrato = new ExtratoRepository();
        $this->categoria = new CategoriaRepository();
        $this->dados_relatorio = "";
    }

    public function index()
    {
        if (is_numeric($this->session->userdata('idConta')) && $this->conta->verificaConta($this->session->userdata('idConta')) == true) {
            $dados["title"] = "Página Relatório";
            $dados['categorias'] = $this->categoria->getCategoriasDespesas();
            $dados["token"] = $this->usuario->getTokenUsuario($this->session->userdata('id'));
            $dados["view"] = "relatorio/v_relatorio_index";
            $this->load->view("v_template", $dados);
        } else {
            $this->load->view("v_template", pageNotFound());
        }
    }

    public function buscarRelatorioAnual()
    {
        if ($this->input->post()) {
            $ano = $this->input->post('ano');
            $categoria = $this->input->post('categoria');
            $resultadoValidacao = $this->validacoes->validarRelatorioAnual($categoria, $ano);
            if ($this->usuario->getTokenByUserById($this->input->post('token'), $this->session->userdata('id')) == false) {
                $json = array('status'=>'error', 'message'=>'Essa operação não pode ser realizada!');
            } elseif ($resultadoValidacao->getErros() == true) {
                $json = array('status'=>'error', 'message'=>$resultadoValidacao->getErros());
            } else {
                $retorno = $this->extrato->gerarRelatorioAnual($categoria, $ano);
                if ($retorno['status'] == 'success') {
                    $json = array('status' => 'success', 'dados' => $retorno['dados']);
                } else {
                    $json = array('status'=>'error', 'message'=>$retorno['error']);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
        }
        $this->load->view("v_template", pageNotFound());
    }

}