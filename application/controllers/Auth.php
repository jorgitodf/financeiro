<?php

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuario_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper("funcoes");
    }

    // public function register()
    // {
    //     $this->load->library('form_validation');
    //
    //     $this->form_validation->set_rules('name', 'Nome', 'required');
    //     $this->form_validation->set_rules('email', 'Email', 'required');
    //     $this->form_validation->set_rules('password', 'Senha', 'required');
    //
    //     if ($this->form_validation->run() === FALSE) {
    //         $this->load->view('template/header');
    //         $this->load->view('auth/register');
    //         $this->load->view('template/footer');
    //     } else {
    //         $data['back'] = '/';
    //         $this->users_model->new();
    //         $this->load->view('template/success', $data);
    //     }
    // }

    public function login()
    {
        $dados["title"] = "Login";
        $dados["view"] = "auth/v_login";
        $this->load->view("v_template", $dados);

        if ($this->input->post()) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            if (empty($email)) {
                $json = array('status'=>'error', 'message'=>'Preencha o E-mail');
            } elseif (empty($password)) {
                $json = array('status'=>'error', 'message'=>'Preencha a Senha');
            } else {
                $return = $this->usuario_model->getEmailSenha($email, $password);
                if ($return['status'] == 'error') {
                    $json = array('status'=>'error', 'message'=>$return['message']);
                }  else {
                    $this->session->set_userdata(['id' => $return['id_usuario'], 'user' => $return['nome_usuario']]);
                    $json = array('status'=>'success', 'message'=>'/');
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));
        }

    }

    public function logout()
    {
        $this->session->unset_userdata('user');
        redirect('auth/login', 'location', 302);
        die();
    }
}
