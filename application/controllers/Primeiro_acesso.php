<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Primeiro_acesso extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('logado');
        $this->logado->get_logado();
        $this->load->model('Condominios_model');
        $this->load->model('Usuarios_model');
    }

    public function index() {
        $this->template->load('template_inicial', 'login/primeiro_acesso_view');
    }

    public function set_bloco_unico() {
        $id_condominio = $this->session->userdata('id_condominio');
        $primeiro_acesso = $this->Usuarios_model->get_primeiro_acesso();
        if ($primeiro_acesso[0]->primeiro_acesso) {
            $set_bloco_unico = $this->Condominios_model->set_bloco_unico($id_condominio);
            if ($set_bloco_unico) {
                $this->remove_primeiro_acesso();
            } else {
                $data['mensagens_erro'] = "Houve um erro, na gravação dos dados, tente novamente";
                $this->template->load('template_inicial', 'login/primeiro_acesso_view', $data);
            }
        } else {
            redirect(base_url('home'));
        }
    }

    public function qtd_digitos() {
        $this->template->load('template_inicial', 'login/primeiro_acesso_qtd_digitos_view');
    }

    public function set_blocos() {
        $id_condominio = $this->session->userdata('id_condominio');
        $primeiro_acesso = $this->Usuarios_model->get_primeiro_acesso();
        if ($primeiro_acesso[0]->primeiro_acesso) {
            $set_blocos = $this->Condominios_model->set_blocos($id_condominio, $qtd_digitos);
            if ($set_blocos) {
                $this->remove_primeiro_acesso();
            } else {
                $data['mensagens_erro'] = "Houve um erro, na gravação dos dados, tente novamente";
                $this->template->load('template_inicial', 'login/primeiro_acesso_view', $data);
            }
        } else {
            redirect(base_url('home'));
        }
    }

    public function remove_primeiro_acesso() {
        $remove = $this->Usuarios_model->remove_primeiro_acesso();
        if ($remove) {
            redirect('home');
        } else {
            $data['mensagens_erro'] = "Houve um erro, na gravação dos dados, tente novamente";
            $this->template->load('template_inicial', 'login/primeiro_acesso_view', $data);
        }
    }

}
