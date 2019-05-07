<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Telefones extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('logado');
        $this->logado->get_logado();
        $this->logado->verifica_primeiro_acesso();
        $this->load->model('Telefones_model');
        $this->load->model('Apartamentos_model');
        $this->load->model('Blocos_model');
    }

    public function index() {
        $perfil = $this->session->userdata('perfil');
        if ($perfil == 2 || $perfil == 4) {
            $data['apartamentos'] = $this->get_apartamentos(0, $this->session->userdata('id_condominio'));
            $this->template->load('template', 'telefones/telefones_view_condominio', $data);
        } else if ($perfil == 1) {
            $data['numero_apartamento'] = $this->Apartamentos_model->get_apartamentos_by_id($this->session->userdata('id_apartamento'));
            $id_bloco = $data['numero_apartamento'][0]->id_bloco;
            if (empty($id_bloco)) {
                $data['nome_bloco'] = "";
            } else {
                $data['nome_bloco'] = $this->Blocos_model->get_bloco_by_id($id_bloco)[0]->nome . " - ";
            }
            $data['telefones'] = $this->Telefones_model->get_telefones($this->session->userdata('id_apartamento'), 0);
            foreach ($data['telefones'] as $key => $t) {
                $data['transmissao'][$key] = array('telefone' => $t->telefone, 'status' => $this->Telefones_model->get_transmissao($t->telefone, $data['numero_apartamento'][0]->numero_apartamento, $this->session->userdata('id_apartamento')));
            }
            $data['apartamento_selecionado'] = 0;
            $this->template->load('template', 'telefones/telefones_view', $data);
        }
    }

    public function gerenciar() {
        $this->logado->verifica_permissoes(array('2','4'));
        $this->form_validation->set_rules('id_apartamento', 'Apartamento', 'required');
        if ($this->form_validation->run() == FALSE) {
            $data['apartamentos'] = $this->get_apartamentos(0, $this->session->userdata('id_condominio'));
            $data['mensagens_erro'] = validation_errors();
            $this->template->load('template', 'telefones/telefones_view_condominio', $data);
        } else {
            $id_apartamento = $this->input->post('id_apartamento');
            $this->session->set_userdata('id_apartamento', $id_apartamento);
            $data['numero_apartamento'] = $this->Apartamentos_model->get_apartamentos_by_id($id_apartamento);
            $id_bloco = $data['numero_apartamento'][0]->id_bloco;
            if (empty($id_bloco)) {
                $data['nome_bloco'] = "";
            } else {
                $data['nome_bloco'] = $this->Blocos_model->get_bloco_by_id($id_bloco)[0]->nome . " - ";
            }
            $data['telefones'] = $this->Telefones_model->get_telefones($id_apartamento, 0);
            foreach ($data['telefones'] as $key => $t) {
                $data['transmissao'][$key] = array('telefone' => $t->telefone, 'status' => $this->Telefones_model->get_transmissao($t->telefone, $data['numero_apartamento'][0]->numero_apartamento, $id_apartamento));
            }

            $data['apartamento_selecionado'] = $id_apartamento;
            $this->template->load('template', 'telefones/telefones_view', $data);
        }
    }

    public function novo() {
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('telefone', 'Telefone', 'required');
        $this->form_validation->set_rules('id_condominio', 'Condomínio', 'required');
        $this->form_validation->set_rules('id_apartamento', 'Apartamento', 'required');
        
        $data['condominios'] = $this->get_condominios($this->session->userdata('id_condominio'));
        if ($this->session->userdata('perfil') == 1) {
            $data['apartamentos'] = $this->get_apartamentos($this->session->userdata('id_apartamento'), 0);
        } else if ($this->session->userdata('perfil') == 2 || $this->session->userdata('perfil') == 4) {
            $apartamento_selecionado = $this->session->userdata('id_apartamento');
            if (!isset($apartamento_selecionado)) {
                $apartamento_selecionado = 0;
            }
            $data['apartamentos'] = $this->get_apartamentos($apartamento_selecionado, 0);
        }

        if ($this->form_validation->run() == FALSE) {
            $data['mensagens_erro'] = validation_errors();
            $this->template->load('template', 'telefones/novo_telefone_view', $data);
        } else {
            $dados = $this->input->post();
            $dados['ativo'] = 1;
            $remove = array('(', ')');
            $dados['telefone'] = str_replace($remove, '', $dados['telefone']);
            $existe = $this->Telefones_model->existe($dados);
            if ($existe) {
                $data['mensagens_erro'] = "Esse telefone já existe para esse apartamento!";
                $this->template->load('template', 'telefones/novo_telefone_view', $data);
            } else {
                $cota = $this->Telefones_model->cota($dados);
                if ($cota) {
                    if (isset($dados['memoria'])) {
                        $cota_memoria = $this->Telefones_model->cota_memoria($dados);
                        if ($cota_memoria) {
                            $dados['memoria'] = 1;
                            $this->insert($dados);
                        } else {
                            $data['mensagens_erro'] = "Seu apartamento já atingiu o limite de 2 telefones na memória do equipamento!";
                            $this->template->load('template', 'telefones/novo_telefone_view', $data);
                        }
                    } else {
                        $dados['memoria'] = 0;
                        $this->insert($dados);
                    }
                } else {
                    $data['mensagens_erro'] = "Seu apartamento já atingiu o limite de 2 telefones!";
                    $this->template->load('template', 'telefones/novo_telefone_view', $data);
                }
            }
        }
    }

    public function insert($dados) {
        $dados['ordem'] = $this->Telefones_model->get_quantidade_telefones($dados) + 1;
        unset($dados['id_bloco']);
        $insert = $this->Telefones_model->insert($dados);
        if ($insert) {
            $id = $insert;
            $this->session->set_userdata("id_telefone", $id);
            $id_apartamento = $dados['id_apartamento'];
            $faixa_padrao = $this->faixa_padrao($id);
            if ($faixa_padrao) {
                redirect(base_url('telefones'));
            } else {
                $mensagem = array('mensagens_erro' => 'Houve um erro inesperado tente novamente!');
                $this->template->load('template', 'telefones/novo_telefone_view', $mensagem);
            }
        } else {
            $mensagem = array('mensagens_erro' => 'Houve um erro inesperado tente novamente!');
            $this->template->load('template', 'telefones/novo_telefone_view', $mensagem);
        }
    }

    public function insert_faixas() {
        $id_telefone = $this->uri->segment(3);
        $id_apartamento = $this->uri->segment(4);
        $data['id_telefone'] = $id_telefone;
        if ($id_telefone == $this->session->userdata('id_telefone')) {
            if (empty($id_apartamento)) {
                if ($this->session->userdata('perfil') == 2 || $this->session->userdata('perfil') == 3) {
                    $this->template->load('template', 'telefones/novo_telefone_faixas_view', $data);
                } else {
                    redirect(base_url('telefones'));
                }
            } elseif ($id_apartamento == $this->session->userdata('id_apartamento') || $this->session->userdata('perfil') == 2) {
                $this->template->load('template', 'telefones/novo_telefone_faixas_view', $data);
            } else {
                redirect(base_url('telefones'));
            }
        } else {
            redirect(base_url('telefones'));
        }
    }

    public function grava_faixas() {
        $hora_inicio = $this->input->get('hora_inicio');
        $hora_fim = $this->input->get('hora_fim');
        $telefone = $this->input->get('id_telefone');
        $dias = explode(",", $this->input->get('dias'));
        $dados = array('seg' => 0, 'ter' => 0, 'qua' => 0, 'qui' => 0, 'sex' => 0, 'sab' => 0, 'dom' => 0);
        $dados['id_telefone'] = $telefone;
        $dados['inicio'] = $hora_inicio;
        $dados['fim'] = $hora_fim;
        $dados['ativo'] = 1;
        foreach ($dias as $d) {
            switch ($d) {
                case 1:
                    $dados['seg'] = 1;
                    break;

                case 2:
                    $dados['ter'] = 1;
                    break;

                case 3:
                    $dados['qua'] = 1;
                    break;

                case 4:
                    $dados['qui'] = 1;
                    break;

                case 5:
                    $dados['sex'] = 1;
                    break;

                case 6:
                    $dados['sab'] = 1;
                    break;

                case 7:
                    $dados['dom'] = 1;
                    break;
            }
        }

        $insert = $this->Telefones_model->insert_faixa($dados);
        if ($insert) {
            echo 1;
        } else {
            echo 2;
        }
    }

    public function get_faixas() {
        $id_telefone = $this->input->get('id_telefone');
        $tipo = $this->input->get('tipo');
        $faixas = $this->Telefones_model->get_faixas($id_telefone, $tipo);
        echo json_encode($faixas);
    }

    public function delete_faixa() {
        $id_faixa = $this->input->get('id_faixa');
        $delete = $this->Telefones_model->delete_faixa($id_faixa);
        if ($delete) {
            echo "1";
        } else {
            echo "0";
        }
    }

    public function ativa_desativa_telefone() {
        $valor = $this->input->get('valor');
        $id_telefone = $this->input->get('id_telefone');
        if ($valor == 'true') {
            $ativa = $this->Telefones_model->ativa_telefone($id_telefone);
            if ($ativa) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            $desativa = $this->Telefones_model->desativa_telefone($id_telefone);
            if ($desativa) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    public function altera_ordem() {
        $perfil = $this->session->userdata('perfil');
        $tipo = $this->input->get('tipo');
        $id_telefone = $this->input->get('id_telefone');
        $id_apartamento = $this->input->get('apartamento_selecionado');
        if ($perfil == 1) {
            $id_apartamento = $this->session->userdata('id_apartamento');
        }
        $ordem_atual = $this->Telefones_model->get_ordem($id_telefone)[0]->ordem;
        $ultimo_item = $this->Telefones_model->ultimo_telefone($id_apartamento)[0]->ultimo;
        if ($ultimo_item > 1) {
            if ($tipo == 1 && $ordem_atual != 1) {
                $telefone_anterior = $this->Telefones_model->telefone_anterior($ordem_atual, $id_apartamento)[0]->id;
                $ordem_anterior = $this->Telefones_model->get_ordem($telefone_anterior)[0]->ordem;
                $altera_ordem_up = $this->Telefones_model->altera_ordem_up($id_telefone, $ordem_atual, $id_apartamento);
                if ($altera_ordem_up) {
                    $this->Telefones_model->altera_ordem_down($telefone_anterior, $ordem_anterior, $id_apartamento);
                }
            } elseif ($tipo == 2 && $ordem_atual != $ultimo_item) {
                $telefone_posterior = $this->Telefones_model->telefone_posterior($ordem_atual, $id_apartamento)[0]->id;
                $ordem_posterior = $this->Telefones_model->get_ordem($telefone_posterior)[0]->ordem;
                $altera_ordem_down = $this->Telefones_model->altera_ordem_down($id_telefone, $ordem_atual, $id_apartamento);
                if ($altera_ordem_down) {
                    $this->Telefones_model->altera_ordem_up($telefone_posterior, $ordem_posterior, $id_apartamento);
                }
            }
            $this->db->query("CALL reinsert_apart($id_apartamento)");
        }
    }

    public function delete() {
        $perfil = $this->session->userdata('perfil');
        $id = $this->uri->segment(3);
        $apartamento_selecionado = $this->uri->segment(4);
        if ($perfil == 1) {
            $apartamento_selecionado = $this->session->userdata('id_apartamento');
        }
        
        $valida = $this->Telefones_model->valida_edicao_exlcusao($id, $apartamento_selecionado);

        $delete = $this->Telefones_model->delete($id);
        if ($delete) {
            $this->reordenar($apartamento_selecionado);
            $data['mensagens_sucesso'] = "Telefone excluido com sucesso!";
        } else {
            $data['mensagens_erro'] = "Houve um erro tente novamente!";
        }
        if ($perfil == 2) {
            redirect(base_url('telefones'));
        } else if ($perfil == 1) {
            $data['numero_apartamento'] = $this->Apartamentos_model->get_apartamentos_by_id($this->session->userdata('id_apartamento'));
            $id_bloco = $data['numero_apartamento'][0]->id_bloco;
            if (empty($id_bloco)) {
                $data['nome_bloco'] = "";
            } else {
                $data['nome_bloco'] = $this->Blocos_model->get_bloco_by_id($id_bloco)[0]->nome . " - ";
            }
            $data['telefones'] = $this->Telefones_model->get_telefones($this->session->userdata('id_apartamento'), 0);
            foreach ($data['telefones'] as $key => $t) {
                $data['transmissao'][$key] = array('telefone' => $t->telefone, 'status' => $this->Telefones_model->get_transmissao($t->telefone, $data['numero_apartamento'][0]->numero_apartamento, $this->session->userdata('id_apartamento')));
            }

            $data['apartamento_selecionado'] = 0;
            $this->template->load('template', 'telefones/telefones_view', $data);
        }
    }

    public function reordenar($id_apartamento) {
        $telefones = $this->Telefones_model->get_telefones($id_apartamento, 0);
        $cont = 1;
        foreach ($telefones as $t) {
            if ($t->ordem != $cont) {
                $this->Telefones_model->altera_ordem_direto($t->id, $cont);
            }
            $cont++;
        }
    }

    public function editar() {
        $id_telefone = $this->uri->segment(3);
        $id_apartamento = $this->session->userdata('id_apartamento');
        $valida = $this->Telefones_model->valida_edicao_exlcusao($id_telefone, $id_apartamento);
        $data['telefone'] = $this->get_telefones_by_id($id_telefone);
        $data['condominios'] = $this->get_condominios($this->session->userdata('id_condominio'));
        $data['apartamentos'] = $this->get_apartamentos($id_apartamento, 0);
        $this->template->load('template', 'telefones/telefones_edit_view', $data);
    }

    public function salva_edicao() {
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('telefone', 'Telefone', 'required');
        $this->form_validation->set_rules('id_condominio', 'Condomínio', 'required');
        $this->form_validation->set_rules('id_apartamento', 'Apartamento', 'required');

        $data['telefone'] = $this->get_telefones_by_id($this->input->post('id'));
        $data['condominios'] = $this->get_condominios($this->session->userdata('id_condominio'));
        if ($this->session->userdata('perfil') == 1) {
            $data['apartamentos'] = $this->get_apartamentos($this->session->userdata('id_apartamento'), 0);
        } else if ($this->session->userdata('perfil') == 2) {
            $data['apartamentos'] = $this->get_apartamentos(0, $this->session->userdata('id_condominio'));
        }

        if ($this->form_validation->run() == FALSE) {
            $data['mensagens_erro'] = validation_errors();
            $this->template->load('template', 'telefones/telefones_edit_view', $data);
        } else {
            $dados = $this->input->post();
            $dados['ativo'] = 1;
            $remove = array('(', ')');
            $dados['telefone'] = str_replace($remove, '', $dados['telefone']);
            $existe = $this->Telefones_model->existe($dados);
            if ($existe) {
                $data['mensagens_erro'] = "Esse telefone já existe para esse apartamento!";
                $this->template->load('template', 'telefones/telefones_edit_view', $data);
            } else {
                unset($dados['id_condominio']);
                if (isset($dados['memoria'])) {
                    $dados['memoria'] = 1;
                } else {
                    $dados['memoria'] = 0;
                }
                if ($dados['memoria_selecionado'] == 0 && $dados['memoria'] == 1) {
                    $cota_memoria = $this->Telefones_model->cota_memoria($dados);
                    if ($cota_memoria) {
                        $this->update($dados);
                    } else {
                        $data['mensagens_erro'] = "Seu apartamento já atingiu o limite de 2 telefones na memória do equipamento!";
                        $this->template->load('template', 'telefones/telefones_edit_view', $data);
                    }
                } else {
                    $this->update($dados);
                }
            }
        }
    }

    public function update($dados) {
        unset($dados['memoria_selecionado']);
        $update = $this->Telefones_model->update($dados);
        if ($update) {
            redirect(base_url('telefones'));
        } else {
            $mensagem = array('mensagens_erro' => 'Houve um erro inesperado tente novamente!');
            $this->template->load('template', 'telefones/telefones_edit_view', $mensagem);
        }
    }

    public function faixa_padrao($id_telefone) {
        $faixas = $this->Telefones_model->get_faixas($id_telefone);
        if (count($faixas) == 0) {
            $insert = $this->Telefones_model->insert_faixa_padrao($id_telefone);
            if ($insert) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return TRUE;
        }
    }

    public function get_telefones_by_id($id) {
        $data = $this->Telefones_model->get_telefones_by_id($id);
        return $data;
    }

    public function get_condominios($id) {
        $data = $this->Telefones_model->get_condominios($id);
        return $data;
    }

    public function get_apartamentos($id_telefone, $id_condominio) {
        $data = $this->Apartamentos_model->get($id_telefone, $id_condominio);
        return $data;
    }

}
