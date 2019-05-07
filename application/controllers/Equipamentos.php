<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Equipamentos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('logado');
        $this->logado->get_logado();
        $this->logado->verifica_primeiro_acesso();
        $this->logado->verifica_permissoes(array('2', '4'));
        $this->load->model('Equipamentos_model');
    }

    public function index() {
        $data['equipamentos'] = $this->Equipamentos_model->get($this->session->userdata('id_condominio'));
        $this->template->load('template', 'equipamentos/equipamentos_view', $data);
    }

    public function novo() {
        if ($this->input->post('control_qtd_digitos')) {
            $this->form_validation->set_rules('qtd_digitos', 'Quantidade de dígitos', 'required|numeric|trim|greater_than[0]');
        }
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('imei', 'IMEI', 'required|numeric|trim|exact_length[15]');
        $this->form_validation->set_rules('id_bloco', 'Bloco', 'required');
        $data['id_condominio'] = $this->session->userdata('id_condominio');
        $data['inputs'] = $this->input->post();
        if ($this->form_validation->run() == FALSE) {
            $data['mensagens_erro'] = validation_errors();
            $this->template->load('template', 'equipamentos/novo_equipamento_view', $data);
        } else {
            $valida_imei = $this->valida_imei($this->input->post('imei'));
            if ($valida_imei) {
                $existe = $this->verifica_existencia($this->input->post('imei'));
                if ($existe) {
                    $data['mensagens_erro'] = "Esse equipamento já existe";
                    $this->template->load('template', 'equipamentos/novo_equipamento_view', $data);
                } else {
                    $dados = $this->input->post();
                    if ($this->input->post('id_bloco') == '9db1737630d272e4fe7673185fa9db36') {
                        $dados['equip_portaria'] = TRUE;
                        $dados['id_bloco'] = NULL;
                    } else {
                        $dados['qtd_digitos'] = FALSE;
                        $dados['equip_portaria'] = FALSE;
                    }
                    $dados['ativo'] = 1;
                    unset($dados['control_qtd_digitos']);
                    $insert = $this->Equipamentos_model->insert($dados);
                    if ($insert) {
                        $data['mensagens_sucesso'] = "Equipamento inserido com sucesso";
                        $data['equipamentos'] = $this->Equipamentos_model->get($this->session->userdata('id_condominio'));
                        $this->template->load('template', 'equipamentos/equipamentos_view', $data);
                    } else {
                        $data['mensagens_erro'] = "Houve um erro, na gravação dos dados, tente novamente";
                        $this->template->load('template', 'equipamentos/novo_equipamento_view', $data);
                    }
                }
            } else {
                $data['mensagens_erro'] = "IMEI inválido";
                $this->template->load('template', 'equipamentos/novo_equipamento_view', $data);
            }
        }
    }

    function valida_imei($imei) {
        settype($imei, 'string');
        $sumTable = array(
            array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
            array(0, 2, 4, 6, 8, 1, 3, 5, 7, 9));
        $sum = 0;
        $flip = 0;
        for ($i = strlen($imei) - 1; $i >= 0; $i--) {
            $sum += $sumTable[$flip++ & 0x1][$imei[$i]];
        }
        return $sum % 10 === 0;
    }

    public function editar() {
        $id = $this->uri->segment(3);
        $valida_editar = $this->Equipamentos_model->valida_editar_excluir($id, $this->session->userdata('id_condominio'));
        $data['equipamento'] = $this->get_equipamento_by_id($id);
        $data['id_condominio'] = $this->session->userdata('id_condominio');
        $this->template->load('template', 'equipamentos/equipamentos_edit_view', $data);
    }

    public function salva_edicao() {
        if ($this->input->post('control_qtd_digitos')) {
            $this->form_validation->set_rules('qtd_digitos', 'Quantidade de dígitos', 'required|numeric|trim|greater_than[0]');
        }
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('id_bloco', 'Bloco', 'required');
        $data['id_condominio'] = $this->session->userdata('id_condominio');
        $id_equipamento = $this->input->post('id');
        if ($this->form_validation->run() == FALSE) {
            $data['mensagens_erro'] = validation_errors();
            $data['equipamento'] = $this->get_equipamento_by_id($id_equipamento);
            $this->template->load('template', 'equipamentos/equipamentos_edit_view', $data);
        } else {
            $dados = $this->input->post();
            unset($dados['imei']);
            unset($dados['control_qtd_digitos']);

            if ($this->input->post('id_bloco') == '9db1737630d272e4fe7673185fa9db36') {
                $dados['equip_portaria'] = TRUE;
                $dados['id_bloco'] = NULL;
            } else {
                $dados['qtd_digitos'] = FALSE;
                $dados['equip_portaria'] = FALSE;
            }
            $update = $this->Equipamentos_model->update($dados);
            if ($update) {
                $data['mensagens_sucesso'] = "Apartamento alterado com sucesso";
                $data['equipamentos'] = $this->Equipamentos_model->get($this->session->userdata('id_condominio'));
                $this->template->load('template', 'equipamentos/equipamentos_view', $data);
            } else {
                $data['mensagens_erro'] = "Houve um erro, na gravação dos dados, tente novamente";
                $this->template->load('template', 'equipamentos/novo_equipamento_view', $data);
            }
        }
    }

    public function verifica_existencia($imei, $id_equipamento = NULL) {
        return $existe = $this->Equipamentos_model->verifica_existencia($imei, $id_equipamento);
    }

    public function get_equipamento_by_id($id) {
        $data = $this->Equipamentos_model->get_equipamento_by_id($id);
        return $data;
    }

    public function delete() {
        $id = $this->uri->segment(3);
        $valida_excluir = $this->Equipamentos_model->valida_editar_excluir($id, $this->session->userdata('id_condominio'));
        $delete = $this->Equipamentos_model->delete($id);
        if ($delete) {
            $data['mensagens_sucesso'] = "Equipamento excluido com sucesso!";
        } else {
            $data['mensagens_erro'] = "Houve um erro tente novamente!";
        }
        $data['equipamentos'] = $this->Equipamentos_model->get($this->session->userdata('id_condominio'));
        $this->template->load('template', 'equipamentos/equipamentos_view', $data);
    }

    public function ativa_desativa_equipamentos() {
        $valor = $this->input->get('valor');
        $id_equipamento = $this->input->get('id_equipamento');
        if ($valor == 'true') {
            $ativa = $this->Equipamentos_model->ativa_equipamento($id_equipamento);
            if ($ativa) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            $desativa = $this->Equipamentos_model->desativa_equipamento($id_equipamento);
            if ($desativa) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    public function get_eventos_equipamentos() {
        $id_equipamento = $this->input->get('id');
        $pendentes = $this->Equipamentos_model->get_pendende_trasmissao($id_equipamento);
        $ultima_comunicacao = $this->Equipamentos_model->get_ultima_comunicacao($id_equipamento);
        $return['pendentes'] = $pendentes;
        $return['ultima_comunicacao'] = substr($ultima_comunicacao->data, 8,2) . "/" . substr($ultima_comunicacao->data, 5,2) . "/" . substr($ultima_comunicacao->data, 0,4). " - ".substr($ultima_comunicacao->data, 10);
        echo json_encode($return);
    }

    public function status() {
        $data['equipamentos'] = $this->Equipamentos_model->get_transmissao($this->session->userdata('id_condominio'));
        $this->template->load('template', 'equipamentos/status_equipamentos_view', $data);
    }

}
