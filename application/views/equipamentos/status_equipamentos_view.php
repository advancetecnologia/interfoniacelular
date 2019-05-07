<h3>Status de transmissão</h3><br/><br/>
<div class="table-responsive">
    <table class="table table-striped">
        <?php if (!empty($equipamentos)) { ?>
            <tr>
                <th class="table_title">Nome</th>
                <th class="table_title">Eventos a serem transmitidos</th>
                <th class="table_title">Última comunicação</th>
            </tr>
            <?php
        }
        foreach ($equipamentos as $e) {
            ?>
            <tr>
                <td><?= $e->nome ?></td>
                <td id="pendentes_<?= $e->id ?>" class="display_none_table">
                    Carregando ...
                </td>
                <td id="ultima_comunicacao_<?= $e->id ?>" class="display_none_table">
                    Carregando ...
                </td>
            </tr>
            <script>
                get_eventos_equipamentos(<?= $e->id ?>);
            </script>
            <?php
        }
        ?>
    </table>
</div>