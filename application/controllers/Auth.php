<?php

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuario_model');
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
        if (empty($this->session->userdata('user'))) {
            $dados["title"] = "Login";
            $dados["view"] = "auth/v_login";
            $this->load->view("v_template", $dados);
        } else {
            redirect('/', 'refresh');
        }
        
        if ($this->input->post()) {
            $email = stripHTMLtags($this->input->post('email'));
            $password = $this->input->post('password');
            if (empty($email)) {
                $json = array('status'=>'error', 'message'=>'Preencha o E-mail');
            } elseif (empty($password)) {
                $json = array('status'=>'error', 'message'=>'Preencha a Senha');
            } else {
                $return = $this->Usuario_model->getEmailSenha($email, $password);
                if ($return['status'] == 'error') {
                    $json = array('status'=>'error', 'message'=>$return['message']);
                } else {
                    $this->session->set_userdata(['id' => $return['id_usuario'], 'user' => $return['nome_usuario']]);
                    $base_url = 'http';
                    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") $base_url .= "s";
                    $base_url .= "://";
                    if ($_SERVER["SERVER_PORT"] != "80") $base_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
                    else $base_url .= $_SERVER["SERVER_NAME"];
                    $base_url."/";
                    $json = array('status' => 'success', 'base_url'=>$base_url);
                }
            }
            return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));    
        }
    }

    public function logout()
    {

        $this->session->unset_userdata('user');
        $this->session->unset_userdata('id');
        $this->session->unset_userdata('idConta');
        redirect('auth/login', 'location', 302);
        die();
    }
}
