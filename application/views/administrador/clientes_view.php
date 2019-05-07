<?php
if (!empty($mensagens_sucesso)) {
    ?>
    <div class="alert alert-success" role="alert">
        <?php echo $mensagens_sucesso ?>
    </div>
    <?php
}

if (!empty($mensagens_erro)) {
    ?>
    <div class="alert alert-warning" role="alert">
        <?php echo $mensagens_erro ?>
    </div>
    <?php
}
?>
<div id="result_msg_apartamentos"></div>
<h3>Condomínios cadastrados</h3><br/><br/>
<table class="table table-striped">
    <?php if (!empty($clientes)) { ?>
        <tr>
            <th class="table_title">Id</th>
            <th class="table_title">Nome</th>
            <th class="table_title">Cnpj</th>
            <th class="table_title">Telefone</th>
            <th class="table_title">Ativo</th>
        </tr>
        <?php
    }
    foreach ($clientes as $c) {
        ?>
        <tr>
            <td><?= $c->id ?></td>
            <td><?= $c->nome ?></td>
            <td><?= $c->cnpj ?></td>
            <td><?= $c->telefone ?></td>
            <?php
            if ($c->ativo) {
                ?>
                <td><input onchange="ativa_desativa_cliente(this,<?= $c->id ?>)" checked="true" type="checkbox"></td>
                <?php
            } else {
                ?>
                <td><input onchange="ativa_desativa_cliente(this,<?= $c->id ?>)" type="checkbox"></td>
                <?php
            }
            ?>
        </tr>
        <?php
    }
    ?>
</table>