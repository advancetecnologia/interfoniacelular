<script>
    var mask_senha = <?= $num_apartamento ?>;
    jQuery(function ($) {
        $("#telefone").mask("(99)999999999");
        $("#senha").mask(mask_senha + "9999");
    });
</script>
<?php
if (!empty($mensagens_erro)) {
    ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $mensagens_erro ?>
    </div>
    <?php
}
if (!empty($mensagens_sucesso)) {
    ?>
    <div class="alert alert-success" role="alert">
        <?php echo $mensagens_sucesso ?>
    </div>
    <?php
}
?>
<h3>Nova senha</h3>
<form class="formularios" method="post" action="<?= base_url('senhas/novo') ?>" data-toggle="validator" role="form">
    <div class="panel panel-default">
        <div class="panel-heading">Dados da senha de acesso</div>
        <div class="panel-body">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">Condomínio</div>
                    <select name="id_condominio" class="form-control">
                        <?php
                        foreach ($condominios as $c) {
                            ?>
                            <option value="<?= $c->id ?>"><?= $c->nome ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">Apartamento</div>
                    <select name="id_apartamento" class="form-control">
                        <?php
                        foreach ($apartamentos as $a) {
                            ?>
                            <option value="<?= $a->id ?>"><?= $a->nome ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">Nome</div>
                    <input id="textNome" name="nome" class="form-control" placeholder=" Digite o Nome" type="text">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">Senha</div>
                    <input name="senha" id="senha" class="form-control" placeholder="Digite a senha" type="text">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group date" data-provide="datepicker">
                    <div class="input-group-addon">Validade da senha</div>
                    <input name="validade" id="senha" class="form-control" placeholder="Digite a validade" type="text">
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-success">Salvar senha</button>
</form>