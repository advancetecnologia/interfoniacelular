<div class="painel_novo_user">
    <div id="form_login_top">
        <div id="form_login_top_esq"><span class="title_login_bold">Condomínio </span><span class="title_login_light">Inteligente</span></div>
        <div id="form_login_top_dir"><img src="<?= base_url('assets/images/logo.jpg'); ?>"></div>
    </div>
    <?php
    if (!empty($mensagens_erro)) {
        ?>
        <div class="alert alert-danger msg_errors2" role="alert">
            <?php
            echo $mensagens_erro;
            ?>
        </div>
        <?php
    }
    if (!empty($mensagens_sucesso)) {
        ?>
        <div class="alert alert-success msg_errors2" role="alert">
            <?php
            echo $mensagens_sucesso;
            ?>
        </div>
        <?php
    }
    ?>
    <div class="text_explicativo"> 
        Dados da nova senha
    </div>
    <form id="form_esquceu_senha" method="post" action="<?= base_url('usuarios/nova_senha') ?>">
        <input type="hidden" name="id" value="<?= $id_usuario ?>">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Nova senha</div>
                <input id="textNome" name="senha" class="form-control" placeholder=" Digite a nova senha" type="password">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Repita nova senha</div>
                <input id="textNome" name="r_senha" class="form-control" placeholder=" Repita a nova senha" type="password">
            </div>
        </div>
        <div class="g-recaptcha" data-sitekey="6LdpTRATAAAAABBO_M6sU4s-ZnvG-oJQ7i61_463"></div>
        <script src='https://www.google.com/recaptcha/api.js?hl=pt-BR'></script><br/>
        <button type="submit" onclick="ativa_loading()" class="btn btn-success">Enviar</button>
        <a href="<?= base_url() ?>" class="btn btn-danger">Voltar</a>
    </form>
</div>