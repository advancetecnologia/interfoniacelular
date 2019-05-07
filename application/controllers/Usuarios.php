<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Apartamentos_model');
        $this->load->model('Condominios_model');
        $this->load->model('Usuarios_model');
        $this->load->model('Estados_cidades_model');
        $this->load->model('Revendas_model');
    }

    public function primeiro_acesso() {
        $this->template->load('template_inicial', 'usuarios/primeiro_acesso_view');
    }

    public function novo() {
        $perfil = $this->uri->segment(3);
        if ($perfil == 1) {
            $this->template->load('template_inicial', 'usuarios/novo_usuario_perfil1_view');
        } else if ($perfil == 2) {
            $this->load->model('Estados_cidades_model');
            $data['estados'] = $this->Estados_cidades_model->get_estados();
            $this->template->load('template_inicial', 'usuarios/novo_usuario_perfil2_view', $data);
        }else if($perfil == 4){
            $this->load->model('Estados_cidades_model');
            $data['estados'] = $this->Estados_cidades_model->get_estados();
            $this->template->load('template_inicial', 'usuarios/novo_usuario_perfil4_view', $data);
        }
    }

    public function esqueceu_senha() {
        $this->form_validation->set_rules('email', 'E-mail', 'required');
        if ($this->form_validation->run() == FALSE) {
            $data['mensagens'] = validation_errors();
            $this->template->load('template_inicial', 'usuarios/esqueceu_senha_view', $data);
        } else {
            $recaptcha = $this->recaptcha($this->input->post('g-recaptcha-response'));
            if ($recaptcha) {
                $email = $this->input->post('email');
                $existe = $this->Usuarios_model->get_usuario($email);
                if ($existe) {
                    $this->gera_url($email);
                } else {
                    $data['mensagens'] = "E-mail não cadastrado";
                    $this->template->load('template_inicial', 'usuarios/esqueceu_senha_view', $data);
                }
            } else {
                $data['mensagens'] = "Captcha não verificado, por favor clique em 'Não sou um robô'";
                $this->template->load('template_inicial', 'usuarios/esqueceu_senha_view', $data);
            }
        }
    }

    public function gera_url($email) {
        $id_usuario = $this->Usuarios_model->get_id_usuario($email)[0]->id;
        $hash = hash('sha256', date('dmYHis') . $id_usuario);
        $url = base_url('usuarios/confirmacao/') . $hash;
        $insert = $this->Usuarios_model->insert_url($id_usuario, $hash);
        $assunto = "Alteração de senha";
        if ($insert) {
            $this->load->library('my_phpmailer');
            $conteudo = "<html>Olá<br/><br/>"
                    . "Você solicitou alteração de senha para o sistema da Pináculo.<br/>"
                    . "Para realizar essa alteração clique no link abaixo:<br><br/>"
                    . "<a href='$url'>Clique aqui</a>"
                    . "<html>";
            $email = $this->my_phpmailer->send_mail($email, $assunto, $conteudo);
            if ($email) {
                $data['mensagens_sucesso'] = "Você receberá um e-mail com as instruções";
                $this->template->load('template_inicial', 'usuarios/esqueceu_senha_view', $data);
            } else {
                $data['mensagens'] = "Houve um erro ao enviar o e-mail de confirmação, tente novamente";
                $this->template->load('template_inicial', 'usuarios/esqueceu_senha_view', $data);
            }
        } else {
            $data['mensagens'] = "Houve um erro inesperado, tente novamente";
            $this->template->load('template_inicial', 'usuarios/esqueceu_senha_view', $data);
        }
    }

    public function confirmacao() {
        $hash = $this->uri->segment(3);
        $verifica = $this->Usuarios_model->verifica_hash($hash);
        if (count($verifica) > 0) {
            $data['id_usuario'] = $verifica[0]->id_usuario;
            $this->template->load('template_inicial', 'usuarios/nova_senha_view', $data);
        } else {
            redirect(base_url());
        }
    }

    function nova_senha() {
        $id_usuario = $this->input->post('id');
        if (isset($id_usuario)) {
            $data['id'] = $id_usuario;
            $this->form_validation->set_rules('senha', 'Nova senha', 'required|min_length[8]');
            $this->form_validation->set_rules('r_senha', 'Repita nova senha', 'required|matches[senha]');
            if ($this->form_validation->run() == FALSE) {
                $data['mensagens_erro'] = validation_errors();
                $this->template->load('template_inicial', 'usuarios/nova_senha_view', $data);
            } else {
                $recaptcha = $this->recaptcha($this->input->post('g-recaptcha-response'));
                if ($recaptcha) {
                    $dados = $this->input->post();
                    $dados['senha'] = md5($dados['senha']);
                    unset($dados['r_senha']);
                    unset($dados['g-recaptcha-response']);
                    $update = $this->Usuarios_model->update_senha($dados);
                    if ($update) {
                        $this->delete_url($id_usuario);
                        $this->session->set_flashdata('sucesso', 'Senha alterada com sucesso');
                        redirect('login');
                    } else {
                        $data['mensagens_erro'] = "Houve um erro no momento da atualização, tente novamente";
                        $this->template->load('template_inicial', 'usuarios/nova_senha_view', $data);
                    }
                } else {
                    $data['mensagens_erro'] = "Captcha não verificado, por favor clique em 'Não sou um robô'";
                    $this->template->load('template_inicial', 'usuarios/nova_senha_view', $data);
                }
            }
        } else {
            redirect(base_url());
        }
    }

    function delete_url($id_usuario) {
        $delete = $this->Usuarios_model->delete_url($id_usuario);
        return $delete;
    }

    public function insert_perfil1() {
        $this->form_validation->set_rules('email', 'E-mail', 'required');
        $this->form_validation->set_rules('senha', 'Senha', 'required|matches[r_senha]|min_length[8]');
        $this->form_validation->set_rules('r_senha', 'Repita Senha', 'required');
        $this->form_validation->set_rules('cod_apartamento', 'Código do apartamento', 'required|trim');
        $recaptcha = $this->recaptcha($this->input->post('g-recaptcha-response'));
        if ($recaptcha) {
            $data['inputs'] = $this->input->post();
            if ($this->form_validation->run() == FALSE) {
                $data['mensagens'] = validation_errors();
                $this->template->load('template_inicial', 'usuarios/novo_usuario_perfil1_view', $data);
            } else {
                $usuario = $this->input->post('email');
                $qtd = $this->Usuarios_model->get_usuario($usuario);
                if ($qtd > 0) {
                    $data['mensagens'] = "E-mail já usado para outro apartamento ou condominio";
                    $this->template->load('template_inicial', 'usuarios/novo_usuario_perfil1_view', $data);
                } else {
                    $cod_apartamento = $this->input->post('cod_apartamento');
                    $valida_apartamento = $this->Apartamentos_model->valida_apartamento($cod_apartamento);
                    if ($valida_apartamento->num_rows() == 0) {
                        $data['mensagens'] = "Código do apartamento inválido, contate o administrador do condomínio";
                        $this->template->load('template_inicial', 'usuarios/novo_usuario_perfil1_view', $data);
                    } else {
                        $dados_apartamento = $valida_apartamento->result()[0];
                        $dados = $this->input->post();
                        $dados['perfil'] = 1;
                        $dados['ativo'] = 1;
                        $dados['id_condominio'] = $dados_apartamento->id_condominio;
                        $dados['id_apartamento'] = $dados_apartamento->id;
                        $dados['id_bloco'] = $dados_apartamento->id_bloco;
                        $dados['senha'] = md5($dados['senha']);
                        unset($dados['r_senha']);
                        unset($dados['cod_apartamento']);
                        $gravar_usuario = $this->Usuarios_model->insert($dados);
                        if ($gravar_usuario) {
                            $this->session->set_flashdata('sucesso', 'Apartamento cadastrado com sucesso');
                            redirect('login');
                        } else {
                            $data['mensagens'] = "Houve um erro inesperado, tente novamente";
                            $this->template->load('template_inicial', 'usuarios/novo_usuario_perfil1_view', $data);
                        }
                    }
                }
            }
        } else {
            $data['mensagens'] = "Captcha não verificado, por favor clique em 'Não sou um robô'";
            $this->template->load('template_inicial', 'usuarios/novo_usuario_perfil1_view', $data);
        }
    }

    public function insert_perfil2() {
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('cnpj', 'CNPJ', 'required');
        $this->form_validation->set_rules('rua', 'Rua', 'required');
        $this->form_validation->set_rules('numero', 'Numero', 'required');
        $this->form_validation->set_rules('cep', 'CEP', 'required');
        $this->form_validation->set_rules('estado', 'Estado', 'required');
        $this->form_validation->set_rules('cidade', 'Cidade', 'required');
        $this->form_validation->set_rules('telefone', 'Telefone', 'required');
        $this->form_validation->set_rules('email', 'E-mail', 'required');
        
        if(!$this->input->post('perfil_revenda')){
            $this->form_validation->set_rules('senha', 'Senha', 'required|matches[r_senha]|min_length[8]');
            $this->form_validation->set_rules('r_senha', 'Repita Senha', 'required');
        }else{
            $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[8]');
        }

        $recaptcha = $this->recaptcha($this->input->post('g-recaptcha-response'));
        if ($recaptcha) {
            if ($this->form_validation->run() == FALSE) {
                $data = array('mensagens' => validation_errors());
                $data['inputs'] = $this->input->post();
                $data['estados'] = $this->Estados_cidades_model->get_estados();
                $this->template->load('template_inicial', 'usuarios/novo_usuario_perfil2_view', $data);
            } else {
                $flag = TRUE;
                $cnpj = str_replace(array('.', '/', '-'), "", $this->input->post('cnpj'));
                $valida_cnpj = $this->valida_cnpj($cnpj);
                if ($valida_cnpj) {
                    $exite = $this->verifica_existencia_condominio($cnpj);
                    if ($exite) {
                        $flag = FALSE;
                        $data = array('mensagens' => "CNPJ já cadastrado");
                    } else {
                        $dados = $this->input->post();
                        $dados['cnpj'] = $cnpj;
                        if($this->input->post('perfil_revenda')){
                            $usuario = $this->input->post('email');
                            $senha = md5($this->input->post('senha'));
                            $valida_usuario =  $this->Usuarios_model->get_revenda($usuario, $senha);
                            if($valida_usuario['num_rows'] != 1){
                                $flag = FALSE;
                                $data = array('mensagens' => "Não foi possível identificar sua revenda");
                            }else{
                                $this->insert_condominio_usuario($dados, $id_revenda = $valida_usuario['result']->id_revenda);
                            }
                        }else{
                            $usuario = $this->input->post('email');
                            $qtd = $this->Usuarios_model->get_usuario($usuario);
                            if ($qtd > 0) {
                                $flag = FALSE;
                                $data = array('mensagens' => "E-mail já usado para outro apartamento, condominio ou revenda");
                            } else {
                                $this->insert_condominio_usuario($dados);
                            }
                        }

                    }
                } else {
                    $flag = FALSE;
                    $data = array('mensagens' => "CNPJ inválido");
                }
                if (!$flag) {
                    $data['inputs'] = $this->input->post();
                    $data['estados'] = $this->Estados_cidades_model->get_estados();
                    $this->template->load('template_inicial', 'usuarios/novo_usuario_perfil2_view', $data);
                }
            }
        } else {
            $data['mensagens'] = "Captcha não verificado, por favor clique em 'Não sou um robô'";
            $data['inputs'] = $this->input->post();
            $data['estados'] = $this->Estados_cidades_model->get_estados();
            $this->template->load('template_inicial', 'usuarios/novo_usuario_perfil2_view', $data);
        }
    }

    public function insert_perfil4() {
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('cnpj', 'CNPJ', 'required');
        $this->form_validation->set_rules('rua', 'Rua', 'required');
        $this->form_validation->set_rules('numero', 'Numero', 'required');
        $this->form_validation->set_rules('cep', 'CEP', 'required');
        $this->form_validation->set_rules('estado', 'Estado', 'required');
        $this->form_validation->set_rules('cidade', 'Cidade', 'required');
        $this->form_validation->set_rules('telefone', 'Telefone', 'required');
        $this->form_validation->set_rules('email', 'E-mail', 'required');
        $this->form_validation->set_rules('senha', 'Senha', 'required|matches[r_senha]|min_length[8]');
        $this->form_validation->set_rules('r_senha', 'Repita Senha', 'required');

        $recaptcha = $this->recaptcha($this->input->post('g-recaptcha-response'));
        if ($recaptcha) {
            if ($this->form_validation->run() == FALSE) {
                $data = array('mensagens' => validation_errors());
                $data['inputs'] = $this->input->post();
                $data['estados'] = $this->Estados_cidades_model->get_estados();
                $this->template->load('template_inicial', 'usuarios/novo_usuario_perfil4_view', $data);
            } else {
                $flag = TRUE;
                $cnpj = str_replace(array('.', '/', '-'), "", $this->input->post('cnpj'));
                $valida_cnpj = $this->valida_cnpj($cnpj);
                if ($valida_cnpj) {
                    $exite = $this->verifica_existencia_revenda($cnpj);
                    if ($exite) {
                        $flag = FALSE;
                        $data = array('mensagens' => "CNPJ já cadastrado");
                    } else {
                        $dados = $this->input->post();
                        $dados['cnpj'] = $cnpj;
                        $usuario = $this->input->post('email');
                        $qtd = $this->Usuarios_model->get_usuario($usuario);
                        if ($qtd > 0) {
                            $flag = FALSE;
                            $data = array('mensagens' => "E-mail já usado para outro apartamento, condominio ou revenda");
                        } else {
                            $this->insert_revenda($dados);
                        }                            
                    }
                } else {
                    $flag = FALSE;
                    $data = array('mensagens' => "CNPJ inválido");
                }
                if (!$flag) {
                    $data['inputs'] = $this->input->post();
                    $data['estados'] = $this->Estados_cidades_model->get_estados();
                    $this->template->load('template_inicial', 'usuarios/novo_usuario_perfil4_view', $data);
                }
            }
        } else {
            $data['mensagens'] = "Captcha não verificado, por favor clique em 'Não sou um robô'";
            $data['inputs'] = $this->input->post();
            $data['estados'] = $this->Estados_cidades_model->get_estados();
            $this->template->load('template_inicial', 'usuarios/novo_usuario_perfil4_view', $data);
        }
    }

    function insert_condominio_usuario($dados, $id_revenda = NULL) {
        $insert = $this->Condominios_model->insert($dados, $id_revenda);
        if ($insert) {
            $this->session->set_flashdata('sucesso', 'Condomínio cadastrado com sucesso');
            redirect('login');
        } else {
            $data = array('mensagens' => "Houve um erro inesperado, tente novamente");
            $data['inputs'] = $this->input->post();
            $data['estados'] = $this->Estados_cidades_model->get_estados();
            $this->template->load('template_inicial', 'usuarios/novo_usuario_perfil2_view', $data);
        }
    }

    function insert_revenda($dados){
        $insert = $this->Revendas_model->insert($dados);
        if($insert){
            $this->session->set_flashdata('sucesso', 'Revenda cadastrada com sucesso, entre em contato com o suporte para ativação');
            redirect('login');
        }else{
            $data = array('mensagens' => "Houve um erro inesperado, tente novamente");
            $data['inputs'] = $this->input->post();
            $data['estados'] = $this->Estados_cidades_model->get_estados();
            $this->template->load('template_inicial', 'usuarios/novo_usuario_perfil4_view', $data);
        }
    }

    function retiraAcentos($string) {
        return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
    }

    function get_cidades() {
        $this->load->model('Estados_cidades_model');
        $estado = $this->input->get('estado');
        $cidade_selecionada = $this->input->get('cidade');
        $cidades = $this->Estados_cidades_model->get_cidades($estado);

        if ($cidades) {
            echo "<option value=''>Selecione uma cidade</option>"
            . "<option value=''></option>";
            foreach ($cidades as $c) {
                if ($cidade_selecionada == $c->id) {
                    echo "<option selected='true' value=$c->id>$c->nome</option>";
                } else {
                    echo "<option value=$c->id>$c->nome</option>";
                }
            }
        }
    }

    function valida_cnpj($cnpj) {
        $j = 0;
        for ($i = 0; $i < (strlen($cnpj)); $i++) {
            if (is_numeric($cnpj[$i])) {
                $num[$j] = $cnpj[$i];
                $j++;
            }
        }

        if (count($num) != 14) {
            $isCnpjValid = false;
        }

        if ($num[0] == 0 && $num[1] == 0 && $num[2] == 0 && $num[3] == 0 && $num[4] == 0 && $num[5] == 0 && $num[6] == 0 && $num[7] == 0 && $num[8] == 0 && $num[9] == 0 && $num[10] == 0 && $num[11] == 0) {
            $isCnpjValid = false;
        } else {
            $j = 5;
            for ($i = 0; $i < 4; $i++) {
                $multiplica[$i] = $num[$i] * $j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $j = 9;
            for ($i = 4; $i < 12; $i++) {
                $multiplica[$i] = $num[$i] * $j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $resto = $soma % 11;
            if ($resto < 2) {
                $dg = 0;
            } else {
                $dg = 11 - $resto;
            }
            if ($dg != $num[12]) {
                $isCnpjValid = false;
            }
        }

        if (!isset($isCnpjValid)) {
            $j = 6;
            for ($i = 0; $i < 5; $i++) {
                $multiplica[$i] = $num[$i] * $j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $j = 9;
            for ($i = 5; $i < 13; $i++) {
                $multiplica[$i] = $num[$i] * $j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $resto = $soma % 11;
            if ($resto < 2) {
                $dg = 0;
            } else {
                $dg = 11 - $resto;
            }
            if ($dg != $num[13]) {
                $isCnpjValid = false;
            } else {
                $isCnpjValid = true;
            }
        }

        return $isCnpjValid;
    }

    function verifica_existencia_condominio($cnpj) {
        $existe = $this->Condominios_model->verifica_existencia_condominio($cnpj);
        if ($existe) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function verifica_existencia_revenda($cnpj) {
        $existe = $this->Revendas_model->verifica_existencia_revenda($cnpj);
        if ($existe) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function perfil() {
        $this->load->library('logado');
        $this->logado->get_logado();
        $data = $this->get_dados_usuario();
        $this->template->load('template', 'usuarios/perfil_view', $data);
    }

    public function get_dados_usuario() {
        $id_user = $this->session->userdata('id_usuario');
        $dados_user = $this->Usuarios_model->get_dados_usuario($id_user);
        $cont = 0;
        foreach ($dados_user as $d) {
            $data['email'] = $d->email;
            switch ($d->perfil) {
                case 1:
                    $data['perfil'] = 'Apartamento';
                    break;
                case 2:
                    $id_condominio = $this->session->userdata('id_condominio');
                    $dados_condominio = $this->Condominios_model->get_condominio($id_condominio);
                    $data['nome_condominio'] = $dados_condominio->nome;
                    $data['id_condominio'] = $id_condominio;
                    $data['cnpj'] = $dados_condominio->cnpj;
                    $return = $this->Condominios_model->get_hash($id_condominio);
                    if($return['num_rows'] == 0){
                        $hash = $this->Condominios_model->set_hash($id_condominio, $dados_condominio->cnpj)['data']->hash;
                        $data['hash'] = $hash;
                    }else{
                        $data['hash'] = $return['data']->hash;
                    }
                    $data['perfil'] = 'Condomínio';
                    break;
                
                case 3:
                    $data['perfil'] = 'Administrador';
                    break;
                    
                case 4:
                    $condominios = $this->Condominios_model->get_condominios_by_revenda($d->id_revenda);
                    $data['condominios'] = array();
                    $cont = 0;
                    foreach($condominios as $key => $c){
                        $return = $this->Condominios_model->get_hash($c->id);
                        $cnpj_revenda = $this->Revendas_model->get_revenda_by_id($this->session->userdata('id_revenda'))->cnpj;
                        if($return['num_rows'] == 0){
                            if($cont == 0){
                                $hash_revenda = $this->Revendas_model->set_hash_revenda($this->session->userdata('id_revenda'), $cnpj_revenda)->hash;
                                $data['hash_revenda'] = $hash_revenda;
                            }
                            $hash = $this->Condominios_model->set_hash($c->id, $c->cnpj)['data']->hash;
                            array_push( $data['condominios'], array('nome'=>$c->nome,"hash"=> $hash, 'cnpj'=>$c->cnpj, 'id_condominio'=>$c->id));
                        }else{
                            $data['hash_revenda'] = $this->Revendas_model->get_hash_revenda($this->session->userdata('id_revenda'))->hash;
                            array_push( $data['condominios'], array('nome'=>$c->nome,"hash"=> $return['data']->hash, 'cnpj'=>$c->cnpj, 'id_condominio'=>$c->id));
                        }
                        $cont++;
                    }
                    $data['id_revenda'] = $this->session->userdata('id_revenda');
                    $data['cnpj_revenda'] = $cnpj_revenda;
                    $data['perfil'] = 'Revenda';
                    break;
            }
        }
        return $data;
    }

    public function altera_senha() {
        $data['id_usuario'] = $this->session->userdata('id_usuario');
        $this->template->load('template', 'usuarios/altera_senha_view', $data);
    }

    public function update_senha() {
        $this->form_validation->set_rules('senha_atual', 'Senha atual', 'required');
        $this->form_validation->set_rules('senha', 'Nova senha', 'required|min_length[8]');
        $this->form_validation->set_rules('r_senha', 'Repita nova senha', 'required|matches[senha]');
        $data['id_usuario'] = $this->input->post('id_usuario');
        if ($this->form_validation->run() == FALSE) {
            $data['mensagens_erro'] = validation_errors();
            $this->template->load('template', 'usuarios/altera_senha_view', $data);
        } else {
            $valida_senha_atual = $this->Usuarios_model->valida_senha_atual($this->input->post('id'), md5($this->input->post('senha_atual')));
            if ($valida_senha_atual) {
                $dados = $this->input->post();
                $dados['senha'] = md5($dados['senha']);
                unset($dados['r_senha']);
                unset($dados['senha_atual']);
                $update = $this->Usuarios_model->update_senha($dados);
                if ($update) {
                    $data = $this->get_dados_usuario();
                    $data['mensagens_sucesso'] = "Senha alterada com sucesso";
                    $this->template->load('template', 'usuarios/perfil_view', $data);
                } else {
                    $data['mensagens_erro'] = "Houve um erro no momento da atualização, tente novamente";
                    $this->template->load('template', 'usuarios/altera_senha_view', $data);
                }
            } else {
                $data['mensagens_erro'] = "Senha atual incorreta";
                $this->template->load('template', 'usuarios/altera_senha_view', $data);
            }
        }
    }

    function recaptcha($recaptcha) {
        $recaptchaResponse = $recaptcha;
        $secret = '6LdpTRATAAAAAKF5c3dqrc4aLrKWyaZBqVaNdCy6';
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data1 = array('secret' => $secret, 'response' => $recaptchaResponse);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $status = json_decode($response, true);
        if ($status['success']) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
