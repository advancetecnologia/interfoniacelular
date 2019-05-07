<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Senhas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('logado');
        $this->logado->get_logado();
        $this->logado->verifica_primeiro_acesso();
        $this->load->model('Telefones_model');
        $this->load->model('Senhas_model');
    }

    public function index() {
        $data['senhas'] = "";
        $this->template->load('template', 'senhas/senhas_view', $data);
    }

    public function novo() {
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('senha', 'Senha', 'callback_valida_senha');
        $this->form_validation->set_rules('id_condominio', 'Condomínio', 'required');
        $this->form_validation->set_rules('id_apartamento', 'Apartamento', 'required');

        $data['condominios'] = $this->get_condominios($this->session->userdata('id_condominio'));
        $data['apartamentos'] = $this->get_apartamentos($this->session->userdata('id_apartamento'), $this->session->userdata('id_condominio'));
        $qtd_apartamentos = count($data['apartamentos']);
        if ($qtd_apartamentos == 1) {
            $data['num_apartamento'] = $data['apartamentos'][0]->nome;
        } else {
            $data['num_apartamento'] = "";
        }

        if ($this->form_validation->run() == FALSE) {
            $data['mensagens_erro'] = validation_errors();
            $this->template->load('template', 'senhas/nova_senha_view', $data);
        } else {
            $dados = $this->input->post();
            $dados['ativo'] = 1;
            $cotas = $this->Senhas_model->cotas($dados);
            if ($cotas) {
                $this->insert($dados);
            } else {
                $data['mensagens_erro'] = "Seu apartamento já atingiu o limite de 10 senhas!";
                $this->template->load('template', 'senhas/nova_senha_view', $data);
            }
        }
    }

    public function insert($dados) {
        $insert = $this->Senhas_model->insert($dados);
        if ($insert) {
            $id = $insert;
            $this->session->set_userdata("id_senha", $id);
            $id_condominio = $dados['id_condominio'];
            $id_apartamento = $dados['id_apartamento'];
            redirect(base_url('senhas/insert_faixas/' . $id . "/" . $id_condominio . "/" . $id_apartamento));
        } else {
            $data['mensagens_erro'] = "Houve um erro inesperado tente novamente!";
            $this->template->load('template', 'senhas/nova_senha_view', $data);
        }
    }

    public function insert_faixas() {
        $id_senha = $this->uri->segment(3);
        $id_condominio = $this->uri->segment(4);
        $id_apartamento = $this->uri->segment(5);
        $data['id_senha'] = $id_senha;

        if ($id_condominio == $this->session->userdata('id_condominio') && $id_senha == $this->session->userdata('id_senha')) {
            if (empty($id_apartamento)) {
                if ($this->session->userdata('perfil') == 2 || $this->session->userdata('perfil') == 3) {
                    $this->template->load('template', 'telefones/novo_telefone_faixas_view', $data);
                } else {
                    redirect(base_url('senhas'));
                }
            } elseif ($id_apartamento == $this->session->userdata('id_apartamento')) {
                $this->template->load('template', 'senhas/nova_senha_faixas_view', $data);
            } else {
                redirect(base_url('senhas'));
            }
        } else {
            redirect(base_url('senhas'));
        }
    }

    public function valida_senha($senha) {
        if ($senha == "") {
            $this->form_validation->set_message('valida_senha', 'O campo Senha é necessário.');
            return FALSE;
        } else {
            $existe = $this->Senhas_model->verifica_existencia($senha);
            if ($existe) {
                $this->form_validation->set_message('valida_senha', 'Essa senha já existe para esse apartamento');
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    public function get_condominios($id) {
        $data = $this->Telefones_model->get_condominios($id);
        return $data;
    }

    public function get_apartamentos($id, $id_condominio) {
        $data = $this->Telefones_model->get_apartamentos($id, $id_condominio);
        return $data;
    }

}
