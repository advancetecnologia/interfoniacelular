<script>
    jQuery(function ($) {
        $("#telefone").mask("(99)99999999?9");
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
$apartamento_selecionado = $this->session->userdata('id_apartamento');
?>

<h3>Novo telefone</h3>
<form class="formularios" method="post" action="<?= base_url('telefones/novo/') ?>" data-toggle="validator" role="form">
    <div class="panel panel-default">
        <div class="panel-heading">Dados do telefone</div>
        <div class="panel-body">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">Nome</div>
                    <input id="textNome" name="nome" class="form-control" placeholder=" Digite o Nome" type="text">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">Telefone</div>
                    <input id="telefone" name="telefone" class="form-control" placeholder="Digite o telefone" type="text">
                </div>
            </div>
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
                            if (!isset($a->nome)) {
                                $bloco = "";
                            } else {
                                $bloco = $a->nome . " - ";
                            }
                            ?>
                            <option value="<?= $a->id ?>"><?= $bloco ?> Apartamento <?= $a->numero_apartamento ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div id="input_telefone_memoria" class="form-group">
                <div class="input-group">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input checked="true" type="checkbox" name="memoria" class="form-check-input">
                            Esse telefone vai para memória do equipamento
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" onclick="ativa_loading()" class="btn btn-success">Salvar telefone</button>
</form>