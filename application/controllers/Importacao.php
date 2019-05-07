<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Importacao extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('logado');
        $this->logado->get_logado();
        $this->logado->verifica_primeiro_acesso();
        $this->logado->verifica_permissoes(array('2','4'));
        $this->load->helper("file");
        $this->load->model('Importacao_model');
        $this->load->model('Telefones_model');
        set_time_limit(500);
    }

    public function index() {
        $this->template->load('template', 'importacao/importacao_view');
    }

    public function mc_soft() {
        $data['id_condominio'] = $this->session->userdata('id_condominio');
        $this->template->load('template', 'importacao/importacao_mc_soft_view', $data);
    }

    public function upload() {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '5000';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $data['mensagens_erro'] = $this->upload->display_errors();
            $this->template->load('template', 'importacao/importacao_view', $data);
        } else {
            $data = $this->upload->data();
            $this->read($data, $this->input->post('sobrescrever'));
        }
    }

    public function read($dados, $tipo) {
        $path = base_url('uploads/');
        $file = fopen($path . $dados['file_name'], "r");
        $log_erros = "";
        $log_sucess = 0;
        $linha = 1;
        while (($row = fgetcsv($file, 1000, ";")) !== FALSE) {
            $dados_insert = array(
                'bloco' => $row[0],
                'apartamento' => $row[1],
                'telefone1' => $row[2],
                'telefone2' => $row[3],
            );
            $insert_bloco = $this->insert_bloco($dados_insert['bloco']);
            if ($insert_bloco['return']) {
                $insert_apartamento = $this->insert_apartamento($dados_insert['apartamento'], $insert_bloco['id_bloco']);
                if ($insert_apartamento['return']) {
                    $insert_telefone = $this->insert_telefone($insert_apartamento['id_apartamento'], $tipo, $dados_insert['telefone1'], $dados_insert['telefone2']);
                    if ($insert_telefone['return']) {
                        $log_sucess ++;
                    } else {
                        $log_erros .= "Linha " . $linha . " - " . $insert_telefone['mensage'] . "<br/>";
                    }
                } else {
                    $log_erros .= "Linha " . $linha . " - " . $insert_apartamento['mensage'] . "<br/>";
                }
            } else {
                $log_erros .= "Linha " . $linha . " - " . $insert_bloco['mensage'] . "<br/>";
            }

            $linha ++;
        }
        fclose($file);
        unlink('./uploads/' . $dados['file_name']);

        $data['log_erros'] = $log_erros;
        $data['log_sucessos'] = $log_sucess;
        $this->template->load('template', 'importacao/importacao_view_sucess', $data);
    }

    public function insert_bloco($bloco) {
        if (empty($bloco)) {
            $retorno['mensage'] = "Campo bloco não pode ficar vazio";
            $retorno['return'] = FALSE;
        } else {
            if (is_numeric($bloco)) {
                $bloco_existe = $this->Importacao_model->verifica_exitencia_bloco($bloco);
                if (!$bloco_existe['return']) {
                    $insert_bloco = $this->Importacao_model->insert_bloco($bloco);
                    if ($insert_bloco['return']) {
                        $retorno['return'] = TRUE;
                        $retorno['id_bloco'] = $insert_bloco['id_bloco'];
                    } else {
                        $retorno['return'] = FALSE;
                        $retorno['mensage'] = "Houve um erro ao gravar no banco de dados";
                    }
                } else {
                    $retorno['id_bloco'] = $bloco_existe['id_bloco'];
                    $retorno['return'] = TRUE;
                }
            } else {
                $retorno['return'] = FALSE;
                $retorno['mensage'] = "Campo bloco aceita somente números";
            }
        }
        return $retorno;
    }

    public function insert_apartamento($apartamento, $bloco) {
        if (empty($apartamento)) {
            $retorno['return'] = FALSE;
            $retorno['mensage'] = "Campo apartamento não pode ficar vazio";
        } else {
            if (is_numeric($apartamento)) {
                $apartamento_existe = $this->Importacao_model->verifica_exitencia_apartamento($apartamento, $bloco);
                if (!$apartamento_existe['return']) {
                    $insert_apartamento = $this->Importacao_model->insert_apartamento($apartamento, $bloco);
                    if ($insert_apartamento['return']) {
                        $retorno['return'] = TRUE;
                        $retorno['id_apartamento'] = $insert_apartamento['id_apartamento'];
                    } else {
                        $retorno['return'] = FALSE;
                        $retorno['mensage'] = "Houve um erro ao gravar no banco de dados";
                    }
                } else {
                    $retorno['id_apartamento'] = $apartamento_existe['id_apartamento'];
                    $retorno['return'] = TRUE;
                }
            } else {
                $retorno['return'] = FALSE;
                $retorno['mensage'] = "Campo apartamento aceita somente números";
            }
        }
        return $retorno;
    }

    public function insert_telefone($id_apartamento, $tipo, $telefone1, $telefone2 = NULL) {
        if(substr($telefone1, 0,1)== 0){
            $telefone1 = substr($telefone1, 1);
        }
        if(substr($telefone2, 0,1)== 0){
            $telefone2 = substr($telefone2, 1);
        }
        $valida_telefone1 = $this->valida_telefone($telefone1, "1º Telefone");
        if ($valida_telefone1['return']) {
            if ($telefone2 != NULL) {
                $valida_telefone2 = $this->valida_telefone($telefone2, "2º Telefone");
            } else {
                $valida_telefone2['return'] = TRUE;
            }
            if ($valida_telefone2['return']) {
                if ($tipo) {
                    $delete_telefones = $this->Telefones_model->delete_telefones_by_apartamento($id_apartamento);
                    if ($delete_telefones) {
                        $insert_telefone = $this->Importacao_model->insert_telefone($id_apartamento, 1, $telefone1, $telefone2);
                        if ($insert_telefone) {
                            $retorno['return'] = TRUE;
                        } else {
                            $retorno['return'] = FALSE;
                            $retorno['mensage'] = "Houve um erro ao gravar no banco de dados";
                        }
                    } else {
                        $retorno['return'] = FALSE;
                        $retorno['mensage'] = "Houve um erro ao gravar no banco de dados";
                    }
                } else {
                    $existe_telefone = $this->Importacao_model->get_telefone_by_apartamento($id_apartamento);
                    if ($existe_telefone) {
                        $retorno['return'] = FALSE;
                        $retorno['mensage'] = "Ja existe telefone cadastrado para esse apartamento";
                    } else {
                        $insert_telefone = $this->Importacao_model->insert_telefone($id_apartamento, 1, $telefone1, $telefone2);
                        if ($insert_telefone) {
                            $retorno['return'] = TRUE;
                        } else {
                            $retorno['return'] = FALSE;
                            $retorno['mensage'] = "Houve um erro ao gravar no banco de dados";
                        }
                    }
                }
            } else {
                $retorno['return'] = FALSE;
                $retorno['mensage'] = $valida_telefone2['mensage'];
            }
        } else {
            $retorno['return'] = FALSE;
            $retorno['mensage'] = $valida_telefone1['mensage'];
        }
        return $retorno;
    }

    public function insert_telefone_mc_soft($id_apartamento, $telefone) {
        if(substr($telefone, 0,1) == 0){
            $telefone = substr($telefone, 1);
        }
        $valida_telefone = $this->valida_telefone($telefone, "Telefone");
        if ($valida_telefone['return']) {
            $dados['id_apartamento'] = $id_apartamento;
            $dados['telefone'] = $telefone;
            $cota = $this->Telefones_model->cota($dados);
            if ($cota) {
                $existe = $this->Telefones_model->existe($dados);
                if ($existe) {
                    $retorno['return'] = FALSE;
                    $retorno['mensage'] = "Esse telefone já existe para esse apartamento!";
                } else {
                    $ordem = $this->Telefones_model->get_quantidade_telefones($dados) + 1;
                    $insert_telefone = $this->Importacao_model->insert_telefone($id_apartamento, $ordem, $telefone);
                    if ($insert_telefone) {
                        $retorno['return'] = TRUE;
                    } else {
                        $retorno['return'] = FALSE;
                        $retorno['mensage'] = "Houve um erro ao gravar no banco de dados";
                    }
                }
            } else {
                $retorno['return'] = FALSE;
                $retorno['mensage'] = "Seu apartamento já atingiu o limite de 2 telefones!";
            }
        } else {
            $retorno['return'] = FALSE;
            $retorno['mensage'] = $valida_telefone['mensage'];
        }
        return $retorno;
    }

    function valida_telefone($telefone, $nome_campo) {
        $retorno = "";
        if ($telefone != "") {
            if (is_numeric($telefone)) {
                $quant_digitos = strlen($telefone);
                if ($quant_digitos >= 10 && $quant_digitos <= 11) {
                    $retorno['return'] = TRUE;
                } else {
                    $retorno['return'] = FALSE;
                    $retorno['mensage'] = "O campo " . $nome_campo . " deve ter no mínimo 10  e no máximo 11 dígitos ";
                }
            } else {
                $retorno['return'] = FALSE;
                $retorno['mensage'] = "O campo " . $nome_campo . " aceita somente números";
            }
        } else {
            $retorno['return'] = FALSE;
            $retorno['mensage'] = "O campo " . $nome_campo . " não pode ficar vazio";
        }
        return $retorno;
    }

    public function upload_mc_soft() {
        $data['id_condominio'] = $this->session->userdata('id_condominio');

        $config['upload_path'] = './uploads_mc_soft/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '5000';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file_mc_soft')) {
            $data['mensagens_erro'] = $this->upload->display_errors();
            $this->template->load('template', 'importacao/importacao_mc_soft_view', $data);
        } else {
            $this->form_validation->set_rules('id_bloco', 'Bloco', 'required');
            if ($this->form_validation->run() == FALSE) {
                $data['mensagens_erro'] = validation_errors();
                $this->template->load('template', 'importacao/importacao_mc_soft_view', $data);
            } else {
                $data = $this->upload->data();
                $id_bloco = $this->input->post('id_bloco');
                $this->read_mc_soft($data, $this->input->post('sobrescrever'), $id_bloco);
            }
        }
    }

    public function read_mc_soft($dados, $tipo, $id_bloco) {
        $path = base_url('uploads_mc_soft/');
        $file = fopen($path . $dados['file_name'], "r");
        $log_erros = "";
        $log_sucess = 0;
        $linha = 1;
        $array_apartamentos_delete = array();
        while (($row = fgetcsv($file, 1000, ";")) !== FALSE) {
            $dados_insert = array(
                'apartamento' => $row[0],
                'telefone' => $row[1],
                'bloco' => $id_bloco,
            );
            $insert_apartamento = $this->insert_apartamento($dados_insert['apartamento'], $dados_insert['bloco']);

            if ($insert_apartamento['return']) {
                $id_apartamento = $insert_apartamento['id_apartamento'];
                if ($tipo && !isset($array_apartamentos_delete[$id_apartamento])) {
                    $delete_telefones = $this->Telefones_model->delete_telefones_by_apartamento($id_apartamento);
                    if ($delete_telefones) {
                        $array_apartamentos_delete[$id_apartamento] = TRUE;
                    }
                }
                $insert_telefone = $this->insert_telefone_mc_soft($insert_apartamento['id_apartamento'], $dados_insert['telefone']);
                if ($insert_telefone['return']) {
                    $log_sucess ++;
                } else {
                    $log_erros .= "Linha " . $linha . " - " . $insert_telefone['mensage'] . "<br/>";
                }
            } else {
                $log_erros .= "Linha " . $linha . " - " . $insert_apartamento['mensage'] . "<br/>";
            }
            $linha ++;
        }

        fclose($file);
        unlink('./uploads_mc_soft/' . $dados['file_name']);

        $data['log_erros'] = $log_erros;
        $data['log_sucessos'] = $log_sucess;
        $this->template->load('template', 'importacao/importacao_view_sucess', $data);
    }

}
