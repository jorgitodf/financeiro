<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/../repositories/ContaRepository.php';
require_once __DIR__ . '/../repositories/UsuarioRepository.php';
require_once __DIR__ . '/../validacoes/Validacoes.php';
require_once __DIR__ . '/../repositories/ExtratoRepository.php';

class Relatorio extends CI_Controller
{
    private $conta;
    private $usuario;
    private $validacoes;
    private $extrato;
    public $dados_relatorio;

    public function __construct()
    {
        parent::__construct();
        $this->conta = new ContaRepository();
        $this->usuario = new UsuarioRepository();
        $this->validacoes = new Validacoes();
        $this->extrato = new ExtratoRepository();
    }

    public function index()
    {
        if (is_numeric($this->session->userdata('idConta')) && $this->conta->verificaConta($this->session->userdata('idConta')) == true) {
            $dados["title"] = "Página Relatório";
            $dados["view"] = "relatorio/v_relatorio_index";
            $dados["token"] = $this->usuario->getTokenUsuario($this->session->userdata('id'));
            $this->load->view("v_template", $dados);
        } else {
            $this->load->view("v_template", pageNotFound());
        }
    }

    public function buscarRelatorioAnual()
    {
        if ($this->input->post()) {
            $ano = $this->input->post('ano');
            $resultadoValidacao = $this->validacoes->validarRelatorioAnual($ano);
            if ($this->usuario->getTokenByUserById($this->input->post('token'), $this->session->userdata('id')) == false) {
                $json = array('status'=>'error', 'message'=>'Essa operação não pode ser realizada!');
            } elseif ($resultadoValidacao->getErros() == true) {
                $json = array('status'=>'error', 'message'=>$resultadoValidacao->getErros());
            } else {
                $retorno = $this->extrato->gerarRelatorioAnual($ano);
                if ($retorno['status'] == 'success') {
                    $base_url = 'http';
                    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") $base_url .= "s";
                        $base_url .= "://";
                    if ($_SERVER["SERVER_PORT"] != "80") $base_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
                    else $base_url .= $_SERVER["SERVER_NAME"];
                        $base_url."/";
                    $json = array('status' => 'success', 'base_url' => $base_url, 'dados' => $retorno['message']);
                } else {
                    $json = array('status'=>'error', 'message'=>$retorno['error']);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
        }
        $this->load->view("v_template", pageNotFound());
    }

    public function listarRelatorioAnual()
    {
        $dados["title"] = "Página Listar Relatório";
        print_r($this->dados_relatorio);exit;
        $dados["view"] = "relatorio/v_relatorio_listar_anual";
        $this->load->view("v_template", $dados);
    }
}