<?php
if (empty($qtd_telefones)) {
    $qtd_telefones = 0;
}
$id_condominio = $this->session->userdata('id_condominio');

?>
<div id="saudacao">
    Seja bem vindo(a)
</div>
<div id="dashboards">
    <?php
    if ($perfil == 2) {
        ?>
        <a href="<?= base_url('apartamentos') ?>">
            <div class="dashboards_box dashboards_amarelo">
                <?= $qtd_apartamentos ?><br/><span class="dashboards_title">Apartamento(s) cadastrados</span>
            </div>
        </a>
        <?php
    } else if ($perfil == 1) {
        ?>
        <a href="<?= base_url('telefones') ?>">
            <div class="dashboards_box dashboards_amarelo">
                <?= $qtd_telefones ?><br/><span class="dashboards_title">Telefone(s) cadastrados</span>
            </div>
        </a>
        <?php
    }else if ($perfil == 4 && empty($id_condominio)){
        ?>
        <script>select_condominio()</script>
        <?php
    }else if($perfil == 4 && !empty($id_condominio)){
        ?>
        <a href="<?= base_url('apartamentos') ?>">
            <div class="dashboards_box dashboards_amarelo">
                <?= $qtd_apartamentos ?><br/><span class="dashboards_title">Apartamento(s) cadastrados</span>
            </div>
        </a>
        <?php
    }
    ?>
</div>