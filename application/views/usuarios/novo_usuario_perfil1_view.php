<?php
if (empty($inputs)) {
    $inputs['email'] = "";
    $inputs['senha'] = "";
    $inputs['r_senha'] = "";
    $inputs['cod_apartamento'] = "";
}
?>
<div class="painel_novo_user">
    <div id="form_login_top">
        <div id="form_login_top_esq"><span class="title_login_bold">Condomínio </span><span class="title_login_light">Inteligente</span></div>
        <div id="form_login_top_dir"><img src="<?= base_url('assets/images/logo.jpg'); ?>"></div>
    </div>
    <?php
    if (isset($mensagens)) {
        ?>
        <div class="alert alert-danger msg_errors2" role="alert">
            <?php
            echo $mensagens;
            ?>
        </div>
        <?php
    }
    ?>
    <div class="text_explicativo"> 
        <div class="text_numeral">2</div> 
        Informe seus dados › Cadastro de apartamento
    </div>
    <form id="form_novo_usuario" method="post" action="<?= base_url('usuarios/insert_perfil1') ?>">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">E-mail</div>
                <input id="textNome" name="email"  value="<?= $inputs['email'] ?>" class="form-control" placeholder=" Digite um e-mail" type="email">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Senha</div>
                <input id="inputEmail" name="senha" value="<?= $inputs['senha'] ?>" class="form-control" placeholder="Digite a senha" type="password">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Repita a senha</div>
                <input id="inputEmail" name="r_senha" class="form-control" value="<?= $inputs['r_senha'] ?>" placeholder="Repita a senha" type="password">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Código do apartamento</div>
                <input id="inputEmail" name="cod_apartamento" class="form-control" value="<?= $inputs['cod_apartamento'] ?>" placeholder="Digite o código do apartamento" type="text">
            </div>
        </div>
        <div class="g-recaptcha" data-sitekey="6LdpTRATAAAAABBO_M6sU4s-ZnvG-oJQ7i61_463"></div>
        <script src='https://www.google.com/recaptcha/api.js?hl=pt-BR'></script><br/>
        <button type="submit" onclick="ativa_loading()" class="btn btn-success">Salvar</button>
        <a href="<?= base_url('usuarios/primeiro_acesso') ?>" class="btn btn-danger">Voltar</a>
    </form>
</div>