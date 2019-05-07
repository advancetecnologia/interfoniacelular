<?php
if (!empty($mensagens_erro)) {
    ?>
    <div class="alert alert-warning" role="alert">
        <?php echo $mensagens_erro ?>
    </div>
    <?php
}
?>
<div class="painel_primeiro_acesso">
    <div id="form_login_top">
        <div id="form_login_top_esq"><span class="title_login_bold">Condomínio </span><span class="title_login_light">Inteligente</span></div>
        <div id="form_login_top_dir"><img src="<?= base_url('assets/images/logo.jpg'); ?>"></div>
    </div>
    <div class="text_explicativo"> 
        Seja bem vindo(a), esse é seu primeiro acesso, temos uma pergunta para lhe fazer, assim vamos ajustar o sistema da melhor forma.<br/><br/>
        O seu condomínio é divididos em blocos:<br/><br/>
        <a href="<?= base_url('primeiro_acesso/set_bloco_unico/') ?>">
            <div id="btn_primeiro_acesso">
                <div id="btn_primeiro_acesso_img">
                    <img src="<?= base_url('assets/images/bloco_unico.jpg'); ?>">
                </div>
                <div id="btn_primeiro_acesso_text">
                   Não
                </div>
            </div>
        </a>
        <a href="<?= base_url('primeiro_acesso/set_blocos/') ?>">
            <div id="btn_primeiro_acesso">
                <div id="btn_primeiro_acesso_img">
                    <img src="<?= base_url('assets/images/diversos_blocos.jpg'); ?>">
                </div>
                <div id="btn_primeiro_acesso_text">
                    Sim
                </div>
            </div>
        </a>
    </div>
</div>