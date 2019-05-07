<?php

class Logado {

    public function get_logado() {
        $CI = & get_instance();
        $CI->load->library('session');
        if ($CI->session->userdata('logado')) {
            return;
        } else {
            $CI->session->set_flashdata('falha', 'Você não está logado');
            redirect(site_url(), 'refresh');
        }
    }

    public function verifica_permissoes($permissoes) {
        $CI = & get_instance();
        $CI->load->library('session');
        foreach ($permissoes as $p) {
            if ($p == $CI->session->userdata('perfil')) {
                return TRUE;
            }
        }
        redirect(base_url('home/acesso_negado'), 'refresh');
    }

    public function verifica_primeiro_acesso() {
        $CI = & get_instance();
        $CI->load->model('Usuarios_model');
        $primeiro_acesso = $CI->Usuarios_model->get_primeiro_acesso();
        if (isset($primeiro_acesso[0])) {
            if ($primeiro_acesso[0]->primeiro_acesso) {
                redirect(base_url('primeiro_acesso'), 'refresh');
            } else {
                return TRUE;
            }
        }
    }

    public function grava_session_bloco_unico() {
        $CI = & get_instance();
        $CI->load->model('Condominios_model');
        $bloco_unico = $CI->Condominios_model->get_bloco_unico();
        $CI->session->set_userdata('bloco_unico', $bloco_unico->bloco_unico);
    }

    public function grava_session_id_bloco_unico($id_condominio) {
        $CI = & get_instance();
        $CI->load->model('Blocos_model');
        $id_bloco_unico = $CI->Blocos_model->get_bloco_unico($id_condominio);
        $CI->session->set_userdata('id_bloco_unico', $id_bloco_unico);
    }

}
