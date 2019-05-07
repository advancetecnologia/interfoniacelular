<h3>Revendas cadastradas</h3><br/><br/>
<table class="table table-striped">
    <?php if (!empty($revendas)) { ?>
        <tr>
            <th class="table_title">Id</th>
            <th class="table_title">Nome</th>
            <th class="table_title">Cnpj</th>
            <th class="table_title">Ativo</th>
        </tr>
        <?php
    }
    foreach ($revendas as $r) {
        ?>
        <tr>
            <td><?= $r->id ?></td>
            <td><?= $r->nome ?></td>
            <td><?= $r->cnpj ?></td>
            <?php
            if ($r->ativo) {
                ?>
                <td><input onchange="ativa_desativa_revenda(this,<?= $r->id ?>)" checked="true" type="checkbox"></td>
                <?php
            } else {
                ?>
                <td><input onchange="ativa_desativa_revenda(this,<?= $r->id ?>)" type="checkbox"></td>
                <?php
            }
            ?>
        </tr>
        <?php
    }
    ?>
</table>