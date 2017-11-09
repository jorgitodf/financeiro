<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuario_model');
        $this->load->helper("funcoes");
        //$this->load->helper('url');
        //$this->load->helper("funcoes");
    }

  	public function index()
  	{
        $dados["title"] = "Novo Usuário";
        $dados["view"] = "usuario/v_usuario_novo";
        $this->load->view("v_template", $dados);

        if ($this->input->post()) {
            $nome = $this->input->post('nome');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $repeat_password = $this->input->post('repeat_password');

            if (empty($nome)) {
                $json = array('status'=>'error', 'message'=>'Preencha o seu Nome!');
            } elseif (empty($email)) {
                $json = array('status'=>'error', 'message'=>'Preencha o seu E-mail!');
            } elseif (empty($password)) {
                $json = array('status'=>'error', 'message'=>'Preencha a sua Senha!');
            } elseif (empty($repeat_password)) {
                $json = array('status'=>'error', 'message'=>'Digite novamente a sua Senha!');
            } elseif ($password !== $repeat_password) {
                $json = array('status'=>'error', 'message'=>'As Senhas digitadas não são iguais!');
            } else {
                $data = ['nome' => $nome, 'email' => $email, 'senha' => cryptySenha($password), 'data_cadastro' => date('Y-m-d H:m:s')];
                $json = $this->usuario_model->createUsuario($data);
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
        }


    }

}
