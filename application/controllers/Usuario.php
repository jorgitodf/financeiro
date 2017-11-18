<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/../repositories/UsuarioRepository.php';

class Usuario extends CI_Controller 
{
    protected $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuario_model');
        $this->load->helper("funcoes");
        $this->usuario = new UsuarioRepository();
    }

  	public function index()
  	{
        $dados["title"] = "Novo Usuário";
        $dados["view"] = "usuario/v_usuario_novo";
        $this->load->view("v_template", $dados);
        if ($this->input->post()) {
            if ($this->Usuario_model->setNome($this->input->post('nome'))['status'] == 'error') {
                $json = array('status'=>'error', 'message'=>$this->Usuario_model->setNome($this->input->post('nome'))['message']);
            } elseif ($this->Usuario_model->setEmaiil($this->input->post('email'))['status'] == 'error') {
                $json = array('status'=>'error', 'message'=>$this->Usuario_model->setEmaiil($this->input->post('email'))['message']);
            } elseif ($this->Usuario_model->setSenha($this->input->post('password'))['status'] == 'error') {
                $json = array('status'=>'error', 'message'=>$this->Usuario_model->setSenha($this->input->post('password'))['message']);
            } elseif ($this->Usuario_model->setRepeatSenha($this->input->post('repeat_password'))['status'] == 'error') {
                $json = array('status'=>'error', 'message'=>$this->Usuario_model->setRepeatSenha($this->input->post('repeat_password'))['message']);
            } else {
                $dados = ['nome'=>$this->Usuario_model->getNome(), 'email'=>$this->Usuario_model->getEmaiil(),
                    'senha'=>$this->Usuario_model->getSenha()];
                $result = $this->usuario->createUsuario($dados);
                if ($result['status'] == 'success') {
                    $json = array('status'=>'success', 'message'=>$result['message']);
                }  else {
                    $json = array('status'=>'error', 'message'=>$result['message']);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
        }
    }

}
