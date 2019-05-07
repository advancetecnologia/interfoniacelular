<h3>Importação de arquivos resultado</h3>
<span class="font_green">Foram importados <strong><?= $log_sucessos ?></strong> linhas com sucesso</span><br/><br/>
<?php
if (!empty($log_erros)) {
    ?>
    <span class="font_red">As seguintes linhas apresentam erros:<br/> <?= $log_erros ?></span>
        <?php
    }
    ?>



