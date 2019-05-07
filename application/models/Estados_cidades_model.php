<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Estados_cidades_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_estados() {
        $this->db->order_by('nome');
        $query = $this->db->get('estados');
        return $query->result();
    }

    function get_cidades($estado) {
        $this->db->where('estado', $estado);
        $query = $this->db->get('cidades');
        return $query->result();
    }

}

?>