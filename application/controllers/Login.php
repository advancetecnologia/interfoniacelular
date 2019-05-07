<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function index() {
        $this->template->load('template_inicial', 'login/login_view');
    }

    public function primeiro_acesso() {
        $this->template->load('template_inicial', 'login/primeiro_acesso_view');
    }

    public function autenticacao() {
        $this->load->model('Usuarios_model');
        $this->load->model('Condominios_model');
        $usuario = $this->input->post('usuario');
        $senha = md5($this->input->post('senha'));
        $data['login'] = $this->Usuarios_model->get_usuario_login($usuario, $senha);

        if ($data['login']) {
            if ($data['login']['0']->email == $usuario && $data['login']['0']->senha == $senha && $data['login']['0']->ativo == 1) {
                if ($data['login']['0']->perfil == 3 && $data['login']['0']->email = 'pinaculo@pinaculo.com.br') {
                    $dados = array(
                        'usuario' => $data['login']['0']->email,
                        'logado' => TRUE,
                        'id_usuario' => $data['login']['0']->id,
                        'perfil' => $data['login']['0']->perfil,
                        'email' => $data['login']['0']->email
                    );
                    $this->session->set_userdata($dados);
                    redirect('administrador', 'refresh');
                } else {
                    $dados = array(
                        'usuario' => $data['login']['0']->email,
                        'logado' => TRUE,
                        'id_usuario' => $data['login']['0']->id,
                        'perfil' => $data['login']['0']->perfil,
                        'email' => $data['login']['0']->email,
                        'id_condominio' => $data['login']['0']->id_condominio,
                        'id_apartamento' => $data['login']['0']->id_apartamento,
                        'id_bloco' => $data['login']['0']->id_bloco,
                        'id_revenda' => $data['login']['0']->id_revenda
                    );
                    if($data['login']['0']->perfil == 4){
                        $condominios = $this->Condominios_model->get_condominios_by_revenda($data['login']['0']->id_revenda);
                        $dados['condominios'] = $condominios;
                    }

                    $this->session->set_userdata($dados);
                    $this->load->model('Equipamentos_model');
                    $equipamentos['equipamentos'] = $this->Equipamentos_model->get($this->session->userdata('id_condominio'));
                    $this->session->set_userdata($equipamentos);
                    redirect('home', 'refresh');
                }
            } else {
                $this->session->set_flashdata('falha', 'Usuario ou senha está incorreta');
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('falha', 'Usuario ou senha está incorreta');
            redirect('login');
        }
    }

}
