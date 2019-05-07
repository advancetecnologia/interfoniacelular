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
<h3>Perfil do usuário</h3>
<div class="list_box">
    <strong>Usuário: </strong><?= $email ?><br/>
    <strong>Perfil do usuário: </strong><?= $perfil ?>
    <?php
    if($perfil == "Revenda"){
        ?>
        <br/>
        <div id="hash_revenda">
            <strong>Hash da revenda: </strong><?= $hash_revenda ?>
        </div>
        <br/><br/>
        <a href="<?php echo base_url('usuarios/altera_senha') ?>" class="btn btn-primary"><span class="glyphicon glyphicon-lock"></span> Alterar senha</a>
        <button onclick = "new_hash_revenda(<?=$id_revenda?>,<?=$cnpj_revenda?>)" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span> Gerar nova hash</button>
        <?php
    }else if($perfil != "Administrador"){
        ?>
        <br/><br/>
        <a href="<?php echo base_url('usuarios/altera_senha') ?>" class="btn btn-primary"><span class="glyphicon glyphicon-lock"></span> Alterar senha</a>
        <?php
    }
    ?>
</div>
<div class="condominio_gerenciados">
    <?php
        if(isset($condominios)){
            ?>
            <h3>Condomínios gerenciados</h3>
            <?php
            foreach($condominios as $c){
                ?>
                <div class="list_box">
                    <strong>Nome: </strong><?=$c['nome']?><br/>
                    <div id="<?=$c['id_condominio']?>">
                        <strong>Hash: </strong><?=$c['hash']?>
                    </div>
                    <br/>
                    <button onclick = "new_hash(<?=$c['id_condominio']?>,<?=$c['cnpj']?>)" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span> Gerar nova hash</button>
                </div>
                <?php
            }
        }else if(isset($nome_condominio)){
            ?>
            <h3>Condomínios gerenciados</h3>
            <?php
                ?>
                <div class="list_box">
                    <strong>Nome: </strong><?=$nome_condominio?><br/>
                    <div id="<?=$id_condominio?>">
                        <strong>Hash: </strong><?=$hash?>
                    </div>
                    <br/>
                    <button onclick = "new_hash(<?=$id_condominio?>, <?=$cnpj?>)" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span> Gerar nova hash</button>
                </div>
            <?php
        }
    ?>
</div>