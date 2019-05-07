<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Blocos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('logado');
        $this->logado->get_logado();
        $this->logado->verifica_primeiro_acesso();
        $this->logado->verifica_permissoes(array('2','4'));
        $this->load->model('Blocos_model');
        if ($this->session->userdata('bloco_unico')) {
            redirect(base_url('home'));
        }
    }

    public function index() {
        $data['blocos'] = $this->Blocos_model->get_by_condominio($this->session->userdata('id_condominio'));
        $this->template->load('template', 'blocos/blocos_view', $data);
    }

    public function novo() {
        $this->form_validation->set_rules('nome', 'Nome do bloco', 'required');
        $this->form_validation->set_rules('identificador', 'Identificador', 'required|numeric|greater_than[0]');
        $data['id_condominio'] = $this->session->userdata('id_condominio');
        $data['inputs'] = $this->input->post();
        if ($this->form_validation->run() == FALSE) {
            $data['mensagens_erro'] = validation_errors();
            $this->template->load('template', 'blocos/novo_bloco_view', $data);
        } else {
            $bloco_unico = $this->get_bloco_unico();
            $dados = $this->input->post();
            $dados['ativo'] = 1;
            $insert = $this->Blocos_model->insert($dados, $bloco_unico);
            switch ($insert) {
                case 0:
                    $data['mensagens_erro'] = "Houve um erro ao gravar a informação, tente novamente";
                    $this->template->load('template', 'blocos/novo_bloco_view', $data);
                    break;

                case 1:
                    $data['mensagens_sucesso'] = "Bloco inserido com sucesso";
                    $data['blocos'] = $this->Blocos_model->get_by_condominio($this->session->userdata('id_condominio'));
                    $this->template->load('template', 'blocos/blocos_view', $data);
                    break;

                case 2:
                    $data['mensagens_erro'] = "Já existe um bloco com o mesmo identificador";
                    $this->template->load('template', 'blocos/novo_bloco_view', $data);
                    break;

                case 3:
                    $data['mensagens_erro'] = "O campo Identificador deve conter um número maior que 0";
                    $this->template->load('template', 'blocos/novo_bloco_view', $data);
                    break;

                case 4:
                    $data['mensagens_erro'] = "Você não pode cadastrar novos blocos, pois tem equipamento(s) cadastrado para o bloco único";
                    $this->template->load('template', 'blocos/novo_bloco_view', $data);
                    break;

                case 5:
                    $data['mensagens_erro'] = "Você não pode cadastrar novos blocos, pois tem apartamento(s) cadastrado para o bloco único";
                    $this->template->load('template', 'blocos/novo_bloco_view', $data);
                    break;
            }
        }
    }

    public function editar() {
        $id = $this->uri->segment(3);
        $valida_editar = $this->Blocos_model->valida_editar_excluir($id, $this->session->userdata('id_condominio'));
        $data['bloco'] = $this->Blocos_model->get_bloco_by_id($id);
        $data['id_condominio'] = $this->session->userdata('id_condominio');
        $this->template->load('template', 'blocos/blocos_edit_view', $data);
    }

    public function salva_edicao() {
        $this->form_validation->set_rules('nome', 'Nome do bloco', 'required');
        $this->form_validation->set_rules('identificador', 'Identificador', 'required|numeric|greater_than[0]');
        $data['bloco'] = $this->Blocos_model->get_bloco_by_id($this->input->post('id'));
        $data['id_condominio'] = $this->session->userdata('id_condominio');
        if ($this->form_validation->run() == FALSE) {
            $data['mensagens_erro'] = validation_errors();
            $this->template->load('template', 'blocos/blocos_edit_view', $data);
        } else {
            $dados = $this->input->post();
            $update = $this->Blocos_model->update($dados);
            switch ($update) {
                case 0:
                    $data['mensagens_erro'] = "Houve um erro ao gravar a informação, tente novamente";
                    $this->template->load('template', 'blocos/blocos_edit_view', $data);
                    break;
                case 1:
                    $data['mensagens_sucesso'] = "Bloco alterado com sucesso";
                    $data['blocos'] = $this->Blocos_model->get_by_condominio($this->session->userdata('id_condominio'));
                    $this->template->load('template', 'blocos/blocos_view', $data);
                    break;
                case 2:
                    $data['mensagens_erro'] = "Já existe um bloco com o mesmo identificador";
                    $this->template->load('template', 'blocos/blocos_edit_view', $data);
                    break;
            }
        }
    }

    public function delete() {
        $id = $this->uri->segment(3);
        $valida_editar = $this->Blocos_model->valida_editar_excluir($id, $this->session->userdata('id_condominio'));
        $delete = $this->Blocos_model->delete($id);
        switch ($delete) {
            case 0:
                $data['mensagens_erro'] = "Houve um erro tente novamente!";
                break;

            case 1:
                $data['mensagens_sucesso'] = "Bloco excluido com sucesso!";
                break;

            case 2:
                $data['mensagens_erro'] = "Existem apartamentos cadastrados para esse bloco!";
                break;

            case 3:
                $data['mensagens_erro'] = "Existem equipamentos cadastrados para esse bloco!";
                break;
        }

        $data['blocos'] = $this->Blocos_model->get_by_condominio($this->session->userdata('id_condominio'));
        $this->template->load('template', 'blocos/blocos_view', $data);
    }

    public function novo_by_modal() {
        $bloco_unico = $this->get_bloco_unico();
        $dados = $this->input->get();
        $dados['ativo'] = 1;
        $insert = $this->Blocos_model->insert($dados, $bloco_unico);
        echo $insert;
    }

    public function get_by_condominio() {
        $id_condominio = $this->input->get('id_condominio');
        $get = $this->Blocos_model->get_by_condominio($id_condominio);
        echo json_encode($get);
    }

    public function get_bloco_unico() {
        $id_condominio = $this->session->userdata('id_condominio');
        $bloco_unico = $this->Blocos_model->get_bloco_unico($id_condominio);
        return $bloco_unico;
    }

}
