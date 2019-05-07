<div id="form_login">
    <div id="form_login_top">
        <div id="form_login_top_esq"><span class="title_login_bold">Condomínio </span><span class="title_login_light">Inteligente</span></div>
        <div id="form_login_top_dir"><img src="<?= base_url('assets/images/logo.jpg'); ?>"></div>
    </div>
    <form id="form_login_inputs" action="<?= base_url('login/autenticacao'); ?>" method="post">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></div>
                <input type="text" class="form-control" name="usuario" id="exampleInputEmail1" placeholder="  E-mail">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></div>
                <input type="password" class="form-control" name="senha" id="exampleInputPassword1" placeholder="Senha">
            </div>
        </div>
        <button type="submit" onclick="ativa_loading()" class="btn btn-success">Entrar</button>
    </form>
    <span class="primeiro_acesso"><a href="<?= base_url('usuarios/primeiro_acesso') ?>">Primeiro acesso?</a></span><span class="esqueceu_senha"><a href="<?= base_url('usuarios/esqueceu_senha') ?>">Esqueceu a senha?</a></span>
</div> 
