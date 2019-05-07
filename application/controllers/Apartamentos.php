<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apartamentos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('logado');
        $this->logado->get_logado();
        $this->logado->verifica_primeiro_acesso();
        $this->logado->verifica_permissoes(array('2','4'));
        $this->load->model('Apartamentos_model');
        $this->load->model('Telefones_model');
        $this->load->model('Usuarios_model');
    }

    public function index() {
        $data['apartamentos'] = $this->Apartamentos_model->get(0, $this->session->userdata('id_condominio'));
        $this->template->load('template', 'apartamentos/apartamentos_view', $data);
    }

    public function novo() {
        $this->form_validation->set_rules('numero_apartamento', 'Número do apartamento', 'required|numeric');
        $this->form_validation->set_rules('id_bloco', 'Bloco', 'required');
        $data['id_condominio'] = $this->session->userdata('id_condominio');
        if ($this->form_validation->run() == FALSE) {
            $data['mensagens_erro'] = validation_errors();
            $this->template->load('template', 'apartamentos/novo_apartamentos_view', $data);
        } else {
            $bloco = $this->input->post('id_bloco');
            $apartamento = $this->input->post('numero_apartamento');
            $existe = $this->verifica_existencia($bloco, $apartamento);
            if ($existe) {
                $data['mensagens_erro'] = "Esse apartamento já existe para esse bloco";
                $this->template->load('template', 'apartamentos/novo_apartamentos_view', $data);
            } else {
                $dados = $this->input->post();
                if ($this->input->post('id_bloco') == 0) {
                    $dados['id_bloco'] = NULL;
                }
                $dados['cod_hash'] = $this->gera_hash($this->session->userdata('id_condominio'), $this->input->post('id_bloco'), $this->input->post('numero_apartamento'));
                $dados['id_condominio'] = $this->session->userdata('id_condominio');
                $dados['ativo'] = 1;
                $insert = $this->Apartamentos_model->insert($dados);
                if ($insert) {
                    $data['apartamentos'] = $this->Apartamentos_model->get(0, $this->session->userdata('id_condominio'));
                    $data['mensagens_sucesso'] = "Apartamento inserido com sucesso";
                    $this->template->load('template', 'apartamentos/apartamentos_view', $data);
                } else {
                    $data['mensagens_erro'] = "Houve um erro, na gravação dos dados, tente novamente";
                    $data['id_condominio'] = $this->session->userdata('id_condominio');
                    $this->template->load('template', 'apartamentos/novo_apartamentos_view', $data);
                }
            }
        }
    }

    public function editar() {
        $id = $this->uri->segment(3);
        $valida_editar = $this->Apartamentos_model->valida_editar_exluir($id, $this->session->userdata('id_condominio'));
        $data['apartamento'] = $this->get_apartamentos_by_id($id);
        $data['id_condominio'] = $this->session->userdata('id_condominio');
        $this->template->load('template', 'apartamentos/apartamentos_edit_view', $data);
    }

    public function salva_edicao() {
        $this->form_validation->set_rules('numero_apartamento', 'Número do apartamento', 'required|numeric');
        $this->form_validation->set_rules('id_bloco', 'Bloco', 'required');
        $id_apartamento = $this->input->post('id');
        $data['id_condominio'] = $this->session->userdata('id_condominio');

        if ($this->form_validation->run() == FALSE) {
            $data['mensagens_erro'] = validation_errors();
            $data['apartamento'] = $this->get_apartamentos_by_id($id_apartamento);
            $this->template->load('template', 'apartamentos/apartamentos_edit_view', $data);
        } else {
            $bloco = $this->input->post('id_bloco');
            $apartamento = $this->input->post('numero_apartamento');
            $existe = $this->verifica_existencia($bloco, $apartamento, $id_apartamento);
            if ($existe) {
                $data['apartamento'] = $this->get_apartamentos_by_id($id_apartamento);
                $data['mensagens_erro'] = "Esse apartamento já existe para esse bloco";
                $this->template->load('template', 'apartamentos/apartamentos_edit_view', $data);
            } else {
                $dados = $this->input->post();
                if ($this->input->post('id_bloco') == 0) {
                    $dados['id_bloco'] = NULL;
                }
                $dados['cod_hash'] = $this->gera_hash($this->session->userdata('id_condominio'), $this->input->post('id_bloco'), $this->input->post('numero_apartamento'));
                $dados['id_condominio'] = $this->session->userdata('id_condominio');
                $update = $this->Apartamentos_model->update($dados);
                if ($update) {
                    $data['apartamentos'] = $this->Apartamentos_model->get(0, $this->session->userdata('id_condominio'));
                    $data['mensagens_sucesso'] = "Apartamento alterado com sucesso";
                    $this->template->load('template', 'apartamentos/apartamentos_view', $data);
                } else {
                    $data['mensagens_erro'] = "Houve um erro, na gravação dos dados, tente novamente";
                    $data['apartamento'] = $this->get_apartamentos_by_id($id_apartamento);
                    $data['id_condominio'] = $this->session->userdata('id_condominio');
                    $this->template->load('template', 'apartamentos/apartamentos_edit_view', $data);
                }
            }
        }
    }

    public function delete() {
        $id = $this->uri->segment(3);
        $valida_excluir = $this->Apartamentos_model->valida_editar_exluir($id, $this->session->userdata('id_condominio'));
        $delete_telefones = $this->Telefones_model->delete_telefones_by_apartamento($id);
        if ($delete_telefones) {
            $delete = $this->Apartamentos_model->delete($id);
            if ($delete) {
                $data['mensagens_sucesso'] = "Apartamento excluido com sucesso!";
            } else {
                $data['mensagens_erro'] = "Houve um erro tente novamente!";
            }
        } else {
            $data['mensagens_erro'] = "Houve um erro tente novamente!";
        }
        $data['apartamentos'] = $this->Apartamentos_model->get(0, $this->session->userdata('id_condominio'));
        $this->template->load('template', 'apartamentos/apartamentos_view', $data);
    }

    public function ativa_desativa_apartamento() {
        $valor = $this->input->get('valor');
        $id_apartamento = $this->input->get('id_apartamento');
        if ($valor == 'true') {
            $ativa = $this->Apartamentos_model->ativa_apartamento($id_apartamento);
            if ($ativa) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            $desativa = $this->Apartamentos_model->desativa_apartamento($id_apartamento);
            if ($desativa) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    public function verifica_existencia($id_bloco, $apartamento, $id_apartamento = NULL) {
        return $existe = $this->Apartamentos_model->verifica_existencia($id_bloco, $apartamento, $id_apartamento);
    }

    public function gera_hash($id_condominio, $id_bloco, $apartamento) {
        $time = time();
        $hash = $id_condominio . $id_bloco . $apartamento . $time;
        $hash_hexa = dechex((float) $hash);
        return $hash_hexa;
    }

    public function get_apartamentos_by_id($id) {
        $data = $this->Apartamentos_model->get_apartamentos_by_id($id);
        return $data;
    }

    public function esvazia_apartamento() {
        $id = $this->input->post('id_apartamento_esvaziar');
        $id_bloco = $this->input->post('id_bloco_esvaziar');
        $id_condominio = $this->session->userdata('id_condominio');
        $valida = $this->Apartamentos_model->valida_editar_exluir($id, $id_condominio);
        $hash = $this->gera_hash($id_condominio, $id_bloco, $id);
        $esvazia = $this->Apartamentos_model->esvazia_apartamento($id, $hash);
        if ($esvazia) {
            $data['mensagens_sucesso'] = "Apartamento esvaziado com sucesso!";
        } else {
            $data['mensagens_erro'] = "Houve um erro, na gravação dos dados, tente novamente";
        }
        $data['apartamentos'] = $this->Apartamentos_model->get(0, $this->session->userdata('id_condominio'));
        $this->template->load('template', 'apartamentos/apartamentos_view', $data);
    }

    public function delete_selecionados() {
        $checkbox = $this->input->post('checkbox');
        $delete_selecionados = $this->Apartamentos_model->delete_selecionados($checkbox);
        if ($delete_selecionados) {
            echo 1;
        } else {
            echo 0;
        }
    }

}
