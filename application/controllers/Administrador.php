<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Administrador extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('logado');
        $this->logado->get_logado();
        $this->logado->verifica_permissoes(array('3'));
       /* if ($this->input->ip_address() != '191.243.135.69') {
            redirect(base_url('acesso_restrito'));
        }*/
        $this->load->model('Condominios_model');
        $this->load->model('Revendas_model');
        $this->load->model('Usuarios_model');
    }

    public function index() {
        $this->template->load('template', 'administrador/administrador_view');
    }

    public function clientes() {
        $data['clientes'] = $this->Condominios_model->get_condominios();
        $this->template->load('template', 'administrador/clientes_view', $data);
    }

    public function revendas(){
        $data['revendas'] = $this->Revendas_model->get_revendas();
        $this->template->load('template', 'administrador/revendas_view', $data);
    }

    public function ativa_desativa_cliente() {
        $valor = $this->input->get('valor');
        $id_condominio = $this->input->get('id_condominio');
        if ($valor == 'true') {
            $ativa_condominio = $this->Condominios_model->ativa_condominio($id_condominio);
            if ($ativa_condominio) {
                $ativa_usuario = $this->Usuarios_model->ativa_usuario($id_condominio);
                if ($ativa_usuario) {
                    echo 1;
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
        } else {
            $desativa_condominio = $this->Condominios_model->desativa_condominio($id_condominio);
            if ($desativa_condominio) {
                $desativa_usuario = $this->Usuarios_model->desativa_usuario($id_condominio);
                if ($desativa_usuario) {
                    echo 1;
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
        }
    }
    
    public function ativa_desativa_revenda() {
        $valor = $this->input->get('valor');
        $id_revenda = $this->input->get('id_revenda');
        if ($valor == 'true') {
            $ativa_revenda = $this->Revendas_model->ativa_revenda($id_revenda);
            if ($ativa_revenda) {
                $ativa_usuario = $this->Usuarios_model->ativa_usuario_by_revenda($id_revenda);
                if ($ativa_usuario) {
                    echo 1;
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
        } else {
            $desativa_revenda = $this->Revendas_model->desativa_revenda($id_revenda);
            if ($desativa_revenda) {
                $desativa_usuario = $this->Usuarios_model->desativa_usuario_by_revenda($id_revenda);
                if ($desativa_usuario) {
                    echo 1;
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
        }
    }

}
