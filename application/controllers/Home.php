<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Telefones_model');
        $this->load->model('Apartamentos_model');
        $this->load->model('Condominios_model');
        $this->load->library('logado');
        $this->logado->verifica_primeiro_acesso();
        $this->logado->get_logado();
    }

    public function index() {
        $id_condominio = $this->session->userdata('id_condominio');
        $perfil = $this->session->userdata('perfil');
        $data['perfil'] = $perfil;
        if(!empty($id_condominio)){
            $bloco_unico = $this->Condominios_model->get_bloco_unico();
            if ($bloco_unico->bloco_unico) {
                $this->logado->grava_session_bloco_unico();
                $this->logado->grava_session_id_bloco_unico($this->session->userdata('id_condominio'));
            }
        }

        if ($perfil == 2) {
            $data['qtd_apartamentos'] = $this->Apartamentos_model->get_qtd_apartamentos($this->session->userdata('id_condominio'));
        } else if ($perfil == 1) {
            $data['qtd_telefones'] = $this->Telefones_model->get_qtd_telefones($this->session->userdata('id_apartamento'));
        }else if($perfil == 4){
           $data['condominios'] = $this->Condominios_model->get_condominios_by_revenda($this->session->userdata('id_revenda'));
           if(!empty($id_condominio)){
            $data['qtd_apartamentos'] = $this->Apartamentos_model->get_qtd_apartamentos($this->session->userdata('id_condominio'));
           }
        }

        $this->template->load('template', 'home/home_view', $data);
    }

    public function acesso_negado() {
        $this->template->load('template', 'home/acesso_negado_view');
    }

}
