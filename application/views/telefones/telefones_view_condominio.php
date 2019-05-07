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

<h3>Telefones</h3>
<form class="formularios" method="post" action="<?= base_url('telefones/gerenciar') ?>" data-toggle="validator" role="form">
    <div class="panel panel-default">
        <div class="panel-heading">Selecione o apartamento que deseja gerenciar</div>
        <div class="panel-body">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">Apartamento</div>
                    <select name="id_apartamento" class="form-control">
                        <?php
                        if (count($apartamentos) > 0) {
                            ?>
                            <option value="">Selecione um apartamento</option>
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
                        } else {
                            ?>
                            <option value="">Sem apartamentos cadastrado</option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" onclick="ativa_loading()" class="btn btn-success">Selecionar</button>
</form>